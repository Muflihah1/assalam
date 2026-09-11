@extends('layouts.customer')

@section('content')
<div class="container-xl">

    <!-- BREADCRUMBS -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.beranda') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.katalog') }}" class="text-decoration-none text-muted">Katalog</a></li>
            <li class="breadcrumb-item active fw-bold text-dark text-truncate" aria-current="page" style="max-width: 300px;">{{ $produk->nama }}</li>
        </ol>
    </nav>

    <!-- 1. MAIN PRODUCT DETAIL (2-COLUMN E-COMMERCE LAYOUT) -->
    <div class="row g-4 mb-5">
        
        <!-- Left: Product Image Gallery -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-2 position-sticky" style="top: 90px; border: 1px solid var(--border-color) !important;">
                <div class="position-relative rounded-4 overflow-hidden" style="background-color: #FAF5F0; padding-top: 85%;">
                    @if($produk->foto_url)
                        <img src="{{ $produk->foto_url }}" 
                             alt="{{ $produk->nama }}" 
                             class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                             id="mainProductImage"
                             loading="lazy">
                    @else
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted">
                            <i class="fa-solid fa-couch fa-3x mb-2" style="color: var(--primary-light);"></i>
                            <span class="small fw-bold">Assalam Mebel Karduluk</span>
                        </div>
                    @endif

                    <!-- Badges on Image -->
                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1">
                        <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(39, 22, 11, 0.85); backdrop-filter: blur(4px); color: #fff;">
                            <i class="fa-solid fa-tree me-1 text-warning"></i> 100% Kayu Solid
                        </span>
                        @if($soldCount > 0)
                            <span class="badge px-3 py-2 rounded-pill fw-bold bg-white text-dark shadow-sm">
                                <i class="fa-solid fa-fire text-danger me-1"></i> Terjual {{ $soldCount }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-3 text-center">
                    <small class="text-muted"><i class="fa-solid fa-shield-halved text-success me-1"></i> Foto produk asli mebel karya pengrajin Assalam Mebel Karduluk Sumenep</small>
                </div>
            </div>
        </div>

        <!-- Right: Product Information & Purchase Actions -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100" style="border: 1px solid var(--border-color) !important;">
                
                <!-- Category / Material Tag -->
                <div class="mb-2">
                    <span class="badge px-3 py-2 rounded-pill text-uppercase fw-bold" style="background-color: var(--primary-subtle); color: var(--primary-color); font-size: 0.75rem; letter-spacing: 0.05em;">
                        {{ $produk->kategori }} • Kayu Jati Solid Sentra Karduluk (Madura)
                    </span>
                </div>

                <!-- Product Name -->
                <h1 class="fw-extrabold text-dark mb-2" style="font-size: 1.85rem; line-height: 1.3;">
                    {{ $produk->nama }}
                </h1>

                <!-- Ratings & Sold Count -->
                <div class="d-flex align-items-center gap-3 flex-wrap mb-4 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-1 text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fa-solid fa-star {{ $i <= round($ratingAverage) ? '' : 'text-black-50 opacity-25' }}"></i>
                        @endfor
                        <span class="fw-extrabold text-dark ms-1">{{ number_format($ratingAverage, 1) }}</span>
                    </div>
                    <span class="text-muted">|</span>
                    <a href="#ulasanSection" class="text-decoration-none fw-bold small" style="color: var(--primary-color);">
                        {{ $ratingCount }} Ulasan Pembeli
                    </a>
                    <span class="text-muted">|</span>
                    <span class="small text-muted"><i class="fa-solid fa-box-check text-success me-1"></i> {{ $soldCount }} unit terjual</span>
                </div>

                <!-- Price Box -->
                <div class="p-3 px-4 rounded-4 mb-4" style="background: linear-gradient(135deg, #FAF5F0 0%, #F5EBE1 100%); border: 1.5px solid var(--border-color);">
                    <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Harga Produk Jadi</small>
                    <div class="d-flex align-items-baseline gap-2">
                        <h2 class="fw-extrabold mb-0" style="color: var(--primary-color); font-size: 2rem;">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </h2>
                        <small class="text-muted">(Bisa bayar DP 50%)</small>
                    </div>
                </div>

                <!-- Short Description -->
                <div class="mb-4">
                    <h6 class="fw-bold text-dark mb-2">Deskripsi Singkat:</h6>
                    <p class="text-secondary" style="line-height: 1.7; font-size: 0.95rem;">
                        {{ $produk->deskripsi }}
                    </p>
                </div>

                <!-- Quantity & Add to Cart Controls -->
                <div class="mb-4 p-3 rounded-4 bg-light border">
                    <div class="row align-items-center g-3">
                        <div class="col-sm-4">
                            <label class="form-label small fw-bold text-dark mb-1">Jumlah Pesanan:</label>
                            <div class="input-group" style="max-width: 140px;">
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="decreaseQty()">-</button>
                                <input type="number" id="orderQuantity" name="quantity" value="1" min="1" max="99" class="form-control form-control-sm text-center fw-bold bg-white" readonly>
                                <button type="button" class="btn btn-outline-secondary btn-sm px-3" onclick="increaseQty()">+</button>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="d-flex gap-2">
                                <button type="button" 
                                        class="btn btn-store-primary flex-grow-1 py-2.5 rounded-3 fw-bold"
                                        onclick="submitAddToCart(this)">
                                    <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang Belanja
                                </button>
                                <a href="{{ route('customer.design', ['product_id' => $produk->id]) }}" 
                                   class="btn btn-store-secondary py-2.5 rounded-3 fw-bold" 
                                   title="Kustomisasi Ukuran & Warna">
                                    <i class="fa-solid fa-pen-ruler me-1"></i> Custom Ukuran & Warna
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Value Props Grid -->
                <div class="row g-2 pt-2 border-top small text-secondary">
                    <div class="col-sm-6 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-certificate text-warning"></i>
                        <span>Kayu Jati Solid Legalitas Perhutani</span>
                    </div>
                    <div class="col-sm-6 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-shield-halved text-success"></i>
                        <span>Sistem Bayar DP 50% & Pelunasan Aman</span>
                    </div>
                    <div class="col-sm-6 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-truck-fast text-primary"></i>
                        <span>Ekspedisi Truk Mebel Khusus Se-Madura Bergaransi</span>
                    </div>
                    <div class="col-sm-6 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-palette text-warning"></i>
                        <span>Finishing Halus Melamine / Natural Doff</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- 2. TABBED CONTENT: DESKRIPSI LENGKAP, SPESIFIKASI, ULASAN -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white" id="ulasanSection" style="border: 1px solid var(--border-color) !important;">
        
        <!-- Tab Navigation Header -->
        <div class="border-bottom px-4 pt-3" style="background-color: var(--bg-body);">
            <ul class="nav nav-tabs border-0 gap-2" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold border-0 py-3 px-4 rounded-top-3" id="desc-tab" data-bs-toggle="tab" data-bs-target="#descTabPane" type="button" role="tab">
                        <i class="fa-solid fa-align-left me-2"></i> Detail & Keunggulan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold border-0 py-3 px-4 rounded-top-3" id="spec-tab" data-bs-toggle="tab" data-bs-target="#specTabPane" type="button" role="tab">
                        <i class="fa-solid fa-ruler-combined me-2"></i> Spesifikasi Material
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold border-0 py-3 px-4 rounded-top-3" id="review-tab" data-bs-toggle="tab" data-bs-target="#reviewTabPane" type="button" role="tab">
                        <i class="fa-solid fa-comments me-2"></i> Ulasan Pembeli ({{ $ratingCount }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content p-4 p-md-5" id="productTabContent">
            
            <!-- Tab 1: Detail & Keunggulan -->
            <div class="tab-pane fade show active" id="descTabPane" role="tabpanel">
                <h5 class="fw-bold text-dark mb-3">Keunggulan & Kualitas Produk</h5>
                <p class="text-secondary mb-4" style="line-height: 1.8;">
                    {{ $produk->deskripsi }}
                </p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-light border">
                            <strong class="d-block small text-dark mb-1"><i class="fa-solid fa-fire text-warning me-1"></i> Oven Kering (Kiln Dry)</strong>
                            <small class="text-muted">Kadar air kayu terkontrol sehingga mebel tidak mudah melengkung, retak, atau susut.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-light border">
                            <strong class="d-block small text-dark mb-1"><i class="fa-solid fa-gem text-primary me-1"></i> Ukiran Seniman Karduluk (Madura)</strong>
                            <small class="text-muted">Dikerjakan tangan (handmade) oleh empu ukir Karduluk Sumenep dengan detail kedalaman relief yang hidup.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded-3 bg-light border">
                            <strong class="d-block small text-dark mb-1"><i class="fa-solid fa-spray-can text-success me-1"></i> Lapisan Top Coat</strong>
                            <small class="text-muted">Finishing polyurethane tahan gores, tahan tumpahan air, dan warna kayu tahan lama.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Spesifikasi Material -->
            <div class="tab-pane fade" id="specTabPane" role="tabpanel">
                <h5 class="fw-bold text-dark mb-3">Spesifikasi Teknis Mebel</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0 small">
                        <tbody>
                            <tr>
                                <th style="width: 250px;" class="bg-light">Bahan Utama</th>
                                <td>Kayu Jati Solid / Kayu Mahoni Perhutani Grade A Pilihan</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Sistem Konstruksi</th>
                                <td>Sambungan Purus & Pasak Tradisional Kayu Solid (Kuat & Kokoh)</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Pilihan Warna Finishing</th>
                                <td>Natural Wood, Walnut Brown, Salak Brown, Dark Mahogany, Emas Antik</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Perlakuan Anti Rayap</th>
                                <td>Obat anti-rayap sistem rendam (Vacuum Preservation System)</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Layanan Kustomisasi</th>
                                <td>Tersedia (Bisa request ukuran presisi ruangan, ornamen ukir, atau warna)</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Estimasi Waktu Pengerjaan</th>
                                <td>Ready Stock kirim 1-3 hari, atau Pre-Order Custom 10-18 hari kerja</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: Ulasan Pembeli -->
            <div class="tab-pane fade" id="reviewTabPane" role="tabpanel">
                
                <!-- Rating Summary Bar -->
                <div class="p-4 rounded-4 bg-light border mb-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-4 text-center border-end">
                            <h1 class="fw-extrabold mb-0" style="color: var(--primary-color); font-size: 3rem;">{{ number_format($ratingAverage, 1) }}</h1>
                            <div class="text-warning fs-5 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= round($ratingAverage) ? '' : 'text-muted opacity-25' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">{{ $ratingCount }} ulasan terverifikasi</small>
                        </div>
                        <div class="col-md-8 ps-md-4">
                            @foreach($ratingBreakdown as $star => $data)
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="small text-muted" style="width: 50px;">{{ $star }} Bintang</span>
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $data['percent'] }}%;" aria-valuenow="{{ $data['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="small text-muted" style="width: 32px; text-align: right;">{{ $data['count'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    
                    <!-- Form Kirim Ulasan -->
                    <div class="col-lg-5">
                        <div class="p-4 rounded-4 border bg-white h-100">
                            <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-pen-to-square me-2" style="color: var(--primary-color);"></i> Tulis Ulasan Produk</h6>

                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <div class="alert alert-warning border-0 rounded-3 small mb-0">
                                        <i class="fa-solid fa-user-shield me-1"></i> Akun administrator tidak dapat menulis ulasan produk.
                                    </div>
                                @else
                                    @if($myReview)
                                        <div class="alert alert-info border-0 rounded-3 small mb-3">
                                            <i class="fa-solid fa-circle-info me-1"></i> Anda telah mengirim ulasan untuk produk ini
                                            (<strong>Status: {{ $myReview->status }}</strong>).
                                        </div>
                                    @endif

                                    <form action="{{ route('customer.produk.review.store', $produk->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Rating Bintang <span class="text-danger">*</span></label>
                                            <div class="star-rating-input d-flex gap-2 fs-4 text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <div class="form-check form-check-inline m-0 p-0">
                                                        <input class="btn-check" type="radio" name="rating" id="rate{{ $i }}" value="{{ $i }}" {{ old('rating', 5) == $i ? 'checked' : '' }} required>
                                                        <label class="btn btn-outline-warning btn-sm px-2 py-1" for="rate{{ $i }}">
                                                            {{ $i }} <i class="fa-solid fa-star"></i>
                                                        </label>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Judul Ulasan</label>
                                            <input type="text" name="title" value="{{ old('title') }}" maxlength="150" class="form-control form-control-sm" placeholder="Contoh: Sangat puas, kayu jati tebal">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Komentar <span class="text-danger">*</span></label>
                                            <textarea name="comment" rows="4" class="form-control form-control-sm" placeholder="Ceritakan kepuasan Anda: kehalusan ukiran, kerapian sambungan, dll..." required>{{ old('comment') }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark mb-1">Foto Mebel (Opsional)</label>
                                            <input type="file" name="photo" accept="image/*" class="form-control form-control-sm">
                                        </div>

                                        <button type="submit" class="btn btn-store-primary w-100 py-2.5">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Ulasan
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="text-center py-4">
                                    <i class="fa-solid fa-lock fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small mb-3">Silakan masuk ke akun Anda untuk membagikan pengalaman berbelanja mebel ini.</p>
                                    <a href="{{ route('login') }}" class="btn btn-store-primary w-100 py-2">
                                        <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk Akun
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Daftar Ulasan Pelanggan -->
                    <div class="col-lg-7">
                        @forelse($filteredReviews as $review)
                            <div class="p-3 p-md-4 rounded-4 border bg-white mb-3 shadow-2xs">
                                <div class="d-flex gap-3">
                                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center fw-bold text-muted flex-shrink-0" style="width: 44px; height: 44px;">
                                        {{ strtoupper(substr($review->author_name ?? 'U', 0, 2)) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-1 mb-1">
                                            <div>
                                                <strong class="d-block small text-dark">{{ $review->author_name }}</strong>
                                                @if($review->is_verified_buyer)
                                                    <span class="badge rounded-pill bg-success-subtle text-success small" style="font-size: 0.65rem;">
                                                        <i class="fa-solid fa-circle-check me-1"></i> Pembeli Terverifikasi
                                                    </span>
                                                @endif
                                            </div>
                                            <small class="text-muted" style="font-size: 0.72rem;">{{ $review->created_at->translatedFormat('d M Y') }}</small>
                                        </div>

                                        <div class="text-warning small mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'text-muted opacity-25' }}"></i>
                                            @endfor
                                        </div>

                                        @if($review->title)
                                            <h6 class="fw-bold text-dark mb-1 small">{{ $review->title }}</h6>
                                        @endif

                                        <p class="text-secondary small mb-2" style="line-height: 1.6;">{{ $review->comment }}</p>

                                        @if($review->url_photo)
                                            <div class="mt-2">
                                                <img src="{{ $review->url_photo }}" alt="Foto ulasan" class="rounded-3 border" style="max-height: 120px; object-fit: cover;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 border rounded-4 bg-light">
                                <i class="fa-solid fa-comment-dots fa-3x text-muted mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Ulasan</h6>
                                <p class="text-muted small mb-0">Jadilah yang pertama memberikan ulasan untuk produk ini!</p>
                            </div>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- 3. REKOMENDASI PRODUK LAINNYA -->
    @if($relatedProducts->isNotEmpty())
        <div class="mb-5">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h4 class="section-title">Produk Terkait</h4>
                    <p class="section-subtitle">Koleksi mebel lainnya yang mungkin Anda sukai</p>
                </div>
                <a href="{{ route('customer.katalog') }}" class="btn btn-sm btn-store-secondary rounded-3">
                    Lihat Semua
                </a>
            </div>

            <div class="row g-3 g-md-4">
                @foreach($relatedProducts as $related)
                    <div class="col-6 col-md-3">
                        <div class="product-card-modern">
                            <div class="product-card-thumb">
                                <a href="{{ route('customer.produk.detail', $related->id) }}">
                                    @if($related->foto_url)
                                        <img src="{{ $related->foto_url }}" alt="{{ $related->nama }}" loading="lazy">
                                    @else
                                        <div class="product-thumb-placeholder">
                                            <i class="fa-solid fa-couch fa-2x mb-2" style="color: var(--primary-light);"></i>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="product-card-body">
                                <h6 class="product-card-title">
                                    <a href="{{ route('customer.produk.detail', $related->id) }}">
                                        {{ $related->nama }}
                                    </a>
                                </h6>
                                <div class="product-card-price">
                                    Rp {{ number_format($related->harga, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="product-card-footer">
                                <button type="button" 
                                        class="btn btn-store-primary w-100 btn-sm" 
                                        onclick="window.addToCart({{ $related->id }}, 1, this)">
                                    <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<!-- 4. MOBILE STICKY BOTTOM ACTION BAR -->
<div class="d-md-none fixed-bottom bg-white border-top p-2 px-3 shadow-lg" style="z-index: 1045; bottom: 64px;">
    <div class="d-flex align-items-center gap-2">
        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Assalam Mebel, saya tertarik dengan produk ' . $produk->nama) }}" target="_blank" class="btn btn-outline-success btn-sm px-3 py-2 rounded-3" title="Chat WhatsApp">
            <i class="fa-brands fa-whatsapp fs-5"></i>
        </a>
        <button type="button" class="btn btn-store-primary flex-grow-1 py-2 fw-bold rounded-3" onclick="submitAddToCart(this)">
            <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function increaseQty() {
        const input = document.getElementById('orderQuantity');
        let val = parseInt(input.value) || 1;
        if (val < 99) input.value = val + 1;
    }

    function decreaseQty() {
        const input = document.getElementById('orderQuantity');
        let val = parseInt(input.value) || 1;
        if (val > 1) input.value = val - 1;
    }

    function submitAddToCart(btn) {
        const qty = parseInt(document.getElementById('orderQuantity').value) || 1;
        window.addToCart({{ $produk->id }}, qty, btn);
    }
</script>
@endpush
