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
            'username' => 'required|string|alpha_dash|min:3|max:50|unique:users,username',
            'whatsapp_number' => ['required', 'string', 'regex:/^(\+?62|0)8[1-9][0-9]{7,12}$/'],
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'alamat' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan. Silakan pilih username lain.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, tanda hubung (-), dan garis bawah (_).',
            'username.min' => 'Username minimal terdiri dari 3 karakter.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp_number.regex' => 'Format nomor WhatsApp tidak valid. Masukkan nomor berawalan 08 atau 62 (contoh: 081234567890 atau 628123456789) tanpa huruf.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal harus 6 karakter.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'alamat.required' => 'Alamat lengkap wajib diisi.',
        ]);

        // Normalisasi nomor telepon ke format internasional 628...
        $cleanPhone = preg_replace('/[^0-9]/', '', $request->whatsapp_number);
        if (str_starts_with($cleanPhone, '08')) {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        User::create([
            'name' => $request->name,
            'username' => strtolower($request->username),
            'whatsapp_number' => $cleanPhone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => 'customer',
        ]);

        // Alur registrasi yang benar: Alihkan ke halaman login tanpa auto-login
        return redirect()->route('login')->with('success', 'Pendaftaran akun berhasil! Silakan masuk menggunakan email atau username Anda.');
    }

    // Proses Login (Mendukung Login via Email ATAU Username)
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Email atau username wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = $request->input('email');
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $field => $loginInput,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->intended(route('customer.beranda'))->with('success', 'Selamat datang kembali, ' . ($user->username ?? $user->name) . '!');
            }
        }

        return back()->withErrors([
            'email' => 'Email/Username atau password yang Anda masukkan salah.',
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