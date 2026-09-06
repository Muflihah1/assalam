<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\ShippingCost; // Sesuaikan dengan nama model Ongkir Anda
use App\Models\Setting;      // Sesuaikan jika menggunakan tabel Settings
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

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
        return view('admin.katalog', compact('listProduk', 'q'));
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

    // ==========================================
    // METHOD FITUR PENGATURAN
    // ==========================================

    public function pengaturan()
    {
        // Mengambil data ongkir jika ada (opsional)
        $shippingCosts = class_exists('App\Models\ShippingCost') ? ShippingCost::all() : [];
        $settings = class_exists('App\Models\Setting') ? Setting::pluck('value', 'key')->toArray() : [];

        return view('admin.pengaturan', compact('shippingCosts', 'settings'));
    }

    // 1. Update Profile (Nama & Email)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->Auth::update([
            'name' => $request->username,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profil administrator berhasil diperbarui!');
    }

    // 2. Update Password Administrator
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        // Cek kecocokan password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai!']);
        }

        // Update ke password baru dengan Hashing
        $user->Auth::update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diganti!');
    }

    // 3. Update WhatsApp Gateway Setting (Opsional)
    public function updateWhatsapp(Request $request)
    {
        $request->validate([
            'wa_number' => 'required|string',
            'wa_status' => 'required|string',
        ]);

        if (class_exists('App\Models\Setting')) {
            Setting::updateOrCreate(['key' => 'wa_number'], ['value' => $request->wa_number]);
            Setting::updateOrCreate(['key' => 'wa_status'], ['value' => $request->wa_status]);
        }

        return back()->with('success', 'Konfirmasi WhatsApp Gateway berhasil disimpan!');
    }

    // 4. Store Shipping Cost (Ongkir Wilayah)
    public function storeShipping(Request $request)
    {
        $request->validate([
            'kecamatan' => 'required|string|max:255',
            'biaya' => 'required|numeric',
        ]);

        if (class_exists('App\Models\ShippingCost')) {
            ShippingCost::create([
                'kecamatan' => $request->kecamatan,
                'biaya' => $request->biaya,
                'status' => $request->status ?? 'Aktif',
            ]);
        }

        return back()->with('success', 'Wilayah tarif ongkir berhasil ditambahkan!');
    }

    // 5. Update Shipping Cost (Ongkir Wilayah)
    public function updateShipping(Request $request, $id)
    {
        $request->validate([
            'kecamatan' => 'required|string|max:255',
            'biaya' => 'required|numeric',
        ]);

        if (class_exists('App\Models\ShippingCost')) {
            $shipping = ShippingCost::findOrFail($id);
            $shipping->update([
                'kecamatan' => $request->kecamatan,
                'biaya' => $request->biaya,
                'status' => $request->status ?? 'Aktif',
            ]);
        }

        return back()->with('success', 'Tarif ongkir wilayah berhasil diperbarui!');
    }
}