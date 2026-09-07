<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Show customer account profile page
     */
    public function index()
    {
        $user = Auth::user();
        $totalOrders = \App\Models\Order::where('user_id', $user->id)->count();
        $activeOrders = \App\Models\Order::where('user_id', $user->id)
            ->whereNotIn('order_status', ['Selesai', 'Ditolak', 'Dibatalkan'])
            ->count();
        $completedOrders = \App\Models\Order::where('user_id', $user->id)
            ->where('order_status', 'Selesai')
            ->count();

        return view('customer.account', compact('user', 'totalOrders', 'activeOrders', 'completedOrders'));
    }

    /**
     * Update customer profile info
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|alpha_dash|unique:users,username,' . $user->id,
            'whatsapp_number' => ['required', 'string', 'regex:/^(\+?62|0)8[1-9][0-9]{7,12}$/'],
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'alamat' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan oleh akun lain.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.regex' => 'Format nomor WhatsApp tidak valid. Gunakan awalan 08 atau 62 (contoh: 08123456789 atau 628123456789) tanpa huruf.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'profile_photo.image' => 'Berkas foto profil harus berupa gambar (JPG, PNG, WEBP).',
            'profile_photo.max' => 'Ukuran berkas foto profil maksimal 5MB.',
        ]);

        // Hapus foto jika diminta
        if ($request->filled('remove_photo') && $request->remove_photo == '1') {
            $user->deleteProfilePhoto();
        }

        // Upload foto profil baru jika ada
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->profile_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('avatars', 'public');
            $user->profile_photo = $path;
        }

        // Normalisasi format nomor WA menjadi 628...
        $phone = preg_replace('/[^0-9]/', '', $request->whatsapp_number);
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        $user->name = $request->name;
        $user->username = $request->username;
        $user->whatsapp_number = $phone;
        $user->email = $request->email;
        $user->alamat = $request->alamat;
        $user->save();

        return back()->with('success', 'Informasi profil & foto akun berhasil diperbarui!');
    }

    /**
     * Update customer password
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan tidak sesuai!']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui!');
    }
}
