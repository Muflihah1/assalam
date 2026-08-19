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

    public function katalog()
    {
        $listProduk = Produk::all();
        return view('admin.katalog', compact('listProduk'));
    }

    public function storeKatalog(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('foto')->store('katalog', 'public');

        Produk::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'foto' => $path,
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
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
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