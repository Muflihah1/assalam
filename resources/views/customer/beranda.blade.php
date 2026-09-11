@extends('layouts.customer')

@section('content')
<div class="container-xl">

    <!-- 1. HERO BANNER SLIDER (SWIPER.JS) -->
    <section class="hero-slider-section mb-4 mb-lg-5">
        <div class="swiper heroSwiper rounded-4 overflow-hidden shadow-sm" style="border: 1px solid var(--border-color);">
            <div class="swiper-wrapper">
                
                <!-- Slide 1: Jati Solid Jepara -->
                <div class="swiper-slide">
                    <div class="hero-slide-content p-4 p-md-5 d-flex align-items-center" style="min-height: 420px; background: linear-gradient(135deg, #2D1A0D 0%, #4A2E1B 60%, #3B2314 100%); color: #ffffff;">
                        <div class="row align-items-center w-100 g-4">
                            <div class="col-lg-7 text-start">
                                <span class="badge px-3 py-2 rounded-pill mb-3" style="background: rgba(217, 119, 6, 0.25); border: 1px solid var(--accent-gold); color: #FDE68A; font-weight: 700; font-size: 0.82rem;">
                                    <i class="fa-solid fa-award text-warning me-1"></i> Pengrajin Mebel Solid Asli
                                </span>
                                <h1 class="display-5 fw-extrabold mb-3" style="line-height: 1.15; letter-spacing: -0.02em;">
                                    Keindahan Kayu Jati Solid untuk Hunian Impian Anda
                                </h1>
                                <p class="lead text-white-50 mb-4 fs-6 pe-lg-4">
                                    Koleksi mebel eksklusif dibuat dari kayu jati perhutani grade A berstandar oven kering. Kokoh, tahan puluhan tahun, dengan ukiran bernilai seni tinggi.
                                </p>
                                <div class="d-flex flex-wrap gap-2 gap-md-3">
                                    <a href="{{ route('customer.katalog') }}" class="btn btn-store-primary px-4 py-3 rounded-3 shadow">
                                        <i class="fa-solid fa-basket-shopping me-1"></i> Jelajahi Katalog Produk
                                    </a>
                                    <a href="{{ route('customer.design') }}" class="btn btn-store-secondary px-4 py-3 rounded-3" style="background: rgba(255,255,255,0.12); color: #ffffff !important; border-color: rgba(255,255,255,0.25);">
                                        <i class="fa-solid fa-pen-ruler me-1"></i> Buka Studio Custom
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-5 d-none d-lg-block text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="rounded-4 overflow-hidden shadow-lg border border-white border-opacity-25" style="max-height: 320px; width: 100%;">
                                        <img src="{{ asset('produk/01_kursi-sofa-ukir-set.jpg') }}" alt="Kursi Sofa Ukir Set Jati" class="img-fluid" style="height: 320px; width: 100%; object-fit: cover;">
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 m-3 p-2 px-3 rounded-3 text-dark bg-white shadow-sm fw-bold small">
                                        <i class="fa-solid fa-certificate text-warning me-1"></i> 100% Kayu Jati Solid
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2: Custom Mebel Sesuai Ukuran -->
                <div class="swiper-slide">
                    <div class="hero-slide-content p-4 p-md-5 d-flex align-items-center" style="min-height: 420px; background: linear-gradient(135deg, #1C1917 0%, #3B2314 60%, #5C3A21 100%); color: #ffffff;">
                        <div class="row align-items-center w-100 g-4">
                            <div class="col-lg-8 text-start">
                                <span class="badge px-3 py-2 rounded-pill mb-3" style="background: rgba(200, 109, 59, 0.25); border: 1px solid var(--accent-orange); color: #FFD8CC; font-weight: 700; font-size: 0.82rem;">
                                    <i class="fa-solid fa-compass-drafting text-warning me-1"></i> Layanan Custom Presisi
                                </span>
                                <h1 class="display-5 fw-extrabold mb-3" style="line-height: 1.15; letter-spacing: -0.02em;">
                                    Rancang Furniture Sesuai Denah & Ukuran Ruangan
                                </h1>
                                <p class="lead text-white-50 mb-4 fs-6 pe-lg-4">
                                    Ruangan sempit atau punya konsep arsitektur sendiri? Tentukan panjang, lebar, tinggi, jenis kayu, dan warna finishing di Studio Desain kami dengan estimasi harga real-time.
                                </p>
                                <div class="d-flex flex-wrap gap-2 gap-md-3">
                                    <a href="{{ route('customer.design') }}" class="btn btn-store-primary px-4 py-3 rounded-3 shadow" style="background-color: var(--accent-orange);">
                                        <i class="fa-solid fa-pen-ruler me-1"></i> Mulai Desain Sekarang
                                    </a>
                                    <a href="{{ route('customer.katalog') }}" class="btn btn-store-secondary px-4 py-3 rounded-3" style="background: rgba(255,255,255,0.12); color: #ffffff !important; border-color: rgba(255,255,255,0.25);">
                                        Lihat Contoh Model
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 d-none d-lg-block text-center">
                                <div class="rounded-4 overflow-hidden shadow-lg border border-white border-opacity-25" style="max-height: 320px; width: 100%;">
                                    <img src="{{ asset('produk/07_pendopo-gazebo-jati.jpg') }}" alt="Gazebo Jati Solid" class="img-fluid" style="height: 320px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Pagination & Navigation Controls -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next d-none d-md-flex text-white"></div>
            <div class="swiper-button-prev d-none d-md-flex text-white"></div>
        </div>
    </section>

    <!-- 2. TRUST BADGES (4 PILAR KEUNGGULAN TOKO) -->
    <section class="mb-5">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="trust-badge-card">
                    <div class="trust-icon-box">
                        <i class="fa-solid fa-tree"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-6 text-dark">100% Kayu Solid</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">Jati & Mahoni Oven Legal Perhutani</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-badge-card">
                    <div class="trust-icon-box">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-6 text-dark">Sentra Karduluk</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">Pahat Tangan Pengrajin Karduluk</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-badge-card">
                    <div class="trust-icon-box">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-6 text-dark">DP 50% Aman</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">Pelunasan Setelah Barang Jadi</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-badge-card">
                    <div class="trust-icon-box">
                        <i class="fa-solid fa-truck-ramp-box"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 fs-6 text-dark">Armada Se-Madura</h6>
                        <small class="text-muted d-block" style="font-size: 0.78rem;">Antar Aman & Pasang Se-Pulau Madura</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. QUICK CATEGORY PILLS (KATEGORI POPULER) -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <h3 class="section-title">Kategori Mebel</h3>
                <p class="section-subtitle">Temukan produk mebel asli sesuai kebutuhan ruangan Anda</p>
            </div>
            <a href="{{ route('customer.katalog') }}" class="small fw-bold text-decoration-none d-none d-md-inline-block">
                Lihat Semua Kategori <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-2 g-md-3">
            <div class="col-4 col-md-2">
                <a href="{{ route('customer.katalog', ['keyword' => 'Kursi']) }}" class="category-pill-card">
                    <div class="category-pill-icon">
                        <i class="fa-solid fa-chair"></i>
                    </div>
                    <span class="category-pill-label">Kursi & Sofa</span>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('customer.katalog', ['keyword' => 'Meja']) }}" class="category-pill-card">
                    <div class="category-pill-icon">
                        <i class="fa-solid fa-table"></i>
                    </div>
                    <span class="category-pill-label">Meja</span>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('customer.katalog', ['keyword' => 'Lemari']) }}" class="category-pill-card">
                    <div class="category-pill-icon">
                        <i class="fa-solid fa-door-closed"></i>
                    </div>
                    <span class="category-pill-label">Lemari</span>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('customer.katalog', ['keyword' => 'Pintu']) }}" class="category-pill-card">
                    <div class="category-pill-icon">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                    <span class="category-pill-label">Pintu & Ukir</span>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('customer.katalog', ['keyword' => 'Podium']) }}" class="category-pill-card">
                    <div class="category-pill-icon">
                        <i class="fa-solid fa-landmark"></i>
                    </div>
                    <span class="category-pill-label">Podium Mimbar</span>
                </a>
            </div>
            <div class="col-4 col-md-2">
                <a href="{{ route('customer.katalog', ['keyword' => 'Pendopo']) }}" class="category-pill-card">
                    <div class="category-pill-icon">
                        <i class="fa-solid fa-house"></i>
                    </div>
                    <span class="category-pill-label">Gazebo / Pendopo</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 4. KOLEKSI MEBEL TERFAVORIT (E-COMMERCE PRODUCT GRID) -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <h3 class="section-title">Koleksi Terpopuler</h3>
                <p class="section-subtitle">Pilihan mebel paling diminati yang siap dipesan atau dikustomisasi</p>
            </div>
            <a href="{{ route('customer.katalog') }}" class="btn btn-sm btn-store-secondary rounded-3">
                Lihat Semua Katalog <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3 g-md-4">
            @forelse($produks as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card-modern">
                        
                        <!-- Thumbnail Image with Badge -->
                        <div class="product-card-thumb">
                            <a href="{{ route('customer.produk.detail', $item->id) }}">
                                @if($item->foto_url)
                                    <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" loading="lazy">
                                @else
                                    <div class="product-thumb-placeholder">
                                        <i class="fa-solid fa-couch fa-2x mb-2" style="color: var(--primary-light);"></i>
                                        <span class="small text-muted fw-bold">Assalam Mebel</span>
                                    </div>
                                @endif
                            </a>

                            <!-- Overlay Badges -->
                            <div class="product-badge-overlay">
                                <span class="badge-tag-solid">Kayu Solid</span>
                                @if($item->rating_count > 0)
                                    <span class="badge-tag-rating">
                                        <i class="fa-solid fa-star text-warning"></i> {{ number_format($item->rating_average, 1) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body Details -->
                        <div class="product-card-body">
                            <span class="product-card-category">{{ $item->kategori }}</span>
                            <h6 class="product-card-title">
                                <a href="{{ route('customer.produk.detail', $item->id) }}" title="{{ $item->nama }}">
                                    {{ $item->nama }}
                                </a>
                            </h6>

                            @if($item->rating_count > 0)
                                <div class="d-flex align-items-center gap-1 mb-2">
                                    <small class="text-muted" style="font-size: 0.72rem;">
                                        Terjual {{ $item->sold_count ?? 0 }} • ({{ $item->rating_count }} ulasan)
                                    </small>
                                </div>
                            @else
                                <div class="mb-2">
                                    <small class="text-muted" style="font-size: 0.72rem;">Model Mebel Pilihan</small>
                                </div>
                            @endif

                            <div class="product-card-price">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="product-card-footer">
                            <button type="button" 
                                    class="btn btn-store-primary w-100 btn-sm" 
                                    onclick="window.addToCart({{ $item->id }}, 1, this)"
                                    title="Tambah ke Keranjang">
                                <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang
                            </button>
                            <a href="{{ route('customer.design', ['product_id' => $item->id]) }}" 
                               class="btn-store-icon" 
                               title="Kustomisasi Ukuran di Studio">
                                <i class="fa-solid fa-pen-ruler"></i>
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 border">
                        <i class="fa-solid fa-couch fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold">Belum Ada Produk Tersedia</h5>
                        <p class="text-muted small mb-0">Silakan kembali lagi nanti atau hubungi kami melalui WhatsApp.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 5. STUDIO CUSTOM FURNITURE SHOWCASE BANNER -->
    <section class="mb-5">
        <div class="rounded-4 p-4 p-md-5 position-relative overflow-hidden shadow-sm" style="background: linear-gradient(135deg, #F5EBE1 0%, #E8DFD5 100%); border: 1.5px solid #D5C2B1;">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <span class="badge px-3 py-2 rounded-pill mb-3 fw-bold" style="background-color: var(--primary-color); color: #ffffff;">
                        <i class="fa-solid fa-pen-ruler me-1"></i> Studio Custom Interaktif
                    </span>
                    <h2 class="fw-extrabold mb-3" style="color: var(--primary-color); letter-spacing: -0.01em;">
                        Punya Konsep Sendiri? Kami Wujudkan Presisi Sesuai Keinginan
                    </h2>
                    <p class="fs-6 mb-4" style="color: var(--text-secondary); max-width: 640px;">
                        Gunakan Studio Custom untuk menentukan dimensi panjang, lebar, dan tinggi secara milimeter. Pilih jenis kayu jati atau mahoni, pilih warna finishing eksklusif, dan dapatkan perhitungan estimasi biaya transparan secara otomatis.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('customer.design') }}" class="btn btn-store-primary px-4 py-3 rounded-3 shadow">
                            <i class="fa-solid fa-compass-drafting me-1"></i> Rancang Furniture Sekarang
                        </a>
                        <a href="https://wa.me/6281234567890" target="_blank" class="btn btn-store-secondary px-4 py-3 rounded-3">
                            <i class="fa-brands fa-whatsapp text-success me-1"></i> Konsultasi via WhatsApp
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block text-center">
                    <div class="p-3 bg-white rounded-4 shadow-sm border d-inline-block text-start">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center text-white" style="background-color: var(--primary-color); width: 40px; height: 40px;">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div>
                                <strong class="d-block small text-dark">Uang Muka 50%</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Pelunasan saat mebel siap dikirim</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center text-white" style="background-color: var(--accent-gold); width: 40px; height: 40px;">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <div>
                                <strong class="d-block small text-dark">Pantau Progres Realtime</strong>
                                <small class="text-muted" style="font-size: 0.72rem;">Notifikasi foto pengerjaan via WhatsApp</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. TESTIMONI PELANGGAN (DARI DATABASE ASLI) -->
    @if(isset($reviews) && $reviews->isNotEmpty())
        <section class="mb-5">
            <div class="text-center mb-4">
                <h3 class="section-title">Ulasan Nyata Pelanggan</h3>
                <p class="section-subtitle">Pengalaman pelanggan yang telah memesan produk mebel kayu solid kami</p>
            </div>

            <div class="row g-3 g-md-4">
                @foreach($reviews as $rev)
                    <div class="col-md-4">
                        <div class="p-4 bg-white rounded-4 border h-100 shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <div class="text-warning mb-2 small">
                                    @for($s = 1; $s <= 5; $s++)
                                        <i class="fa-solid fa-star {{ $s <= $rev->rating ? '' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                    @if($rev->produk)
                                        <span class="badge bg-light text-dark border ms-2 small" style="font-size: 0.65rem;">
                                            {{ Str::limit($rev->produk->nama, 25) }}
                                        </span>
                                    @endif
                                </div>
                                @if($rev->title)
                                    <h6 class="fw-bold text-dark mb-1 small">{{ $rev->title }}</h6>
                                @endif
                                <p class="small text-secondary mb-3" style="line-height: 1.6;">
                                    "{{ $rev->comment }}"
                                </p>
                            </div>
                            <div class="d-flex align-items-center gap-2 pt-2 border-top">
                                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-muted fw-bold" style="width: 36px; height: 36px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($rev->author_name ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <strong class="d-block small text-dark">{{ $rev->author_name }}</strong>
                                    <small class="text-muted" style="font-size: 0.72rem;">Pembeli Terverifikasi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.heroSwiper', {
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        }
    });
</script>
@endpush