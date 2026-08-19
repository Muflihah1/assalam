<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman Login
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin' 
                ? redirect()->route('admin.dashboard') 
                : redirect()->route('customer.beranda');
        }
        return view('auth.login');
    }

    // Menampilkan halaman Register
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('customer.beranda');
        }
        return view('auth.register');
    }

    // Proses Register Akun Baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string',
        ], [
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'email.required' => 'Alamat email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 6 karakter.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'whatsapp_number' => $request->whatsapp_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => 'customer',
        ]);

        // Otomatis login setelah registrasi untuk pengalaman berbelanja yang mulus
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('customer.beranda'))->with('success', 'Selamat datang ' . $user->name . '! Akun Anda berhasil didaftarkan.');
    }

    // Proses Login (Pengecekan Role Admin & Pelanggan)
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->intended(route('customer.beranda'))->with('success', 'Selamat datang kembali, ' . $user->name . '!');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('customer.beranda')->with('success', 'Anda telah berhasil keluar akun.');
    }
}