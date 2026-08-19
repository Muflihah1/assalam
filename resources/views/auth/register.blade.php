<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ASSALAM MEBEL</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #5d4037; /* Coklat Tua Kayu */
            --secondary-color: #8d6e63; /* Coklat Muda */
            --accent-color: #d7ccc8; /* Krem Lembut */
            --input-border: #bcaaa4;
        }

        body {
            background: linear-gradient(135deg, var(--accent-color) 0%, #ffffff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .main-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            width: 100%;
            max-width: 1000px;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .auth-image {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1586023492125-27b2c045efd7?q=80&w=1920') center/cover no-repeat;
            position: relative;
            min-height: 300px;
        }
        
        .auth-image::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(0deg, rgba(93,64,55,0.8) 0%, rgba(93,64,55,0.2) 100%);
        }

        .image-content {
            position: relative;
            z-index: 1;
            color: white;
            padding: 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
        }

        .image-content h2 { font-weight: 800; font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .image-content p { font-size: 1.1rem; opacity: 0.9; }

        .auth-form {
            flex: 1;
            padding: 50px;
        }

        .brand-title {
            color: var(--primary-color);
            font-weight: 900;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.85rem;
        }

        .form-control {
            background-color: #fafafa;
            border: 2px solid var(--input-border);
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(93, 64, 55, 0.25);
        }

        .password-container { position: relative; }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary-color);
            z-index: 10;
        }

        .btn-daftar {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(93, 64, 55, 0.3);
        }

        .btn-daftar:hover {
            background: linear-gradient(to right, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(93, 64, 55, 0.4);
            color: white;
        }

        .login-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .main-container { flex-direction: column-reverse; }
            .auth-image { min-height: 150px; }
            .image-content { padding: 20px; }
            .image-content h2 { font-size: 1.8rem; }
            .auth-form { padding: 30px; }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="auth-image">
        <div class="image-content">
            <h2>ASSALAM<br>MEBEL</h2>
            <p>Kreasi Kayu Terbaik untuk Hunian Impian Anda.</p>
        </div>
    </div>

    <div class="auth-form">
        <div class="text-center mb-4">
            <a href="{{ route('customer.beranda') }}">
                <img src="{{ asset('logo.png') }}" alt="Assalam Mebel" class="mb-2" style="max-height: 60px; width: auto; object-fit: contain;">
            </a>
            <h3 class="brand-title">Buat Akun Baru</h3>
            <p class="text-muted small">Silakan lengkapi data diri Anda</p>
        </div>

        <!-- FORM DIARAHKAN KE ROUTE REGISTER STORE DENGAN METHOD POST -->
        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger p-2 small mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="whatsapp_number" class="form-control" placeholder="0812..." value="{{ old('whatsapp_number') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="anda@email.com" value="{{ old('email') }}" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" class="form-control" placeholder="********" required>
                        <i class="bi bi-eye-slash toggle-password" onclick="togglePasswordVisibility('password', this)"></i>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <div class="password-container">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="*********" required>
                        <i class="bi bi-eye-slash toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)"></i>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Mebel No. 1..." required>{{ old('alamat') }}</textarea>
            </div>

            <!-- TOMBOL DIUBAH MENJADI TYPE SUBMIT -->
            <button type="submit" class="btn btn-daftar w-100 py-3 mb-3">
                DAFTAR SEKARANG
            </button>

            <div class="text-center">
                <p class="small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="login-link">Masuk Disini</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId, iconElement) {
        const inputField = document.getElementById(fieldId);
        if (inputField.type === "password") {
            inputField.type = "text";
            iconElement.classList.remove("bi-eye-slash");
            iconElement.classList.add("bi-eye");
        } else {
            inputField.type = "password";
            iconElement.classList.remove("bi-eye");
            iconElement.classList.add("bi-eye-slash");
        }
    }
</script>

</body>
</html>