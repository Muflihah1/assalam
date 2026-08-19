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
        $newOrdersCount = Order::where('production_status', 'Menunggu Konfirmasi')->orWhere('payment_status', 'Menunggu Pembayaran DP')->count();
        $inProductionCount = Order::whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan'])->count();
        $totalCustomersCount = User::where('role', 'customer')->count();
        
        $recentOrders = Order::with(['user', 'customDesign'])->latest()->take(5)->get();
        $inProgressOrders = Order::with('customDesign')->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan'])->latest()->take(6)->get();

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
    public function pesananMasuk()
    {
        $listPesananMasuk = Order::with(['user', 'customDesign', 'progresses'])->latest()->get();
        return view('admin.pesanan_masuk', compact('listPesananMasuk'));
    }

    /**
     * Verifikasi Pembayaran DP & Ubah Status
     */
    public function verifyDP(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status_progres' => 'required|string',
        ]);

        if (str_contains($request->status_progres, 'Ditolak')) {
            $order->update([
                'payment_status' => 'Ditolak',
                'production_status' => 'Menunggu Konfirmasi',
            ]);
        } else {
            $order->update([
                'payment_status' => 'DP Terverifikasi',
                'production_status' => 'Dalam Pengerjaan',
                'current_stage' => 'Pesanan Diterima',
            ]);

            // Update step 2 & 3
            OrderProgress::where('order_id', $order->id)->where('step_number', 2)->update(['status' => 'Selesai', 'completed_at' => now()]);
            OrderProgress::where('order_id', $order->id)->where('step_number', 3)->update(['status' => 'Sedang Berjalan']);

            // Trigger Notifikasi WhatsApp
            try {
                app(\App\Services\WhatsAppNotificationService::class)->sendDPVerified($order);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::info("WA Notification trigger error: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Status pembayaran DP dan antrean pesanan #' . $order->order_number . ' berhasil diperbarui!');
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
    public function riwayat()
    {
        $listRiwayat = Order::with(['user', 'customDesign', 'progresses'])->latest()->get();
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
