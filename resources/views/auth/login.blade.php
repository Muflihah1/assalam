<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ASSALAM MEBEL</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #5d4037;
            --secondary-color: #8d6e63;
            --accent-color: #d7ccc8;
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
            display: flex;
            width: 100%;
            max-width: 900px;
            overflow: hidden;
        }

        /* Kolom Kiri: Gambar */
        .auth-image {
            flex: 1;
            background: url('https://images.unsplash.com/photo-1595428774223-ef52624120d3?q=80&w=1920') center/cover no-repeat;
            position: relative;
        }
        
        .auth-image::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(93, 64, 55, 0.6);
        }

        /* Kolom Kanan: Form */
        .auth-form {
            flex: 1;
            padding: 60px;
        }

        .brand-title { color: var(--primary-color); font-weight: 900; }
        .input-group-text { background: transparent !important; color: var(--secondary-color); }
        .form-control {
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 12px;
        }
        .form-control:focus { border-color: var(--primary-color); box-shadow: none; }

        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: bold;
            padding: 12px;
            transition: 0.3s;
        }

        .btn-login:hover { transform: translateY(-2px); color: white; box-shadow: 0 5px 15px rgba(93, 64, 55, 0.3); }

        .toggle-password { cursor: pointer; }

        @media (max-width: 768px) {
            .main-container { flex-direction: column; }
            .auth-image { min-height: 200px; }
            .auth-form { padding: 30px; }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="auth-image"></div>
    
    <div class="auth-form">
        <h3 class="brand-title mb-4">Selamat Datang Kembali</h3>
        
        <!-- FORM DIARAHKAN KE ROUTE LOGIN DENGAN METHOD POST -->
        <form action="{{ route('login.authenticate') }}" method="POST">
            @csrf

            @if (session('success'))
                <div class="alert alert-success p-2 small">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger p-2 small">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label small">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="example@mail.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="loginPassword" name="password" class="form-control" placeholder="••••••••" required>
                    <span class="input-group-text toggle-password" onclick="togglePasswordVisibility('loginPassword', 'eyeIconLogin')">
                        <i class="fa-regular fa-eye-slash" id="eyeIconLogin"></i>
                    </span>
                </div>
            </div>

            <div class="d-flex justify-content-between mb-4 small">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>
                <a href="#" class="text-decoration-none text-muted">Lupa Password?</a>
            </div>

            <!-- TOMBOL DIUBAH MENJADI TYPE SUBMIT AGAR FORM BISA DIKIRIM -->
            <button type="submit" class="btn btn-login w-100 mb-3">LOGIN SEKARANG</button>

            <div class="text-center small">
                Belum punya akun? <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none">Daftar Sekarang</a>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
</script>

</body>
</html>s