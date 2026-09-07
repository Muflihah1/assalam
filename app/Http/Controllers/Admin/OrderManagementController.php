<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProgress;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderManagementController extends Controller
{
    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        $newOrdersCount = Order::where(function ($q) {
            $q->where('order_status', 'Menunggu Konfirmasi')
              ->orWhere('production_status', 'Menunggu Konfirmasi')
              ->orWhere('payment_status', 'Menunggu Verifikasi DP');
        })->count();

        $inProductionCount = Order::whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan'])->count();
        $totalCustomersCount = User::where('role', 'customer')->count();
        
        $recentOrders = Order::with(['user', 'customDesign.produk'])->latest()->take(5)->get();
        $inProgressOrders = Order::with(['customDesign.produk'])->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan'])->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'newOrdersCount', 
            'inProductionCount', 
            'totalCustomersCount', 
            'recentOrders',
            'inProgressOrders'
        ));
    }

    /**
     * Pesanan Masuk & Verifikasi
     */
    public function pesananMasuk(Request $request)
    {
        $query = Order::with(['user', 'customDesign.produk', 'progresses']);

        // Tab filter
        $activeTab = $request->query('tab', 'all');
        if ($activeTab === 'menunggu_konfirmasi') {
            $query->where('order_status', 'Menunggu Konfirmasi');
        } elseif ($activeTab === 'menunggu_dp') {
            $query->whereIn('payment_status', ['Menunggu Pembayaran DP', 'Menunggu Verifikasi DP', 'Bukti DP Ditolak']);
        } elseif ($activeTab === 'dalam_pengerjaan') {
            $query->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan', 'Penyelesaian']);
        } elseif ($activeTab === 'pelunasan') {
            $query->whereIn('payment_status', ['Menunggu Verifikasi Pelunasan', 'Bukti Pelunasan Ditolak']);
        } elseif ($activeTab === 'selesai') {
            $query->where('order_status', 'Selesai')->orWhere('production_status', 'Selesai');
        } elseif ($activeTab === 'batal') {
            $query->whereIn('order_status', ['Ditolak', 'Dibatalkan']);
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $terms = array_filter(preg_split('/\s+/', $search));
            $query->where(function ($sub) use ($search, $terms) {
                // Pencarian exact phrase
                $sub->where('order_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('recipient_phone', 'like', "%{$search}%")
                    ->orWhere('order_status', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%")
                    ->orWhere('production_status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%")
                          ->orWhere('whatsapp_number', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customDesign', function ($cd) use ($search) {
                        $cd->where('category', 'like', "%{$search}%")
                           ->orWhere('wood_material', 'like', "%{$search}%")
                           ->orWhere('color_name', 'like', "%{$search}%");
                    });

                // Cocokkan per kata kunci
                foreach ($terms as $term) {
                    $sub->orWhere('order_number', 'like', "%{$term}%")
                        ->orWhere('recipient_name', 'like', "%{$term}%")
                        ->orWhere('recipient_phone', 'like', "%{$term}%");
                }
            });
        }

        $listPesananMasuk = $query->latest()->get();

        // Hitung statistik untuk summary card
        $stats = [
            'total' => Order::count(),
            'menunggu_konfirmasi' => Order::where('order_status', 'Menunggu Konfirmasi')->count(),
            'menunggu_verif_dp' => Order::where('payment_status', 'Menunggu Verifikasi DP')->count(),
            'dalam_produksi' => Order::whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan', 'Penyelesaian'])->count(),
            'menunggu_pelunasan' => Order::where('payment_status', 'Menunggu Verifikasi Pelunasan')->count(),
            'selesai' => Order::where('order_status', 'Selesai')->count(),
            'batal' => Order::whereIn('order_status', ['Ditolak', 'Dibatalkan'])->count(),
        ];

        return view('admin.pesanan_masuk', compact('listPesananMasuk', 'activeTab', 'stats'));
    }

    /**
     * 1. Konfirmasi & Terima Pesanan oleh Admin
     */
    public function confirmOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $adminNotes = $request->filled('admin_notes')
            ? $request->admin_notes
            : 'Pesanan telah diperiksa dan disetujui oleh admin. Silakan lakukan pembayaran DP untuk memulai persiapan bahan & produksi.';

        $order->update([
            'order_status' => 'Pesanan Diterima',
            'production_status' => 'Menunggu Pembayaran DP',
            'payment_status' => 'Menunggu Pembayaran DP',
            'current_stage' => 'Validasi Pembayaran',
            'admin_notes' => $adminNotes,
        ]);

        // Tandai step 1 (Konfirmasi Pesanan) selesai
        OrderProgress::where('order_id', $order->id)->where('step_number', 1)->update([
            'status' => 'Selesai',
            'completed_at' => now(),
            'notes' => $adminNotes
        ]);

        // Aktifkan step 2 (Validasi Pembayaran)
        OrderProgress::where('order_id', $order->id)->where('step_number', 2)->update([
            'status' => 'Sedang Berjalan',
            'notes' => 'Menunggu pembayaran uang muka (DP) dari pelanggan'
        ]);

        // Trigger Notifikasi WhatsApp
        try {
            app(\App\Services\WhatsAppNotificationService::class)->sendOrderConfirmed($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        return back()->with('success', 'Pesanan #' . $order->order_number . ' BERHASIL DITERIMA! Alur dialihkan ke tahap Validasi Pembayaran DP.');
    }

    /**
     * 2. Tolak Pesanan oleh Admin beserta Alasan
     */
    public function rejectOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|min:3|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan pesanan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 3 karakter.',
        ]);

        $order->update([
            'order_status' => 'Ditolak',
            'production_status' => 'Ditolak',
            'payment_status' => 'Ditolak',
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => 'Pesanan ditolak: ' . $request->rejection_reason,
        ]);

        // Update progress step 1 menjadi Dibatalkan
        OrderProgress::where('order_id', $order->id)->where('step_number', 1)->update([
            'status' => 'Dibatalkan',
            'notes' => 'Pesanan ditolak oleh admin: ' . $request->rejection_reason
        ]);

        // Trigger Notifikasi WhatsApp
        try {
            app(\App\Services\WhatsAppNotificationService::class)->sendOrderRejected($order, $request->rejection_reason);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        return back()->with('success', 'Pesanan #' . $order->order_number . ' BERHASIL DITOLAK. Alasan penolakan telah disimpan dan diberitahukan ke pelanggan.');
    }

    /**
     * 3. Verifikasi Pembayaran DP & Masuk Antrean Produksi
     */
    public function verifyDP(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'order_status' => 'Diproses',
            'payment_status' => 'DP Terverifikasi',
            'production_status' => 'Antrean Produksi',
            'current_stage' => 'Pesanan Diterima',
            'rejection_reason' => null,
            'admin_notes' => 'Pembayaran DP telah diverifikasi sah. Pesanan masuk antrean pengerjaan pengrajin.',
        ]);

        // Update step 2 selesai & step 3 aktif
        OrderProgress::where('order_id', $order->id)->where('step_number', 2)->update([
            'status' => 'Selesai', 
            'completed_at' => now(),
            'notes' => 'Pembayaran DP diverifikasi oleh admin'
        ]);
        OrderProgress::where('order_id', $order->id)->where('step_number', 3)->update([
            'status' => 'Sedang Berjalan',
            'notes' => 'Pesanan masuk antrean workshop pengrajin mebel'
        ]);

        // Trigger Notifikasi WhatsApp
        try {
            app(\App\Services\WhatsAppNotificationService::class)->sendDPVerified($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        return back()->with('success', 'Pembayaran DP pesanan #' . $order->order_number . ' BERHASIL DIVERIFIKASI! Pesanan masuk ke antrean workshop.');
    }

    /**
     * 3b. Tolak Bukti Pembayaran DP oleh Admin
     */
    public function rejectDP(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|min:3|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan bukti transfer DP wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 3 karakter.',
        ]);

        $reason = $request->rejection_reason;

        $order->update([
            'payment_status' => 'Bukti DP Ditolak',
            'rejection_reason' => $reason,
            'admin_notes' => 'Bukti transfer DP ditolak: ' . $reason . '. Silakan unggah bukti pembayaran yang valid.',
        ]);

        // Tandai step 2
        OrderProgress::where('order_id', $order->id)->where('step_number', 2)->update([
            'status' => 'Sedang Berjalan',
            'notes' => 'Bukti DP ditolak admin: ' . $reason . ' (Menunggu unggah ulang pelanggan)'
        ]);

        return back()->with('warning', 'Bukti pembayaran DP pesanan #' . $order->order_number . ' DITOLAK. Pelanggan dapat mengunggah bukti baru.');
    }

    /**
     * 4. Verifikasi Pembayaran Pelunasan
     */
    public function verifyPelunasan(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'payment_status' => 'Lunas',
            'remaining_payment' => 0,
            'rejection_reason' => null,
            'admin_notes' => 'Pelunasan sisa tagihan telah diverifikasi sah oleh admin. Pembayaran lunas.',
        ]);

        return back()->with('success', 'Pelunasan pesanan #' . $order->order_number . ' BERHASIL DIVERIFIKASI! Status pembayaran lunas.');
    }

    /**
     * 4b. Tolak Bukti Pelunasan oleh Admin
     */
    public function rejectPelunasan(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|min:3|max:1000',
        ], [
            'rejection_reason.required' => 'Alasan penolakan bukti pelunasan wajib diisi.',
            'rejection_reason.min' => 'Alasan penolakan minimal 3 karakter.',
        ]);

        $reason = $request->rejection_reason;

        $order->update([
            'payment_status' => 'Bukti Pelunasan Ditolak',
            'rejection_reason' => $reason,
            'admin_notes' => 'Bukti pelunasan ditolak: ' . $reason . '. Silakan unggah bukti pelunasan yang valid.',
        ]);

        return back()->with('warning', 'Bukti pelunasan pesanan #' . $order->order_number . ' DITOLAK. Pelanggan dapat mengunggah bukti pelunasan baru.');
    }

    /**
     * Halaman Kelola Progres Produksi
     */
    public function progresProduksi($id = null)
    {
        if ($id) {
            $progres = Order::with(['user', 'customDesign.produk', 'progresses', 'items'])->findOrFail($id);
        } else {
            $progres = Order::with(['user', 'customDesign.produk', 'progresses', 'items'])
                ->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan', 'Penyelesaian', 'Pengiriman'])
                ->latest()
                ->first();
        }

        $allOrders = Order::with(['customDesign', 'user'])->latest()->get();

        return view('admin.progres_produksi', compact('progres', 'allOrders'));
    }

    /**
     * Update progres produksi pesanan (Alur Berurutan / Sequential Stepper)
     */
    public function updateProgres(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'tahap' => 'required|string',
            'catatan' => 'nullable|string',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov|max:20480',
        ]);

        $stageMap = [
            'Konfirmasi Pesanan' => 1,
            'Validasi Pembayaran' => 2,
            'Pesanan Diterima' => 3,
            'Menyiapkan Bahan' => 4,
            'Perakitan' => 5,
            'Penyelesaian' => 6,
            'Pengiriman' => 7,
            'Pesanan Selesai' => 8,
        ];

        $currentStage = $order->current_stage ?? 'Konfirmasi Pesanan';
        $currentStepNum = $stageMap[$currentStage] ?? 1;
        $targetStepNum = $stageMap[$request->tahap] ?? $currentStepNum;

        // 1. Validasi Locking: Tahap fisik 4 s/d 8 terkunci jika DP belum terverifikasi
        if ($targetStepNum >= 4 && !in_array($order->payment_status, ['DP Terverifikasi', 'Lunas'])) {
            return back()->with('error', 'Tahapan pengerjaan fisik (' . $request->tahap . ') terkunci! Pembayaran uang muka (DP) wajib diverifikasi terlebih dahulu.');
        }

        // 2. Validasi Sequential: Mencegah loncat tahapan sembarangan
        if ($targetStepNum > $currentStepNum + 1) {
            $nextStageName = array_search($currentStepNum + 1, $stageMap) ?: 'tahap berikutnya';
            return back()->with('error', 'Tahapan produksi harus berjalan secara berurutan! Anda saat ini berada di tahap "' . $currentStage . '". Silakan lanjutkan ke "' . $nextStageName . '" terlebih dahulu.');
        }

        // Upload media files jika ada
        $uploadedMedia = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('progress_docs', 'public');
                $uploadedMedia[] = $path;
            }
        }

        // Update target progress step
        $progressStep = OrderProgress::where('order_id', $order->id)
            ->where('step_number', $targetStepNum)
            ->first();

        if ($progressStep) {
            $existingMedia = $progressStep->media_files ?? [];
            $allMedia = array_merge($existingMedia, $uploadedMedia);

            $progressStep->update([
                'status' => $targetStepNum == 8 ? 'Selesai' : 'Sedang Berjalan',
                'media_files' => $allMedia,
                'notes' => $request->catatan ?: $progressStep->notes,
                'completed_at' => $targetStepNum == 8 ? now() : $progressStep->completed_at,
            ]);
        }

        // Jika maju ke tahap baru, tandai tahap-tahap sebelumnya sebagai Selesai
        if ($targetStepNum > $currentStepNum) {
            OrderProgress::where('order_id', $order->id)
                ->where('step_number', '<', $targetStepNum)
                ->where('status', '!=', 'Selesai')
                ->update([
                    'status' => 'Selesai',
                    'completed_at' => now()
                ]);
        }

        // Sinkronisasi status pesanan dan produksi
        $productionStatus = 'Antrean Produksi';
        if ($targetStepNum == 8) {
            $productionStatus = 'Selesai';
        } elseif ($targetStepNum == 7) {
            $productionStatus = 'Pengiriman';
        } elseif ($targetStepNum == 6) {
            $productionStatus = 'Penyelesaian';
        } elseif ($targetStepNum >= 4) {
            $productionStatus = 'Dalam Pengerjaan';
        } elseif ($targetStepNum == 3) {
            $productionStatus = 'Antrean Produksi';
        }

        $orderStatus = $order->order_status;
        if ($targetStepNum == 8) {
            $orderStatus = 'Selesai';
        } elseif ($targetStepNum == 7) {
            $orderStatus = 'Dikirim';
        } elseif ($targetStepNum >= 3 && $targetStepNum <= 6) {
            $orderStatus = 'Diproses';
        }

        $order->update([
            'current_stage' => $request->tahap,
            'admin_notes' => $request->catatan ?: $order->admin_notes,
            'production_status' => $productionStatus,
            'order_status' => $orderStatus,
        ]);

        // Trigger Notifikasi WhatsApp Otomatis
        try {
            $waService = app(\App\Services\WhatsAppNotificationService::class);
            if ($targetStepNum == 8) {
                $waService->sendOrderFinished($order);
            } else {
                $waService->sendProgressUpdated($order, $request->tahap, $request->catatan);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        $msg = ($targetStepNum > $currentStepNum)
            ? 'Pengerjaan berhasil dimajukan ke tahap "' . $request->tahap . '"!'
            : 'Dokumentasi & catatan tahap "' . $request->tahap . '" berhasil diperbarui!';

        return back()->with('success', $msg);
    }

    /**
     * Riwayat Pemesanan Admin
     */
    public function riwayat(Request $request)
    {
        $query = Order::with(['user', 'customDesign', 'progresses']);

        if ($request->filled('q')) {
            $search = trim($request->q);
            $terms = array_filter(preg_split('/\s+/', $search));
            $query->where(function ($sub) use ($search, $terms) {
                $sub->where('order_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('recipient_phone', 'like', "%{$search}%")
                    ->orWhere('order_status', 'like', "%{$search}%")
                    ->orWhere('production_status', 'like', "%{$search}%")
                    ->orWhere('payment_status', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                          ->orWhere('username', 'like', "%{$search}%")
                          ->orWhere('whatsapp_number', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('customDesign', function ($cd) use ($search) {
                        $cd->where('category', 'like', "%{$search}%")
                           ->orWhere('wood_material', 'like', "%{$search}%")
                           ->orWhere('color_name', 'like', "%{$search}%");
                    });

                foreach ($terms as $term) {
                    $sub->orWhere('order_number', 'like', "%{$term}%")
                        ->orWhere('recipient_name', 'like', "%{$term}%")
                        ->orWhere('recipient_phone', 'like', "%{$term}%");
                }
            });
        }

        $listRiwayat = $query->latest()->get();
        return view('admin.riwayat', compact('listRiwayat'));
    }

    /**
     * Hapus riwayat pesanan
     */
    public function destroyRiwayat($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return back()->with('success', 'Data riwayat pesanan berhasil dihapus!');
    }
}
