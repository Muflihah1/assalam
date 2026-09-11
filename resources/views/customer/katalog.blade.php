@extends('layouts.customer')

@section('content')
<div class="container-xl">

    <!-- 1. BREADCRUMBS & CATALOG HEADER -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.beranda') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Katalog Produk</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="section-title mb-1">Katalog Produk Mebel Solid</h2>
            <p class="section-subtitle">
                @if(!empty($keyword))
                    Menampilkan hasil pencarian untuk: <strong class="text-dark">"{{ $keyword }}"</strong> ({{ $katalogs->count() }} produk)
                @else
                    Koleksi furniture kayu jati & mahoni solid sentra Karduluk Madura siap beli atau custom ({{ $katalogs->count() }} produk)
                @endif
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('customer.design') }}" class="btn btn-store-primary rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-pen-ruler"></i>
                <span>Studio Custom Furniture</span>
            </a>
        </div>
    </div>

    <!-- 2. QUICK CATEGORY PILLS BAR -->
    @php
        $activeKw = strtolower(trim($keyword ?? ''));
        $quickCategories = [
            '' => ['label' => 'Semua Produk', 'icon' => 'fa-cubes'],
            'Kursi' => ['label' => 'Kursi & Sofa', 'icon' => 'fa-chair'],
            'Meja' => ['label' => 'Meja', 'icon' => 'fa-table'],
            'Lemari' => ['label' => 'Lemari Pakaian', 'icon' => 'fa-door-closed'],
            'Pintu' => ['label' => 'Pintu & Gebyok', 'icon' => 'fa-door-open'],
            'Ukir' => ['label' => 'Ukiran Klasik Karduluk', 'icon' => 'fa-gem'],
            'Podium' => ['label' => 'Mimbar Podium', 'icon' => 'fa-landmark'],
            'Pendopo' => ['label' => 'Gazebo / Pendopo', 'icon' => 'fa-house'],
        ];
    @endphp

    <div class="d-flex gap-2 overflow-auto pb-3 mb-4" style="scrollbar-width: none;">
        @foreach($quickCategories as $kw => $meta)
            @php
                $isActive = ($kw === '' && empty($activeKw)) || ($kw !== '' && str_contains($activeKw, strtolower($kw)));
            @endphp
            <a href="{{ $kw === '' ? route('customer.katalog') : route('customer.katalog', ['keyword' => $kw]) }}" 
               class="btn btn-sm rounded-pill text-nowrap d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold transition-all {{ $isActive ? 'btn-dark' : 'btn-white border bg-white text-secondary' }}"
               style="{{ $isActive ? 'background-color: var(--primary-color); border-color: var(--primary-color);' : '' }}">
                <i class="fa-solid {{ $meta['icon'] }} {{ $isActive ? 'text-warning' : 'text-muted' }}"></i>
                <span>{{ $meta['label'] }}</span>
            </a>
        @endforeach
    </div>

    <!-- 3. SEARCH & SORTING TOOLBAR -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white" style="border: 1px solid var(--border-color) !important;">
        <form action="{{ route('customer.katalog') }}" method="GET" class="row g-2 align-items-center">
            
            <!-- Search Keyword Input -->
            <div class="col-md-7 col-lg-8">
                <div class="position-relative">
                    <input type="text" 
                           name="keyword" 
                           value="{{ $keyword }}" 
                           class="form-control rounded-pill ps-4 pe-5 py-2" 
                           placeholder="Cari nama mebel, model, atau kata kunci (contoh: Kursi Tamu, Meja, Lemari)..."
                           style="border: 1.5px solid var(--border-color);">
                    <button type="submit" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 rounded-circle text-muted">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <!-- Sorting Select Dropdown -->
            <div class="col-md-5 col-lg-4 d-flex gap-2 align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted small py-2">
                        <i class="fa-solid fa-arrow-down-wide-short me-1"></i> Urutkan:
                    </span>
                    <select name="sort" class="form-select border-start-0 py-2 small" onchange="this.form.submit()">
                        <option value="latest" {{ ($sort ?? '') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc" {{ ($sort ?? '') == 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                        <option value="price_desc" {{ ($sort ?? '') == 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                        <option value="name_asc" {{ ($sort ?? '') == 'name_asc' ? 'selected' : '' }}>Nama Produk: A - Z</option>
                    </select>
                </div>

                @if(!empty($keyword))
                    <a href="{{ route('customer.katalog') }}" class="btn btn-outline-secondary rounded-pill px-3" title="Reset Filter">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- 4. RESPONSIVE PRODUCT GRID -->
    @if($katalogs->count() > 0)
        <div class="row g-3 g-md-4 mb-5">
            @foreach($katalogs as $item)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card-modern">
                        
                        <!-- Thumbnail Frame with Badges -->
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

                        <!-- Card Body -->
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
                                    <small class="text-muted" style="font-size: 0.72rem;">Model Populer Karduluk</small>
                                </div>
                            @endif

                            <div class="product-card-price">
                                Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Card Action Buttons -->
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
            @endforeach
        </div>
    @else
        <!-- EMPTY STATE (Pencarian Tidak Ditemukan) -->
        <div class="card border-0 shadow-sm rounded-4 text-center p-5 mb-5 bg-white" style="border: 1px solid var(--border-color) !important;">
            <div class="py-4">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-muted mb-3" style="width: 72px; height: 72px;">
                    <i class="fa-solid fa-magnifying-glass fa-2x"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Produk Tidak Ditemukan</h4>
                <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
                    Kami tidak menemukan mebel yang cocok dengan kata kunci <strong>"{{ $keyword }}"</strong>. Coba periksa ejaan atau gunakan kata kunci lain seperti kursi, meja, atau lemari.
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('customer.katalog') }}" class="btn btn-store-primary px-4 py-2 rounded-3">
                        <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Produk
                    </a>
                    <a href="{{ route('customer.design') }}" class="btn btn-store-secondary px-4 py-2 rounded-3">
                        <i class="fa-solid fa-pen-ruler me-1"></i> Buat Pesanan Custom
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection