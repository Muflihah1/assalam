@extends('layouts.customer')

@section('content')
<style>
    /* Product Cards Styling */
    .product-card {
        border: 1.5px solid var(--wood-border);
        border-radius: 20px;
        background: var(--light-card);
        padding: 20px;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s ease;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.06);
    }

    .product-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-6px);
        box-shadow: 0 15px 30px rgba(93, 64, 55, 0.15);
    }

    .product-img-holder {
        border: 1.5px solid var(--light-border);
        border-radius: 14px;
        height: 200px;
        background-color: #fdfaf6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-main);
        overflow: hidden;
    }

    .product-img-holder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Buttons Style */
    .btn-orange {
        background: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border: none;
        border-radius: 10px;
        padding: 9px 12px;
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
        background: var(--light-bg);
        color: var(--text-main);
        font-weight: 600;
        border-radius: 10px;
        font-size: 13px;
        padding: 8px 12px;
        transition: 0.2s;
    }

    .btn-outline-dark-theme:hover {
        background: var(--wood-bg);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .price-text {
        color: var(--primary-color);
        font-weight: 800;
    }

    /* Modal Clean Theme */
    .modal-content-custom {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        color: var(--text-main);
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- TITLE SEKSI KATALOG -->
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Katalog Produk Mebel</h3>
            <p class="text-muted small mb-0">Temukan koleksi mebel siap beli atau kustomisasi sesuai ukuran ruangan Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customer.cart') }}" class="btn btn-outline-dark px-3 py-2 rounded-3 fw-bold">
                <i class="fa-solid fa-cart-shopping me-1"></i> Lihat Keranjang
            </a>
            <a href="{{ route('customer.design') }}" class="btn btn-dark px-3 py-2 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                <i class="fa-solid fa-pen-ruler me-1"></i> Buka Studio Custom
            </a>
        </div>
    </div>

    <!-- GRID PRODUK DINAMIS -->
    <div class="row g-4">
        @forelse($katalogs as $item)
            <div class="col-lg-4 col-md-6">
                <div class="product-card">
                    <div class="product-img-holder mb-3">
                        @if($item->foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($item->foto) }}" alt="{{ $item->nama }}">
                        @else
                            <div class="text-center p-3 text-muted">
                                <i class="fa-solid fa-couch fa-2x mb-1" style="color: var(--primary-color);"></i>
                                <span class="d-block fw-bold small text-dark">{{ $item->nama }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="text-start">
                        <h5 class="fw-bold text-dark mb-1">{{ $item->nama }}</h5>
                        <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 80) }}</p>
                        <h5 class="price-text mb-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</h5>
                    </div>

                    <div class="d-flex flex-column gap-2 mt-auto pt-2 border-top" style="border-color: var(--light-border) !important;">
                        <div class="d-flex gap-2">
                            <!-- Form Tambah ke Keranjang Langsung -->
                            <form action="{{ route('customer.cart.add', $item->id) }}" method="POST" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-orange w-100">
                                    <i class="fa-solid fa-cart-plus me-1"></i> + Keranjang
                                </button>
                            </form>
                            <!-- Tombol Detail / Review -->
                            <button class="btn btn-outline-dark-theme flex-shrink-0" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $item->id }}" title="Lihat Detail">
                                <i class="fa-solid fa-circle-info"></i>
                            </button>
                        </div>
                        <a href="{{ route('customer.design') }}" class="btn btn-outline-dark-theme text-decoration-none text-center">
                            <i class="fa-solid fa-pen-ruler me-1"></i> Kustomisasi Model Ini 🎨
                        </a>
                    </div>
                </div>
            </div>

            <!-- MODAL DETAIL PRODUK DINAMIS PER ITEM -->
            <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-content-custom p-4 shadow-lg">
                        <div class="modal-body text-center p-0">
                            <div class="product-img-holder mb-3" style="height: 220px;">
                                @if($item->foto)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item->foto) }}" alt="{{ $item->nama }}">
                                @else
                                    <span class="fw-bold fs-5 text-dark">{{ $item->nama }}</span>
                                @endif
                            </div>
                            <h4 class="fw-bold text-dark mb-2">{{ $item->nama }}</h4>
                            <p class="text-muted small mb-3">{{ $item->deskripsi }}</p>
                            <h4 class="price-text mb-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</h4>
                            
                            <!-- Form Tambah dari Modal dengan Pilihan Jumlah Qty -->
                            <form action="{{ route('customer.cart.add', $item->id) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                    <label class="small fw-bold text-muted">Jumlah:</label>
                                    <input type="number" name="quantity" value="1" min="1" max="50" class="form-control text-center" style="width: 80px; border-radius: 8px;">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary w-50 py-2.5 rounded-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-orange py-2.5 rounded-3 w-50 fw-bold">
                                        <i class="fa-solid fa-cart-plus me-1"></i> Beli & Masuk Keranjang
                                    </button>
                                </div>
                            </form>
                            
                            <div class="text-center pt-2 border-top">
                                <a href="{{ route('customer.design') }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                                    Atau kustomisasi ukuran & bahan di Studio Custom →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="product-card py-5">
                    <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Belum ada produk katalog yang ditambahkan.</p>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection