@extends('layouts.customer')

@section('content')
<style>
    /* 1. HERO BANNER - ELEGANT WARM GRADIENT */
    .hero-section {
        background: linear-gradient(135deg, #5d4037 0%, #3e2723 100%);
        border: 1px solid var(--light-border);
        border-radius: 24px;
        color: #ffffff;
        padding: 50px 40px;
        position: relative;
        box-shadow: 0 12px 35px rgba(93, 64, 55, 0.15);
    }

    .hero-title {
        font-size: 2.75rem;
        font-weight: 800;
        line-height: 1.2;
    }

    .badge-dark-custom {
        background: rgba(215, 122, 97, 0.2);
        border: 1px solid var(--accent-orange);
        color: #ffd8cc;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 30px;
    }

    /* PRODUCT CARDS */
    .product-card {
        border: 1.5px solid var(--wood-border);
        border-radius: 20px;
        background: var(--light-card);
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.06);
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .product-card:hover {
        transform: translateY(-6px);
        border-color: var(--primary-color);
        box-shadow: 0 15px 30px rgba(93, 64, 55, 0.15);
    }

    .product-img-holder {
        border-radius: 16px;
        height: 210px;
        background: #fdfaf6;
        border: 1.5px solid var(--light-border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-main);
        overflow: hidden;
        position: relative;
    }

    .product-img-holder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* BUTTON STYLES */
    .btn-orange {
        background: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        padding: 9px 16px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .btn-orange:hover {
        background: var(--secondary-color);
        color: #ffffff;
        box-shadow: 0 5px 15px rgba(93, 64, 55, 0.2);
    }

    .btn-outline-dark-theme {
        border: 1.5px solid var(--wood-border);
        background: #ffffff;
        color: var(--text-main);
        font-weight: 700;
        border-radius: 12px;
        padding: 9px 16px;
        font-size: 0.875rem;
        transition: 0.2s;
    }

    .btn-outline-dark-theme:hover {
        background: var(--wood-bg);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 30px 20px;
        }
        .hero-title {
            font-size: 1.85rem;
        }
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- 1. HERO BANNER -->
    <section class="hero-section mb-5">
        <div class="row align-items-center">
            <div class="col-lg-10 mb-2">
                <span class="badge badge-dark-custom mb-3">✨ Toko & Custom Mebel Kayu Solid Terpercaya</span>
                <h1 class="hero-title mb-3">Wujudkan Furniture Impian Presisi Sesuai Ruangan Anda</h1>
                <p class="text-white-50 fs-5 mb-4">Jelajahi koleksi mebel siap beli atau rancang mebel impian Anda dengan ukuran presisi, jenis kayu solid perhutani, dan pilihan warna tone eksklusif.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('customer.katalog') }}" class="btn btn-outline-dark-theme px-4 py-3 rounded-3" style="background: rgba(255,255,255,0.12); color: white; border-color: rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-basket-shopping me-1"></i> Jelajahi Katalog Produk
                    </a>
                    <a href="{{ route('customer.design') }}" class="btn btn-orange px-4 py-3 rounded-3 shadow-lg" style="background-color: var(--accent-orange);">
                        <i class="fa-solid fa-pen-ruler me-1"></i> Buka Studio Custom 🎨
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. PRODUK UNGGULAN DINAMIS -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Koleksi Mebel Terfavorit</h3>
                <p class="text-muted small mb-0">Pilihan model populer yang siap dibeli langsung atau dikustomisasi.</p>
            </div>
            <a href="{{ route('customer.katalog') }}" class="btn btn-outline-dark-theme text-decoration-none">
                Lihat Semua Katalog →
            </a>
        </div>

        <div class="row g-4">
            @forelse($produks as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="product-card p-3">
                        <div class="product-img-holder mb-3">
                            @if($item->foto)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item->foto) }}" alt="{{ $item->nama }}">
                            @else
                                <div class="text-center p-3 text-muted">
                                    <i class="fa-solid fa-couch fa-3x mb-1" style="color: var(--primary-color);"></i>
                                    <span class="d-block fw-bold small text-dark">{{ $item->nama }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">{{ $item->nama }}</h5>
                            <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 65) }}</p>
                            <h5 class="fw-extrabold mb-3" style="color: var(--primary-color);">Rp {{ number_format($item->harga, 0, ',', '.') }}</h5>
                        </div>
                        
                        <div class="d-flex gap-2 pt-2 border-top" style="border-color: var(--light-border) !important;">
                            <form action="{{ route('customer.cart.add', $item->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-orange w-100 fw-bold">
                                    <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang
                                </button>
                            </form>
                            <a href="{{ route('customer.design') }}" class="btn btn-outline-dark-theme text-decoration-none" title="Kustomisasi Produk">
                                <i class="fa-solid fa-pen-ruler"></i> Custom
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-4">
                    <div class="product-card p-5">
                        <p class="text-muted mb-0">Belum ada produk yang ditampilkan.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- 3. BANNER CALL TO ACTION BAWAH -->
    <section class="rounded-4 p-4 p-md-5 text-center mb-5 shadow-sm position-relative overflow-hidden" style="background-color: var(--wood-bg); border: 2px solid var(--wood-border);">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-3" style="color: var(--primary-color);">Punya Sketsa atau Konsep Furniture Sendiri?</h2>
                <p class="fs-6 mb-4" style="color: var(--text-main);">Rancang bentuk, ukuran, dan bahan furniture impianmu secara interaktif di Studio Custom Assalam Mebel!</p>
                <a href="{{ route('customer.design') }}" class="btn btn-orange btn-lg px-5 py-3 text-decoration-none fw-bold rounded-3 shadow-lg">
                    Buka Studio Custom Sekarang 🚀
                </a>
            </div>
        </div>
    </section>

</div>
@endsection