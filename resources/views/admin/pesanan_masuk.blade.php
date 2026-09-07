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
    }

    .nav-tabs-order .nav-link {
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.875rem;
        padding: 10px 16px;
        border-radius: 12px 12px 0 0;
        position: relative;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .nav-tabs-order .nav-link:hover {
        color: var(--primary-color);
        background-color: rgba(93, 64, 55, 0.04);
    }

    .nav-tabs-order .nav-link.active {
        color: var(--primary-color);
        background-color: transparent;
        font-weight: 700;
    }

    .nav-tabs-order .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: var(--primary-color);
        border-radius: 3px 3px 0 0;
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
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 14%; text-align: left;">Pesanan & Tanggal</th>
                        <th style="width: 18%; text-align: left;">Pemesan & Kontak</th>
                        <th style="width: 22%; text-align: left;">Spesifikasi Mebel</th>
                        <th style="width: 20%; text-align: left;">Rincian Pembayaran</th>
                        <th style="width: 26%;">Aksi & Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listPesananMasuk as $item)
                    <tr>
                        <!-- 1. PESANAN & TANGGAL -->
                        <td>
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
                                   class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-0.5" 
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
                                <span><i class="fa-solid fa-tree me-1 text-secondary"></i>{{ $item->customDesign->wood_material ?? 'Kayu Jati' }}</span>
                                @if($item->customDesign && $item->customDesign->length_cm)
                                    <span class="ms-1">({{ $item->customDesign->length_cm }}×{{ $item->customDesign->width_cm }}×{{ $item->customDesign->height_cm }} cm)</span>
                                @endif
                            </div>
                            @if($item->customDesign && $item->customDesign->color_name)
                                <div class="small text-muted" style="font-size: 0.78rem;">
                                    <span>Tone: <strong>{{ $item->customDesign->color_name }}</strong></span>
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
                            @if($item->payment_status === 'Lunas')
                                <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill small">
                                    <i class="fa-solid fa-check-double me-1"></i>Lunas
                                </span>
                            @elseif($item->payment_status === 'Menunggu Verifikasi Pelunasan')
                                <span class="badge bg-info-subtle text-info px-2 py-0.5 rounded-pill small border border-info">
                                    <i class="fa-solid fa-receipt me-1"></i>Perlu Verif. Pelunasan
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
                        </td>

                        <!-- 5. AKSI & TINDAKAN -->
                        <td class="text-center">
                            <div class="d-flex flex-column gap-1.5 align-items-center">
                                
                                <!-- Aksi Konfirmasi Awal (Menunggu Konfirmasi) -->
                                @if($item->order_status === 'Menunggu Konfirmasi')
                                    <div class="d-flex gap-1 justify-content-center w-100">
                                        <button type="button" class="btn btn-success btn-action-sm flex-grow-1"
                                                data-bs-toggle="modal" data-bs-target="#modalTerimaPesanan{{ $item->id }}">
                                            <i class="fa-solid fa-check me-1"></i> Terima
                                        </button>
                                        <button type="button" class="btn btn-outline-danger btn-action-sm flex-grow-1"
                                                data-bs-toggle="modal" data-bs-target="#modalTolakPesanan{{ $item->id }}">
                                            <i class="fa-solid fa-xmark me-1"></i> Tolak
                                        </button>
                                    </div>
                                @endif

                                <!-- Aksi Verifikasi Bukti DP -->
                                @if($item->payment_status === 'Menunggu Verifikasi DP' || ($item->dp_receipt_proof && in_array($item->payment_status, ['Menunggu Pembayaran DP', 'Bukti DP Ditolak'])))
                                    <button type="button" class="btn btn-warning text-dark btn-action-sm w-100 shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalVerifikasiDP{{ $item->id }}">
                                        <i class="fa-solid fa-receipt me-1"></i> Verifikasi Bukti DP
                                    </button>
                                @endif

                                <!-- Aksi Verifikasi Pelunasan -->
                                @if($item->payment_status === 'Menunggu Verifikasi Pelunasan' || ($item->final_receipt_proof && in_array($item->payment_status, ['Bukti Pelunasan Ditolak', 'DP Terverifikasi'])))
                                    <button type="button" class="btn btn-info text-white btn-action-sm w-100 shadow-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalVerifikasiPelunasan{{ $item->id }}">
                                        <i class="fa-solid fa-file-invoice-dollar me-1"></i> Verifikasi Pelunasan
                                    </button>
                                @endif

                                <!-- Tombol Kelola Progres Workshop -->
                                @if(!in_array($item->order_status, ['Ditolak', 'Dibatalkan']))
                                    <a href="{{ route('admin.progres.produksi', $item->id) }}" class="btn btn-outline-dark btn-action-sm w-100" style="border-color: var(--light-border);">
                                        <i class="fa-solid fa-hammer me-1 text-warning"></i> Progres Workshop
                                    </a>
                                @endif

                                @if(in_array($item->order_status, ['Ditolak', 'Dibatalkan']) && $item->rejection_reason)
                                    <div class="alert alert-danger p-1.5 mb-0 text-start w-100" style="font-size: 0.75rem; border-radius: 8px;">
                                        <strong>Alasan:</strong> {{ $item->rejection_reason }}
                                    </div>
                                @endif

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