@extends('layouts.customer')

@section('content')
<style>
    .account-hero-card {
        background: linear-gradient(135deg, #5d4037 0%, #3e2723 100%);
        border-radius: 24px;
        color: #ffffff;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(93, 64, 55, 0.15);
        margin-bottom: 24px;
    }

    .account-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.2s ease;
    }

    .account-card:hover {
        border-color: var(--wood-border);
        box-shadow: 0 8px 25px rgba(93, 64, 55, 0.08);
    }

    .profile-avatar-box {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .profile-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        background: #ffffff;
    }

    .avatar-upload-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--accent-gold);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2.5px solid #ffffff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        transition: all 0.2s ease;
    }

    .avatar-upload-badge:hover {
        background-color: #b45309;
        transform: scale(1.1);
    }

    .stat-pill {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 12px 18px;
        backdrop-filter: blur(8px);
        text-align: center;
        min-width: 110px;
    }

    .form-control-custom, .form-select-custom {
        background-color: #fdfbf7 !important;
        border: 1.5px solid var(--wood-border) !important;
        color: var(--text-main) !important;
        border-radius: 12px;
        padding: 10px 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
        background-color: #ffffff !important;
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
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2);
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- HEADER JUDUL -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Profil & Akun Saya</h3>
        <p class="text-muted small mb-0">Kelola identitas akun, nomor WhatsApp aktif, alamat pengiriman mebel, dan keamanan kata sandi.</p>
    </div>

    <!-- HERO RINGKASAN AKUN PELANGGAN -->
    <div class="account-hero-card">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
            <!-- Profil Singkat -->
            <div class="d-flex align-items-center gap-3.5">
                <div class="profile-avatar-box flex-shrink-0">
                    <img id="userAvatarHero" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="profile-avatar-img">
                    <label for="profilePhotoInput" class="avatar-upload-badge" title="Ganti foto profil">
                        <i class="fa-solid fa-camera fa-xs"></i>
                    </label>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <h4 class="fw-bold mb-0 text-white">{{ Auth::user()->name }}</h4>
                        <span class="badge rounded-pill px-2.5 py-1 small" style="background-color: rgba(217, 119, 6, 0.35); border: 1px solid #f59e0b; color: #fef3c7;">
                            <i class="fa-solid fa-certificate me-1"></i> Pelanggan Terverifikasi
                        </span>
                    </div>
                    <p class="mb-1 text-white-50 small">
                        @if(Auth::user()->username)
                            <span class="me-2 text-warning fw-bold">@<span>{{ Auth::user()->username }}</span></span>
                        @endif
                        <span class="me-2">•</span>
                        <span>{{ Auth::user()->email }}</span>
                    </p>
                    <small class="text-white-50" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-calendar-check me-1"></i> Bergabung sejak {{ Auth::user()->created_at ? Auth::user()->created_at->translatedFormat('F Y') : '-' }}
                    </small>
                </div>
            </div>

            <!-- Kartu Statistik Aktivitas -->
            <div class="d-flex gap-2.5 flex-wrap">
                <div class="stat-pill">
                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">Total Pesanan</small>
                    <h4 class="fw-bold mb-0 text-white">{{ $totalOrders ?? 0 }}</h4>
                </div>
                <div class="stat-pill">
                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">Sedang Diproses</small>
                    <h4 class="fw-bold mb-0 text-warning">{{ $activeOrders ?? 0 }}</h4>
                </div>
                <div class="stat-pill">
                    <small class="text-white-50 d-block" style="font-size: 0.72rem;">Pesanan Selesai</small>
                    <h4 class="fw-bold mb-0 text-success">{{ $completedOrders ?? 0 }}</h4>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Cepat Bawah Hero -->
        <div class="d-flex gap-2 mt-3 pt-3 border-top border-white-50 flex-wrap" style="border-opacity: 0.2 !important;">
            <a href="{{ route('customer.riwayat') }}" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-receipt me-1 text-primary"></i> Riwayat Pesanan
            </a>
            <a href="{{ route('customer.progress') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-chart-line me-1 text-warning"></i> Pantau Progres Aktif
            </a>
            <a href="{{ route('customer.design') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-pen-ruler me-1 text-warning"></i> Studio Custom Jati
            </a>
            <a href="{{ route('customer.cart') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 fw-semibold">
                <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI SUKSES / ERROR -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
            <strong class="d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Terjadi kesalahan pada pengisian form:</strong>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- FORM UTAMA PROFIL (FOTO, KONTAK & ALAMAT) -->
    <form id="profileForm" action="{{ route('customer.account.profile') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <input type="file" name="profile_photo" id="profilePhotoInput" class="d-none" accept="image/jpeg,image/png,image/webp,image/jpg" onchange="previewAvatar(this)">
        <input type="hidden" name="remove_photo" id="removePhotoInput" value="0">

        <div class="row g-4">
            
            <!-- KOLOM KIRI: BIODATA & ALAMAT PENGIRIMAN -->
            <div class="col-lg-8">
                <!-- 1. KARTU INFORMASI PRIBADI -->
                <div class="account-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--light-border) !important;">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-address-card me-2" style="color: var(--primary-color);"></i>Informasi Pribadi & Kontak
                        </h6>
                        <small class="text-muted">Data ini digunakan untuk pengiriman & notifikasi pesanan</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-custom" value="{{ old('name', Auth::user()->name) }}" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid var(--wood-border);">@</span>
                                <input type="text" name="username" class="form-control form-control-custom border-start-0" style="border-radius: 0 12px 12px 0 !important;" value="{{ old('username', Auth::user()->username) }}" required placeholder="username_unik">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-success border-end-0" style="border-radius: 12px 0 0 12px; border: 1.5px solid var(--wood-border);">
                                    <i class="fa-brands fa-whatsapp"></i>
                                </span>
                                <input type="text" name="whatsapp_number" class="form-control form-control-custom border-start-0" style="border-radius: 0 12px 12px 0 !important;" value="{{ old('whatsapp_number', Auth::user()->whatsapp_number) }}" required placeholder="08123456789">
                            </div>
                            <small class="text-muted mt-1 d-block" style="font-size: 0.72rem;">Digunakan untuk menerima notifikasi foto pengerjaan mebel & resi.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control form-control-custom" value="{{ old('email', Auth::user()->email) }}" required placeholder="email@domain.com">
                        </div>
                    </div>
                </div>

                <!-- 2. KARTU ALAMAT PENGIRIMAN MEBEL -->
                <div class="account-card">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--light-border) !important;">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-truck-ramp-box me-2" style="color: var(--primary-color);"></i>Alamat Pengiriman Utama
                        </h6>
                        <span class="badge bg-light text-dark border px-2.5 py-1">Tujuan Mebel</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark small fw-bold">Alamat Lengkap Pengantaran:</label>
                        <textarea name="alamat" class="form-control form-control-custom" rows="3" placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan/desa, kecamatan, kabupaten/kota, dan patokan lokasi agar sopir truk ekspedisi mudah menemukan rumah Anda...">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-orange-fill px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: FOTO PROFIL DETAIL & KEAMANAN AKUN -->
            <div class="col-lg-4">
                <!-- KARTU KONTROL FOTO AKUN -->
                <div class="account-card text-center">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom text-start" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-image me-1 text-warning"></i> Foto Profil Akun
                    </h6>
                    <div class="position-relative mx-auto mb-3" style="width: 110px; height: 110px;">
                        <img id="userAvatarSide" src="{{ Auth::user()->profile_photo_url }}" alt="Avatar" class="w-100 h-100 rounded-circle border shadow-sm" style="object-fit: cover; border-color: var(--primary-color) !important;">
                    </div>
                    
                    <div class="d-flex justify-content-center gap-2 flex-wrap mb-2">
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3" onclick="document.getElementById('profilePhotoInput').click()">
                            <i class="fa-solid fa-camera me-1"></i> Ganti Foto
                        </button>
                        @if(Auth::user()->profile_photo)
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="hapusFotoProfil()">
                                <i class="fa-solid fa-trash-can me-1"></i> Hapus
                            </button>
                        @endif
                    </div>
                    <small class="text-muted d-block" style="font-size: 0.72rem;">Klik tombol "Simpan Perubahan" setelah memilih foto baru.</small>
                </div>

                <!-- KARTU KEAMANAN / GANTI PASSWORD -->
                <div class="account-card">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-shield-halved me-1" style="color: var(--primary-color);"></i> Keamanan & Kata Sandi
                    </h6>
                    <button type="button" class="btn btn-outline-dark w-100 rounded-3 py-2 fw-semibold small shadow-2xs mb-2" data-bs-toggle="modal" data-bs-target="#modalChangePassword">
                        <i class="fa-solid fa-key me-1 text-warning"></i> Ganti Kata Sandi Akun
                    </button>
                    <small class="text-muted d-block text-center" style="font-size: 0.72rem;">Disarankan mengganti kata sandi secara berkala untuk menjaga keamanan akun Anda.</small>
                </div>

                <!-- KARTU LAYANAN KONSULTASI ADMIN -->
                <div class="account-card bg-light">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">
                        <i class="fa-brands fa-whatsapp text-success me-1"></i> Butuh Bantuan Custom?
                    </h6>
                    <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                        Hubungi tim pengrajin Assalam Mebel langsung untuk konsultasi desain custom, negosiasi ukuran khusus, atau pengiriman keluar pulau.
                    </p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('wa_number', '085234567890')) }}?text=Halo%20Assalam%20Mebel,%20saya%20pelanggan%20atas%20nama%20{{ urlencode(Auth::user()->name) }}%20ingin%20konsultasi%20mebel%20custom" target="_blank" class="btn btn-outline-success btn-sm w-100 rounded-3 fw-bold">
                        <i class="fa-brands fa-whatsapp me-1"></i> Hubungi CS via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- MODAL GANTI KATA SANDI -->
<div class="modal fade" id="modalChangePassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('customer.account.password') }}" method="POST">
            @csrf
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fa-solid fa-key me-2"></i> Ganti Kata Sandi Akun
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark small fw-bold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="custCurrentPass" class="form-control form-control-custom border-end-0" required placeholder="Masukkan kata sandi lama">
                            <span class="input-group-text bg-white border-start-0 cursor-pointer" style="border: 1.5px solid var(--wood-border);" onclick="togglePasswordVisibility('custCurrentPass', 'eyeCurrent')">
                                <i class="fa-solid fa-eye text-muted" id="eyeCurrent"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark small fw-bold">Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password" id="custNewPass" class="form-control form-control-custom border-end-0" required placeholder="Minimal 6 karakter" minlength="6">
                            <span class="input-group-text bg-white border-start-0 cursor-pointer" style="border: 1.5px solid var(--wood-border);" onclick="togglePasswordVisibility('custNewPass', 'eyeNew')">
                                <i class="fa-solid fa-eye text-muted" id="eyeNew"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark small fw-bold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="custConfirmPass" class="form-control form-control-custom border-end-0" required placeholder="Ulangi kata sandi baru" minlength="6">
                            <span class="input-group-text bg-white border-start-0 cursor-pointer" style="border: 1.5px solid var(--wood-border);" onclick="togglePasswordVisibility('custConfirmPass', 'eyeConfirm')">
                                <i class="fa-solid fa-eye text-muted" id="eyeConfirm"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4 fw-bold" style="background-color: var(--primary-color); border: none;">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran foto terlalu besar! Maksimal 5MB.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgHero = document.getElementById('userAvatarHero');
                const imgSide = document.getElementById('userAvatarSide');
                if (imgHero) imgHero.src = e.target.result;
                if (imgSide) imgSide.src = e.target.result;
                const removeInput = document.getElementById('removePhotoInput');
                if (removeInput) removeInput.value = '0';
            };
            reader.readAsDataURL(file);
        }
    }

    function hapusFotoProfil() {
        if (confirm('Apakah Anda yakin ingin menghapus foto profil dan kembali ke avatar standar?')) {
            const removeInput = document.getElementById('removePhotoInput');
            if (removeInput) removeInput.value = '1';
            const fileInput = document.getElementById('profilePhotoInput');
            if (fileInput) fileInput.value = '';
            document.getElementById('profileForm').submit();
        }
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input) return;

        if (input.type === "password") {
            input.type = "text";
            if (icon) {
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
                icon.classList.remove("text-muted");
                icon.style.color = "var(--primary-color)";
            }
        } else {
            input.type = "password";
            if (icon) {
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
                icon.classList.add("text-muted");
                icon.style.color = "";
            }
        }
    }
</script>
@endsection