<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ShippingCost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $shippingCosts = ShippingCost::all();
        return view('admin.pengaturan', compact('settings', 'shippingCosts'));
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $admin->id,
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'Nama administrator wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'profile_photo.image' => 'Berkas foto profil harus berupa gambar (JPG, PNG, WEBP).',
            'profile_photo.max' => 'Ukuran berkas foto maksimal 5MB.',
        ]);

        // Hapus foto jika diminta
        if ($request->filled('remove_photo') && $request->remove_photo == '1') {
            $admin->deleteProfilePhoto();
        }

        // Upload foto profil baru jika ada
        if ($request->hasFile('profile_photo')) {
            if ($admin->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($admin->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($admin->profile_photo);
            }
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $admin->profile_photo = $path;
        }

        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->save();

        return back()->with('success', 'Profil administrator & foto akun berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan tidak sesuai!']);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Kata sandi administrator berhasil diperbarui!');
    }

    public function updateWhatsapp(Request $request)
    {
        Setting::updateOrCreate(['key' => 'wa_number'], ['value' => $request->wa_number]);
        Setting::updateOrCreate(['key' => 'wa_status'], ['value' => $request->wa_status]);
        Setting::updateOrCreate(['key' => 'wa_template'], ['value' => $request->wa_template]);

        return back()->with('success', 'Gateway WhatsApp berhasil diperbarui!');
    }

    public function storeShipping(Request $request)
    {
        ShippingCost::create([
            'kecamatan' => $request->kecamatan,
            'biaya' => $request->biaya,
            'status' => $request->status ?? 'Aktif'
        ]);

        return back()->with('success', 'Wilayah pengiriman berhasil ditambahkan!');
    }

    public function updateShipping(Request $request, $id)
    {
        $shipping = ShippingCost::findOrFail($id);
        $shipping->update([
            'kecamatan' => $request->kecamatan,
            'biaya' => $request->biaya,
            'status' => $request->status
        ]);

        return back()->with('success', 'Tarif ongkir berhasil diperbarui!');
    }
}