<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASSALAM MEBEL - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px 0; }
        .auth-card { width: 100%; max-width: 480px; border: 2px solid #000; border-radius: 20px; padding: 30px; background: #fff; }
        .toggle-password { cursor: pointer; }
    </style>
</head>
<body>

<div class="auth-card text-center">
    <h4 class="fw-bold mb-1">ASSALAM MEBEL</h4>
    <p class="small text-muted mb-4">[Form Pendaftaran]</p>

    <!-- Ketiak diklik, form ini akan menjalankan registerStore di AuthController -->
    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger p-2 small text-start">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="text-start mb-2">
            <label class="form-label small mb-1">Nama Akun</label>
            <input type="text" name="name" class="form-control border-dark rounded-3" value="{{ old('name') }}" required>
        </div>

        <div class="text-start mb-2">
            <label class="form-label small mb-1">Nomor WhatsApp (Aktif)</label>
            <input type="text" name="whatsapp" class="form-control border-dark rounded-3" value="{{ old('whatsapp') }}" required>
        </div>

        <div class="text-start mb-2">
            <label class="form-label small mb-1">Email Address</label>
            <input type="email" name="email" class="form-control border-dark rounded-3" value="{{ old('email') }}" required>
        </div>

        <!-- Password dengan Icon Mata -->
        <div class="text-start mb-2">
            <label class="form-label small mb-1">Password</label>
            <div class="input-group">
                <input type="password" id="regPassword" name="password" class="form-control border-dark rounded-start-3" required>
                <span class="input-group-text bg-white border-dark rounded-end-3 toggle-password" onclick="togglePasswordVisibility('regPassword', 'eyeIconReg')">
                    <i class="fa-regular fa-eye-slash" id="eyeIconReg"></i>
                </span>
            </div>
        </div>

        <!-- Konfirmasi Password dengan Icon Mata -->
        <div class="text-start mb-2">
            <label class="form-label small mb-1">Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" id="regPasswordConfirm" name="password_confirmation" class="form-control border-dark rounded-start-3" required>
                <span class="input-group-text bg-white border-dark rounded-end-3 toggle-password" onclick="togglePasswordVisibility('regPasswordConfirm', 'eyeIconConfirm')">
                    <i class="fa-regular fa-eye-slash" id="eyeIconConfirm"></i>
                </span>
            </div>
        </div>

        <div class="text-start mb-4">
            <label class="form-label small mb-1">Alamat</label>
            <input type="text" name="alamat" class="form-control border-dark rounded-3" value="{{ old('alamat') }}" required>
        </div>

        <!-- Tombol ini langsung bawa user ke Beranda -->
        <button type="submit" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-bold mb-3">DAFTAR SEKARANG</button>

        <p class="small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">Login Disini</a></p>
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