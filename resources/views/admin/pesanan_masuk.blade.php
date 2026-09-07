@extends('admin.layout')

@section('content')
<style>
    .admin-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
        padding: 24px;
    }

    .stat-card-order {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        padding: 16px 20px;
        transition: all 0.25s ease;
    }

    .stat-card-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(93, 64, 55, 0.08);
        border-color: var(--primary-color);
    }

    .stat-card-order .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .nav-tabs-order {
        border-bottom: 2px solid var(--light-border);
        gap: 8px;
        overflow-x: auto;
        flex-wrap: nowrap;
        scrollbar-width: none;
        padding-bottom: 8px;
    }

    .nav-tabs-order .nav-link {
        border: 1.5px solid var(--light-border) !important;
        color: var(--text-dark) !important;
        background-color: #ffffff !important;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 8px 16px;
        border-radius: 10px !important;
        position: relative;
        white-space: nowrap;
        transition: all 0.2s ease;
        text-decoration: none;
        margin: 0 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .nav-tabs-order .nav-link:hover {
        color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        background-color: rgba(93, 64, 55, 0.06) !important;
    }

    .nav-tabs-order .nav-link.active {
        color: #ffffff !important;
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.25) !important;
    }

    .nav-tabs-order .nav-link.active .badge.bg-light {
        background-color: rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }

    .nav-tabs-order .nav-link.active .badge.text-dark {
        color: #ffffff !important;
    }

    .order-thumb-box {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid var(--light-border);
        flex-shrink: 0;
    }

    .btn-action-sm {
        padding: 5px 10px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 8px;
        white-space: nowrap;
    }

    .action-dots-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--light-bg);
        border: 1.5px solid var(--light-border);
        color: var(--text-dark);
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .action-dots-btn:hover, .action-dots-btn:focus, .show > .action-dots-btn {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2);
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">KELOLA PESANAN MASUK & VERIFIKASI</h4>
            <p class="small text-muted mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Verifikasi rincian pesanan baru, periksa mutasi bukti transfer DP / pelunasan, dan atur antrean produksi.</p>
        </div>
        <div>
            <a href="{{ route('admin.progres.produksi') }}" class="btn btn-dark rounded-3 px-3 py-2 fw-semibold" style="background-color: var(--primary-color); border: none;">
                <i class="fa-solid fa-gears me-1"></i> Buka Workshop Progres
            </a>
        </div>
    </div>

    <!-- STATISTIK KARTU OPERASIONAL -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-order d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Menunggu Konfirmasi</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['menunggu_konfirmasi'] ?? 0 }}</h3>
                </div>
                <div class="icon-box bg-warning-subtle text-warning">
                    <i class="fa-regular fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-order d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Verifikasi Bukti DP</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['menunggu_verif_dp'] ?? 0 }}</h3>
                </div>
                <div class="icon-box bg-info-subtle text-info">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-order d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Dalam Produksi</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['dalam_produksi'] ?? 0 }}</h3>
                </div>
                <div class="icon-box bg-primary-subtle text-primary">
                    <i class="fa-solid fa-hammer"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="stat-card-order d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Verif. Pelunasan</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['menunggu_pelunasan'] ?? 0 }}</h3>
                </div>
                <div class="icon-box bg-success-subtle text-success">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- QUICK FILTER TABS -->
    <div class="mb-3">
        <ul class="nav nav-tabs-order pb-1">
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? 'all') === 'all' ? 'active' : '' }}" href="{{ route('admin.pesanan.masuk', array_merge(request()->except('tab'), ['tab' => 'all'])) }}">
                    Semua Pesanan <span class="badge rounded-pill bg-light text-dark ms-1">{{ $stats['total'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') === 'menunggu_konfirmasi' ? 'active' : '' }}" href="{{ route('admin.pesanan.masuk', array_merge(request()->except('tab'), ['tab' => 'menunggu_konfirmasi'])) }}">
                    Menunggu Konfirmasi 
                    @if(($stats['menunggu_konfirmasi'] ?? 0) > 0)
                        <span class="badge rounded-pill bg-warning text-dark ms-1">{{ $stats['menunggu_konfirmasi'] }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') === 'menunggu_dp' ? 'active' : '' }}" href="{{ route('admin.pesanan.masuk', array_merge(request()->except('tab'), ['tab' => 'menunggu_dp'])) }}">
                    Menunggu DP
                    @if(($stats['menunggu_verif_dp'] ?? 0) > 0)
                        <span class="badge rounded-pill bg-danger text-white ms-1">{{ $stats['menunggu_verif_dp'] }} perlu verif</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') === 'dalam_pengerjaan' ? 'active' : '' }}" href="{{ route('admin.pesanan.masuk', array_merge(request()->except('tab'), ['tab' => 'dalam_pengerjaan'])) }}">
                    Dalam Pengerjaan <span class="badge rounded-pill bg-light text-dark ms-1">{{ $stats['dalam_produksi'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') === 'pelunasan' ? 'active' : '' }}" href="{{ route('admin.pesanan.masuk', array_merge(request()->except('tab'), ['tab' => 'pelunasan'])) }}">
                    Pelunasan
                    @if(($stats['menunggu_pelunasan'] ?? 0) > 0)
                        <span class="badge rounded-pill bg-info text-white ms-1">{{ $stats['menunggu_pelunasan'] }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') === 'selesai' ? 'active' : '' }}" href="{{ route('admin.pesanan.masuk', array_merge(request()->except('tab'), ['tab' => 'selesai'])) }}">
                    Selesai <span class="badge rounded-pill bg-light text-dark ms-1">{{ $stats['selesai'] ?? 0 }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($activeTab ?? '') === 'batal' ? 'active' : '' }}" href="{{ route('admin.pesanan.masuk', array_merge(request()->except('tab'), ['tab' => 'batal'])) }}">
                    Dibatalkan / Ditolak <span class="badge rounded-pill bg-light text-dark ms-1">{{ $stats['batal'] ?? 0 }}</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- KOTAK PENCARIAN PESANAN MASUK -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
        <form action="{{ route('admin.pesanan.masuk') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'all' }}">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nomor pesanan, nama pemesan, nomor WhatsApp, kategori mebel, atau status...">
                </div>
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.pesanan.masuk', ['tab' => $activeTab ?? 'all']) }}" class="btn btn-outline-secondary rounded-3" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        @if(request('q'))
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="small text-muted">
                    <i class="fa-solid fa-filter me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong> ({{ $listPesananMasuk->count() }} pesanan ditemukan)
                </div>
                <a href="{{ route('admin.pesanan.masuk', ['tab' => $activeTab ?? 'all']) }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua di Tab Ini
                </a>
            </div>
        @endif
    </div>

    <!-- TABEL PESANAN MASUK -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 20%;" class="ps-3">Pesanan & Tanggal</th>
                        <th style="width: 22%;">Pemesan & Kontak</th>
                        <th style="width: 26%;">Spesifikasi Mebel Jati</th>
                        <th style="width: 24%;">Status & Pembayaran</th>
                        <th style="width: 8%; text-align: center;" class="pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listPesananMasuk as $item)
                    <tr>
                        <!-- 1. PESANAN & TANGGAL -->
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2.5">
                                @php
                                    $itemThumb = null;
                                    if ($item->customDesign && $item->customDesign->sketch_image) {
                                        $itemThumb = asset('storage/' . $item->customDesign->sketch_image);
                                    } elseif ($item->customDesign && $item->customDesign->produk && $item->customDesign->produk->foto_url) {
                                        $itemThumb = $item->customDesign->produk->foto_url;
                                    } elseif ($item->items && $item->items->first() && $item->items->first()->image) {
                                        $itemThumb = asset($item->items->first()->image);
                                    }
                                @endphp
                                @if($itemThumb)
                                    <img src="{{ $itemThumb }}" alt="Mebel" class="order-thumb-box shadow-sm">
                                @else
                                    <div class="order-thumb-box bg-light d-flex align-items-center justify-content-center text-muted">
                                        <i class="fa-solid fa-couch fa-lg" style="color: var(--primary-color);"></i>
                                    </div>
                                @endif
                                <div>
                                    <strong class="text-dark d-block fs-6" style="color: var(--primary-color) !important;">
                                        #{{ $item->order_number }}
                                    </strong>
                                    <span class="small text-muted" style="font-size: 0.78rem;">
                                        {{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}
                                    </span>
                                    <div class="mt-1">
                                        <span class="badge px-2 py-0.5 rounded-pill small border" style="background-color: var(--wood-bg); color: var(--text-dark); font-size: 0.72rem;">
                                            {{ $item->current_stage ?? 'Tahap 1' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- 2. PEMESAN & KONTAK -->
                        <td>
                            <strong class="text-dark d-block">{{ $item->recipient_name ?? $item->user->name }}</strong>
                            <div class="small text-muted mb-1">
                                @if($item->user && $item->user->username)
                                    <span>@<span>{{ $item->user->username }}</span></span>
                                @endif
                            </div>
                            @php
                                $cleanPhone = preg_replace('/[^0-9]/', '', $item->recipient_phone ?? $item->user->whatsapp_number ?? '');
                                if (str_starts_with($cleanPhone, '0')) {
                                    $cleanPhone = '62' . substr($cleanPhone, 1);
                                }
                            @endphp
                            @if($cleanPhone)
                                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($item->recipient_name ?? $item->user->name) }},%20kami%20dari%20Assalam%20Mebel%20mengenai%20pesanan%20%23{{ $item->order_number }}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-0.5 shadow-2xs" 
                                   style="font-size: 0.75rem;">
                                    <i class="fa-brands fa-whatsapp me-1"></i>{{ $item->recipient_phone ?? $item->user->whatsapp_number }}
                                </a>
                            @else
                                <span class="small text-muted">-</span>
                            @endif
                        </td>

                        <!-- 3. SPESIFIKASI MEBEL -->
                        <td>
                            <strong class="text-dark d-block">{{ $item->customDesign->category ?? 'Custom Furniture' }}</strong>
                            @if($item->items && $item->items->count() > 0)
                                <div class="small text-secondary mb-1">
                                    {{ $item->items->pluck('product_name')->implode(', ') }}
                                </div>
                            @endif
                            <div class="small text-muted" style="font-size: 0.8rem;">
                                <span><i class="fa-solid fa-tree me-1 text-success"></i>Kayu Jati Solid</span>
                                @if($item->customDesign && $item->customDesign->length_cm)
                                    <span class="ms-1">({{ $item->customDesign->length_cm }}×{{ $item->customDesign->width_cm }}×{{ $item->customDesign->height_cm }} cm)</span>
                                @endif
                            </div>
                            @if($item->customDesign && $item->customDesign->color_name)
                                <div class="small text-muted" style="font-size: 0.78rem;">
                                    <span>Tone: <strong>{{ $item->customDesign->color_name }}</strong></span>
                                </div>
                            @endif

                            @php
                                $rowSpecialNotes = $item->customer_notes ?? ($item->customDesign->notes ?? null);
                            @endphp
                            @if(!empty($rowSpecialNotes))
                                <div class="mt-2 p-2 rounded-3 shadow-2xs" style="background-color: #fefce8; border: 1.5px dashed #f59e0b; max-width: 320px;">
                                    <div class="d-flex align-items-center gap-1.5 fw-bold text-dark mb-0.5" style="font-size: 0.74rem;">
                                        <i class="fa-solid fa-note-sticky text-warning"></i>
                                        <span>Catatan Khusus Pelanggan:</span>
                                    </div>
                                    <div class="text-dark fw-semibold fst-italic" style="font-size: 0.78rem; line-height: 1.35;">
                                        "{{ $rowSpecialNotes }}"
                                    </div>
                                </div>
                            @endif
                        </td>

                        <!-- 4. RINCIAN PEMBAYARAN -->
                        <td>
                            <div class="mb-1">
                                <span class="small text-muted">Total:</span>
                                <strong class="text-dark fs-6 ms-1">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                            </div>
                            <div class="small text-muted mb-1.5" style="font-size: 0.78rem;">
                                <span>DP (50%): <strong class="text-success">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong></span>
                                @if($item->remaining_payment > 0)
                                    <span class="ms-1">| Sisa: <strong class="text-danger">Rp {{ number_format($item->remaining_payment, 0, ',', '.') }}</strong></span>
                                @endif
                            </div>

                            <!-- Payment Status Badge -->
                            <div>
                                @if($item->payment_status === 'Lunas')
                                    <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill small">
                                        <i class="fa-solid fa-check-double me-1"></i>Lunas
                                    </span>
                                @elseif($item->payment_status === 'Menunggu Verifikasi Pelunasan')
                                    <span class="badge bg-info-subtle text-info px-2 py-0.5 rounded-pill small border border-info">
                                        <i class="fa-solid fa-receipt me-1"></i>Verif. Pelunasan
                                    </span>
                                @elseif($item->payment_status === 'Bukti Pelunasan Ditolak')
                                    <span class="badge bg-danger-subtle text-danger px-2 py-0.5 rounded-pill small border border-danger">
                                        <i class="fa-solid fa-xmark me-1"></i>Pelunasan Ditolak
                                    </span>
                                @elseif($item->payment_status === 'DP Terverifikasi')
                                    <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill small">
                                        <i class="fa-solid fa-check me-1"></i>DP Terverifikasi
                                    </span>
                                @elseif($item->payment_status === 'Menunggu Verifikasi DP')
                                    <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-0.5 rounded-pill small border border-warning">
                                        <i class="fa-solid fa-receipt me-1"></i>Perlu Verif. DP
                                    </span>
                                @elseif($item->payment_status === 'Bukti DP Ditolak')
                                    <span class="badge bg-danger-subtle text-danger px-2 py-0.5 rounded-pill small border border-danger">
                                        <i class="fa-solid fa-xmark me-1"></i>Bukti DP Ditolak
                                    </span>
                                @elseif($item->payment_status === 'Menunggu Pembayaran DP')
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-0.5 rounded-pill small">
                                        Menunggu Bayar DP
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark px-2 py-0.5 rounded-pill small border">{{ $item->payment_status }}</span>
                                @endif

                                @if($item->order_status === 'Menunggu Konfirmasi')
                                    <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-0.5 rounded-pill small border border-warning ms-1">
                                        Konfirmasi Awal
                                    </span>
                                @elseif($item->order_status === 'Ditolak' || $item->order_status === 'Dibatalkan')
                                    <span class="badge bg-danger-subtle text-danger px-2 py-0.5 rounded-pill small ms-1">
                                        {{ $item->order_status }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- 5. AKSI DENGAN TITIK TIGA (DROPDOWN 3-DOTS) -->
                        <td class="text-center pe-3">
                            <div class="dropdown">
                                <button class="action-dots-btn shadow-2xs position-relative" 
                                        type="button" 
                                        data-bs-toggle="dropdown" 
                                        aria-expanded="false" 
                                        title="Pilihan Aksi Pesanan">
                                    <i class="fa-solid fa-ellipsis-vertical fs-6"></i>
                                    @if($item->order_status === 'Menunggu Konfirmasi' || $item->payment_status === 'Menunggu Verifikasi DP' || $item->payment_status === 'Menunggu Verifikasi Pelunasan')
                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                                            <span class="visually-hidden">Perlu Tindakan</span>
                                        </span>
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 border py-1.5" style="min-width: 220px; font-size: 0.85rem;">
                                    <li>
                                        <button class="dropdown-item py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalDetailPesanan{{ $item->id }}">
                                            <i class="fa-solid fa-circle-info text-primary" style="width: 18px;"></i>
                                            <span class="fw-semibold">Lihat Detail Lengkap</span>
                                        </button>
                                    </li>

                                    <!-- Konfirmasi Terima / Tolak -->
                                    @if($item->order_status === 'Menunggu Konfirmasi')
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <button class="dropdown-item py-2 d-flex align-items-center gap-2 text-success fw-bold" data-bs-toggle="modal" data-bs-target="#modalTerimaPesanan{{ $item->id }}">
                                                <i class="fa-solid fa-circle-check text-success" style="width: 18px;"></i>
                                                <span>Terima Pesanan</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTolakPesanan{{ $item->id }}">
                                                <i class="fa-solid fa-ban text-danger" style="width: 18px;"></i>
                                                <span>Tolak Pesanan</span>
                                            </button>
                                        </li>
                                    @endif

                                    <!-- Verifikasi Bukti DP -->
                                    @if($item->payment_status === 'Menunggu Verifikasi DP' || ($item->dp_receipt_proof && in_array($item->payment_status, ['Menunggu Pembayaran DP', 'Bukti DP Ditolak'])))
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <button class="dropdown-item py-2 d-flex align-items-center gap-2 text-warning-emphasis fw-bold" data-bs-toggle="modal" data-bs-target="#modalVerifikasiDP{{ $item->id }}">
                                                <i class="fa-solid fa-receipt text-warning" style="width: 18px;"></i>
                                                <span>Verifikasi Bukti DP</span>
                                            </button>
                                        </li>
                                    @endif

                                    <!-- Verifikasi Bukti Pelunasan -->
                                    @if($item->payment_status === 'Menunggu Verifikasi Pelunasan' || ($item->final_receipt_proof && in_array($item->payment_status, ['Bukti Pelunasan Ditolak', 'DP Terverifikasi'])))
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <button class="dropdown-item py-2 d-flex align-items-center gap-2 text-info-emphasis fw-bold" data-bs-toggle="modal" data-bs-target="#modalVerifikasiPelunasan{{ $item->id }}">
                                                <i class="fa-solid fa-file-invoice-dollar text-info" style="width: 18px;"></i>
                                                <span>Verifikasi Pelunasan</span>
                                            </button>
                                        </li>
                                    @endif

                                    <!-- Progres Workshop -->
                                    @if(!in_array($item->order_status, ['Ditolak', 'Dibatalkan']))
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-dark" href="{{ route('admin.progres.produksi', $item->id) }}">
                                                <i class="fa-solid fa-hammer text-secondary" style="width: 18px;"></i>
                                                <span>Progres Workshop</span>
                                            </a>
                                        </li>
                                    @endif

                                    <!-- WhatsApp Direct -->
                                    @if($cleanPhone)
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-success" href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($item->recipient_name ?? $item->user->name) }},%20kami%20dari%20Assalam%20Mebel%20mengenai%20pesanan%20%23{{ $item->order_number }}" target="_blank">
                                                <i class="fa-brands fa-whatsapp text-success" style="width: 18px;"></i>
                                                <span>Hubungi WhatsApp</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-muted py-5 text-center">
                            <i class="fa-solid {{ request('q') ? 'fa-magnifying-glass' : 'fa-clipboard-check' }} fa-3x mb-2 text-muted"></i>
                            @if(request('q'))
                                <h6 class="fw-bold text-dark mt-2 mb-1">Tidak Ditemukan</h6>
                                <p class="mb-3">Tidak ada pesanan masuk yang cocok dengan kata kunci "<strong>{{ request('q') }}</strong>".</p>
                                <a href="{{ route('admin.pesanan.masuk', ['tab' => $activeTab ?? 'all']) }}" class="btn btn-outline-dark btn-sm rounded-3 px-3">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Pencarian
                                </a>
                            @else
                                <p class="mb-0">Tidak ada pesanan dalam kategori tab ini.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========================================================
         KUMPULAN MODAL INTERAKTIF (DI LUAR TABEL AGAR VALID HTML)
         ======================================================== -->
    @foreach($listPesananMasuk as $item)

        <!-- 0. MODAL DETAIL LENGKAP PESANAN -->
        <div class="modal fade" id="modalDetailPesanan{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                        <h5 class="modal-title fs-6 fw-bold">
                            <i class="fa-solid fa-file-lines me-2"></i> Rincian Pesanan #{{ $item->order_number }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        <!-- Ringkasan Status & Tanggal -->
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom flex-wrap gap-2">
                            <div>
                                <span class="text-muted small d-block">Waktu Pemesanan:</span>
                                <strong class="text-dark">{{ $item->created_at ? $item->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB</strong>
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <span class="badge px-3 py-1.5 rounded-pill border" style="background-color: var(--wood-bg); color: var(--text-dark);">
                                    Tahap: {{ $item->current_stage ?? 'Tahap 1' }}
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-1.5 rounded-pill border">
                                    {{ $item->order_status }}
                                </span>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill border border-primary">
                                    {{ $item->payment_status }}
                                </span>
                            </div>
                        </div>

                        @php
                            $modalSpecialNotes = $item->customer_notes ?? ($item->customDesign->notes ?? null);
                        @endphp
                        @if($modalSpecialNotes)
                            <div class="p-3 mb-3 rounded-3 border border-warning" style="background-color: #fffbeb;">
                                <div class="d-flex align-items-center justify-content-between mb-1.5 flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning text-dark px-2.5 py-1 fw-bold">
                                            <i class="fa-solid fa-note-sticky me-1"></i> CATATAN KHUSUS PELANGGAN
                                        </span>
                                        <span class="text-muted small fw-semibold">Wajib diprioritaskan oleh Tukang Kayu / Workshop</span>
                                    </div>
                                    <span class="badge bg-white text-secondary border border-warning-subtle small px-2 py-0.5">Custom Request</span>
                                </div>
                                <div class="p-2.5 bg-white rounded-2 border border-warning-subtle text-dark fw-medium mt-1" style="font-size: 0.92rem; line-height: 1.5;">
                                    "{{ $modalSpecialNotes }}"
                                </div>
                            </div>
                        @endif

                        <div class="row g-3 mb-4">
                            <!-- Informasi Pemesan & Pengiriman -->
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <h6 class="fw-bold text-dark mb-2 pb-1 border-bottom" style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-user me-1 text-primary"></i> Data Pemesan & Pengiriman
                                    </h6>
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Nama Lengkap:</span>
                                        <strong class="text-dark">{{ $item->recipient_name ?? $item->user->name }}</strong>
                                        @if($item->user && $item->user->username)
                                            <span class="small text-muted">(@{{ $item->user->username }})</span>
                                        @endif
                                    </div>
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">WhatsApp:</span>
                                        <strong class="text-success"><i class="fa-brands fa-whatsapp me-1"></i>{{ $item->recipient_phone ?? $item->user->whatsapp_number }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Alamat Pengiriman:</span>
                                        <span class="text-dark small">{{ $item->shipping_address ?? 'Alamat belum diatur' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Spesifikasi Mebel Custom -->
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 border h-100">
                                    <h6 class="fw-bold text-dark mb-2 pb-1 border-bottom" style="font-size: 0.85rem;">
                                        <i class="fa-solid fa-couch me-1" style="color: var(--primary-color);"></i> Spesifikasi Mebel Kayu Jati
                                    </h6>
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Kategori Furniture:</span>
                                        <strong class="text-dark">{{ $item->customDesign->category ?? 'Custom Mebel' }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Bahan Kayu:</span>
                                        <span class="badge bg-success-subtle text-success border border-success fw-bold">
                                            <i class="fa-solid fa-tree me-1"></i> Kayu Jati Solid Grade A (Perhutani)
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Dimensi Presisi (P × L × T):</span>
                                        <strong class="text-dark">
                                            {{ $item->customDesign->length_cm ?? 180 }} cm × {{ $item->customDesign->width_cm ?? 80 }} cm × {{ $item->customDesign->height_cm ?? 75 }} cm
                                        </strong>
                                    </div>
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Warna Finishing:</span>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <span class="rounded-circle border" style="width: 20px; height: 20px; background-color: {{ $item->customDesign->color_hex ?? '#d97706' }}; display: inline-block;"></span>
                                            <strong class="text-dark small">{{ $item->customDesign->color_name ?? 'Amber Gold' }}</strong>
                                            <span class="text-muted small">({{ $item->customDesign->color_hex ?? '#d97706' }})</span>
                                        </div>
                                    </div>

                                    @if($item->customDesign && $item->customDesign->sketch_image)
                                        <div class="mt-2 pt-2 border-top">
                                            <span class="small text-muted d-block mb-1">Sketsa / Foto Referensi:</span>
                                            <a href="{{ asset('storage/' . $item->customDesign->sketch_image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $item->customDesign->sketch_image) }}" alt="Sketsa" class="img-thumbnail rounded-3 shadow-sm" style="max-height: 80px;">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Rincian Biaya & Skema Pembayaran DANA -->
                        <div class="p-3 rounded-3 border" style="background: linear-gradient(135deg, #fdfbf7 0%, #f6ede3 100%);">
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-wallet me-1 text-primary"></i> Rincian Pembayaran (Metode DANA)
                            </h6>
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <div class="p-2 bg-white rounded-3 border shadow-2xs">
                                        <span class="text-muted d-block small">Total Tagihan:</span>
                                        <strong class="text-dark fs-6">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-white rounded-3 border shadow-2xs">
                                        <span class="text-muted d-block small">Wajib DP (50%):</span>
                                        <strong class="text-success fs-6">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="p-2 bg-white rounded-3 border shadow-2xs">
                                        <span class="text-muted d-block small">Sisa Pelunasan (50%):</span>
                                        <strong class="text-danger fs-6">Rp {{ number_format($item->remaining_payment, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>

                            @if($item->dp_receipt_proof || $item->final_receipt_proof)
                                <div class="d-flex gap-3 mt-3 pt-2 border-top flex-wrap">
                                    @if($item->dp_receipt_proof)
                                        <div>
                                            <span class="small fw-bold text-dark d-block mb-1">Bukti Transfer DP:</span>
                                            <a href="{{ asset('storage/' . $item->dp_receipt_proof) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                                <i class="fa-solid fa-file-image me-1 text-warning"></i> Lihat Bukti DP
                                            </a>
                                        </div>
                                    @endif
                                    @if($item->final_receipt_proof)
                                        <div>
                                            <span class="small fw-bold text-dark d-block mb-1">Bukti Pelunasan:</span>
                                            <a href="{{ asset('storage/' . $item->final_receipt_proof) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                                <i class="fa-solid fa-file-invoice-dollar me-1 text-info"></i> Lihat Bukti Pelunasan
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4">
                        @if($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($item->recipient_name ?? $item->user->name) }},%20kami%20dari%20Assalam%20Mebel%20mengenai%20pesanan%20%23{{ $item->order_number }}" target="_blank" class="btn btn-outline-success btn-sm rounded-3 px-3">
                                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                            </a>
                        @endif
                        @if(!in_array($item->order_status, ['Ditolak', 'Dibatalkan']))
                            <a href="{{ route('admin.progres.produksi', $item->id) }}" class="btn btn-dark btn-sm rounded-3 px-3" style="background-color: var(--primary-color); border: none;">
                                <i class="fa-solid fa-hammer me-1"></i> Buka Workshop Progres
                            </a>
                        @endif
                        <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1. MODAL TERIMA PESANAN -->
        <div class="modal fade" id="modalTerimaPesanan{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-success text-white rounded-top-4">
                        <h5 class="modal-title fs-6 fw-bold">
                            <i class="fa-solid fa-circle-check me-2"></i> Terima Pesanan #{{ $item->order_number }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.pesanan.confirm', $item->id) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4 text-start">
                            <div class="p-3 bg-light rounded-3 border mb-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="small text-muted d-block">Nama Pemesan:</span>
                                        <strong class="text-dark">{{ $item->recipient_name ?? $item->user->name }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="small text-muted d-block">WhatsApp:</span>
                                        <strong class="text-success"><i class="fa-brands fa-whatsapp me-1"></i>{{ $item->recipient_phone ?? $item->user->whatsapp_number }}</strong>
                                    </div>
                                    <div class="col-12 border-top pt-2 mt-2">
                                        <span class="small text-muted d-block">Mebel:</span>
                                        <strong class="text-dark">{{ $item->customDesign->category ?? 'Custom Mebel' }}</strong>
                                    </div>
                                    <div class="col-6 border-top pt-2">
                                        <span class="small text-muted d-block">Total Tagihan:</span>
                                        <strong class="text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                    </div>
                                    <div class="col-6 border-top pt-2">
                                        <span class="small text-muted d-block">Wajib DP (50%):</span>
                                        <strong class="text-success">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>

                            @php
                                $terimaSpecialNotes = $item->customer_notes ?? ($item->customDesign->notes ?? null);
                            @endphp
                            @if($terimaSpecialNotes)
                                <div class="p-3 rounded-3 border border-warning-subtle mb-3" style="background-color: #fffbeb;">
                                    <div class="d-flex align-items-center gap-1.5 text-warning-emphasis fw-bold small mb-1">
                                        <i class="fa-solid fa-note-sticky text-warning"></i>
                                        <span>Catatan Khusus dari Pemesan:</span>
                                    </div>
                                    <div class="small text-dark fw-medium p-2 bg-white rounded border border-warning-subtle fst-italic">
                                        "{{ $terimaSpecialNotes }}"
                                    </div>
                                </div>
                            @endif

                            <div class="alert alert-info small py-2 px-3 mb-3 border-0 bg-info-subtle text-info-emphasis rounded-3">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Dengan menyetujui pesanan ini, status dialihkan ke <strong>Validasi Pembayaran DP</strong>. Pelanggan akan mendapatkan instruksi pembayaran uang muka di akun mereka.
                            </div>

                            <div class="mb-2">
                                <label class="form-label small fw-bold">Catatan untuk Pelanggan (Opsional):</label>
                                <textarea name="admin_notes" class="form-control rounded-3" rows="3" placeholder="Tuliskan catatan konfirmasi...">{{ $item->admin_notes ?? 'Pesanan telah diperiksa dan disetujui oleh admin. Silakan lakukan pembayaran DP untuk memulai tahap persiapan bahan & produksi.' }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success btn-sm rounded-3 px-4 fw-bold">
                                <i class="fa-solid fa-check me-1"></i> Ya, Terima Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. MODAL TOLAK PESANAN -->
        <div class="modal fade" id="modalTolakPesanan{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-danger text-white rounded-top-4">
                        <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Tolak Pesanan #{{ $item->order_number }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.pesanan.reject', $item->id) }}" method="POST">
                        @csrf
                        <div class="modal-body p-4 text-start">
                            <p class="text-muted small mb-3">Harap berikan alasan penolakan yang jelas agar pelanggan memahami mengapa pesanannya belum dapat diproses.</p>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" class="form-control rounded-3" rows="3" required placeholder="Contoh: Stok bahan kayu jati kualitas A saat ini sedang kosong, atau dimensi yang diajukan melebihi kapasitas pengiriman."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light rounded-bottom-4">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger btn-sm rounded-3 px-4">Tolak Pesanan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 3. MODAL VERIFIKASI PEMBAYARAN DP (DENGAN OPSI TERIMA & TOLAK BUKTI) -->
        <div class="modal fade" id="modalVerifikasiDP{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                        <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-money-bill-wave me-1"></i> Verifikasi Pembayaran DP #{{ $item->order_number }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        <div class="row g-3">
                            <div class="col-md-6 border-end">
                                <h6 class="fw-bold text-dark mb-2">Rincian Tagihan DP</h6>
                                <div class="p-3 bg-light rounded-3 border mb-3">
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Nama Pelanggan:</span>
                                        <strong class="text-dark">{{ $item->recipient_name ?? $item->user->name }}</strong>
                                    </div>
                                    <div class="row g-2 border-top pt-2">
                                        <div class="col-6">
                                            <span class="small text-muted d-block">Tagihan Total:</span>
                                            <strong class="text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="small text-muted d-block">Wajib DP (50%):</span>
                                            <strong class="text-success fs-6">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $dpSpecialNotes = $item->customer_notes ?? ($item->customDesign->notes ?? null);
                                @endphp
                                @if($dpSpecialNotes)
                                    <div class="p-2.5 rounded-3 border border-warning-subtle mb-3" style="background-color: #fffbeb; font-size: 0.82rem;">
                                        <span class="fw-bold text-warning-emphasis d-block mb-1"><i class="fa-solid fa-note-sticky me-1"></i> Catatan Khusus Pemesan:</span>
                                        <div class="fst-italic text-dark bg-white p-2 rounded border border-warning-subtle">
                                            "{{ $dpSpecialNotes }}"
                                        </div>
                                    </div>
                                @endif

                                <!-- Form Tolak Bukti DP -->
                                <div class="p-3 rounded-3 border border-danger-subtle bg-danger-subtle bg-opacity-25 mt-3">
                                    <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Bukti Tidak Valid / Kurang Nominal?</h6>
                                    <p class="small text-muted mb-2">Jika bukti transfer palsu, buram, atau nominal kurang, tolak dengan alasan agar pelanggan dapat mengunggah ulang.</p>
                                    <form action="{{ route('admin.pesanan.reject_dp', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <textarea name="rejection_reason" class="form-control form-control-sm rounded-3" rows="2" required placeholder="Contoh: Nominal transfer kurang Rp 200.000, atau bukti struk buram/tidak terbaca."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 w-100 fw-bold">
                                            <i class="fa-solid fa-ban me-1"></i> Tolak Bukti DP Ini
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-6 text-center">
                                <h6 class="fw-bold text-dark mb-2 text-start">Foto Bukti Transfer Pelanggan</h6>
                                @if($item->dp_receipt_proof)
                                    <div class="p-2 border rounded-3 bg-light text-center">
                                        <a href="{{ asset('storage/' . $item->dp_receipt_proof) }}" target="_blank" title="Klik untuk ukuran asli">
                                            <img src="{{ asset('storage/' . $item->dp_receipt_proof) }}" alt="Bukti DP" class="img-fluid rounded-3 shadow-sm" style="max-height: 260px; object-fit: contain;">
                                        </a>
                                        <div class="mt-1 small text-muted"><i class="fa-solid fa-magnifying-glass me-1"></i> Klik untuk perbesar</div>
                                    </div>
                                @else
                                    <div class="alert alert-warning small p-3 text-start">
                                        <i class="fa-solid fa-info-circle me-1"></i> Pelanggan belum mengunggah foto bukti transfer DP.
                                    </div>
                                @endif

                                <!-- Form Terima Bukti DP -->
                                <form action="{{ route('admin.pesanan.verify_dp', $item->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <p class="small text-muted mb-2 text-start">Pastikan dana telah masuk ke rekening / akun DANA sebelum memverifikasi.</p>
                                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-4 w-100 fw-bold py-2">
                                        <i class="fa-solid fa-check-double me-1"></i> Verifikasi Sah & Masuk Workshop
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. MODAL VERIFIKASI PEMBAYARAN PELUNASAN (DENGAN OPSI TERIMA & TOLAK BUKTI) -->
        <div class="modal fade" id="modalVerifikasiPelunasan{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 shadow-lg border-0">
                    <div class="modal-header bg-success text-white rounded-top-4">
                        <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-receipt me-1"></i> Verifikasi Pelunasan #{{ $item->order_number }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 text-start">
                        <div class="row g-3">
                            <div class="col-md-6 border-end">
                                <h6 class="fw-bold text-dark mb-2">Rincian Sisa Pelunasan</h6>
                                <div class="p-3 bg-light rounded-3 border mb-3">
                                    <div class="mb-2">
                                        <span class="small text-muted d-block">Nama Pelanggan:</span>
                                        <strong class="text-dark">{{ $item->recipient_name ?? $item->user->name }}</strong>
                                    </div>
                                    <div class="row g-2 border-top pt-2">
                                        <div class="col-6">
                                            <span class="small text-muted d-block">Sisa Tagihan:</span>
                                            <strong class="text-danger fs-6">Rp {{ number_format($item->remaining_payment, 0, ',', '.') }}</strong>
                                        </div>
                                        <div class="col-6">
                                            <span class="small text-muted d-block">Status Saat Ini:</span>
                                            <span class="badge bg-warning-subtle text-warning-emphasis">{{ $item->payment_status }}</span>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $lunasSpecialNotes = $item->customer_notes ?? ($item->customDesign->notes ?? null);
                                @endphp
                                @if($lunasSpecialNotes)
                                    <div class="p-2.5 rounded-3 border border-warning-subtle mb-3" style="background-color: #fffbeb; font-size: 0.82rem;">
                                        <span class="fw-bold text-warning-emphasis d-block mb-1"><i class="fa-solid fa-note-sticky me-1"></i> Catatan Khusus Pemesan:</span>
                                        <div class="fst-italic text-dark bg-white p-2 rounded border border-warning-subtle">
                                            "{{ $lunasSpecialNotes }}"
                                        </div>
                                    </div>
                                @endif

                                <!-- Form Tolak Bukti Pelunasan -->
                                <div class="p-3 rounded-3 border border-danger-subtle bg-danger-subtle bg-opacity-25 mt-3">
                                    <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Bukti Tidak Valid?</h6>
                                    <p class="small text-muted mb-2">Jika mutasi pelunasan belum masuk atau salah nominal, tolak dengan alasan berikut:</p>
                                    <form action="{{ route('admin.pesanan.reject_pelunasan', $item->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-2">
                                            <textarea name="rejection_reason" class="form-control form-control-sm rounded-3" rows="2" required placeholder="Contoh: Bukti transfer pelunasan tidak valid / mutasi belum masuk."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 w-100 fw-bold">
                                            <i class="fa-solid fa-ban me-1"></i> Tolak Bukti Pelunasan
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-6 text-center">
                                <h6 class="fw-bold text-dark mb-2 text-start">Foto Bukti Transfer Pelunasan</h6>
                                @if($item->final_receipt_proof)
                                    <div class="p-2 border rounded-3 bg-light text-center">
                                        <a href="{{ asset('storage/' . $item->final_receipt_proof) }}" target="_blank" title="Klik untuk ukuran asli">
                                            <img src="{{ asset('storage/' . $item->final_receipt_proof) }}" alt="Bukti Pelunasan" class="img-fluid rounded-3 shadow-sm" style="max-height: 260px; object-fit: contain;">
                                        </a>
                                        <div class="mt-1 small text-muted"><i class="fa-solid fa-magnifying-glass me-1"></i> Klik untuk perbesar</div>
                                    </div>
                                @else
                                    <div class="alert alert-warning small p-3 text-start">
                                        <i class="fa-solid fa-info-circle me-1"></i> Pelanggan belum mengunggah foto bukti transfer pelunasan.
                                    </div>
                                @endif

                                <!-- Form Terima Bukti Pelunasan -->
                                <form action="{{ route('admin.pesanan.verify_pelunasan', $item->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    <p class="small text-muted mb-2 text-start">Verifikasi pelunasan akan mengubah status pembayaran menjadi <strong>Lunas</strong>.</p>
                                    <button type="submit" class="btn btn-success btn-sm rounded-3 px-4 w-100 fw-bold py-2">
                                        <i class="fa-solid fa-check-double me-1"></i> Verifikasi Lunas & Siap Kirim
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 py-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    @endforeach

</div>
@endsection