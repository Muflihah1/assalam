@extends('layouts.customer')

@section('content')
<style>
    /* Theme Palette Variables (Putih Bersih & Cokelat Mebel - Disamakan dengan Halaman Progress) */
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

    .history-card {
        background-color: var(--light-card);
        border: 2px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.12);
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.2s ease-in-out;
    }

    .history-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .badge-status-done {
        background-color: #d1fae5;
        color: var(--accent-green);
        border: 1.5px solid #a7f3d0;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge-status-process {
        background-color: #ffedd5;
        color: var(--accent-amber);
        border: 1.5px solid #fed7aa;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .product-img-box {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #ffffff;
        border: 1.5px solid var(--light-border);
        flex-shrink: 0;
    }

    .product-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-orange-outline {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        font-weight: 700;
        border-radius: 30px;
        padding: 8px 24px;
        font-size: 0.875rem;
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
        padding: 8px 20px;
        font-size: 0.875rem;
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
</style>

<div class="container-fluid w-100 px-3 px-md-5 py-4">

    <!-- HEADER / JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Riwayat Pemesanan</h3>
            <p class="text-muted small mb-0">Daftar transaksi dan status pesanan mebel custom Anda.</p>
        </div>
    </div>

    <!-- PESANAN 1: SELESAI -->
    <div class="history-card">
        <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom" style="border-color: var(--light-border) !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-bag-shopping fs-5" style="color: var(--primary-color);"></i>
                <span class="fw-bold text-dark small">08 Ags 2026</span>
                <span class="text-muted small">| #ORD-8821</span>
            </div>
            <span class="badge-status-done"><i class="fa-solid fa-circle-check me-1"></i> Pesanan Selesai</span>
        </div>

        <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between">
            <div class="d-flex gap-3 align-items-center">
                <div class="product-img-box">
                    <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=500&auto=format&fit=crop" alt="Foto Mebel">
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Custom Sofa 3-Seater Minimalis</h5>
                    <p class="text-muted small mb-1">Kayu Jati Perhutani - Finishing Natural Teak</p>
                    <p class="text-muted small mb-0">Ukuran: 180 cm x 80 cm x 75 cm</p>
                </div>
            </div>

            <div class="text-md-end mt-3 mt-md-0">
                <span class="text-muted small d-block mb-1">Total Belanja</span>
                <h4 class="fw-bold mb-3" style="color: var(--accent-amber);">Rp 4.500.000</h4>
                <div class="d-flex gap-2 justify-content-md-end">
                    <button class="btn btn-action-dark" onclick="bukaModalDetail('#ORD-8821')">Detail Pesanan</button>
                    <button class="btn btn-orange-outline" onclick="bukaModalUlasan('#ORD-8821')">Beri Ulasan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- PESANAN 2: DALAM PROSES -->
    <div class="history-card">
        <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom" style="border-color: var(--light-border) !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-bag-shopping fs-5" style="color: var(--primary-color);"></i>
                <span class="fw-bold text-dark small">12 Ags 2026</span>
                <span class="text-muted small">| #ORD-8825</span>
            </div>
            <span class="badge-status-process"><i class="fa-solid fa-spinner fa-spin me-1"></i> Dalam Proses</span>
        </div>

        <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between">
            <div class="d-flex gap-3 align-items-center">
                <div class="product-img-box">
                    <img src="https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=500&auto=format&fit=crop" alt="Foto Mebel">
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1">Meja Makan Kayu Solid 6 Kursi</h5>
                    <p class="text-muted small mb-1">Kayu Mahoni Grade A - Finishing Walnut Brown</p>
                    <p class="text-muted small mb-0">Ukuran: 200 cm x 90 cm x 78 cm</p>
                </div>
            </div>

            <div class="text-md-end mt-3 mt-md-0">
                <span class="text-muted small d-block mb-1">Total Belanja</span>
                <h4 class="fw-bold mb-3" style="color: var(--accent-amber);">Rp 6.200.000</h4>
                <div class="d-flex gap-2 justify-content-md-end">
                    <a href="/customer/progress" class="btn btn-orange-outline">Cek Progress</a>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- MODAL DETAIL PESANAN -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-white-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-3 mb-3 rounded-3" style="background-color: #f5eddf; border: 1px solid var(--light-border);">
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">No. Transaksi</span>
                    <span class="fw-bold text-dark" id="modalOrderNo">#ORD-8821</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Status</span>
                    <span class="fw-bold" style="color: var(--accent-green);">Selesai</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Metode Pembayaran</span>
                    <span class="text-dark fw-bold">QRIS / E-Wallet / Transfer</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">DP Terbayar</span>
                    <span class="fw-bold" style="color: var(--accent-amber);">Rp 3.000.000</span>
                </div>
            </div>
            <button type="button" class="btn btn-secondary w-100 py-2 rounded-3 fw-bold text-white" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL ULASAN -->
<div class="modal fade" id="modalUlasan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-white-custom p-4 text-center">
            <h5 class="fw-bold text-dark mb-2">Berikan Ulasan</h5>
            <p class="text-muted small mb-3">Bagaimana kualitas produk dan pelayanan kami?</p>

            <div class="mb-3 fs-3" style="color: var(--accent-amber);">
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
                <i class="fa-regular fa-star cursor-pointer"></i>
            </div>

            <textarea class="form-control mb-3 text-dark border-2" rows="3" placeholder="Tuliskan ulasan Anda..." style="background-color: #fdfcfb; border-color: var(--light-border) !important; border-radius: 12px;"></textarea>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);" onclick="kirimUlasan()">Kirim Ulasan</button>
            </div>
        </div>
    </div>
</div>

<script>
    function bukaModalDetail(orderNo) {
        document.getElementById('modalOrderNo').innerText = orderNo;
        let modal = new bootstrap.Modal(document.getElementById('modalDetail'));
        modal.show();
    }

    function bukaModalUlasan(orderNo) {
        let modal = new bootstrap.Modal(document.getElementById('modalUlasan'));
        modal.show();
    }

    function kirimUlasan() {
        let modalEl = document.getElementById('modalUlasan');
        let modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }
</script>
@endsection