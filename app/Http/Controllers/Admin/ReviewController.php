<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Daftar ulasan produk untuk moderasi admin.
     */
    public function index(Request $request)
    {
        $query = ProductReview::with(['produk', 'user', 'order'])->latest();

        $activeTab = $request->query('tab', 'all');
        if ($activeTab === 'menunggu') {
            $query->where('status', 'Menunggu Persetujuan');
        } elseif ($activeTab === 'disetujui') {
            $query->where('status', 'Disetujui');
        } elseif ($activeTab === 'ditolak') {
            $query->where('status', 'Ditolak');
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($sub) use ($search) {
                $sub->where('comment', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhereHas('produk', fn ($p) => $p->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $reviews = $query->get();

        $tabCounts = [
            'all' => ProductReview::count(),
            'menunggu' => ProductReview::where('status', 'Menunggu Persetujuan')->count(),
            'disetujui' => ProductReview::where('status', 'Disetujui')->count(),
            'ditolak' => ProductReview::where('status', 'Ditolak')->count(),
        ];

        return view('admin.ulasan', compact('reviews', 'activeTab', 'tabCounts'));
    }

    /**
     * Setujui ulasan agar tampil di halaman publik.
     */
    public function approve($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update([
            'status' => 'Disetujui',
            'admin_note' => null,
        ]);

        return back()->with('success', 'Ulasan dari ' . ($review->user->name ?? 'Pelanggan') . ' telah disetujui dan ditampilkan di halaman produk.');
    }

    /**
     * Tolak ulasan dengan alasan.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'required|string|max:500',
        ], [
            'admin_note.required' => 'Alasan penolakan ulasan wajib diisi.',
        ]);

        $review = ProductReview::findOrFail($id);
        $review->update([
            'status' => 'Ditolak',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('warning', 'Ulasan telah ditolak dan disembunyikan dari halaman produk.');
    }

    /**
     * Hapus permanen ulasan.
     */
    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);
        $author = $review->user->name ?? 'Pelanggan';
        $review->delete();

        return back()->with('success', "Ulasan dari {$author} berhasil dihapus permanen.");
    }
}
