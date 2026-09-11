<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Kata Sandi Baru - ASSALAM MEBEL</title>
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
        .input-group-text { background: #fff !important; color: var(--secondary-color); border-color: #E5E7EB; }
        .form-control {
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.95rem;
        }
        .form-control:focus { border-color: var(--primary-color); box-shadow: none; }

        .btn-save-pwd {
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

        .btn-save-pwd:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 8px 20px rgba(59, 35, 20, 0.35);
        }

        .toggle-password { cursor: pointer; }

        .user-badge {
            background: #FAF5F0;
            border: 1px solid #E8DFD5;
            border-radius: 12px;
            padding: 10px 14px;
            margin-bottom: 20px;
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
            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold mb-2">
                <i class="fa-solid fa-check-circle me-1"></i> Terverifikasi
            </span>
            <h4 class="fw-bold mb-1">Kata Sandi Baru</h4>
            <p class="small text-white-50 mb-0">Lindungi akun mebel Anda dengan kata sandi yang kuat.</p>
        </div>
    </div>
    
    <div class="auth-form">
        <div class="text-center mb-3">
            <a href="{{ route('customer.beranda') }}">
                <img src="{{ asset('logo.png') }}" alt="Assalam Mebel" style="max-height: 60px; width: auto; object-fit: contain;">
            </a>
        </div>
        
        <div class="text-center mb-3">
            <h3 class="brand-title mb-1">Buat Kata Sandi Baru</h3>
            <p class="text-muted small mb-0">Silakan tentukan kata sandi baru untuk akun Anda.</p>
        </div>

        @if (isset($user))
            <div class="user-badge d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; color: var(--primary-color);">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark small">{{ $user->name }}</div>
                    <div class="text-muted" style="font-size: 0.78rem;">{{ $user->email }} ({{ $user->whatsapp_number }})</div>
                </div>
            </div>
        @endif

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

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label small fw-bold text-dark">Kata Sandi Baru</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="newPassword" name="password" class="form-control border-start-0 border-end-0" 
                           placeholder="Minimal 6 karakter" required autofocus minlength="6">
                    <button type="button" class="btn btn-outline-secondary border-start-0 bg-white toggle-password" 
                            onclick="togglePasswordVisibility('newPassword', 'eyeIconNew')" title="Tampilkan/Sembunyikan" tabindex="-1">
                        <i class="fa-solid fa-eye-slash text-muted" id="eyeIconNew"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold text-dark">Ulangi Kata Sandi Baru</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-lock-check"></i></span>
                    <input type="password" id="confirmPassword" name="password_confirmation" class="form-control border-start-0 border-end-0" 
                           placeholder="Ketik ulang kata sandi baru" required minlength="6">
                    <button type="button" class="btn btn-outline-secondary border-start-0 bg-white toggle-password" 
                            onclick="togglePasswordVisibility('confirmPassword', 'eyeIconConfirm')" title="Tampilkan/Sembunyikan" tabindex="-1">
                        <i class="fa-solid fa-eye-slash text-muted" id="eyeIconConfirm"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-save-pwd w-100 mb-3">
                <i class="fa-solid fa-key me-2"></i> SIMPAN KATA SANDI BARU
            </button>

            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
                    <i class="fa-solid fa-arrow-left me-1"></i> Batal & Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input) return;

        if (input.type === "password") {
            input.type = "text";
            if (icon) {
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
                icon.classList.remove("text-muted");
                icon.style.color = "var(--primary-color)";
            }
        } else {
            input.type = "password";
            if (icon) {
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
                icon.classList.add("text-muted");
                icon.style.color = "";
            }
        }
    }
</script>

</body>
</html>
