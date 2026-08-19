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
    public function design()
    {
        $settings = StudioSetting::pluck('value', 'key');
        $shippingCosts = ShippingCost::where('status', 'Aktif')->get();
        return view('customer.design', compact('settings', 'shippingCosts'));
    }

    /**
     * Store a new custom furniture order (Requires Authentication)
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk (login) atau daftar akun terlebih dahulu untuk mengajukan pesanan mebel custom!');
        }

        $request->validate([
            'category' => 'required|string',
            'length_cm' => 'required|numeric|min:10',
            'width_cm' => 'required|numeric|min:10',
            'height_cm' => 'required|numeric|min:10',
            'wood_material' => 'required|string',
            'color_name' => 'required|string',
            'color_hex' => 'required|string',
            'tone_percent' => 'nullable|numeric',
            'sketch_image' => 'nullable|image|max:5120',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Formula kalkulasi estimasi harga berdasarkan volume dan material
        $volume = ($request->length_cm * $request->width_cm * $request->height_cm) / 1000000; // dalam m3
        $baseMaterialPrice = 3000000;
        if (str_contains(strtolower($request->wood_material), 'jati')) {
            $baseMaterialPrice = 4500000;
        } elseif (str_contains(strtolower($request->wood_material), 'mahoni')) {
            $baseMaterialPrice = 3500000;
        }

        $calculatedPrice = round(max(2500000, $baseMaterialPrice * max(0.8, $volume * 2)), -4);
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

        // Buat Order
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount,
            'shipping_cost' => $shippingCost,
            'remaining_payment' => $remainingPayment,
            'payment_method' => $request->payment_method ?? 'qris',
            'payment_status' => 'DP Terverifikasi',
            'production_status' => 'Antrean Produksi',
            'current_stage' => 'Konfirmasi Pesanan',
            'recipient_name' => $user->name,
            'recipient_phone' => $user->whatsapp_number,
            'shipping_address' => $user->alamat ?? 'Surabaya, Jawa Timur',
            'customer_notes' => $request->notes,
        ]);

        // Buat Custom Design
        CustomDesign::create([
            'order_id' => $order->id,
            'category' => $request->category,
            'length_cm' => $request->length_cm,
            'width_cm' => $request->width_cm,
            'height_cm' => $request->height_cm,
            'wood_material' => $request->wood_material,
            'color_name' => $request->color_name,
            'color_hex' => $request->color_hex,
            'tone_percent' => $request->tone_percent ?? 100,
            'sketch_image' => $sketchPath,
            'notes' => $request->notes,
        ]);

        // Inisialisasi 8 tahapan progres produksi
        $stages = [
            ['step' => 1, 'name' => 'Konfirmasi Pesanan', 'status' => 'Selesai', 'completed_at' => now()],
            ['step' => 2, 'name' => 'Validasi Pembayaran', 'status' => 'Selesai', 'completed_at' => now()],
            ['step' => 3, 'name' => 'Pesanan Diterima', 'status' => 'Sedang Berjalan', 'completed_at' => null],
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
                'notes' => $stage['step'] === 1 ? 'Pesanan berhasil dibuat oleh pelanggan' : null,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesanan custom berhasil diajukan!',
                'redirect' => route('customer.progress')
            ]);
        }

        return redirect()->route('customer.progress')->with('success', 'Pesanan custom berhasil diajukan dan masuk ke tahap antrean produksi!');
    }

    /**
     * Show the active order progress
     */
    public function progress()
    {
        $user = Auth::user();
        $order = Order::with(['customDesign', 'progresses', 'items'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('customer.progress', compact('order'));
    }

    /**
     * Selesaikan pelunasan sisa tagihan
     */
    public function payRemaining(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $order->update([
            'remaining_payment' => 0,
            'payment_status' => 'Lunas'
        ]);

        return back()->with('success', 'Pelunasan berhasil diverifikasi!');
    }

    /**
     * Konfirmasi pesanan selesai
     */
    public function confirmCompleted(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $order->update([
            'production_status' => 'Selesai',
            'current_stage' => 'Pesanan Selesai'
        ]);

        // Tandai step 8 selesai
        $step8 = OrderProgress::where('order_id', $order->id)->where('step_number', 8)->first();
        if ($step8) {
            $step8->update([
                'status' => 'Selesai',
                'completed_at' => now()
            ]);
        }

        return redirect()->route('customer.riwayat')->with('success', 'Terima kasih atas konfirmasi Anda! Pesanan telah selesai.');
    }

    /**
     * Show order history
     */
    public function riwayat()
    {
        $user = Auth::user();
        $orders = Order::with(['customDesign', 'progresses', 'items'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('customer.riwayat', compact('orders'));
    }
}
