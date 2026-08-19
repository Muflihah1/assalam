<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - ASSALAM MEBEL</title>
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
            --primary-color: #3e2723; /* Coklat Sangat Tua (Khas Admin) */
            --secondary-color: #5d4037;
        }

        body {
            background: #2b1d18;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .admin-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-top: 5px solid #d7ccc8;
        }

        .brand-title {
            color: var(--primary-color);
            font-weight: 900;
            letter-spacing: 1px;
        }

        .form-control {
            border: 2px solid #eee;
            border-radius: 10px;
            padding: 12px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .btn-admin {
            background: var(--primary-color);
            border: none;
            border-radius: 50px;
            color: white;
            font-weight: bold;
            padding: 12px;
            transition: 0.3s;
        }

        .btn-admin:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        .toggle-password { cursor: pointer; }
    </style>
</head>
<body>

<div class="admin-card">
    <div class="text-center mb-4">
        <img src="{{ asset('logo.png') }}" alt="Assalam Mebel" class="mb-3" style="max-height: 70px; width: auto; object-fit: contain;">
        <h3 class="brand-title mb-1">ADMIN PANEL</h3>
        <p class="text-muted small">ASSALAM MEBEL</p>
    </div>

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger p-2 small text-center">{{ $errors->first() }}</div>
        @endif

        <div class="mb-3">
            <label class="form-label small fw-bold">Email Administrator</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-2 border-end-0 rounded-start-3"><i class="fa-regular fa-envelope text-muted"></i></span>
                <input type="email" name="email" class="form-control border-start-0" placeholder="admin@assalam.com" value="{{ old('email') }}" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label small fw-bold">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-2 border-end-0 rounded-start-3"><i class="fa-solid fa-lock text-muted"></i></span>
                <input type="password" id="adminPassword" name="password" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                <span class="input-group-text bg-light border-2 border-start-0 rounded-end-3 toggle-password" onclick="togglePasswordVisibility('adminPassword', 'eyeIconAdmin')">
                    <i class="fa-regular fa-eye-slash text-muted" id="eyeIconAdmin"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn btn-admin w-100 mb-3">MASUK SEBAGAI ADMIN</button>
        
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-muted small text-decoration-none"><i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Login Pelanggan</a>
        </div>
    </form>
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
</html>