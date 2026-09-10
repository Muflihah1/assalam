<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ProductReview;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Simpan ulasan baru untuk produk katalog.
     * Hanya akun pelanggan (bukan admin) yang boleh mengirim ulasan.
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan masuk (login) terlebih dahulu untuk menulis ulasan produk.');
        }

        if ($user->role === 'admin') {
            $message = 'Akun administrator tidak diizinkan menulis ulasan produk. Gunakan akun pelanggan.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 403);
            }

            return redirect()->route('customer.produk.detail', $id)->with('error', $message);
        }

        $produk = Produk::findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:150',
            'comment' => 'required|string|min:5|max:2000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'order_item_id' => 'nullable|integer|exists:order_items,id',
        ], [
            'rating.required' => 'Rating bintang wajib dipilih (1-5).',
            'rating.integer' => 'Rating harus berupa angka bintang 1 sampai 5.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'comment.required' => 'Komentar ulasan wajib diisi.',
            'comment.min' => 'Komentar ulasan minimal 5 karakter.',
            'comment.max' => 'Komentar ulasan maksimal 2000 karakter.',
            'photo.image' => 'Berkas foto ulasan harus berupa gambar (JPG, PNG, WEBP).',
            'photo.max' => 'Ukuran foto ulasan maksimal 3MB.',
        ]);

        // Verifikasi pembeli: apakah user pernah membeli produk ini (order items terhubung)
        $verifiedBuyer = false;
        $orderId = null;

        if ($request->filled('order_item_id')) {
            $orderItem = OrderItem::where('id', $request->order_item_id)
                ->where('produk_id', $produk->id)
                ->whereHas('order', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->whereNotIn('order_status', ['Menunggu Konfirmasi', 'Ditolak', 'Dibatalkan']);
                })
                ->first();

            if ($orderItem) {
                $verifiedBuyer = true;
                $orderId = $orderItem->order_id;
            }
        } else {
            // Deteksi otomatis dari riwayat pesanan pelanggan
            $orderItem = OrderItem::where('produk_id', $produk->id)
                ->whereHas('order', function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->whereNotIn('order_status', ['Menunggu Konfirmasi', 'Ditolak', 'Dibatalkan']);
                })
                ->first();

            if ($orderItem) {
                $verifiedBuyer = true;
                $orderId = $orderItem->order_id;
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('review_photos', 'public');
        }

        ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $user->id,
            'order_id' => $orderId,
            'rating' => (int) $request->rating,
            'title' => $request->title,
            'comment' => trim($request->comment),
            'photo' => $photoPath,
            'is_verified_buyer' => $verifiedBuyer,
            'status' => 'Menunggu Persetujuan',
        ]);

        return redirect()
            ->route('customer.produk.detail', $produk->id)
            ->with('success', 'Terima kasih! Ulasan Anda telah dikirim dan akan tampil setelah diverifikasi admin.');
    }

    /**
     * Hapus ulasan milik sendiri.
     */
    public function destroy(Request $request, $reviewId)
    {
        $user = Auth::user();

        $review = ProductReview::where('user_id', $user->id)->findOrFail($reviewId);
        $produkId = $review->produk_id;
        $review->delete();

        return redirect()
            ->route('customer.produk.detail', $produkId)
            ->with('success', 'Ulasan Anda berhasil dihapus.');
    }
}
