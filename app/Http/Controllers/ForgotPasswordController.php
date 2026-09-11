<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetOtp;
use App\Models\User;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    protected WhatsAppNotificationService $waService;

    public function __construct(WhatsAppNotificationService $waService)
    {
        $this->waService = $waService;
    }

    /**
     * Tampilkan formulir permintaan reset password (input email/username/no WA)
     */
    public function showRequestForm()
    {
        if (Auth::check()) {
            return Auth::user()->role === 'admin'
                ? redirect()->route('admin.dashboard')
                : redirect()->route('customer.beranda');
        }

        return view('auth.forgot-password');
    }

    /**
     * Cari pengguna dan kirim OTP 6-digit ke nomor WhatsApp
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
        ], [
            'identifier.required' => 'Masukkan Email, Username, atau Nomor WhatsApp akun Anda.',
        ]);

        $rawIdentifier = trim($request->input('identifier'));

        // Cari user berdasarkan Email, Username, atau Nomor WhatsApp
        $user = null;

        if (filter_var($rawIdentifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $rawIdentifier)->first();
        } else {
            // Cek jika nomor telepon
            $cleanPhone = WhatsAppNotificationService::formatPhoneNumber($rawIdentifier);
            if (!empty($cleanPhone) && strlen($cleanPhone) >= 9) {
                $user = User::where('whatsapp_number', $cleanPhone)
                    ->orWhere('whatsapp_number', '0' . substr($cleanPhone, 2))
                    ->orWhere('whatsapp_number', '+' . $cleanPhone)
                    ->first();
            }

            // Jika belum ditemukan, cari via username atau email langsung
            if (!$user) {
                $user = User::where('username', strtolower($rawIdentifier))
                    ->orWhere('email', $rawIdentifier)
                    ->first();
            }
        }

        if (!$user) {
            return back()->withInput()->withErrors([
                'identifier' => 'Akun dengan email, username, atau nomor telepon tersebut tidak ditemukan dalam sistem.',
            ]);
        }

        if (empty($user->whatsapp_number)) {
            return back()->withInput()->withErrors([
                'identifier' => 'Akun ini belum memiliki nomor WhatsApp terdaftar. Silakan hubungi Customer Service kami untuk bantuan pemulihan.',
            ]);
        }

        // Cek rate-limiting pengiriman OTP (minimal jeda 60 detik antar permintaan)
        $latestOtp = PasswordResetOtp::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($latestOtp && $latestOtp->created_at->diffInSeconds(now()) < 60) {
            $remainingSec = 60 - $latestOtp->created_at->diffInSeconds(now());
            return back()->withInput()->withErrors([
                'identifier' => "Kode OTP baru saja dikirim. Harap tunggu {$remainingSec} detik sebelum meminta kode baru.",
            ]);
        }

        // Tandai OTP lama yang belum terpakai sebagai hangus
        PasswordResetOtp::where('user_id', $user->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Generate OTP 6 digit
        $otpCode = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        // Simpan ke database
        PasswordResetOtp::create([
            'user_id' => $user->id,
            'identifier' => $rawIdentifier,
            'otp_code' => $otpCode,
            'is_used' => false,
            'expires_at' => $expiresAt,
        ]);

        // Kirim notifikasi WhatsApp
        $this->waService->sendOtpForgotPassword($user, $otpCode, 5);

        // Mask nomor WhatsApp (contoh: 62812****7890)
        $phone = $user->whatsapp_number;
        $maskedPhone = (strlen($phone) > 7)
            ? substr($phone, 0, 4) . '****' . substr($phone, -3)
            : $phone;

        // Simpan informasi sesi untuk proses verifikasi berikutnya
        session([
            'pwd_reset_user_id' => $user->id,
            'pwd_reset_masked_phone' => $maskedPhone,
            'pwd_reset_identifier' => $rawIdentifier,
            'pwd_reset_otp_sent_at' => now()->timestamp,
        ]);

        // Jika dalam mode debug / lokal, berikan petunjuk OTP agar mudah dites jika sidecar WA belum terhubung
        $devHint = config('app.debug') ? " (Mode Pengembang: OTP Anda adalah {$otpCode})" : "";

        return redirect()->route('password.verify_otp_view')
            ->with('success', "Kode OTP 6-digit telah dikirimkan ke WhatsApp nomor {$maskedPhone}. Silakan masukkan kode untuk melanjutkan.{$devHint}");
    }

    /**
     * Tampilkan formulir input verifikasi kode OTP
     */
    public function showVerifyOtpForm()
    {
        $userId = session('pwd_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi permintaan reset password telah berakhir. Silakan masukkan kembali identitas akun Anda.');
        }

        $user = User::find($userId);
        if (!$user) {
            session()->forget(['pwd_reset_user_id', 'pwd_reset_masked_phone', 'pwd_reset_identifier']);
            return redirect()->route('password.request')->with('error', 'Pengguna tidak valid.');
        }

        $maskedPhone = session('pwd_reset_masked_phone', 'WhatsApp Anda');
        
        // Ambil OTP aktif terakhir untuk keperluan timer / dev hint
        $activeOtp = PasswordResetOtp::where('user_id', $user->id)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        $remainingSeconds = $activeOtp ? max(0, $activeOtp->expires_at->diffInSeconds(now())) : 0;
        $sentAt = session('pwd_reset_otp_sent_at', now()->timestamp);
        $cooldownRemaining = max(0, 60 - (now()->timestamp - $sentAt));

        return view('auth.verify-otp', compact('user', 'maskedPhone', 'remainingSeconds', 'cooldownRemaining', 'activeOtp'));
    }

    /**
     * Verifikasi kode OTP yang diinputkan pengguna
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ], [
            'otp_code.required' => 'Kode OTP 6-digit wajib diisi.',
            'otp_code.size' => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $userId = session('pwd_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi verifikasi telah kedaluwarsa. Silakan ajukan ulang.');
        }

        $otpInput = trim($request->input('otp_code'));

        $otpRecord = PasswordResetOtp::where('user_id', $userId)
            ->where('otp_code', $otpInput)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withInput()->withErrors([
                'otp_code' => 'Kode OTP salah atau telah kedaluwarsa (masa berlaku 5 menit). Silakan cek kembali pesan WhatsApp Anda atau klik Kirim Ulang.',
            ]);
        }

        // Tandai OTP telah digunakan
        $otpRecord->is_used = true;

        // Buat token reset aman untuk halaman ganti password
        $resetToken = Str::random(64);
        $otpRecord->token = $resetToken;
        $otpRecord->save();

        // Simpan token ke sesi
        session([
            'pwd_reset_verified_token' => $resetToken,
            'pwd_reset_verified_user_id' => $userId,
        ]);

        return redirect()->route('password.reset_view', ['token' => $resetToken])
            ->with('success', 'Verifikasi WhatsApp berhasil! Silakan atur kata sandi baru untuk akun Anda.');
    }

    /**
     * Kirim ulang kode OTP jika belum sampai atau kedaluwarsa
     */
    public function resendOtp()
    {
        $userId = session('pwd_reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request')
                ->with('error', 'Sesi permintaan telah kedaluwarsa. Silakan mulai kembali.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('password.request')->with('error', 'Akun tidak ditemukan.');
        }

        $sentAt = session('pwd_reset_otp_sent_at', 0);
        $diff = now()->timestamp - $sentAt;
        if ($diff < 60) {
            $remain = 60 - $diff;
            return back()->with('error', "Harap tunggu {$remain} detik sebelum meminta kirim ulang kode OTP.");
        }

        // Hanguskan OTP sebelumnya
        PasswordResetOtp::where('user_id', $user->id)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Buat OTP baru
        $otpCode = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = now()->addMinutes(5);

        PasswordResetOtp::create([
            'user_id' => $user->id,
            'identifier' => $user->whatsapp_number,
            'otp_code' => $otpCode,
            'is_used' => false,
            'expires_at' => $expiresAt,
        ]);

        // Kirim notifikasi WA
        $this->waService->sendOtpForgotPassword($user, $otpCode, 5);

        session(['pwd_reset_otp_sent_at' => now()->timestamp]);

        $devHint = config('app.debug') ? " (Mode Pengembang: OTP baru adalah {$otpCode})" : "";

        return back()->with('success', "Kode OTP baru telah berhasil dikirim ke WhatsApp Anda.{$devHint}");
    }

    /**
     * Tampilkan formulir atur kata sandi baru
     */
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->query('token', session('pwd_reset_verified_token'));

        if (!$token) {
            return redirect()->route('password.request')
                ->with('error', 'Tautan atau token reset tidak valid. Silakan lakukan proses verifikasi ulang.');
        }

        $otpRecord = PasswordResetOtp::where('token', $token)
            ->where('is_used', true)
            ->where('updated_at', '>=', now()->subMinutes(20))
            ->latest()
            ->first();

        if (!$otpRecord) {
            return redirect()->route('password.request')
                ->with('error', 'Token reset password tidak valid atau telah kedaluwarsa. Silakan ulangi proses verifikasi.');
        }

        $user = $otpRecord->user;

        return view('auth.reset-password', compact('token', 'user'));
    }

    /**
     * Simpan kata sandi baru pengguna ke database
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'token.required' => 'Token reset password tidak valid.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok. Harap periksa kembali.',
        ]);

        $token = $request->input('token');

        $otpRecord = PasswordResetOtp::where('token', $token)
            ->where('is_used', true)
            ->where('updated_at', '>=', now()->subMinutes(20))
            ->latest()
            ->first();

        if (!$otpRecord || !$otpRecord->user) {
            return redirect()->route('password.request')
                ->with('error', 'Permintaan reset tidak valid atau telah kedaluwarsa. Silakan ulangi proses.');
        }

        $user = $otpRecord->user;
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Hanguskan token agar tidak bisa dipakai ulang
        $otpRecord->token = null;
        $otpRecord->save();

        // Bersihkan sesi reset password
        session()->forget([
            'pwd_reset_user_id',
            'pwd_reset_masked_phone',
            'pwd_reset_identifier',
            'pwd_reset_otp_sent_at',
            'pwd_reset_verified_token',
            'pwd_reset_verified_user_id',
        ]);

        return redirect()->route('login')
            ->with('success', 'Kata sandi Anda berhasil diperbarui! Silakan masuk menggunakan kata sandi baru Anda.');
    }
}
