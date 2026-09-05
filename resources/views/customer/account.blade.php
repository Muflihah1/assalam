@extends('layouts.customer')

@section('content')
<style>
    .wireframe-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.06);
        padding: 24px;
        margin-bottom: 20px;
    }

    .profile-avatar-box {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }

    .profile-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--primary-color);
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2);
    }

    .form-control-custom {
        background-color: #fdfcfb !important;
        border: 1.5px solid var(--wood-border) !important;
        color: var(--text-dark) !important;
        border-radius: 12px;
        padding: 10px 14px;
    }

    .form-control-custom:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.2rem rgba(93, 64, 55, 0.15) !important;
    }

    .btn-orange-fill {
        background-color: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border-radius: 12px;
        padding: 10px 24px;
        border: none;
        transition: all 0.2s;
    }

    .btn-orange-fill:hover {
        background-color: var(--secondary-color);
        color: #ffffff;
    }

    .btn-danger-outline {
        border: 1.5px solid #dc2626;
        color: #dc2626;
        background: transparent;
        font-weight: 600;
        border-radius: 12px;
        padding: 10px 24px;
        transition: all 0.2s;
    }

    .btn-danger-outline:hover {
        background: #dc2626;
        color: #ffffff;
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- HEADER HALAMAN -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Pengaturan Akun Pelanggan</h3>
        <p class="text-muted small mb-0">Kelola informasi profil, nomor kontak, alamat pengiriman, dan keamanan akun Anda.</p>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: FOTO PROFIL & RINGKASAN AKUN -->
        <div class="col-lg-4">
            <div class="wireframe-card text-center">
                <div class="profile-avatar-box mb-3">
                    <img id="userAvatarPreview" src="https://api.dicebear.com/7.x/adventurer/svg?seed={{ urlencode(Auth::user()->name ?? 'Budi') }}" alt="Avatar" class="profile-avatar-img">
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name }}</h5>
                <p class="text-muted small mb-3">{{ Auth::user()->email }}</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge px-3 py-1.5 rounded-pill small fw-bold" style="background-color: #d1fae5; color: #15803d; border: 1px solid #a7f3d0;">
                        <i class="fa-solid fa-user-check me-1"></i> Customer Terverifikasi
                    </span>
                </div>

                <hr style="border-color: var(--light-border);" class="my-4">

                <button class="btn btn-danger-outline w-100" data-bs-toggle="modal" data-bs-target="#modalLogout">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar Akun
                </button>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM DETAIL PROFIL & KEAMANAN -->
        <div class="col-lg-8">
            
            <!-- 1. INFORMASI PROFIL -->
            <div class="wireframe-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-user me-2" style="color: var(--primary-color);"></i> Informasi Profil & Kontak</h5>
                </div>

                <form action="{{ route('customer.account.profile') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-custom" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0">@</span>
                                <input type="text" name="username" class="form-control form-control-custom border-start-0 ps-1" value="{{ old('username', Auth::user()->username) }}" required placeholder="username_anda">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Nomor Telepon / WhatsApp</label>
                            <input type="tel" name="whatsapp_number" class="form-control form-control-custom" 
                                   value="{{ old('whatsapp_number', Auth::user()->whatsapp_number) }}" 
                                   inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9+]/g, '')"
                                   placeholder="Contoh: 08123456789 atau 62812345678" required>
                            <div class="form-text small">Mendukung awalan 08 atau 62 (hanya angka).</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control form-control-custom" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-dark small fw-bold">Alamat Pengiriman Utama</label>
                            <textarea name="alamat" class="form-control form-control-custom" rows="2" placeholder="Nama jalan, RT/RW, kecamatan, kota..." required>{{ old('alamat', Auth::user()->alamat) }}</textarea>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-orange-fill">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </form>
            </div>

            <!-- 2. KEAMANAN & PASSWORD -->
            <div class="wireframe-card">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-shield-halved me-2" style="color: var(--primary-color);"></i> Keamanan & Ganti Password</h5>
                </div>

                <form action="{{ route('customer.account.password') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" id="custCurrentPass" name="current_password" class="form-control form-control-custom border-end-0" placeholder="Password lama" required>
                                <button type="button" class="btn btn-outline-secondary border bg-white" onclick="togglePasswordVisibility('custCurrentPass', 'eyeCustCurr')" title="Tampilkan/Sembunyikan Password" tabindex="-1">
                                    <i class="fa-solid fa-eye-slash text-muted" id="eyeCustCurr"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="custNewPass" name="password" class="form-control form-control-custom border-end-0" placeholder="Min. 6 karakter" required>
                                <button type="button" class="btn btn-outline-secondary border bg-white" onclick="togglePasswordVisibility('custNewPass', 'eyeCustNew')" title="Tampilkan/Sembunyikan Password" tabindex="-1">
                                    <i class="fa-solid fa-eye-slash text-muted" id="eyeCustNew"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" id="custConfPass" name="password_confirmation" class="form-control form-control-custom border-end-0" placeholder="Ulangi password" required>
                                <button type="button" class="btn btn-outline-secondary border bg-white" onclick="togglePasswordVisibility('custConfPass', 'eyeCustConf')" title="Tampilkan/Sembunyikan Password" tabindex="-1">
                                    <i class="fa-solid fa-eye-slash text-muted" id="eyeCustConf"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-orange-fill">
                            <i class="fa-solid fa-key me-1"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- MODAL KONFIRMASI LOGOUT -->
<div class="modal fade" id="modalLogout" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 p-3 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
            <div class="modal-body">
                <div class="mb-3 text-danger">
                    <i class="fa-solid fa-right-from-bracket fa-3x"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Keluar Akun?</h5>
                <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari akun pelanggan Anda?</p>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-bold w-50 py-2 rounded-3">Ya, Keluar</button>
                    </div>
                </form>
            </div>
        </div>
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
@endsection