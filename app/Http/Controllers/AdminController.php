<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'Anda tidak memiliki hak akses sebagai admin.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function katalog(Request $request)
    {
        $query = Produk::query();
        $q = trim($request->input('q', $request->input('keyword', '')));
        $tipe = $request->input('tipe');

        if ($tipe && in_array($tipe, ['ready', 'pre_order'])) {
            $query->where('tipe_produk', $tipe);
        }

        if ($q !== '') {
            $terms = array_filter(preg_split('/\s+/', $q));
            $query->where(function ($sub) use ($terms, $q) {
                $sub->where('nama', 'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%");

                $sub->orWhere(function ($allTermsQuery) use ($terms) {
                    foreach ($terms as $term) {
                        $allTermsQuery->where(function ($termQ) use ($term) {
                            $termQ->where('nama', 'like', "%{$term}%")
                                  ->orWhere('deskripsi', 'like', "%{$term}%");
                            if (is_numeric($term)) {
                                $termQ->orWhere('harga', 'like', "%{$term}%");
                            }
                        });
                    }
                });
            });
        }

        $listProduk = $query->latest()->get();
        return view('admin.katalog', compact('listProduk', 'q', 'tipe'));
    }

    public function storeKatalog(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tipe_produk' => 'nullable|in:ready,pre_order',
            'estimasi_po' => 'nullable|max:100',
        ]);

        $path = $request->file('foto')->store('katalog', 'public');
        $tipeProduk = $request->input('tipe_produk', 'pre_order');
        $estimasiPo = $tipeProduk === 'ready' ? null : ($request->input('estimasi_po') ?: '14-21 Hari Kerja');

        Produk::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto' => $path,
            'tipe_produk' => $tipeProduk,
            'estimasi_po' => $estimasiPo,
        ]);

        return redirect()->route('admin.katalog')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function updateKatalog(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'tipe_produk' => 'nullable|in:ready,pre_order',
            'estimasi_po' => 'nullable|max:100',
        ]);

        $tipeProduk = $request->input('tipe_produk', $produk->tipe_produk ?? 'pre_order');
        $estimasiPo = $tipeProduk === 'ready' ? null : ($request->input('estimasi_po') ?: ($produk->estimasi_po ?: '14-21 Hari Kerja'));

        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'tipe_produk' => $tipeProduk,
            'estimasi_po' => $estimasiPo,
        ];

        if ($request->hasFile('foto')) {
            if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
                Storage::disk('public')->delete($produk->foto);
            }

            $data['foto'] = $request->file('foto')->store('katalog', 'public');
        }

        $produk->update($data);

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroyKatalog($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->foto && Storage::disk('public')->exists($produk->foto)) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.katalog')->with('success', 'Produk berhasil dihapus!');
    }

    public function pesananMasuk()
    {
        return view('admin.pesanan_masuk');
    }

    public function progresProduksi()
    {
        return view('admin.progres_produksi');
    }

    public function dataPelanggan()
    {
        return view('admin.data_pelanggan');
    }

    public function riwayat()
    {
        return view('admin.riwayat');
    }

    public function pengaturan()
    {
        return view('admin.pengaturan');
    }
}