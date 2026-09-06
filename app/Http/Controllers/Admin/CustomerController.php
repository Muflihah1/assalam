<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< Updated upstream
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('q')) {
            $search = trim($request->q);
            $terms = array_filter(preg_split('/\s+/', $search));
            $query->where(function ($sub) use ($search, $terms) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp_number', 'like', "%{$search}%");

                foreach ($terms as $term) {
                    $sub->orWhere('name', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('whatsapp_number', 'like', "%{$term}%");
                }
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();
        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.data_pelanggan', compact('customers', 'totalCustomers'));
=======
        // Ambil pesanan aktif milik user beserta semua relasinya
        $order = Order::with(['customDesign', 'items', 'progresses'])
            ->where('user_id', Auth::id())
            ->whereIn('production_status', ['Diterima', 'Dalam Proses', 'Selesai'])
            ->latest()
            ->first();

        return view('customer.progress', compact('order'));
>>>>>>> Stashed changes
    }

    public function payRemaining(Request $request, $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        // Ubah status pembayaran / sisa pelunasan
        $order->update([
            'payment_status' => 'pending_verification', // Menunggu verifikasi admin
            'remaining_payment' => 0,
        ]);

        return redirect()->back()->with('success', 'Konfirmasi pelunasan berhasil dikirim. Menunggu verifikasi admin.');
    }

    public function confirmCompleted($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        $order->update([
            'production_status' => 'Selesai',
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Pesanan telah dikonfirmasi selesai.');
    }
}