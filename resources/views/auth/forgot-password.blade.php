<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - ASSALAM MEBEL</title>
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

        /* Kolom Kiri: Gambar Mebel Solid */
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

        /* Kolom Kanan: Form */
        .auth-form {
            flex: 1.15;
            padding: 50px;
        }

        .brand-title { color: var(--primary-color); font-weight: 800; font-size: 1.6rem; }
        .input-group-text { background: #fff !important; color: var(--secondary-color); border-color: #E5E7EB; }
        .form-control {
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.95rem;
        }
        .form-control:focus { border-color: var(--primary-color); box-shadow: none; }

        .btn-send-otp {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: 700;
            padding: 13px;
            font-size: 0.95rem;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
        }

        .btn-send-otp:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.45);
        }

        .info-card {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 20px;
            font-size: 0.86rem;
            color: #166534;
        }

        @media (max-width: 768px) {
            .main-container { flex-direction: column; }
            .auth-image { min-height: 180px; padding: 25px; }
            .auth-form { padding: 30px 20px; }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="auth-image">
        <div class="auth-image-content">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">
                <i class="fa-solid fa-shield-halved me-1"></i> Keamanan Akun
            </span>
            <h4 class="fw-bold mb-1">Pemulihan Kata Sandi</h4>
            <p class="small text-white-50 mb-0">Verifikasi instan via WhatsApp resmi Assalam Mebel Karduluk.</p>
        </div>
    </div>
    
    <div class="auth-form">
        <div class="text-center mb-3">
            <a href="{{ route('customer.beranda') }}">
                <img src="{{ asset('logo.png') }}" alt="Assalam Mebel" style="max-height: 60px; width: auto; object-fit: contain;">
            </a>
        </div>
        
        <div class="text-center mb-4">
            <h3 class="brand-title mb-1">Lupa Kata Sandi?</h3>
            <p class="text-muted small mb-0">Jangan khawatir, kami akan membantu memulihkan akun Anda.</p>
        </div>

        <div class="info-card d-flex align-items-start gap-2">
            <i class="fa-brands fa-whatsapp text-success fs-5 mt-1"></i>
            <div>
                <strong>Verifikasi OTP WhatsApp</strong><br>
                Masukkan email, username, atau nomor WhatsApp terdaftar. Kami akan mengirimkan 6-digit kode OTP langsung ke pesan WhatsApp Anda.
            </div>
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

        <form action="{{ route('password.send_otp') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Email / Username / Nomor WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-user"></i></span>
                    <input type="text" name="identifier" class="form-control" 
                           placeholder="Contoh: 08123456789 atau user@email.com" 
                           value="{{ old('identifier') }}" required autofocus>
                </div>
                <div class="form-text text-muted small mt-1">
                    <i class="fa-solid fa-circle-info me-1"></i>Pastikan nomor WhatsApp Anda aktif untuk menerima pesan.
                </div>
            </div>

            <button type="submit" class="btn btn-send-otp w-100 mb-3">
                <i class="fa-brands fa-whatsapp me-2 fs-5 align-middle"></i> KIRIM KODE OTP WHATSAPP
            </button>

            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="text-decoration-none small fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Halaman Masuk
                </a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
