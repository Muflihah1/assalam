@extends('layouts.customer')

@section('content')
<style>
    /* Theme Palette Variables (Putih Bersih & Cokelat Mebel) */
    :root {
        --primary-color: #5d4037;     /* Cokelat Mebel */
        --secondary-color: #8d6e63;   /* Cokelat Sedang */
        --accent-orange: #d77a61;     /* Terracotta / Oranye Hangat */
        --accent-amber: #b45309;      /* Amber / Emas Kecokelatan */
        --accent-green: #15803d;      /* Hijau Sukses Lebih Tegas */
        
        --light-bg: #fdfcfb;          /* Putih Sedikit Hangat pada Background Utama */
        --light-card: #eadbc8;        /* Kartu Kotak Berwarna Kayu/Krem yang Jauh Lebih Tegas & Kontras */
        --light-border: #c4b5a3;      /* Garis Batas Lebih Gelap */
        --text-dark: #2d3748;         /* Teks Gelap Nyaman Dibaca */
        --text-muted: #4a5568;        /* Teks Redup Lebih Tebal & Jelas */
        --hover-bg: #dfcebc;          /* Warna Saat Disorot Lebih Gelap */
    }

    body {
        background-color: var(--light-bg) !important;
        color: var(--text-dark);
    }

    .wireframe-card {
        background-color: var(--light-card);
        border: 2px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.12);
        padding: 24px;
        margin-bottom: 20px;
    }

    .profile-avatar-box {
        position: relative;
        width: 110px;
        height: 110px;
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

    .btn-edit-avatar {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: var(--primary-color);
        color: #ffffff;
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-edit-avatar:hover {
        background-color: var(--secondary-color);
    }

    .form-control-light-custom {
        background-color: #fdfcfb !important;
        border: 2px solid var(--light-border) !important;
        color: var(--text-dark) !important;
        border-radius: 12px;
        padding: 10px 14px;
    }

    .form-control-light-custom:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(93, 64, 55, 0.15) !important;
    }

    .form-control-light-custom::placeholder {
        color: var(--text-muted) !important;
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

    .btn-orange-outline {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        font-weight: 700;
        border-radius: 12px;
        padding: 8px 24px;
        transition: all 0.2s;
    }

    .btn-orange-outline:hover {
        background: var(--primary-color);
        color: #ffffff;
    }

    .btn-danger-outline {
        border: 2px solid #dc2626;
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

    .address-card {
        background-color: #f5eddf;
        border: 2px solid var(--light-border);
        border-radius: 16px;
        padding: 18px;
    }

    .badge-default-address {
        background-color: rgba(93, 64, 55, 0.1);
        color: var(--primary-color);
        border: 1.5px solid var(--light-border);
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 700;
    }

    .map-container {
        width: 100%;
        height: 180px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--light-border);
        margin-top: 12px;
    }

    .map-modal-container {
        width: 100%;
        height: 250px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--light-border);
    }

    /* PILIHAN AVATAR ANIME PRESET */
    .avatar-preset-option {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--light-border);
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #fff;
    }

    .avatar-preset-option:hover, .avatar-preset-option.selected {
        border-color: var(--primary-color);
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.3);
    }

    /* MODAL PUTIH BERSIH */
    .modal-white-custom {
        background-color: #ffffff !important;
        color: var(--text-dark) !important;
        border: 2px solid var(--light-border) !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
</style>

<div class="container-fluid w-100 px-3 px-md-5 py-4">

    <!-- HEADER HALAMAN -->
    <div class="mb-4">
        <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Pengaturan Akun</h3>
        <p class="text-muted small mb-0">Kelola informasi profil, alamat pengiriman, dan keamanan akun Anda.</p>
    </div>

    <div class="row g-4">
        <!-- KOLOM KIRI: FOTO PROFIL & RINGKASAN AKUN -->
        <div class="col-lg-4">
            <div class="wireframe-card text-center">
                <div class="profile-avatar-box mb-3">
                    <img id="userAvatarPreview" src="https://api.dicebear.com/7.x/bottts/svg?seed=BudiAnime1" alt="Avatar Anime" class="profile-avatar-img">
                    <button class="btn-edit-avatar" onclick="bukaModalPilihAvatar()" title="Ubah Foto">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                </div>

                <h5 class="fw-bold text-dark mb-1">Budi Santoso</h5>
                <p class="text-muted small mb-3">budi.santoso@gmail.com</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge px-3 py-2 rounded-pill small fw-bold" style="background-color: #d1fae5; color: var(--accent-green); border: 1.5px solid #a7f3d0;">
                        <i class="fa-solid fa-user-check me-1"></i> Customer Terverifikasi
                    </span>
                </div>

                <hr style="border-color: var(--light-border);" class="my-4">

                <button class="btn btn-danger-outline w-100" onclick="bukaModalLogout()">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar Akun
                </button>
            </div>
        </div>

        <!-- KOLOM KANAN: FORM DETAIL PROFIL, ALAMAT, & KEAMANAN -->
        <div class="col-lg-8">
            
            <!-- 1. INFORMASI PROFIL -->
            <div class="wireframe-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-user me-2" style="color: var(--primary-color);"></i> Informasi Profil</h5>
                </div>

                <form id="formProfil" onsubmit="simpanProfil(event)">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-light-custom" value="Budi Santoso" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-bold">Nomor Telepon / WhatsApp</label>
                            <input type="tel" class="form-control form-control-light-custom" value="081234567890" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-dark small fw-bold">Alamat Email</label>
                            <input type="email" class="form-control form-control-light-custom" value="budi.santoso@gmail.com" required>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-orange-fill">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <!-- 2. ALAMAT PENGIRIMAN + MAPS -->
            <div class="wireframe-card">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-location-dot me-2" style="color: var(--primary-color);"></i> Alamat Pengiriman</h5>
                    <button class="btn btn-orange-outline btn-sm" onclick="bukaModalTambahAlamat()">+ Tambah Alamat</button>
                </div>

                <div class="address-card mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="fw-bold text-dark me-2">Rumah Utama</span>
                            <span class="badge-default-address">Utama</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                            </button>
                            <ul class="dropdown-menu shadow border-2" style="border-color: var(--light-border) !important; border-radius: 12px;">
                                <li><a class="dropdown-item small fw-bold text-dark py-2" href="#" onclick="bukaModalEditAlamat()">Edit Alamat & Titik Map</a></li>
                            </ul>
                        </div>
                    </div>
                    <p class="small mb-1 text-dark"><strong class="text-dark">Budi Santoso</strong> (081234567890)</p>
                    <p class="small mb-2 text-muted">Jl. Pemuda No. 45, Kecamatan Genteng, Kota Surabaya, Jawa Timur 60271</p>
                    
                    <!-- MAPS -->
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.9255850989066!2d112.74712531530932!3d-7.260655494758957!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f9611eb232cb%3A0x1382fb8f3822f5c2!2sJl.%20Pemuda%2C%20Surabaya%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1680000000000!5m2!1sid!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>

            <!-- 3. KEAMANAN & PASSWORD -->
            <div class="wireframe-card">
                <h5 class="fw-bold text-dark mb-4"><i class="fa-solid fa-shield-halved me-2" style="color: var(--primary-color);"></i> Keamanan Akun</h5>

                <form id="formPassword" onsubmit="ubahPassword(event)">
                    <div class="row g-3">
                        <!-- Password Saat Ini -->
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-light-custom" id="currentPassword" placeholder="Masukkan sandi lama" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary border-2 text-muted" type="button" onclick="togglePassword('currentPassword', this)" style="border-color: var(--light-border) !important; background-color: #f5eddf; border-top-right-radius: 12px; border-bottom-right-radius: 12px;" title="Lihat/Sembunyikan">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <span class="d-block text-muted mt-1" style="font-size: 0.7rem;">Sandi yang saat ini Anda gunakan untuk login.</span>
                        </div>

                        <!-- Password Baru -->
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-light-custom" id="newPassword" placeholder="Masukkan sandi baru" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary border-2 text-muted" type="button" onclick="togglePassword('newPassword', this)" style="border-color: var(--light-border) !important; background-color: #f5eddf; border-top-right-radius: 12px; border-bottom-right-radius: 12px;" title="Lihat/Sembunyikan">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <span class="d-block text-muted mt-1" style="font-size: 0.7rem;">Sandi baru pengganti yang diinginkan.</span>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-bold">Konfirmasi Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control form-control-light-custom" id="confirmPassword" placeholder="Ulangi sandi baru" required style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                <button class="btn btn-outline-secondary border-2 text-muted" type="button" onclick="togglePassword('confirmPassword', this)" style="border-color: var(--light-border) !important; background-color: #f5eddf; border-top-right-radius: 12px; border-bottom-right-radius: 12px;" title="Lihat/Sembunyikan">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <span class="d-block text-muted mt-1" style="font-size: 0.7rem;">Ketik ulang password baru untuk verifikasi.</span>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-orange-fill">Perbarui Password</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<!-- MODAL PILIH / UBAH AVATAR ANIME ATAU DARI GALERI -->
<div class="modal fade" id="modalPilihAvatar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-white-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Ubah Avatar Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <p class="text-muted small mb-3">Pilih salah satu avatar gaya anime / ilustrasi keren di bawah ini, atau unggah gambar sendiri dari galeri perangkat Anda.</p>

            <!-- PILIHAN AVATAR ANIME / ILUSTRASI PRESET -->
            <label class="form-label text-dark small fw-bold mb-2">Pilih Avatar Gaya Anime:</label>
            <div class="d-flex justify-content-between gap-2 mb-4">
                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=AnimeHero1" class="avatar-preset-option" onclick="pilihAvatarPreset(this.src)" alt="Anime 1">
                <img src="https://api.dicebear.com/7.x/adventurer/svg?seed=AnimeHero2" class="avatar-preset-option" onclick="pilihAvatarPreset(this.src)" alt="Anime 2">
                <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=AnimeHero3" class="avatar-preset-option" onclick="pilihAvatarPreset(this.src)" alt="Anime 3">
                <img src="https://api.dicebear.com/7.x/bottts/svg?seed=AnimeHero4" class="avatar-preset-option" onclick="pilihAvatarPreset(this.src)" alt="Anime 4">
            </div>

            <div class="text-center text-muted small my-2">--- ATAU ---</div>

            <!-- UPLOAD DARI GALERI -->
            <div class="mt-2">
                <button type="button" class="btn btn-outline-secondary py-2 rounded-3 fw-bold border-2 d-flex align-items-center justify-content-center gap-2 mx-auto w-100" style="border-color: var(--light-border) !important; color: var(--text-dark); background-color: #f5eddf;" onclick="document.getElementById('uploadGaleriInput').click()">
                    <i class="fa-solid fa-image" style="color: var(--primary-color);"></i> Unggah Gambar dari Galeri
                </button>
                <input type="file" id="uploadGaleriInput" class="d-none" accept="image/*" onchange="prosesUploadGaleri(event)">
            </div>

            <div class="mt-4 text-end">
                <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-bold text-white" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP: SUKSES -->
<div class="modal fade" id="modalSuksesGeneral" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-white-custom p-4 text-center">
            <div class="mb-3">
                <i class="fa-solid fa-circle-check" style="font-size: 3.5rem; color: var(--accent-green);"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2" id="modalSuksesJudul">Berhasil!</h4>
            <p class="text-muted small mb-4" id="modalSuksesPesan">Perubahan data akun Anda telah berhasil disimpan.</p>

            <button type="button" class="btn fw-bold w-100 py-2 rounded-3 text-white" style="background-color: var(--accent-green);" data-bs-dismiss="modal">Selesai</button>
        </div>
    </div>
</div>

<!-- MODAL POPUP: TAMBAH / EDIT ALAMAT & MAPS -->
<div class="modal fade" id="modalAlamat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-white-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0" id="modalAlamatTitle">Tambah Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form onsubmit="simpanAlamat(event)">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Label Alamat</label>
                            <input type="text" class="form-control form-control-light-custom" id="inputLabelAlamat" placeholder="Contoh: Rumah / Kantor" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Nama Penerima & No. Telp</label>
                            <input type="text" class="form-control form-control-light-custom" id="inputPenerimaAlamat" value="Budi Santoso (081234567890)" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Alamat Lengkap</label>
                            <textarea class="form-control form-control-light-custom" id="inputDetailAlamat" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kecamatan, kota..." required></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Pilih Titik Pin Peta (Google Maps)</label>
                        <div class="map-modal-container mb-2">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.9255850989066!2d112.74712531530932!3d-7.260655494758957!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7f9611eb232cb%3A0x1382fb8f3822f5c2!2sJl.%20Pemuda%2C%20Surabaya%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1680000000000!5m2!1sid!2sid" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                        <p class="text-muted small" style="font-size: 0.75rem;"><i class="fa-solid fa-circle-info me-1" style="color: var(--primary-color);"></i> Pin lokasi presisi membantu pengiriman mebel dengan aman.</p>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn fw-bold px-4 py-2 rounded-3 text-white" style="background-color: var(--primary-color);">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL POPUP: KONFIRMASI LOGOUT -->
<div class="modal fade" id="modalLogout" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-white-custom p-4 text-center">
            <div class="mb-3">
                <i class="fa-solid fa-right-from-bracket" style="font-size: 3.5rem; color: #dc2626;"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Konfirmasi Keluar</h4>
            <p class="text-muted small mb-4">Apakah Anda yakin ingin keluar dari akun Anda?</p>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger fw-bold w-50 py-2 rounded-3" onclick="prosesLogout()">Ya, Keluar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // FUNGSI TOGGLE LIHAT / SEMBUNYIKAN PASSWORD (MATA)
    function togglePassword(fieldId, btn) {
        let inputField = document.getElementById(fieldId);
        let icon = btn.querySelector('i');

        if (inputField.type === "password") {
            inputField.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            inputField.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    function tampilkanModalSukses(judul, pesan) {
        document.getElementById('modalSuksesJudul').innerText = judul;
        document.getElementById('modalSuksesPesan').innerText = pesan;
        let modal = new bootstrap.Modal(document.getElementById('modalSuksesGeneral'));
        modal.show();
    }

    function simpanProfil(e) {
        e.preventDefault();
        tampilkanModalSukses('Profil Diperbarui', 'Data informasi profil Anda telah berhasil disimpan.');
    }

    function ubahPassword(e) {
        e.preventDefault();
        document.getElementById('formPassword').reset();
        tampilkanModalSukses('Password Diubah', 'Kata sandi akun Anda telah berhasil diperbarui.');
    }

    function bukaModalPilihAvatar() {
        let modal = new bootstrap.Modal(document.getElementById('modalPilihAvatar'));
        modal.show();
    }

    function pilihAvatarPreset(url) {
        document.getElementById('userAvatarPreview').src = url;
        let modalEl = document.getElementById('modalPilihAvatar');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        setTimeout(() => {
            tampilkanModalSukses('Avatar Diperbarui', 'Avatar gaya anime pilihan Anda telah berhasil disimpan.');
        }, 300);
    }

    function prosesUploadGaleri(event) {
        let file = event.target.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('userAvatarPreview').src = e.target.result;
                let modalEl = document.getElementById('modalPilihAvatar');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                setTimeout(() => {
                    tampilkanModalSukses('Foto Profil Diperbarui', 'Gambar dari galeri berhasil diunggah.');
                }, 300);
            }
            reader.readAsDataURL(file);
        }
    }

    function bukaModalTambahAlamat() {
        document.getElementById('modalAlamatTitle').innerText = 'Tambah Alamat Baru';
        document.getElementById('inputLabelAlamat').value = '';
        document.getElementById('inputDetailAlamat').value = '';
        let modal = new bootstrap.Modal(document.getElementById('modalAlamat'));
        modal.show();
    }

    function bukaModalEditAlamat() {
        document.getElementById('modalAlamatTitle').innerText = 'Edit Alamat & Titik Map';
        document.getElementById('inputLabelAlamat').value = 'Rumah Utama';
        document.getElementById('inputDetailAlamat').value = 'Jl. Pemuda No. 45, Kecamatan Genteng, Kota Surabaya, Jawa Timur 60271';
        let modal = new bootstrap.Modal(document.getElementById('modalAlamat'));
        modal.show();
    }

    function simpanAlamat(e) {
        e.preventDefault();
        let modalEl = document.getElementById('modalAlamat');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        setTimeout(() => {
            tampilkanModalSukses('Alamat Disimpan', 'Detail alamat pengiriman dan titik peta Anda telah berhasil diperbarui.');
        }, 400);
    }

    function bukaModalLogout() {
        let modal = new bootstrap.Modal(document.getElementById('modalLogout'));
        modal.show();
    }

    function prosesLogout() {
        let modalEl = document.getElementById('modalLogout');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        window.location.href = "/login";
    }
</script>
@endsection