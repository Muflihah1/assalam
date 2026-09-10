<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Halaman Detail Produk + Ulasan lengkap (Komentar & Rating).
     * Publik: tamu dapat membaca ulasan tanpa login.
     */
    public function show(Request $request, $id)
    {
        $produk = Produk::with(['approvedReviews.user'])->findOrFail($id);

        $approvedReviews = $produk->approvedReviews()->with('user')->get();

        $ratingCount = $approvedReviews->count();
        $ratingAverage = $ratingCount > 0 ? round($approvedReviews->avg('rating'), 1) : 0.0;

        // Distribusi rating per bintang (1-5)
        $ratingBreakdown = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = $approvedReviews->where('rating', $star)->count();
            $ratingBreakdown[$star] = [
                'count' => $count,
                'percent' => $ratingCount > 0 ? round(($count / $ratingCount) * 100) : 0,
            ];
        }

        // Filter ulasan berdasarkan bintang (opsional via query ?rating=5)
        $filteredReviews = $approvedReviews;
        $activeFilter = (int) $request->query('rating', 0);
        if ($activeFilter >= 1 && $activeFilter <= 5) {
            $filteredReviews = $approvedReviews->where('rating', $activeFilter)->values();
        }

        $soldCount = $produk->sold_count;

        // Produk terkait sederhana (produk lain terbaru, kecuali produk ini)
        $relatedProducts = Produk::where('id', '!=', $produk->id)
            ->latest()
            ->take(4)
            ->get();

        // Ulasan milik user yang sedang login untuk produk ini (agar bisa edit/hapus status)
        $myReview = null;
        if (auth()->check()) {
            $myReview = ProductReview::where('produk_id', $produk->id)
                ->where('user_id', auth()->id())
                ->latest()
                ->first();
        }

        return view('customer.produk_detail', compact(
            'produk',
            'approvedReviews',
            'filteredReviews',
            'ratingAverage',
            'ratingCount',
            'ratingBreakdown',
            'activeFilter',
            'soldCount',
            'relatedProducts',
            'myReview'
        ));
    }
}
