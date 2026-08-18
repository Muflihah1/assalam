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
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ], [
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengubah password.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
        ]);

        $admin->name = $request->username;
        $admin->email = $request->email;

        // Jika kolom password lama diisi
        if ($request->filled('current_password')) {
            // Cek apakah password lama cocok dengan database
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan salah!']);
            }

            // Jika cocok, update ke password baru
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Profil dan password berhasil diperbarui!');
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