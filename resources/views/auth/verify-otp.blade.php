<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP WhatsApp - ASSALAM MEBEL</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3B2314;
            --secondary-color: #5C3A21;
            --accent-color: #FAF5F0;
            --wa-color: #25D366;
            --wa-hover: #1EBE5D;
        }

        body {
            background: linear-gradient(135deg, #FAF5F0 0%, #E8DFD5 100%);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(59, 35, 20, 0.12);
            display: flex;
            width: 100%;
            max-width: 920px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        .auth-image {
            flex: 1;
            background: url('{{ asset('produk/01_kursi-sofa-ukir-set.jpg') }}') center/cover no-repeat;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 40px;
        }
        
        .auth-image::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(59, 35, 20, 0.45) 0%, rgba(36, 20, 11, 0.85) 100%);
        }

        .auth-image-content {
            position: relative;
            z-index: 2;
            color: #fff;
        }

        .auth-form {
            flex: 1.15;
            padding: 50px;
        }

        .brand-title { color: var(--primary-color); font-weight: 800; font-size: 1.55rem; }

        /* Digit OTP inputs */
        .otp-inputs-wrapper {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }

        .otp-digit {
            width: 52px;
            height: 60px;
            font-size: 1.7rem;
            font-weight: 800;
            text-align: center;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            background: #FDFBF9;
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        .otp-digit:focus {
            outline: none;
            border-color: #25D366;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(37, 211, 102, 0.15);
        }

        .btn-verify {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            padding: 13px;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 35, 20, 0.25);
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 8px 20px rgba(59, 35, 20, 0.35);
        }

        .resend-box {
            background: #F9FAFB;
            border: 1px dashed #D1D5DB;
            border-radius: 14px;
            padding: 14px;
            text-align: center;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .main-container { flex-direction: column; }
            .auth-image { min-height: 180px; padding: 25px; }
            .auth-form { padding: 30px 18px; }
            .otp-digit { width: 44px; height: 52px; font-size: 1.4rem; gap: 6px; }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="auth-image">
        <div class="auth-image-content">
            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold mb-2">
                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Gateway
            </span>
            <h4 class="fw-bold mb-1">Verifikasi Dua Langkah</h4>
            <p class="small text-white-50 mb-0">Melindungi akun Anda dengan verifikasi kode sandi sekali pakai.</p>
        </div>
    </div>
    
    <div class="auth-form">
        <div class="text-center mb-3">
            <a href="{{ route('customer.beranda') }}">
                <img src="{{ asset('logo.png') }}" alt="Assalam Mebel" style="max-height: 60px; width: auto; object-fit: contain;">
            </a>
        </div>
        
        <div class="text-center mb-3">
            <div class="d-inline-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle mb-2" style="width: 52px; height: 52px;">
                <i class="fa-brands fa-whatsapp fs-3"></i>
            </div>
            <h3 class="brand-title mb-1">Verifikasi Kode OTP</h3>
            <p class="text-muted small mb-0">
                Kode 6-digit telah dikirim ke WhatsApp: <br>
                <span class="fw-bold text-dark fs-6">{{ $maskedPhone }}</span>
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success p-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-circle-check fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger p-3 small rounded-3 mb-3 d-flex align-items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger p-3 small rounded-3 mb-3">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (config('app.debug') && isset($activeOtp))
            <div class="alert alert-warning p-2 small rounded-3 mb-3 d-flex align-items-center justify-content-between">
                <span><i class="fa-solid fa-code me-1"></i><strong>Mode Debug:</strong> OTP Terkirim: <code>{{ $activeOtp->otp_code }}</code></span>
                <button type="button" class="btn btn-sm btn-outline-dark py-0 px-2" onclick="fillOtp('{{ $activeOtp->otp_code }}')">Isi Cepat</button>
            </div>
        @endif

        <form action="{{ route('password.verify_otp') }}" method="POST" id="verifyOtpForm">
            @csrf

            <!-- Hidden input for single 6-digit value -->
            <input type="hidden" name="otp_code" id="otpCodeHidden" value="{{ old('otp_code') }}">

            <div class="text-center mb-2">
                <label class="form-label small fw-bold text-muted text-uppercase tracking-wide">Masukkan 6 Digit Angka</label>
                <div class="otp-inputs-wrapper" id="otpInputs">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="0" autofocus>
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="1">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="2">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="3">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="4">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="otp-digit" data-index="5">
                </div>
            </div>

            <!-- Expiration Countdown -->
            <div class="text-center mb-3">
                <small class="text-muted">
                    <i class="fa-regular fa-clock me-1"></i> Masa berlaku kode: 
                    <span id="countdownTimer" class="fw-bold text-danger">--:--</span>
                </small>
            </div>

            <button type="submit" class="btn btn-verify w-100 mb-3" id="btnSubmitVerify">
                <i class="fa-solid fa-shield-check me-2"></i> VERIFIKASI & LANJUTKAN
            </button>
        </form>

        <!-- Kirim Ulang OTP -->
        <div class="resend-box">
            <span class="small text-muted d-block mb-2">Tidak menerima pesan WhatsApp?</span>
            <form action="{{ route('password.resend_otp') }}" method="POST" id="resendForm">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-semibold" id="btnResend" disabled>
                    <i class="fa-solid fa-arrows-rotate me-1"></i> Kirim Ulang Kode (<span id="resendCountdown">60s</span>)
                </button>
            </form>
        </div>

        <div class="text-center pt-3">
            <a href="{{ route('password.request') }}" class="text-decoration-none small text-muted">
                <i class="fa-solid fa-pen-to-square me-1"></i> Ganti Nomor atau Akun
            </a>
        </div>
    </div>
</div>

<script>
    // Inisialisasi input OTP 6 kotak
    const inputs = document.querySelectorAll('.otp-digit');
    const hiddenInput = document.getElementById('otpCodeHidden');
    const verifyForm = document.getElementById('verifyOtpForm');

    function syncHiddenInput() {
        let val = '';
        inputs.forEach(input => val += input.value.trim());
        hiddenInput.value = val;
    }

    function fillOtp(code) {
        if (!code || code.length !== 6) return;
        inputs.forEach((input, i) => {
            input.value = code[i] || '';
        });
        syncHiddenInput();
        inputs[5].focus();
    }

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Hanya izinkan angka
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            syncHiddenInput();

            // Auto submit jika sudah 6 digit
            if (hiddenInput.value.length === 6) {
                // opsional atau biarkan user klik
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });

        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pasteData = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            if (pasteData) {
                fillOtp(pasteData);
            }
        });
    });

    verifyForm.addEventListener('submit', (e) => {
        syncHiddenInput();
        if (hiddenInput.value.length !== 6) {
            e.preventDefault();
            alert('Silakan lengkapi 6 digit kode OTP sebelum melanjutkan.');
            inputs[0].focus();
        }
    });

    // Countdown Timer Masa Berlaku OTP (5 menit)
    let remainingSec = {{ $remainingSeconds ?? 300 }};
    const countdownEl = document.getElementById('countdownTimer');

    function updateRemaining() {
        if (remainingSec <= 0) {
            countdownEl.textContent = 'Kedaluwarsa';
            countdownEl.classList.remove('text-danger');
            countdownEl.classList.add('text-muted');
            return;
        }
        const m = Math.floor(remainingSec / 60);
        const s = remainingSec % 60;
        countdownEl.textContent = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        remainingSec--;
    }
    updateRemaining();
    const intervalRemaining = setInterval(updateRemaining, 1000);

    // Countdown Timer Cooldown Kirim Ulang (60 detik)
    let cooldownSec = {{ $cooldownRemaining ?? 60 }};
    const btnResend = document.getElementById('btnResend');
    const resendCountdown = document.getElementById('resendCountdown');

    function updateCooldown() {
        if (cooldownSec <= 0) {
            btnResend.removeAttribute('disabled');
            btnResend.innerHTML = '<i class="fa-solid fa-arrows-rotate me-1"></i> Kirim Ulang Kode Sekarang';
            return;
        }
        btnResend.setAttribute('disabled', 'disabled');
        resendCountdown.textContent = `${cooldownSec}s`;
        cooldownSec--;
    }
    updateCooldown();
    const intervalCooldown = setInterval(updateCooldown, 1000);
</script>

</body>
</html>
