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

    /* HORIZONTAL TIMELINE DENGAN 8 CARD KLIKABLE */
    .timeline-scroll-container {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .timeline-step-card {
        background-color: #f5eddf;
        border: 2px solid var(--light-border);
        border-radius: 14px;
        padding: 14px 10px;
        flex: 1;
        min-width: 110px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .timeline-step-card:hover {
        border-color: var(--primary-color);
        background-color: #fffaf0;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(93, 64, 55, 0.18);
    }

    .timeline-step-card.active-step {
        border-color: var(--primary-color);
        background-color: #fffaf0;
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.15);
    }

    .upload-date-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        margin-bottom: 6px;
        min-height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
    }

    .step-img-box {
        width: 100%;
        height: 110px;
        background-color: #ffffff;
        border: 1.5px solid var(--light-border);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 8px;
        position: relative;
    }

    .step-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .step-title-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.2;
    }

    /* BUTTONS */
    .btn-orange-outline {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        font-weight: 700;
        border-radius: 30px;
        padding: 8px 30px;
        transition: all 0.2s;
    }

    .btn-orange-outline:hover {
        background: var(--primary-color);
        color: #ffffff;
    }

    .btn-action-dark {
        border: 2px solid var(--light-border);
        background-color: #f5eddf;
        color: var(--text-dark);
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: all 0.2s;
    }

    .btn-action-dark:hover {
        border-color: var(--primary-color);
        background-color: var(--hover-bg);
        color: var(--primary-color);
    }

    /* MODAL PUTIH BERSIH */
    .modal-white-custom {
        background-color: #ffffff !important;
        color: var(--text-dark) !important;
        border: 2px solid var(--light-border) !important;
        border-radius: 20px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .media-preview-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }

    .media-box-item {
        background-color: #f5eddf;
        border: 1px solid var(--light-border);
        border-radius: 10px;
        height: 120px;
        overflow: hidden;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .media-box-item:hover {
        transform: scale(1.02);
        border-color: var(--primary-color);
    }

    .media-box-item img, .media-box-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="container-fluid w-100 px-3 px-md-5 py-4">

    <!-- 1. SPESIFIKASI PELANGGAN -->
    <div class="wireframe-card">
        <h4 class="fw-bold text-dark mb-3 text-decoration-underline" style="text-underline-offset: 6px; color: var(--primary-color);">Spesifikasi Pelanggan</h4>
        <div class="row g-3">
            <div class="col-md-12">
                <p class="mb-2 text-muted"><strong class="text-dark">Deskripsi:</strong> Custom Sofa 3-Seater Minimalis Kayu Jati Perhutani dengan finishing Natural Teak & Busa Ekstra Empuk.</p>
                <p class="mb-2 text-muted"><strong class="text-dark">Ukuran:</strong> 180 cm (Panjang) x 80 cm (Lebar) x 75 cm (Tinggi)</p>
                <p class="mb-0 text-muted"><strong class="text-dark">Nama Pelanggan:</strong> Budi Santoso (#ORD-8821)</p>
            </div>
        </div>
    </div>

    <!-- 2. TIMELINE UPDATES (8 TAHAP SESUAI GAMBAR & KLIKABLE) -->
    <div class="wireframe-card">
        <h5 class="fw-bold text-dark mb-4 text-uppercase tracking-wider" style="color: var(--primary-color);">TIMELINE UPDATES</h5>

        <div class="timeline-scroll-container">
            
            <!-- Tahap 1: Konfirmasi Pesanan -->
            <div class="timeline-step-card" onclick="bukaModalTimeline('Konfirmasi Pesanan', '08 Ags 2026', 'Selesai')">
                <div class="upload-date-label">
                    <i class="fa-solid fa-circle-check text-success"></i> 08 Ags 2026
                </div>
                <div class="step-img-box">
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=500&auto=format&fit=crop" alt="Konfirmasi Pesanan">
                </div>
                <div class="step-title-label">Konfirmasi Pesanan</div>
            </div>

            <!-- Tahap 2: Validasi Pembayaran -->
            <div class="timeline-step-card" onclick="bukaModalTimeline('Validasi Pembayaran', '09 Ags 2026', 'Selesai')">
                <div class="upload-date-label">
                    <i class="fa-solid fa-circle-check text-success"></i> 09 Ags 2026
                </div>
                <div class="step-img-box">
                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=500&auto=format&fit=crop" alt="Validasi Pembayaran">
                </div>
                <div class="step-title-label">Validasi Pembayaran</div>
            </div>

            <!-- Tahap 3: Pesanan Diterima -->
            <div class="timeline-step-card" onclick="bukaModalTimeline('Pesanan Diterima', '10 Ags 2026', 'Selesai')">
                <div class="upload-date-label">
                    <i class="fa-solid fa-circle-check text-success"></i> 10 Ags 2026
                </div>
                <div class="step-img-box">
                    <img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500&auto=format&fit=crop" alt="Pesanan Diterima">
                </div>
                <div class="step-title-label">Pesanan Diterima</div>
            </div>

            <!-- Tahap 4: Menyiapkan Bahan -->
            <div class="timeline-step-card" onclick="bukaModalTimeline('Menyiapkan Bahan', '11 Ags 2026', 'Selesai')">
                <div class="upload-date-label">
                    <i class="fa-solid fa-circle-check text-success"></i> 11 Ags 2026
                </div>
                <div class="step-img-box">
                    <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500&auto=format&fit=crop" alt="Menyiapkan Bahan">
                </div>
                <div class="step-title-label">Menyiapkan Bahan</div>
            </div>

            <!-- Tahap 5: Perakitan 100% -->
            <div class="timeline-step-card active-step" onclick="bukaModalTimeline('Perakitan 100%', '12 Ags 2026', 'Sedang Berjalan')">
                <div class="upload-date-label">
                    <i class="fa-solid fa-circle-check text-success"></i> 12 Ags 2026
                </div>
                <div class="step-img-box">
                    <img src="https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=500&auto=format&fit=crop" alt="Perakitan 100%">
                </div>
                <div class="step-title-label">Perakitan 100%</div>
            </div>

            <!-- Tahap 6: Penyelesaian -->
            <div class="timeline-step-card" onclick="bukaModalTimeline('Penyelesaian', '14 Ags 2026', 'Selesai')">
                <div class="upload-date-label">
                    <i class="fa-solid fa-circle-check text-success"></i> 14 Ags 2026
                </div>
                <div class="step-img-box">
                    <img src="https://images.unsplash.com/photo-1513694203232-719a280e022f?w=500&auto=format&fit=crop" alt="Penyelesaian">
                </div>
                <div class="step-title-label">Penyelesaian</div>
            </div>

            <!-- Tahap 7: Pengiriman -->
            <div class="timeline-step-card" onclick="bukaModalTimeline('Pengiriman', 'Pending', 'Belum Dimulai')">
                <div class="upload-date-label text-muted">
                    <i class="fa-regular fa-circle text-muted"></i> Pending
                </div>
                <div class="step-img-box">
                    <span class="text-muted small text-center px-1"><i class="fa-solid fa-image fa-lg d-block mb-1" style="color: var(--primary-color);"></i>Belum Upload</span>
                </div>
                <div class="step-title-label">Pengiriman</div>
            </div>

            <!-- Tahap 8: Pesanan Selesai -->
            <div class="timeline-step-card" onclick="bukaModalTimeline('Pesanan Selesai', 'Pending', 'Belum Dimulai')">
                <div class="upload-date-label text-muted">
                    <i class="fa-regular fa-circle text-muted"></i> Pending
                </div>
                <div class="step-img-box">
                    <span class="text-muted small text-center px-1"><i class="fa-solid fa-image fa-lg d-block mb-1" style="color: var(--primary-color);"></i>Belum Upload</span>
                </div>
                <div class="step-title-label">Pesanan Selesai</div>
            </div>

        </div>
    </div>

    <!-- 3. BOTTOM ACTION BOX (PELUNASAN & PESANAN SELESAI) -->
    <div class="row g-3">
        <!-- BOX KIRI: SISA PELUNASAN -->
        <div class="col-lg-7">
            <div class="wireframe-card d-flex flex-column align-items-center justify-content-center py-4 mb-0 h-100">
                <h4 class="fw-bold text-dark mb-3">Sisa Pelunasan : <span style="color: var(--accent-amber);">Rp 1.500.000</span></h4>
                <button class="btn btn-orange-outline" onclick="bukaModalPelunasan()">Bayar</button>
            </div>
        </div>

        <!-- BOX KANAN: PESANAN SELESAI -->
        <div class="col-lg-5">
            <div class="wireframe-card d-flex align-items-center justify-content-center py-4 mb-0 h-100">
                <button class="btn btn-action-dark w-100 py-3 fs-5 shadow-sm" onclick="bukaModalSelesai()">Pesanan Selesai</button>
            </div>
        </div>
    </div>

</div>

<!-- MODAL POPUP 1: DETAIL FOTO / VIDEO TIMELINE -->
<div class="modal fade" id="modalTimelineDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-white-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1" id="modalTimelineTitle">Detail Progress</h5>
                    <p class="text-muted small mb-0">Tanggal Update: <span id="modalTimelineDate" class="fw-bold text-dark"></span></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <hr class="text-muted">

            <p class="small text-muted mb-2 fw-bold">Klik gambar di bawah untuk memperbesar:</p>
            
            <div class="media-preview-grid">
                <div class="media-box-item" onclick="bukaLightbox('https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=1000&auto=format&fit=crop')">
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=500&auto=format&fit=crop" alt="Dokumentasi 1">
                </div>
                <div class="media-box-item" onclick="bukaLightbox('https://images.unsplash.com/photo-1504148455328-c376907d081c?w=1000&auto=format&fit=crop')">
                    <img src="https://images.unsplash.com/photo-1504148455328-c376907d081c?w=500&auto=format&fit=crop" alt="Dokumentasi 2">
                </div>
                <div class="media-box-item" onclick="bukaLightbox('https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=1000&auto=format&fit=crop')">
                    <img src="https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=500&auto=format&fit=crop" alt="Dokumentasi 3">
                </div>
                <div class="media-box-item bg-light d-flex flex-column align-items-center justify-content-center" onclick="bukaLightboxVideo()">
                    <i class="fa-solid fa-video fa-2x mb-1" style="color: var(--primary-color);"></i>
                    <span class="text-muted fw-bold" style="font-size: 0.75rem;">Putar Video</span>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP 2: LIGHTBOX PREVIEW SATU GAMBAR -->
<div class="modal fade" id="modalLightbox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content modal-white-custom p-3 text-center">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold text-dark small">Pratinjau Foto</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="rounded-3 overflow-hidden bg-dark d-flex align-items-center justify-content-center" style="min-height: 300px; max-height: 450px;">
                <img id="lightboxImage" src="" alt="Pratinjau Besar" class="w-100 h-auto" style="object-fit: contain; max-height: 450px;">
                <div id="lightboxVideoContainer" class="w-100 d-none">
                    <video id="lightboxVideo" controls class="w-100" style="max-height: 450px;">
                        <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                        Browser Anda tidak mendukung pemutar video.
                    </video>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-sm btn-secondary px-3 py-1 fw-bold" data-bs-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP 3: PEMBAYARAN PELUNASAN (DENGAN E-WALLET & BANK BCA TANPA MANDIRI) -->
<div class="modal fade" id="modalPelunasan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content modal-white-custom p-4 text-center">
            <h5 class="fw-bold text-dark mb-1">PEMBAYARAN PELUNASAN</h5>
            <p class="text-muted small mb-3">Selesaikan pelunasan sebelum barang dikirim</p>

            <p class="fw-bold fs-3 mb-3" style="color: var(--accent-amber);">Rp 1.500.000</p>

            <!-- PILIHAN METODE PEMBAYARAN -->
            <div class="text-start mb-3">
                <label class="form-label fw-bold small text-dark">Pilih Metode Pembayaran:</label>
                <select id="metodePembayaran" class="form-select border-2" style="border-color: var(--light-border);" onchange="gantiMetodeBayar()">
                    <option value="qris">QRIS (Semua E-Wallet & M-Banking)</option>
                    <option value="dana">E-Wallet : DANA</option>
                    <option value="gopay">E-Wallet : GoPay</option>
                    <option value="transfer">Transfer Bank (BCA)</option>
                </select>
            </div>

            <!-- KONTEN INFO PEMBAYARAN BERDASARKAN PILIHAN -->
            <div id="infoQris" class="p-3 border rounded-4 mb-3 bg-white shadow-sm" style="border-color: var(--light-border) !important;">
                <div class="border p-3 rounded-3 mb-2 mx-auto bg-light" style="width: 150px; height: 150px;">
                    <div class="d-flex h-100 align-items-center justify-content-center text-dark fw-bold">
                        [ QRIS CODE ]
                    </div>
                </div>
                <span class="small text-muted">Scan menggunakan aplikasi bank atau e-wallet apa saja.</span>
            </div>

            <div id="infoDana" class="p-3 border rounded-4 mb-3 bg-white shadow-sm d-none" style="border-color: var(--light-border) !important;">
                <div class="text-primary fw-bold fs-5 mb-1">DANA</div>
                <p class="mb-1 small text-muted">Nomor Akun DANA Mebel:</p>
                <h5 class="fw-bold text-dark mb-2">0812-3456-7890</h5>
                <span class="small text-muted">Atas Nama: Assalam Mebel Official</span>
            </div>

            <div id="infoGopay" class="p-3 border rounded-4 mb-3 bg-white shadow-sm d-none" style="border-color: var(--light-border) !important;">
                <div class="text-success fw-bold fs-5 mb-1">GoPay</div>
                <p class="mb-1 small text-muted">Nomor Akun GoPay Mebel:</p>
                <h5 class="fw-bold text-dark mb-2">0812-3456-7890</h5>
                <span class="small text-muted">Atas Nama: Assalam Mebel Official</span>
            </div>

            <div id="infoTransfer" class="text-start mx-auto small mb-4 text-muted d-none" style="max-width: 280px;">
                <p class="mb-1">🏦 BCA : <span class="fw-bold text-dark">8830-xx-xxx</span></p>
                <span class="d-block mt-2 text-center text-muted" style="font-size: 0.75rem;">Atas Nama: CV. Assalam Mebel Indonesia</span>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);" onclick="prosesLunas()">Konfirmasi Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL POPUP 4: SUKSES PEMBAYARAN -->
<div class="modal fade" id="modalLunasSukses" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-white-custom p-4 text-center">
            <div class="mb-3">
                <i class="fa-solid fa-circle-check text-success" style="font-size: 3.5rem;"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Pembayaran Berhasil!</h4>
            <p class="text-muted small mb-4">Terima kasih, pembayaran pelunasan Anda telah berhasil diverifikasi oleh sistem.</p>

            <button type="button" class="btn fw-bold w-100 py-2 rounded-3 text-white" style="background-color: var(--accent-green);" onclick="tutupModalSukses()">Selesai & Muat Ulang</button>
        </div>
    </div>
</div>

<!-- MODAL POPUP 5: KONFIRMASI PESANAN SELESAI -->
<div class="modal fade" id="modalSelesai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-white-custom p-4 text-center">
            <div class="mb-3">
                <i class="fa-solid fa-circle-check text-success" style="font-size: 3.5rem;"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Konfirmasi Pesanan Selesai</h4>
            <p class="text-muted small mb-4">Apakah Anda telah menerima produk mebel dalam kondisi baik dan lengkap?</p>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--accent-green);" onclick="prosesPesananSelesai()">Ya, Selesai</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Buka Modal Detail Timeline per Kotak
    function bukaModalTimeline(title, date, status) {
        document.getElementById('modalTimelineTitle').innerText = title + " (" + status + ")";
        document.getElementById('modalTimelineDate').innerText = date;
        let modal = new bootstrap.Modal(document.getElementById('modalTimelineDetail'));
        modal.show();
    }

    // Buka Lightbox Gambar Besar 1 Persatu
    function bukaLightbox(urlGambar) {
        document.getElementById('lightboxImage').src = urlGambar;
        document.getElementById('lightboxImage').classList.remove('d-none');
        document.getElementById('lightboxVideoContainer').classList.add('d-none');
        
        let lightboxModal = new bootstrap.Modal(document.getElementById('modalLightbox'));
        lightboxModal.show();
    }

    // Buka Lightbox Video
    function bukaLightboxVideo() {
        document.getElementById('lightboxImage').classList.add('d-none');
        document.getElementById('lightboxVideoContainer').classList.remove('d-none');
        
        let lightboxModal = new bootstrap.Modal(document.getElementById('modalLightbox'));
        lightboxModal.show();
    }

    // Ganti Konten Berdasarkan Metode Pembayaran yang Dipilih
    function gantiMetodeBayar() {
        let metode = document.getElementById('metodePembayaran').value;
        
        document.getElementById('infoQris').classList.add('d-none');
        document.getElementById('infoDana').classList.add('d-none');
        document.getElementById('infoGopay').classList.add('d-none');
        document.getElementById('infoTransfer').classList.add('d-none');

        if (metode === 'qris') {
            document.getElementById('infoQris').classList.remove('d-none');
        } else if (metode === 'dana') {
            document.getElementById('infoDana').classList.remove('d-none');
        } else if (metode === 'gopay') {
            document.getElementById('infoGopay').classList.remove('d-none');
        } else if (metode === 'transfer') {
            document.getElementById('infoTransfer').classList.remove('d-none');
        }
    }

    // Buka Modal Pelunasan
    function bukaModalPelunasan() {
        let modal = new bootstrap.Modal(document.getElementById('modalPelunasan'));
        modal.show();
    }

    // Eksekusi Pelunasan -> Tutup Modal Pembayaran, Lalu Tampilkan Modal Sukses
    function prosesLunas() {
        let modalPembayaranEl = document.getElementById('modalPelunasan');
        let modalPembayaran = bootstrap.Modal.getInstance(modalPembayaranEl);
        if (modalPembayaran) modalPembayaran.hide();

        setTimeout(() => {
            let modalSukses = new bootstrap.Modal(document.getElementById('modalLunasSukses'));
            modalSukses.show();
        }, 400);
    }

    // Tutup Modal Sukses & Reload
    function tutupModalSukses() {
        location.reload();
    }

    // Buka Modal Pesanan Selesai
    function bukaModalSelesai() {
        let modal = new bootstrap.Modal(document.getElementById('modalSelesai'));
        modal.show();
    }

    // Eksekusi Pesanan Selesai (Langsung Redirect)
    function prosesPesananSelesai() {
        let modalEl = document.getElementById('modalSelesai');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        window.location.href = "/customer/riwayat";
    }
</script>
@endsection