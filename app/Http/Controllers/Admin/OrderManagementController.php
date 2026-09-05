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
        return view('admin.pesanan_masuk', compact('listPesananMasuk'));
    }

    /**
     * 1. Konfirmasi & Terima Pesanan oleh Admin
     */
    public function confirmOrder(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'order_status' => 'Diterima',
            'production_status' => 'Diterima',
            'payment_status' => 'Menunggu Pembayaran DP',
            'current_stage' => 'Validasi Pembayaran',
            'admin_notes' => 'Pesanan telah diperiksa dan diterima oleh admin. Silakan lakukan pembayaran DP.',
        ]);

        // Tandai step 1 (Konfirmasi Pesanan) selesai
        OrderProgress::where('order_id', $order->id)->where('step_number', 1)->update([
            'status' => 'Selesai',
            'completed_at' => now(),
            'notes' => 'Pesanan diterima & disetujui oleh admin'
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

        return back()->with('success', 'Pesanan #' . $order->order_number . ' BERHASIL DITERIMA! Pelanggan sekarang dapat melakukan pembayaran DP.');
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
            'production_status' => 'Dalam Pengerjaan',
            'current_stage' => 'Pesanan Diterima',
            'admin_notes' => 'Pembayaran DP telah diverifikasi. Pesanan masuk ke tahap pengerjaan.',
        ]);

        // Update step 2 & 3
        OrderProgress::where('order_id', $order->id)->where('step_number', 2)->update([
            'status' => 'Selesai', 
            'completed_at' => now(),
            'notes' => 'Pembayaran DP diverifikasi oleh admin'
        ]);
        OrderProgress::where('order_id', $order->id)->where('step_number', 3)->update([
            'status' => 'Sedang Berjalan',
            'notes' => 'Pesanan masuk antrean pengerjaan pengrajin'
        ]);

        // Trigger Notifikasi WhatsApp
        try {
            app(\App\Services\WhatsAppNotificationService::class)->sendDPVerified($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        return back()->with('success', 'Pembayaran DP pesanan #' . $order->order_number . ' BERHASIL DIVERIFIKASI! Pesanan masuk ke pengerjaan.');
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
            'admin_notes' => 'Pelunasan sisa tagihan telah diverifikasi oleh admin. Pembayaran lunas.',
        ]);

        return back()->with('success', 'Pelunasan pesanan #' . $order->order_number . ' BERHASIL DIVERIFIKASI! Status pembayaran lunas.');
    }

    /**
     * Halaman Kelola Progres Produksi
     */
    public function progresProduksi($id = null)
    {
        if ($id) {
            $progres = Order::with(['user', 'customDesign', 'progresses'])->findOrFail($id);
        } else {
            $progres = Order::with(['user', 'customDesign', 'progresses'])
                ->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan'])
                ->latest()
                ->first();
        }

        $allOrders = Order::with('customDesign')->latest()->get();

        return view('admin.progres_produksi', compact('progres', 'allOrders'));
    }

    /**
     * Update progres produksi pesanan
     */
    public function updateProgres(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'tahap' => 'required|string',
            'catatan' => 'nullable|string',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4,mov|max:20480',
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

        $currentStepNum = $stageMap[$request->tahap] ?? 3;

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
            ->where('step_number', $currentStepNum)
            ->first();

        if ($progressStep) {
            $existingMedia = $progressStep->media_files ?? [];
            $allMedia = array_merge($existingMedia, $uploadedMedia);

            $progressStep->update([
                'status' => 'Sedang Berjalan',
                'media_files' => $allMedia,
                'notes' => $request->catatan,
                'completed_at' => now(),
            ]);
        }

        // Tandai step-step sebelumnya sebagai selesai
        OrderProgress::where('order_id', $order->id)
            ->where('step_number', '<', $currentStepNum)
            ->update(['status' => 'Selesai']);

        $order->update([
            'current_stage' => $request->tahap,
            'admin_notes' => $request->catatan,
            'production_status' => $currentStepNum == 8 ? 'Selesai' : ($currentStepNum >= 4 ? 'Dalam Pengerjaan' : 'Antrean Produksi')
        ]);

        // Trigger Notifikasi WhatsApp Otomatis
        try {
            $waService = app(\App\Services\WhatsAppNotificationService::class);
            if ($currentStepNum == 8) {
                $waService->sendOrderFinished($order);
            } else {
                $waService->sendProgressUpdated($order, $request->tahap, $request->catatan);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        return back()->with('success', 'Progres produksi pesanan #' . $order->order_number . ' tahap "' . $request->tahap . '" berhasil diperbarui!');
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
