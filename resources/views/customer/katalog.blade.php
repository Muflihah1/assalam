@extends('layouts.customer')

@section('content')
<style>
    /* Theme Palette Variables (Tema Putih Bersih & Cokelat Mebel) */
    :root {
        --primary-color: #5d4037;     /* Cokelat Mebel */
        --secondary-color: #8d6e63;   /* Cokelat Sedang */
        --accent-orange: #d77a61;     /* Terracotta / Oranye Hangat */
        
        --light-bg: #ffffff;          /* Putih Bersih Total */
        --light-card: #edd6bd;        /* Kartu Kotak Berwarna Kayu/Krem yang Jauh Lebih Tegas & Kontras */
        --light-border: #bfa084;      /* Garis Batas Lebih Gelap dan Tegas */
        --text-dark: #2d3748;         /* Teks Gelap Nyaman Dibaca */
        --text-muted: #4a5568;        /* Teks Redup Lebih Tebal & Jelas */
        --hover-bg: #e4c4a7;        /* Warna Saat Disorot Lebih Gelap */
    }

    body {
        background-color: var(--light-bg) !important;
        color: var(--text-dark);
    }

    /* Panah Carousel Baris Atas (Custom Styled) */
    .hero-carousel .carousel-control-prev,
    .hero-carousel .carousel-control-next {
        width: 48px;
        height: 48px;
        background-color: var(--primary-color);
        border: 1px solid var(--primary-color);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
        transition: all 0.2s ease;
        z-index: 5;
    }

    .hero-carousel .carousel-control-prev { left: -10px; }
    .hero-carousel .carousel-control-next { right: -10px; }

    .hero-carousel .carousel-control-prev:hover,
    .hero-carousel .carousel-control-next:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        box-shadow: 0 4px 10px rgba(93, 64, 55, 0.3);
    }

    /* Mengubah warna ikon panah menjadi putih */
    .hero-carousel .carousel-control-prev-icon,
    .hero-carousel .carousel-control-next-icon {
        filter: invert(1) grayscale(100%) brightness(200%);
        width: 20px;
        height: 20px;
    }

    /* Product Cards Styling (Kotak dengan Warna Kayu / Krem yang Jauh Lebih Pekat) */
    .product-card {
        border: 2px solid var(--light-border);
        border-radius: 20px;
        background: var(--light-card);
        padding: 24px;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.12);
    }

    .product-card:hover {
        background-color: var(--hover-bg);
        border-color: var(--primary-color);
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(93, 64, 55, 0.2);
    }

    .product-img-holder {
        border: 1.5px solid var(--light-border);
        border-radius: 14px;
        height: 220px;
        background-color: #fdfaf6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-dark);
        overflow: hidden;
    }

    .product-img-holder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 14px;
    }

    /* Buttons Style */
    .btn-orange {
        background: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 10px 0;
        width: 100%;
        transition: all 0.2s;
    }

    .btn-orange:hover {
        background: var(--secondary-color);
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(93, 64, 55, 0.2);
    }

    .btn-outline-dark-theme {
        border: 2px solid var(--light-border);
        background: var(--light-card);
        color: var(--text-dark);
        font-weight: 600;
        border-radius: 10px;
        font-size: 13px;
        padding: 8px 0;
        width: 100%;
        transition: 0.2s;
    }

    .btn-outline-dark-theme:hover {
        background: var(--hover-bg);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .price-text {
        color: var(--primary-color);
        font-weight: 800;
    }

    /* Modal Clean Theme */
    .modal-content-dark {
        background-color: var(--light-card);
        border: 2px solid var(--light-border);
        border-radius: 20px;
        color: var(--text-dark);
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- ================= BARIS ATAS: CAROUSEL HERO MELEBAR ================= -->
    <div id="heroCarousel" class="carousel slide hero-carousel mb-5" data-bs-ride="false">
        <div class="carousel-inner px-2">

            <!-- SLIDE ATAS 1 -->
            <div class="carousel-item active">
                <div class="product-card">
                    <div class="product-img-holder mb-3">
                        <span class="fw-bold fs-4">Gambar Produk Utama 1</span>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Sofa Modern Luxury Edition</h3>
                    <p class="text-muted mb-3">Sofa sudut ruang tamu dengan rangka kayu jati solid & kain beludru impor super lembut.</p>
                    <h4 class="price-text mb-4">Rp 4.800.000</h4>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <a href="{{ route('customer.design') }}" class="btn btn-orange text-decoration-none py-2 fs-6">
                                Custom Sekarang 🎨
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SLIDE ATAS 2 -->
            <div class="carousel-item">
                <div class="product-card">
                    <div class="product-img-holder mb-3">
                        <span class="fw-bold fs-4">Gambar Produk Utama 2</span>
                    </div>
                    <h3 class="fw-bold mb-2 text-dark">Set Meja Makan Jati Scandinavian</h3>
                    <p class="text-muted mb-3">Paket 1 meja makan minimalis kayu jati tua + 6 kursi dudukan empuk ergonomis.</p>
                    <h4 class="price-text mb-4">Rp 5.200.000</h4>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <a href="{{ route('customer.design') }}" class="btn btn-orange text-decoration-none py-2 fs-6">
                                Custom Sekarang 🎨
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- PANAH NAVIGASI BARIS ATAS -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <!-- TITLE SEKSI BAWAH -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-1">Semua Koleksi Produk</h3>
        <p class="text-muted">Pilih furniture favoritmu dan atur spesifikasinya sesuai selera</p>
    </div>

    <!-- ================= BARIS BAWAH: GRID PRODUK DINAMIS ================= -->
    <div class="row g-4">
        @forelse($katalogs as $item)
            <div class="col-md-4">
                <div class="product-card">
                    <div class="product-img-holder mb-3">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                        @else
                            <span class="fw-bold fs-5 text-dark">{{ $item->nama }}</span>
                        @endif
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-2">{{ $item->nama }}</h5>
                    <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 60) }}</p>
                    <h5 class="price-text mb-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</h5>

                    <div class="row g-2 mt-auto">
                        <div class="col-6">
                            <button class="btn btn-outline-dark-theme" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}">Detail</button>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('customer.design') }}" class="btn btn-orange d-block text-center text-decoration-none">Custom</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= MODAL DETAIL PRODUK DINAMIS PER ITEM ================= -->
            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-dark p-4">
                        <div class="modal-body text-center p-0">
                            <div class="product-img-holder mb-3" style="height: 200px;">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}">
                                @else
                                    <span class="fw-bold fs-5 text-dark">Preview Tidak Tersedia</span>
                                @endif
                            </div>
                            <h4 class="fw-bold text-dark mb-2">{{ $item->nama }}</h4>
                            <p class="text-muted small mb-3">{{ $item->deskripsi ?? 'Dibuat menggunakan kayu pilihan berkualitas tinggi, pengerjaan rapi, dan tahan lama untuk interior rumah Anda.' }}</p>
                            <h4 class="price-text mb-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</h4>
                            
                            <a href="{{ route('customer.design') }}" class="btn btn-orange py-2 rounded-3 w-100 fw-bold d-block text-decoration-none">
                                Lanjut Custom Produk Ini 🚀
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="product-card py-5">
                    <p class="text-muted mb-0">Belum ada produk katalog yang ditambahkan oleh admin.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection