@extends('admin.layout')

@section('content')
<style>
    .admin-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
        transition: transform 0.2s ease;
    }

    .stat-card-katalog {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        padding: 16px 20px;
        transition: all 0.25s ease;
    }

    .stat-card-katalog:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(93, 64, 55, 0.08);
        border-color: var(--primary-color);
    }

    .stat-card-katalog .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .table-katalog thead th {
        background-color: #faf6f0 !important;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--light-border);
        padding: 14px 14px;
    }

    .table-katalog tbody td {
        padding: 14px 14px;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-border);
    }

    .table-katalog tbody tr:hover {
        background-color: rgba(93, 64, 55, 0.02) !important;
    }

    .katalog-thumb-box {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #fdfaf6;
        border: 1.5px solid var(--light-border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        cursor: pointer;
    }

    .katalog-thumb-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .katalog-thumb-box:hover img {
        transform: scale(1.15);
    }

    /* Action dots button (3-dots) */
    .action-dots-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #ffffff;
        border: 1.5px solid var(--light-border);
        color: var(--text-dark);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-dots-btn:hover, .action-dots-btn:focus {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    .product-grid-card {
        background: #ffffff;
        border: 1.5px solid var(--light-border);
        border-radius: 18px;
        padding: 18px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
    }

    .product-grid-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(93, 64, 55, 0.1);
    }

    .grid-img-holder {
        height: 170px;
        border-radius: 12px;
        background: #fdfaf6;
        border: 1px solid var(--light-border);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
    }

    .grid-img-holder img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Live preview upload box */
    .upload-preview-box {
        width: 100%;
        height: 160px;
        border: 2px dashed var(--light-border);
        border-radius: 12px;
        background: #faf6f0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        cursor: pointer;
        position: relative;
    }

    .upload-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">MANAJEMEN KATALOG PRODUK</h4>
            <p class="text-muted small mb-0">Kelola koleksi mebel siap jual, harga standar, serta model dasar untuk Studio Custom Jati.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('customer.katalog') }}" target="_blank" class="btn btn-outline-dark px-3 py-2 rounded-3 fw-semibold shadow-sm" style="border-color: var(--light-border);">
                <i class="fa-solid fa-eye me-1 text-primary"></i> Tinjau Katalog Toko
            </a>
            <button type="button" class="btn btn-dark px-4 py-2 rounded-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambahProduk" style="background-color: var(--primary-color); border: none;">
                <i class="fa fa-plus me-1"></i> Tambah Produk Baru
            </button>
        </div>
    </div>

    <!-- STATISTIK RINGKAS KATALOG -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card-katalog d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Koleksi Mebel</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $listProduk->count() }} <span class="fs-6 fw-normal text-muted">Produk</span></h3>
                </div>
                <div class="icon-box" style="background-color: rgba(93, 64, 55, 0.1); color: var(--primary-color);">
                    <i class="fa-solid fa-couch"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-katalog d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Standar Material Kayu</span>
                    <h5 class="fw-bold text-success mb-0"><i class="fa-solid fa-tree me-1"></i> 100% Kayu Jati Solid</h5>
                    <small class="text-muted" style="font-size: 0.75rem;">Grade A / Jepara & Pasuruan Craft</small>
                </div>
                <div class="icon-box" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                    <i class="fa-solid fa-certificate"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-katalog d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Rentang Harga Koleksi</span>
                    @php
                        $minPrice = $listProduk->min('harga') ?? 0;
                        $maxPrice = $listProduk->max('harga') ?? 0;
                    @endphp
                    <h6 class="fw-bold text-dark mb-0">
                        Rp {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}
                    </h6>
                    <small class="text-muted" style="font-size: 0.75rem;">Terhubung langsung ke Studio Custom</small>
                </div>
                <div class="icon-box" style="background-color: rgba(217, 119, 6, 0.1); color: #d97706;">
                    <i class="fa-solid fa-tags"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- KOTAK PENCARIAN & VIEW TOGGLE (TABEL / GRID) -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <form action="{{ route('admin.katalog') }}" method="GET" class="d-flex flex-grow-1 gap-2 align-items-center" style="max-width: 720px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nama produk mebel, deskripsi kayu, atau harga...">
                </div>
                <button type="submit" class="btn btn-dark rounded-3 fw-bold px-3 text-nowrap" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.katalog') }}" class="btn btn-outline-secondary rounded-3" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </form>

            <!-- TABS SWITCHER: TABEL vs GRID -->
            <ul class="nav nav-pills bg-light p-1 rounded-3 border" id="viewModeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active py-1.5 px-3 rounded-2 fw-semibold small" id="tab-table-btn" data-bs-toggle="pill" data-bs-target="#tab-table-content" type="button" role="tab">
                        <i class="fa-solid fa-table-list me-1"></i> Tabel
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link py-1.5 px-3 rounded-2 fw-semibold small" id="tab-grid-btn" data-bs-toggle="pill" data-bs-target="#tab-grid-content" type="button" role="tab">
                        <i class="fa-solid fa-grip me-1"></i> Galeri Grid
                    </button>
                </li>
            </ul>
        </div>

        @if(request('q'))
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="small text-muted">
                    <i class="fa-solid fa-filter me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong> ({{ $listProduk->count() }} produk ditemukan)
                </div>
                <a href="{{ route('admin.katalog') }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Produk
                </a>
            </div>
        @endif
    </div>

    <!-- TAB CONTENTS -->
    <div class="tab-content" id="viewModeContent">
        
        <!-- 1. TAMPILAN TABEL MODERN DENGAN TITIK TIGA -->
        <div class="tab-pane fade show active" id="tab-table-content" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
                <div class="table-responsive">
                    <table class="table table-hover table-katalog align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">No</th>
                                <th style="width: 80px;">Foto</th>
                                <th>Nama Produk & Material</th>
                                <th>Deskripsi Mebel</th>
                                <th>Harga Standar</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($listProduk as $index => $item)
                            <tr>
                                <td class="text-center fw-bold text-muted small">
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    <div class="katalog-thumb-box" data-bs-toggle="modal" data-bs-target="#modalPreviewProduk{{ $item->id }}" title="Klik untuk perbesar">
                                        @if($item->foto_url)
                                            <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}">
                                        @else
                                            <i class="fa-solid fa-couch text-muted"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong class="text-dark d-block fs-6">{{ $item->nama }}</strong>
                                    <div class="d-flex align-items-center gap-1 mt-1">
                                        <span class="badge bg-success-subtle text-success border border-success" style="font-size: 0.72rem;">
                                            <i class="fa-solid fa-tree me-1"></i> Kayu Jati Solid
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted small" title="{{ $item->deskripsi }}">
                                        {{ Str::limit($item->deskripsi, 85) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold fs-6" style="color: var(--primary-color);">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center pe-3">
                                    <!-- DROPDOWN 3-DOTS AKSI -->
                                    <div class="dropdown">
                                        <button class="action-dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                            <i class="fa-solid fa-ellipsis-vertical fs-6"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 border py-1.5" style="min-width: 200px; font-size: 0.85rem;">
                                            <li>
                                                <button class="dropdown-item py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalPreviewProduk{{ $item->id }}">
                                                    <i class="fa-solid fa-eye text-primary" style="width: 18px;"></i>
                                                    <span>Lihat Detail Lengkap</span>
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item py-2 d-flex align-items-center gap-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEditProduk"
                                                        data-id="{{ $item->id }}" 
                                                        data-nama="{{ $item->nama }}" 
                                                        data-harga="{{ $item->harga }}" 
                                                        data-deskripsi="{{ $item->deskripsi }}"
                                                        data-foto="{{ $item->foto_url }}">
                                                    <i class="fa-solid fa-pen-to-square text-warning" style="width: 18px;"></i>
                                                    <span>Edit Data & Harga</span>
                                                </button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('customer.design', ['product_id' => $item->id]) }}" target="_blank">
                                                    <i class="fa-solid fa-pen-ruler text-info" style="width: 18px;"></i>
                                                    <span>Uji di Studio Custom</span>
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <form action="{{ route('admin.katalog.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $item->nama }} dari katalog?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger">
                                                        <i class="fa-solid fa-trash text-danger" style="width: 18px;"></i>
                                                        <span class="fw-semibold">Hapus Produk</span>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid {{ request('q') ? 'fa-magnifying-glass' : 'fa-box-open' }} fa-3x mb-3 text-secondary"></i>
                                    @if(request('q'))
                                        <h5 class="fw-bold text-dark mb-1">Produk Tidak Ditemukan</h5>
                                        <p class="text-muted small mb-3">Tidak ada produk katalog yang cocok dengan pencarian "<strong>{{ request('q') }}</strong>".</p>
                                        <a href="{{ route('admin.katalog') }}" class="btn btn-outline-dark btn-sm rounded-3 px-3">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Reset Pencarian
                                        </a>
                                    @else
                                        <h5 class="fw-bold text-dark mb-1">Belum Ada Produk di Katalog</h5>
                                        <p class="text-muted small mb-0">Klik tombol "Tambah Produk Baru" di atas untuk menambahkan koleksi mebel.</p>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 2. TAMPILAN GALERI GRID -->
        <div class="tab-pane fade" id="tab-grid-content" role="tabpanel">
            <div class="row g-4">
                @forelse($listProduk as $item)
                <div class="col-lg-4 col-md-6">
                    <div class="product-grid-card">
                        <div>
                            <div class="grid-img-holder position-relative">
                                @if($item->foto_url)
                                    <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}">
                                @else
                                    <div class="text-muted text-center">
                                        <i class="fa-solid fa-couch fa-3x mb-1" style="color: var(--primary-color);"></i>
                                        <span class="small d-block fw-bold text-dark">Tanpa Foto</span>
                                    </div>
                                @endif
                                <span class="position-absolute top-0 start-0 m-2 badge bg-success-subtle text-success border border-success small">
                                    <i class="fa-solid fa-tree me-1"></i> Kayu Jati
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h5 class="fw-bold text-dark mb-0">{{ $item->nama }}</h5>
                                <!-- Titik 3 di Card -->
                                <div class="dropdown">
                                    <button class="action-dots-btn border-0 shadow-none" type="button" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border py-1">
                                        <li>
                                            <button class="dropdown-item py-1.5 small" data-bs-toggle="modal" data-bs-target="#modalPreviewProduk{{ $item->id }}">
                                                <i class="fa-solid fa-eye me-2 text-primary"></i> Detail
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item py-1.5 small" data-bs-toggle="modal" data-bs-target="#modalEditProduk"
                                                    data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-harga="{{ $item->harga }}" data-deskripsi="{{ $item->deskripsi }}" data-foto="{{ $item->foto_url }}">
                                                <i class="fa-solid fa-pen-to-square me-2 text-warning"></i> Edit
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-1.5 small" href="{{ route('customer.design', ['product_id' => $item->id]) }}" target="_blank">
                                                <i class="fa-solid fa-pen-ruler me-2 text-info"></i> Studio Custom
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('admin.katalog.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk {{ $item->nama }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item py-1.5 small text-danger">
                                                    <i class="fa-solid fa-trash me-2 text-danger"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 85) }}</p>
                            <h5 class="fw-extrabold mb-3" style="color: var(--primary-color);">Rp {{ number_format($item->harga, 0, ',', '.') }}</h5>
                        </div>
                        
                        <div class="d-flex justify-content-between gap-2 border-top pt-3" style="border-color: var(--light-border) !important;">
                            <button type="button" class="btn btn-outline-dark btn-sm rounded-3 px-3 w-50 fw-semibold" 
                                    data-bs-toggle="modal" data-bs-target="#modalEditProduk"
                                    data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-harga="{{ $item->harga }}" data-deskripsi="{{ $item->deskripsi }}" data-foto="{{ $item->foto_url }}">
                                <i class="fa fa-pen-to-square me-1"></i> Edit
                            </button>
                            
                            <a href="{{ route('customer.design', ['product_id' => $item->id]) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-3 px-3 w-50 fw-semibold">
                                <i class="fa-solid fa-pen-ruler me-1"></i> Kustom
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Tidak ada produk ditemukan.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- MODAL PREVIEW DETAIL PRODUK (PER PRODUK) -->
@foreach($listProduk as $item)
<div class="modal fade" id="modalPreviewProduk{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fs-6 fw-bold text-dark">{{ $item->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="rounded-3 overflow-hidden border mb-3" style="height: 260px; background: #faf6f0;">
                    @if($item->foto_url)
                        <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}" style="width: 100%; height: 100%; object-fit: contain;">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted">
                            <i class="fa-solid fa-couch fa-3x mb-2"></i>
                            <span>Tidak ada foto produk</span>
                        </div>
                    @endif
                </div>

                <div class="text-start">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1">
                            <i class="fa-solid fa-tree me-1"></i> Kayu Jati Solid Grade A
                        </span>
                        <h4 class="fw-extrabold mb-0" style="color: var(--primary-color);">Rp {{ number_format($item->harga, 0, ',', '.') }}</h4>
                    </div>
                    <label class="small fw-bold text-muted d-block mb-1">Deskripsi Produk:</label>
                    <p class="text-secondary small bg-light p-3 rounded-3 border mb-3">{{ $item->deskripsi }}</p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('customer.design', ['product_id' => $item->id]) }}" target="_blank" class="btn btn-outline-dark w-50 py-2 rounded-3 fw-bold small">
                        <i class="fa-solid fa-pen-ruler me-1"></i> Buka Studio Custom
                    </a>
                    <button type="button" class="btn btn-dark w-50 py-2 rounded-3 fw-bold small" style="background-color: var(--primary-color); border: none;"
                            data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalEditProduk"
                            data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-harga="{{ $item->harga }}" data-deskripsi="{{ $item->deskripsi }}" data-foto="{{ $item->foto_url }}">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Produk
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- MODAL TAMBAH PRODUK -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-plus-circle me-1"></i> Tambah Produk Katalog Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.katalog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Produk Mebel:</label>
                        <input type="text" name="nama" class="form-control rounded-3" placeholder="Contoh: Kursi Sofa Ukir Jepara" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Bahan Kayu:</label>
                        <input type="text" class="form-control rounded-3 bg-light" value="Kayu Jati Solid Grade A (Default Toko)" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Standar (Rp):</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" name="harga" class="form-control rounded-end-3" placeholder="Contoh: 4500000" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi Produk:</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Rincian dimensi bawaan, motif ukiran, spesifikasi busa/kain..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Foto Produk Mebel:</label>
                        <div class="upload-preview-box mb-2" id="boxTambahPreview" onclick="document.getElementById('inputTambahFoto').click()">
                            <div id="tambahPlaceholder" class="text-center p-3 text-muted">
                                <i class="fa-solid fa-cloud-arrow-up fa-2x mb-2 text-secondary"></i>
                                <span class="d-block small fw-semibold text-dark">Klik untuk pilih gambar foto mebel</span>
                                <small class="text-muted" style="font-size: 0.75rem;">JPG, PNG, atau WEBP (Maks 2MB)</small>
                            </div>
                            <img id="tambahPreviewImg" src="" alt="Preview" class="d-none">
                        </div>
                        <input type="file" id="inputTambahFoto" name="foto" class="form-control d-none" accept="image/*" required onchange="previewImageTambah(event)">
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4 fw-bold" style="background-color: var(--primary-color); border: none;">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT PRODUK -->
<div class="modal fade" id="modalEditProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-pen-to-square me-1"></i> Edit Produk Katalog</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditProduk" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Produk:</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Standar (Rp):</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">Rp</span>
                            <input type="number" id="edit_harga" name="harga" class="form-control rounded-end-3" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi:</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control rounded-3" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ganti Foto Produk (Opsional):</label>
                        <div class="upload-preview-box mb-2" id="boxEditPreview" onclick="document.getElementById('inputEditFoto').click()">
                            <img id="editPreviewImg" src="" alt="Foto Saat Ini" style="width: 100%; height: 100%; object-fit: contain;">
                            <div id="editOverlay" class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-50 text-white text-center py-1 small">
                                <i class="fa-solid fa-camera me-1"></i> Klik untuk mengganti foto
                            </div>
                        </div>
                        <input type="file" id="inputEditFoto" name="foto" class="form-control d-none" accept="image/*" onchange="previewImageEdit(event)">
                        <small class="text-muted" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengubah foto produk.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4 fw-bold" style="background-color: var(--primary-color); border: none;">Perbarui Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalEdit = document.getElementById('modalEditProduk');
    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            document.getElementById('edit_nama').value = button.getAttribute('data-nama') || '';
            document.getElementById('edit_deskripsi').value = button.getAttribute('data-deskripsi') || '';
            document.getElementById('edit_harga').value = button.getAttribute('data-harga') || '';
            document.getElementById('formEditProduk').action = '/admin/katalog/' + button.getAttribute('data-id');

            let currentFoto = button.getAttribute('data-foto');
            let previewImg = document.getElementById('editPreviewImg');
            if (currentFoto) {
                previewImg.src = currentFoto;
                previewImg.classList.remove('d-none');
            } else {
                previewImg.src = '';
                previewImg.classList.add('d-none');
            }
        });
    }
});

function previewImageTambah(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('tambahPreviewImg');
            const placeholder = document.getElementById('tambahPlaceholder');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewImageEdit(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('editPreviewImg');
            preview.src = e.target.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection