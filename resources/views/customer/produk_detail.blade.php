@extends('layouts.customer')

@section('content')
<style>
    .detail-img-holder {
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        height: 380px;
        background-color: #fdfaf6;
        overflow: hidden;
    }
    .detail-img-holder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .star-rating-display i { color: #f59e0b; }
    .star-rating-display .star-empty i { color: #d1d5db; }
    .review-card {
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        background: var(--light-card);
        transition: 0.2s;
    }
    .review-card:hover { border-color: var(--primary-color); }
    .review-avatar {
        width: 42px; height: 42px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-color);
    }
    .rating-bar {
        height: 8px;
        border-radius: 999px;
        background: #e5e7eb;
        overflow: hidden;
    }
    .rating-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: #f59e0b;
    }
    .btn-orange {
        background: var(--primary-color);
        color: #fff;
        font-weight: 700;
        border: none;
        border-radius: 10px;
    }
    .btn-orange:hover { background: var(--secondary-color); color: #fff; }
    .btn-outline-dark-theme {
        border: 1.5px solid var(--wood-border);
        background: var(--light-bg);
        color: var(--text-main);
        font-weight: 600;
        border-radius: 10px;
    }
    .btn-outline-dark-theme:hover {
        background: var(--wood-bg);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .star-input { display: none; }
    .star-input + label { font-size: 1.7rem; color: #d1d5db; cursor: pointer; transition: 0.15s; margin: 0 2px; }
    .star-input:checked ~ label { color: #f59e0b; }
    .star-hover-group label:hover, .star-hover-group label:hover ~ label { color: #f59e0b; }
    .related-card {
        border: 1.5px solid var(--wood-border);
        border-radius: 16px;
        background: var(--light-card);
        transition: 0.25s;
    }
    .related-card:hover { transform: translateY(-4px); border-color: var(--primary-color); }
    .related-img { height: 130px; border-radius: 12px 12px 0 0; object-fit: cover; width: 100%; }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- BREADCRUMB -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.beranda') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.katalog') }}" class="text-decoration-none text-muted">Katalog</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">{{ $produk->nama }}</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        <!-- GAMBAR PRODUK -->
        <div class="col-lg-5">
            <div class="detail-img-holder mb-3">
                @if($produk->foto_url)
                    <img src="{{ $produk->foto_url }}" alt="{{ $produk->nama }}" loading="lazy">
                @else
                    <div class="d-flex h-100 align-items-center justify-content-center text-muted">
                        <i class="fa-solid fa-couch fa-4x" style="color: var(--primary-color);"></i>
                    </div>
                @endif
            </div>

            <!-- RINGKASAN ULASAN SINGKAT -->
            <div class="review-card p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-center">
                        <h2 class="fw-extrabold mb-0" style="color: var(--primary-color);">{{ number_format($ratingAverage, 1) }}</h2>
                        <div class="star-rating-display fs-6">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-solid {{ $i <= round($ratingAverage) ? 'fa-star' : 'fa-star star-empty' }}"></i>
                            @endfor
                        </div>
                        <small class="text-muted">{{ $ratingCount }} ulasan</small>
                    </div>
                    <div class="flex-grow-1">
                        @foreach($ratingBreakdown as $star => $data)
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="small text-muted" style="width: 52px;">{{ $star }} <i class="fa-solid fa-star" style="color:#f59e0b; font-size: 0.65rem;"></i></span>
                                <div class="rating-bar flex-grow-1">
                                    <div class="rating-bar-fill" style="width: {{ $data['percent'] }}%;"></div>
                                </div>
                                <span class="small text-muted" style="width: 24px; text-align: right;">{{ $data['count'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- INFO & AKSI PRODUK -->
        <div class="col-lg-7">
            <h2 class="fw-bold text-dark mb-2">{{ $produk->nama }}</h2>

            <div class="d-flex align-items-center gap-3 flex-wrap mb-3">
                <div class="star-rating-display">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fa-solid {{ $i <= round($ratingAverage) ? 'fa-star' : 'fa-star star-empty' }}"></i>
                    @endfor
                </div>
                <a href="#ulasan" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    {{ $ratingCount }} ulasan
                </a>
                <span class="text-muted small">|</span>
                <span class="small text-muted"><i class="fa-solid fa-cart-check me-1"></i> {{ $soldCount }} terjual</span>
            </div>

            <h3 class="fw-extrabold mb-4" style="color: var(--primary-color);">
                Rp {{ number_format($produk->harga, 0, ',', '.') }}
            </h3>

            <p class="text-dark mb-4" style="line-height: 1.7;">{{ $produk->deskripsi }}</p>

            <div class="d-flex flex-column flex-sm-row gap-2 mb-4">
                <!-- Form Tambah ke Keranjang -->
                <form action="{{ route('customer.cart.add', $produk->id) }}" method="POST" class="flex-grow-1">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-orange w-100 py-2.5">
                        <i class="fa-solid fa-cart-plus me-1"></i> Tambah ke Keranjang
                    </button>
                </form>
                <a href="{{ route('customer.design', ['product_id' => $produk->id]) }}" class="btn btn-outline-dark-theme text-decoration-none text-center px-4 py-2.5">
                    <i class="fa-solid fa-pen-ruler me-1"></i> Kustomisasi Model Ini
                </a>
            </div>

            <div class="row g-2 small text-muted">
                <div class="col-md-6"><i class="fa-solid fa-award me-2" style="color: var(--accent-gold);"></i> Kayu Jati Solid Grade A (Perhutani)</div>
                <div class="col-md-6"><i class="fa-solid fa-shield-halved me-2 text-success"></i> Transaksi terverifikasi & bergaransi</div>
                <div class="col-md-6"><i class="fa-solid fa-truck-fast me-2 text-primary"></i> Pengiriman aman seluruh Indonesia</div>
                <div class="col-md-6"><i class="fa-solid fa-pen-ruler me-2" style="color: var(--accent-gold);"></i> Bisa dikustomisasi ukuran & warna</div>
            </div>
        </div>
    </div>

    <!-- SECTION ULASAN LENGKAP -->
    <div id="ulasan" class="mb-5">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1"><i class="fa-solid fa-comments me-2" style="color: var(--accent-gold);"></i>Ulasan Pembeli</h4>
                <p class="text-muted small mb-0">Baca pengalaman nyata pelanggan yang telah membeli produk ini.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- FORM ULASAN (LOGIN PELANGGAN) -->
            <div class="col-lg-5">
                <div class="review-card p-4 h-100">
                    <h6 class="fw-bold text-dark mb-3"><i class="fa-solid fa-pen-to-square me-2 text-primary"></i> Tulis Ulasan Anda</h6>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <div class="alert alert-warning border-0 rounded-3 small mb-0">
                                <i class="fa-solid fa-user-shield me-1"></i> Akun administrator tidak dapat menulis ulasan produk.
                            </div>
                        @else
                            @if($myReview)
                                <div class="alert alert-info border-0 rounded-3 small">
                                    <i class="fa-solid fa-circle-info me-1"></i> Anda sudah mengirim ulasan untuk produk ini
                                    (<strong>{{ $myReview->status }}</strong>).
                                    @if($myReview->status === 'Ditolak' && $myReview->admin_note)
                                        <br><span class="text-danger">Alasan admin: {{ $myReview->admin_note }}</span>
                                    @endif
                                </div>
                            @endif

                            <form action="{{ route('customer.produk.review.store', $produk->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- INPUT BINTANG -->
                                <label class="form-label small fw-bold text-dark mb-1">Rating Bintang <span class="text-danger">*</span></label>
                                <div class="star-hover-group d-flex mb-3">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="star-input" {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="star{{ $i }}" title="{{ $i }} bintang"><i class="fa-solid fa-star"></i></label>
                                    @endfor
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark mb-1">Judul Ulasan</label>
                                    <input type="text" name="title" value="{{ old('title') }}" maxlength="150" class="form-control form-control-custom form-control-sm" placeholder="Contoh: Ukiran rapi, kayu berkualitas!">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark mb-1">Komentar <span class="text-danger">*</span></label>
                                    <textarea name="comment" rows="4" class="form-control form-control-custom form-control-sm" placeholder="Ceritakan pengalaman Anda: kualitas kayu, keindahan ukiran, pengiriman, pelayanan..." required>{{ old('comment') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-dark mb-1">Foto Produk (opsional)</label>
                                    <input type="file" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="form-control form-control-sm form-control-custom">
                                </div>

                                <button type="submit" class="btn btn-orange w-100 py-2.5">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Kirim Ulasan
                                </button>
                                <small class="text-muted d-block mt-2 text-center" style="font-size: 0.72rem;">
                                    <i class="fa-solid fa-shield-halved text-success me-1"></i> Ulasan akan tampil setelah diverifikasi admin.
                                    Status pembeli terverifikasi dideteksi otomatis dari riwayat pesanan Anda.
                                </small>
                            </form>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fa-solid fa-lock fa-2x text-muted mb-3" style="color: var(--secondary-color) !important;"></i>
                            <p class="text-muted small mb-3">Masuk ke akun pelanggan Anda untuk menulis ulasan produk ini.</p>
                            <a href="{{ route('login') }}" class="btn btn-orange w-100 py-2">
                                <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk untuk Menulis Ulasan
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- DAFTAR ULASAN + FILTER -->
            <div class="col-lg-7">
                <!-- FILTER BINTANG -->
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <a href="{{ route('customer.produk.detail', $produk->id) }}"
                       class="btn btn-sm rounded-pill px-3 fw-bold {{ $activeFilter === 0 ? 'text-white' : '' }}"
                       style="{{ $activeFilter === 0 ? 'background-color: var(--primary-color);' : 'background:#fff; border:1.5px solid var(--light-border); color: var(--text-main);' }}">
                        Semua ({{ $ratingCount }})
                    </a>
                    @foreach($ratingBreakdown as $star => $data)
                        <a href="{{ route('customer.produk.detail', ['id' => $produk->id, 'rating' => $star]) }}"
                           class="btn btn-sm rounded-pill px-3 fw-bold {{ $activeFilter === $star ? 'text-white' : '' }}"
                           style="{{ $activeFilter === $star ? 'background-color: var(--primary-color);' : 'background:#fff; border:1.5px solid var(--light-border); color: var(--text-main);' }}">
                            {{ $star }} ★ ({{ $data['count'] }})
                        </a>
                    @endforeach
                </div>

                @forelse($filteredReviews as $review)
                    <div class="review-card p-3 p-md-4 mb-3">
                        <div class="d-flex gap-3">
                            <img src="{{ $review->author_avatar }}" alt="{{ $review->author_name }}" class="review-avatar flex-shrink-0">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
                                    <div>
                                        <span class="fw-bold text-dark small">{{ $review->author_name }}</span>
                                        @if($review->is_verified_buyer)
                                            <span class="badge rounded-pill ms-1" style="background-color: rgba(21, 128, 61, 0.12); color: #15803d; font-size: 0.62rem;">
                                                <i class="fa-solid fa-circle-check me-1"></i>Pembeli Terverifikasi
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $review->created_at->translatedFormat('d M Y, H:i') }}</small>
                                </div>

                                <div class="star-rating-display my-1" style="font-size: 0.8rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid {{ $i <= $review->rating ? 'fa-star' : 'fa-star star-empty' }}"></i>
                                    @endfor
                                </div>

                                @if($review->title)
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $review->title }}</h6>
                                @endif

                                <p class="text-dark mb-2" style="font-size: 0.86rem; line-height: 1.6;">{{ $review->comment }}</p>

                                @if($review->url_photo)
                                    <img src="{{ $review->url_photo }}" alt="Foto ulasan" class="rounded-3 border" style="max-height: 130px; border-color: var(--light-border) !important;">
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="review-card p-5 text-center">
                        <i class="fa-solid fa-comment-dots fa-3x text-muted mb-3" style="color: var(--secondary-color) !important;"></i>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Ulasan{{ $activeFilter ? " {$activeFilter} Bintang" : '' }}</h6>
                        <p class="text-muted small mb-0">Jadilah yang pertama memberikan ulasan untuk produk ini!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- PRODUK TERKAIT -->
    @if($relatedProducts->isNotEmpty())
        <div class="mb-5">
            <h4 class="fw-bold mb-4" style="color: var(--primary-color);">Produk Lainnya</h4>
            <div class="row g-3">
                @foreach($relatedProducts as $related)
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('customer.produk.detail', $related->id) }}" class="text-decoration-none">
                            <div class="related-card h-100">
                                @if($related->foto_url)
                                    <img src="{{ $related->foto_url }}" alt="{{ $related->nama }}" class="related-img" loading="lazy">
                                @else
                                    <div class="related-img d-flex align-items-center justify-content-center bg-light">
                                        <i class="fa-solid fa-couch fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div class="p-3">
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">{{ $related->nama }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-extrabold small" style="color: var(--primary-color);">Rp {{ number_format($related->harga, 0, ',', '.') }}</span>
                                        <span class="small text-muted" style="font-size: 0.7rem;">
                                            @if($related->rating_count > 0)
                                                <i class="fa-solid fa-star" style="color:#f59e0b;"></i> {{ number_format($related->rating_average, 1) }}
                                            @else
                                                Baru
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
