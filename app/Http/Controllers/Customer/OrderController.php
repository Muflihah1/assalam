<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\CustomDesign;
use App\Models\OrderProgress;
use App\Models\StudioSetting;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Show the custom design workbench studio (Public Access)
     */
    public function design(Request $request)
    {
        $settings = StudioSetting::pluck('value', 'key');
        $shippingCosts = ShippingCost::where('status', 'Aktif')->get();
        $katalogs = \App\Models\Produk::latest()->get();
        
        $selectedProduct = null;
        if ($request->filled('product_id')) {
            $selectedProduct = \App\Models\Produk::find($request->product_id);
        }

        // Jika tidak ada ID produk tertentu, pasang produk pertama sebagai model dasar
        if (!$selectedProduct && $katalogs->isNotEmpty()) {
            $selectedProduct = $katalogs->first();
        }

        $paymentSettings = \App\Models\Setting::pluck('value', 'key');

        return view('customer.design', compact('settings', 'shippingCosts', 'selectedProduct', 'katalogs', 'paymentSettings'));
    }

    /**
     * Store a new custom furniture order (Requires Authentication)
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk (login) atau daftar akun terlebih dahulu untuk mengajukan pesanan mebel custom!');
        }

        // Guard: akun admin tidak boleh mengajukan desain/pesanan
        if (Auth::user()->role === 'admin') {
            $message = 'Akun administrator tidak diizinkan melakukan pengajuan desain atau pemesanan. Silakan gunakan akun pelanggan untuk bertransaksi.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 403);
            }

            return redirect()->route('customer.katalog')->with('error', $message);
        }

        $request->validate([
            'product_id' => 'required|exists:produks,id',
            'category' => 'required|string|max:100',
            'length_cm' => 'required|numeric|min:20|max:600',
            'width_cm' => 'required|numeric|min:4|max:500',
            'height_cm' => 'required|numeric|min:2|max:500',
            'wood_material' => 'nullable|string|max:100',
            'color_name' => 'required|string|max:100',
            'color_hex' => 'required|string|max:20',
            'tone_percent' => 'nullable|numeric|min:40|max:160',
            'sketch_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'notes' => 'nullable|string|max:1000',
        ], [
            'product_id.required' => 'Model produk dasar mebel wajib dipilih.',
            'category.required' => 'Kategori mebel wajib dipilih.',
            'length_cm.required' => 'Panjang mebel wajib diisi.',
            'length_cm.min' => 'Panjang minimal 20 cm.',
            'width_cm.required' => 'Lebar mebel wajib diisi.',
            'width_cm.min' => 'Lebar minimal 4 cm.',
            'height_cm.required' => 'Tinggi mebel wajib diisi.',
            'height_cm.min' => 'Tinggi minimal 2 cm.',
            'color_name.required' => 'Warna finishing mebel wajib dipilih.',
            'sketch_image.image' => 'Berkas sketsa harus berupa gambar (JPG, PNG, WEBP).',
            'sketch_image.max' => 'Ukuran sketsa gambar maksimal 5MB.',
        ]);

        $user = Auth::user();

        // Pilihan kayu dikunci secara permanen pada Kayu Jati Solid Grade A
        $woodMaterial = 'Kayu Jati Solid Grade A (Perhutani)';

        // Ambil produk katalog referensi
        $refProduct = \App\Models\Produk::findOrFail($request->product_id);
        $baseProductPrice = (float) $refProduct->harga;

        // Volume kubikasi mebel custom
        $volume = ($request->length_cm * $request->width_cm * $request->height_cm) / 1000000; // m3
        $standardVolume = (180 * 80 * 75) / 1000000; // 1.08 m3
        $volumeRatio = max(0.75, min(2.5, $volume / $standardVolume));

        // Kalkulasi harga berdasarkan harga produk jati dasar katalog dikali rasio ukuran
        $calculatedPrice = round(($baseProductPrice * $volumeRatio), -4);
        $shippingCost = 50000;
        $totalPrice = $calculatedPrice + $shippingCost;
        $dpAmount = round($totalPrice * 0.5);
        $remainingPayment = $totalPrice - $dpAmount;

        // Upload sketsa jika ada
        $sketchPath = null;
        if ($request->hasFile('sketch_image')) {
            $sketchPath = $request->file('sketch_image')->store('custom_sketches', 'public');
        }

        $orderNumber = 'ORD-' . strtoupper(Str::random(4)) . rand(1000, 9999);

        // Buat Order dengan alur status: Menunggu Konfirmasi dari Admin & Metode DANA
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount,
            'shipping_cost' => $shippingCost,
            'remaining_payment' => $remainingPayment,
            'payment_method' => 'dana',
            'order_status' => 'Menunggu Konfirmasi',
            'payment_status' => 'Belum Bayar',
            'production_status' => 'Menunggu Konfirmasi',
            'current_stage' => 'Konfirmasi Pesanan',
            'recipient_name' => $user->name,
            'recipient_phone' => $user->whatsapp_number,
            'shipping_address' => $user->alamat ?? 'Alamat belum diatur',
            'customer_notes' => $request->notes,
        ]);

        // Buat Custom Design dengan relasi ke product_id katalog
        CustomDesign::create([
            'order_id' => $order->id,
            'product_id' => $refProduct->id,
            'category' => $refProduct->kategori ?? $request->category,
            'length_cm' => $request->length_cm,
            'width_cm' => $request->width_cm,
            'height_cm' => $request->height_cm,
            'wood_material' => $woodMaterial,
            'color_name' => $request->color_name,
            'color_hex' => $request->color_hex,
            'tone_percent' => $request->tone_percent ?? 100,
            'sketch_image' => $sketchPath,
            'notes' => $request->notes,
        ]);

        // Inisialisasi 8 tahapan progres produksi
        $stages = [
            ['step' => 1, 'name' => 'Konfirmasi Pesanan', 'status' => 'Sedang Berjalan', 'completed_at' => null],
            ['step' => 2, 'name' => 'Validasi Pembayaran', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 3, 'name' => 'Pesanan Diterima', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 4, 'name' => 'Menyiapkan Bahan', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 5, 'name' => 'Perakitan', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 6, 'name' => 'Penyelesaian', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 7, 'name' => 'Pengiriman', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 8, 'name' => 'Pesanan Selesai', 'status' => 'Pending', 'completed_at' => null],
        ];

        foreach ($stages as $stage) {
            OrderProgress::create([
                'order_id' => $order->id,
                'step_number' => $stage['step'],
                'stage_name' => $stage['name'],
                'status' => $stage['status'],
                'completed_at' => $stage['completed_at'],
                'notes' => $stage['step'] === 1 ? 'Menunggu peninjauan dan konfirmasi pesanan oleh admin' : null,
            ]);
        }

        // Trigger Notifikasi WhatsApp Otomatis
        try {
            app(\App\Services\WhatsAppNotificationService::class)->sendOrderCreated($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan custom berhasil diajukan! Mohon tunggu konfirmasi admin.',
                'redirect' => route('customer.progress')
            ]);
        }

        return redirect()->route('customer.progress')->with('success', 'Pesanan custom berhasil diajukan! Pesanan Anda saat ini sedang menunggu konfirmasi admin.');
    }

    /**
     * Show the active order progress
     */
    public function progress()
    {
        $user = Auth::user();
        $order = Order::with(['customDesign.produk', 'progresses', 'items'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('customer.progress', compact('order'));
    }

    /**
     * Upload Bukti Pembayaran DP (Setelah Pesanan Diterima Admin)
     */
    public function uploadDP(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'dp_receipt_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'dp_receipt_proof.required' => 'Bukti transfer DP wajib diunggah.',
            'dp_receipt_proof.image' => 'Berkas bukti transfer harus berupa gambar (JPG, PNG, WEBP).',
            'dp_receipt_proof.max' => 'Ukuran berkas maksimal 5MB.',
        ]);

        $path = $request->file('dp_receipt_proof')->store('receipts', 'public');

        $order->update([
            'dp_receipt_proof' => $path,
            'payment_status' => 'Menunggu Verifikasi DP',
            'rejection_reason' => null, // Reset alasan penolakan sebelumnya
            'admin_notes' => 'Pelanggan telah mengunggah bukti pembayaran DP baru. Menunggu verifikasi admin.',
        ]);

        return back()->with('success', 'Bukti transfer DP berhasil diunggah! Admin akan segera memverifikasi pembayaran Anda.');
    }

    /**
     * Unggah Bukti Pelunasan Sisa Tagihan (Aman & Tidak Auto Lunas)
     */
    public function payRemaining(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'final_receipt_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'final_receipt_proof.required' => 'Bukti transfer pelunasan wajib diunggah.',
            'final_receipt_proof.image' => 'Berkas bukti transfer harus berupa gambar (JPG, PNG, WEBP).',
            'final_receipt_proof.max' => 'Ukuran berkas maksimal 5MB.',
        ]);

        $path = $request->file('final_receipt_proof')->store('receipts', 'public');

        $order->update([
            'final_receipt_proof' => $path,
            'payment_status' => 'Menunggu Verifikasi Pelunasan',
            'rejection_reason' => null, // Reset alasan penolakan sebelumnya
            'admin_notes' => 'Pelanggan telah mengunggah bukti pelunasan baru. Menunggu verifikasi admin.',
        ]);

        return back()->with('success', 'Bukti transfer pelunasan berhasil diunggah! Pembayaran akan diverifikasi oleh admin.');
    }

    /**
     * Batalkan Pesanan oleh Pelanggan (Hanya pada tahap Menunggu Konfirmasi)
     */
    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // Hanya boleh dibatalkan jika belum disetujui / belum diproses
        if ($order->order_status !== 'Menunggu Konfirmasi') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan secara mandiri karena sudah disetujui oleh admin atau telah memasuki tahap pengerjaan. Silakan hubungi admin via WhatsApp untuk bantuan.');
        }

        $reason = $request->input('reason', 'Dibatalkan oleh pelanggan');

        $order->update([
            'order_status' => 'Dibatalkan',
            'production_status' => 'Dibatalkan',
            'payment_status' => 'Dibatalkan',
            'rejection_reason' => $reason,
            'admin_notes' => 'Pesanan dibatalkan sendiri oleh pelanggan: ' . $reason,
        ]);

        // Update step 1 progress menjadi Dibatalkan
        OrderProgress::where('order_id', $order->id)->where('step_number', 1)->update([
            'status' => 'Dibatalkan',
            'notes' => 'Pesanan dibatalkan oleh pemesan: ' . $reason,
        ]);

        return redirect()->route('customer.riwayat')->with('success', 'Pesanan #' . $order->order_number . ' telah berhasil dibatalkan.');
    }

    /**
     * Konfirmasi pesanan selesai oleh pelanggan
     */
    public function confirmCompleted(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // 1. Validasi: Pesanan harus sudah disetujui admin dan tidak dibatalkan/ditolak
        if (in_array($order->order_status, ['Menunggu Konfirmasi', 'Ditolak', 'Dibatalkan'])) {
            return back()->with('error', 'Pesanan belum dapat diselesaikan karena belum disetujui oleh admin atau telah dibatalkan.');
        }

        // 2. Validasi: Pembayaran harus sudah lunas (DP dan sisa pelunasan)
        if ($order->payment_status !== 'Lunas' && $order->remaining_payment > 0) {
            return back()->with('error', 'Pesanan belum dapat diselesaikan karena tagihan pembayaran belum lunas. Silakan lakukan pembayaran DP atau pelunasan sisa terlebih dahulu.');
        }

        // 3. Validasi: Mebel harus sudah mencapai tahap pengiriman / selesai dikerjakan
        $allowedStages = ['Pengiriman', 'Penyelesaian', 'Pesanan Selesai'];
        if (!in_array($order->current_stage, $allowedStages) && !in_array($order->production_status, ['Pengiriman', 'Selesai'])) {
            return back()->with('error', 'Pesanan belum dapat diselesaikan karena produk mebel masih dalam proses pengerjaan di workshop (' . $order->current_stage . ').');
        }

        $order->update([
            'order_status' => 'Selesai',
            'production_status' => 'Selesai',
            'current_stage' => 'Pesanan Selesai'
        ]);

        // Tandai step 8 selesai
        $step8 = OrderProgress::where('order_id', $order->id)->where('step_number', 8)->first();
        if ($step8) {
            $step8->update([
                'status' => 'Selesai',
                'completed_at' => now(),
                'notes' => 'Pesanan telah diterima dengan baik dan diselesaikan oleh pelanggan.'
            ]);
        }

        // Trigger Notifikasi WhatsApp Otomatis
        try {
            app(\App\Services\WhatsAppNotificationService::class)->sendPaymentCompleted($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
        }

        return redirect()->route('customer.riwayat')->with('success', 'Terima kasih atas konfirmasi Anda! Pesanan telah selesai dan tercatat di riwayat transaksi.');
    }

    /**
     * Show order history with tab filtering
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();
        $query = Order::with(['customDesign.produk', 'progresses', 'items'])
            ->where('user_id', $user->id);

        $activeTab = $request->query('tab', 'all');
        if ($activeTab === 'menunggu_konfirmasi') {
            $query->where('order_status', 'Menunggu Konfirmasi');
        } elseif ($activeTab === 'menunggu_bayar') {
            $query->where(function($q) {
                $q->whereIn('payment_status', ['Menunggu Pembayaran DP', 'Menunggu Verifikasi DP', 'Menunggu Verifikasi Pelunasan', 'Bukti DP Ditolak', 'Bukti Pelunasan Ditolak'])
                  ->orWhere(function($sub) {
                      $sub->where('payment_status', 'DP Terverifikasi')
                          ->where('remaining_payment', '>', 0)
                          ->whereIn('current_stage', ['Penyelesaian', 'Pengiriman']);
                  });
            });
        } elseif ($activeTab === 'diproses') {
            $query->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan', 'Penyelesaian']);
        } elseif ($activeTab === 'dikirim') {
            $query->where(function($q) {
                $q->where('production_status', 'Pengiriman')
                  ->orWhere('current_stage', 'Pengiriman');
            });
        } elseif ($activeTab === 'selesai') {
            $query->where(function($q) {
                $q->where('order_status', 'Selesai')
                  ->orWhere('production_status', 'Selesai');
            });
        } elseif ($activeTab === 'batal') {
            $query->whereIn('order_status', ['Ditolak', 'Dibatalkan']);
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($sub) use ($search) {
                $sub->where('order_number', 'like', "%{$search}%")
                    ->orWhere('order_status', 'like', "%{$search}%")
                    ->orWhere('production_status', 'like', "%{$search}%")
                    ->orWhereHas('customDesign', function ($cd) use ($search) {
                        $cd->where('category', 'like', "%{$search}%")
                           ->orWhere('wood_material', 'like', "%{$search}%")
                           ->orWhere('color_name', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->latest()->get();

        // Hitung count per tab untuk badges
        $tabCounts = [
            'all' => Order::where('user_id', $user->id)->count(),
            'menunggu_konfirmasi' => Order::where('user_id', $user->id)->where('order_status', 'Menunggu Konfirmasi')->count(),
            'menunggu_bayar' => Order::where('user_id', $user->id)->where(function($q) {
                $q->whereIn('payment_status', ['Menunggu Pembayaran DP', 'Menunggu Verifikasi DP', 'Menunggu Verifikasi Pelunasan', 'Bukti DP Ditolak', 'Bukti Pelunasan Ditolak']);
            })->count(),
            'diproses' => Order::where('user_id', $user->id)->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan', 'Penyelesaian'])->count(),
            'dikirim' => Order::where('user_id', $user->id)->where(function($q) {
                $q->where('production_status', 'Pengiriman')->orWhere('current_stage', 'Pengiriman');
            })->count(),
            'selesai' => Order::where('user_id', $user->id)->where(function($q) {
                $q->where('order_status', 'Selesai')->orWhere('production_status', 'Selesai');
            })->count(),
            'batal' => Order::where('user_id', $user->id)->whereIn('order_status', ['Ditolak', 'Dibatalkan'])->count(),
        ];

        return view('customer.riwayat', compact('orders', 'activeTab', 'tabCounts'));
    }
}
