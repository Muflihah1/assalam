@extends('layouts.customer')

@section('content')
<style>
    :root {
        --primary-color: #5d4037;     /* Cokelat Mebel */
        --secondary-color: #8d6e63;   /* Cokelat Sedang */
        --accent-orange: #d77a61;     /* Terracotta / Oranye Hangat */
        
        /* Variabel Tema Putih Bersih */
        --light-bg: #ffffff;          /* Putih Total */
        --light-card: #ffffff;        /* Kartu Putih Bersih */
        --light-border: #e2e8f0;      /* Garis Batas Abu-abu Lembut */
        --text-dark: #2d3748;         /* Teks Gelap Nyaman Dibaca */
        --text-muted: #718096;        /* Teks Redup */
        
        /* Warna Krem Kayu yang Lebih Kaya dan Berwarna */
        --wood-bg: #edd6bd;           /* Warna krem kayu hangat yang lebih kuat */
        --wood-border: #bfa084;       /* Garis batas kayu yang lebih kontras */
    }

    body {
        background-color: var(--light-bg) !important;
        color: var(--text-dark);
    }

    /* 1. HERO BANNER - ELEGANT WARM GRADIENT */
    .hero-section {
        background: linear-gradient(135deg, #5d4037 0%, #3e2723 100%);
        border: 1px solid var(--light-border);
        border-radius: 24px;
        color: #ffffff;
        padding: 60px 40px;
        position: relative;
        box-shadow: 0 10px 30px rgba(93, 64, 55, 0.1);
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .badge-dark {
        background: rgba(215, 122, 97, 0.15);
        border: 1px solid var(--accent-orange);
        color: var(--accent-orange);
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 30px;
    }

    /* 4. PRODUCT CARDS - KOTAK PRODUK FAVORIT (WARNA KREM KAYU) */
    .product-card {
        border: 2px solid var(--wood-border);
        border-radius: 20px;
        background: var(--wood-bg);
        box-shadow: 0 6px 16px rgba(93, 64, 55, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-8px);
        border-color: var(--primary-color);
        box-shadow: 0 15px 30px rgba(93, 64, 55, 0.15);
    }

    .product-img-holder {
        border-radius: 16px;
        height: 220px;
        background: #fdfaf6;
        border: 1.5px solid var(--wood-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dark);
    }

    /* BUTTON STYLES */
    .btn-orange {
        background: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        transition: all 0.2s;
    }

    .btn-orange:hover {
        background: var(--secondary-color);
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(93, 64, 55, 0.2);
    }

    .btn-outline-dark-theme {
        border: 2px solid var(--wood-border);
        background: var(--wood-bg);
        color: var(--text-dark);
        font-weight: 700;
        border-radius: 12px;
        padding: 10px 20px;
        transition: 0.2s;
    }

    .btn-outline-dark-theme:hover {
        background: #e4c4a7;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- 1. HERO BANNER (Bagian Visual disebelah kanan dihapus sesuai permintaan) -->
    <section class="hero-section mb-5">
        <div class="row align-items-center">
            <div class="col-lg-10 mb-4 mb-lg-0">
                <span class="badge badge-dark mb-3">✨ Custom Furniture Premium</span>
                <h1 class="hero-title mb-3">Bikin Furniture Impian Sesuai Gaya & Ruanganmu</h1>
                <p class="text-white-50 fs-5 mb-4">Pilih material kayu premium, atur ukuran presisi, hingga tentukan warna favoritmu langsung di studio kustom kami.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('customer.katalog') }}" class="btn btn-outline-dark-theme px-4 py-3 rounded-3" style="background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.3);">Lihat Katalog</a>
                    <a href="{{ route('customer.design') }}" class="btn btn-orange px-4 py-3 rounded-3">Mulai Custom 🎨</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KEUNGGULAN (Dihapus sesuai permintaan) -->

    <!-- 3. KATEGORI (Dihapus sesuai permintaan) -->

    <!-- 4. PRODUK UNGGULAN (WARNA KREM KAYU LEBIH JELAS) -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Produk Terfavorit</h3>
                <p class="text-muted fs-5 mb-4">Desain paling populer dan banyak dibuat kustom</p>
            </div>
            <a href="{{ route('customer.katalog') }}" class="btn btn-outline-dark-theme text-decoration-none">Lihat Semua Katalog →</a>
        </div>

        <div class="row g-4">
            <!-- Product Card 1 -->
            <div class="col-md-4">
                <div class="product-card p-3">
                    <div class="product-img-holder mb-3">
                        <span class="fw-bold fs-5 text-dark">Sofa Minimalis Oak</span>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Sofa Modern 3 Seater</h5>
                    <p class="text-muted small mb-2">Kayu Jati + Busa Premium Layer</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="price-text fw-bold" style="color: var(--primary-color);">Rp 3.500.000</span>
                        <a href="{{ route('customer.design') }}" class="btn btn-orange btn-sm text-decoration-none">Custom</a>
                    </div>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="col-md-4">
                <div class="product-card p-3">
                    <div class="product-img-holder mb-3">
                        <span class="fw-bold fs-5 text-dark">Set Meja Makan Wood</span>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Meja Makan Jati Scandinavian</h5>
                    <p class="text-muted small mb-2">Include 4 Kursi + Finishing Melamin</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="price-text fw-bold" style="color: var(--primary-color);">Rp 4.200.000</span>
                        <a href="{{ route('customer.design') }}" class="btn btn-orange btn-sm text-decoration-none">Custom</a>
                    </div>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="col-md-4">
                <div class="product-card p-3">
                    <div class="product-img-holder mb-3">
                        <span class="fw-bold fs-5 text-dark">Lemari Sliding Glass</span>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">Lemari Pakaian 2 Pintu</h5>
                    <p class="text-muted small mb-2">Full Cermin + LED Ambient Warm</p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="price-text fw-bold" style="color: var(--primary-color);">Rp 2.800.000</span>
                        <a href="{{ route('customer.design') }}" class="btn btn-orange btn-sm text-decoration-none">Custom</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. BANNER CTA BAWAH (WARNA KREM KAYU) -->
    <section class="rounded-4 p-5 text-center mb-5 shadow-lg position-relative overflow-hidden" style="background-color: var(--wood-bg); border: 2px solid var(--wood-border);">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-3" style="color: var(--primary-color);">Punya Konsep Furniture Sendiri?</h2>
                <p class="fs-5 mb-4" style="color: var(--text-dark);">Rancang bentuk, ukuran, dan bahan furniture impianmu secara interaktif di studio kustom kami!</p>
                <a href="{{ route('customer.design') }}" class="btn btn-orange btn-lg px-5 text-decoration-none fw-bold rounded-3 shadow-lg">
                    Mulai Custom Sekarang 🚀
                </a>
            </div>
        </div>
    </section>

</div>
@endsection