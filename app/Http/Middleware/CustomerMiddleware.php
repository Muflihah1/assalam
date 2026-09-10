<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware 'customer':
 * Memastikan AKUN ADMIN tidak dapat melakukan aksi transaksi pelanggan
 * (tambah/ubah/hapus keranjang, checkout, pengajuan desain custom, pembayaran).
 * Tamu (belum login) tetap boleh — pembatasan login ditangani controller masing-masing.
 */
class CustomerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            $message = 'Akun administrator tidak diizinkan melakukan pemesanan, pembelian, keranjang belanja, atau pengajuan desain. Gunakan akun pelanggan untuk bertransaksi.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 403);
            }

            if ($request->is('admin/*') || $request->routeIs('admin.*')) {
                return redirect()->route('admin.dashboard')->with('error', $message);
            }

            return redirect()
                ->route('customer.katalog')
                ->with('error', $message);
        }

        return $next($request);
    }
}
