<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderProgress;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Tampilkan Halaman Keranjang Belanja
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $shippingCosts = ShippingCost::where('status', 'Aktif')->get();
        $defaultShipping = $shippingCosts->first()->biaya ?? 50000;
        $total = $subtotal > 0 ? ($subtotal + $defaultShipping) : 0;
        $dpAmount = round($total * 0.5);

        return view('customer.cart', compact('cart', 'subtotal', 'shippingCosts', 'defaultShipping', 'total', 'dpAmount'));
    }

    /**
     * Tambah Produk Katalog ke Keranjang Belanja
     */
    public function add(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $cart = session()->get('cart', []);

        $cartKey = 'prod_' . $produk->id;
        $qtyToAdd = (int) $request->input('quantity', 1);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $qtyToAdd;
        } else {
            $cart[$cartKey] = [
                'type' => 'katalog',
                'product_id' => $produk->id,
                'name' => $produk->nama,
                'price' => (float) $produk->harga,
                'quantity' => $qtyToAdd,
                'image' => $produk->foto,
                'description' => $produk->deskripsi,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk "' . $produk->nama . '" berhasil ditambahkan ke keranjang!',
                'cartCount' => count($cart),
            ]);
        }

        return redirect()->back()->with('success', 'Produk "' . $produk->nama . '" berhasil dimasukkan ke keranjang belanja!');
    }

    /**
     * Ubah Kuantitas Item di Keranjang
     */
    public function update(Request $request, $key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $action = $request->input('action'); // 'increase', 'decrease', or direct qty
            
            if ($action === 'increase') {
                $cart[$key]['quantity'] += 1;
            } elseif ($action === 'decrease') {
                $cart[$key]['quantity'] -= 1;
                if ($cart[$key]['quantity'] <= 0) {
                    unset($cart[$key]);
                }
            } elseif ($request->has('quantity')) {
                $qty = (int) $request->input('quantity');
                if ($qty <= 0) {
                    unset($cart[$key]);
                } else {
                    $cart[$key]['quantity'] = $qty;
                }
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart')->with('success', 'Keranjang belanja berhasil diperbarui!');
    }

    /**
     * Hapus Item dari Keranjang
     */
    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $itemName = $cart[$key]['name'];
            unset($cart[$key]);
            session()->put('cart', $cart);
            return redirect()->route('customer.cart')->with('success', 'Item "' . $itemName . '" berhasil dihapus dari keranjang.');
        }

        return redirect()->route('customer.cart');
    }

    /**
     * Proses Checkout Keranjang Belanja Menjadi Pesanan Nyata
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.katalog')->with('error', 'Keranjang belanja Anda masih kosong!');
        }

        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:25',
            'shipping_address' => 'required|string|max:500',
            'shipping_cost' => 'required|numeric',
            'payment_method' => 'required|string',
            'customer_notes' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Hitung total belanja
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        $shippingCost = (float) $request->shipping_cost;
        $totalPrice = $subtotal + $shippingCost;
        $dpAmount = round($totalPrice * 0.5);
        $remainingPayment = $totalPrice - $dpAmount;

        $orderNumber = 'ORD-' . strtoupper(Str::random(4)) . rand(1000, 9999);

        // 1. Buat Header Pesanan
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => $user->id,
            'total_price' => $totalPrice,
            'dp_amount' => $dpAmount,
            'shipping_cost' => $shippingCost,
            'remaining_payment' => $remainingPayment,
            'payment_method' => $request->payment_method,
            'payment_status' => 'DP Terverifikasi', // Otomatis terverifikasi untuk flow demo / simulasi pembayaran sukses
            'production_status' => 'Antrean Produksi',
            'current_stage' => 'Konfirmasi Pesanan',
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'shipping_address' => $request->shipping_address,
            'customer_notes' => $request->customer_notes,
        ]);

        // 2. Simpan Item-Item Belanja
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'produk_id' => $item['product_id'] ?? null,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['price'] * $item['quantity'],
                'image' => $item['image'] ?? null,
            ]);
        }

        // 3. Inisialisasi 8 Tahapan Progres
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
                'notes' => $stage['step'] === 1 ? 'Pesanan katalog berhasil di-checkout oleh pelanggan' : null,
            ]);
        }

        // 4. Kosongkan keranjang belanja
        session()->forget('cart');

        return redirect()->route('customer.progress')->with('success', 'Pesanan #' . $order->order_number . ' berhasil dibuat! Silakan pantau pengerjaan dan pengiriman di timeline progres.');
    }
}
