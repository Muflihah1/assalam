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

    .order-sidebar-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 16px;
    }

    /* Stepper Timeline Admin */
    .workshop-stepper {
        position: relative;
        padding-left: 36px;
    }

    .workshop-stepper::before {
        content: '';
        position: absolute;
        top: 14px;
        bottom: 14px;
        left: 15px;
        width: 3px;
        background-color: var(--light-border);
        border-radius: 3px;
    }

    .step-item {
        position: relative;
        padding-bottom: 24px;
    }

    .step-item:last-child {
        padding-bottom: 0;
    }

    .step-node {
        position: absolute;
        left: -36px;
        top: 0;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        z-index: 2;
        transition: all 0.3s ease;
    }

    .step-node.completed {
        background-color: #16a34a;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.2);
    }

    .step-node.active {
        background-color: var(--primary-color);
        color: #ffffff;
        box-shadow: 0 0 0 5px rgba(93, 64, 55, 0.25);
        animation: pulseNode 2s infinite;
    }

    .step-node.locked {
        background-color: #e5e7eb;
        color: #9ca3af;
        border: 1px solid #d1d5db;
    }

    .step-node.next {
        background-color: #ffffff;
        color: var(--primary-color);
        border: 2.5px solid var(--primary-color);
    }

    @keyframes pulseNode {
        0%, 100% { box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(217, 119, 6, 0.15); }
    }

    .step-content {
        background-color: #ffffff;
        border: 1.5px solid var(--light-border);
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.2s ease;
    }

    .step-content.active {
        border-color: var(--primary-color);
        background-color: rgba(93, 64, 55, 0.03);
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.06);
    }

    .media-thumb-gallery {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--light-border);
        cursor: pointer;
        transition: transform 0.2s;
    }

    .media-thumb-gallery:hover {
        transform: scale(1.08);
        border-color: var(--primary-color);
    }

    .dropzone-box {
        border: 2px dashed var(--wood-border);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background-color: var(--light-bg);
        cursor: pointer;
        transition: all 0.2s;
    }

    .dropzone-box:hover {
        border-color: var(--primary-color);
        background-color: #ffffff;
    }

    /* SEARCHABLE ORDER DROPDOWN */
    .order-picker-dropdown {
        position: relative;
    }

    .order-picker-btn {
        background-color: #ffffff;
        border: 1.5px solid var(--light-border);
        border-radius: 12px;
        padding: 8px 14px;
        min-width: 280px;
        max-width: 420px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
    }

    .order-picker-btn:hover, .order-picker-btn:focus {
        border-color: var(--primary-color);
        box-shadow: 0 4px 14px rgba(93, 64, 55, 0.12);
        background-color: #ffffff;
    }

    .order-picker-menu {
        min-width: 360px;
        max-width: 440px;
        border-radius: 18px;
        border: 1.5px solid var(--light-border);
        box-shadow: 0 12px 35px rgba(93, 64, 55, 0.16);
        padding: 14px;
        z-index: 1060;
    }

    .order-picker-list {
        max-height: 320px;
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    .order-picker-item {
        display: block;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid transparent;
        text-decoration: none;
        color: var(--text-dark);
        margin-bottom: 4px;
        transition: all 0.15s ease;
    }

    .order-picker-item:hover {
        background-color: var(--wood-bg);
        border-color: var(--wood-border);
        color: var(--primary-color);
    }

    .order-picker-item.active {
        background-color: rgba(93, 64, 55, 0.08);
        border-color: var(--primary-color);
    }
</style>

<div class="container-fluid px-0 py-2">

    <!-- TOP BAR DENGAN DROPDOWN PEMILIH PESANAN (SEARCHABLE) -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">KONSOL PROGRES WORKSHOP PRODUKSI</h4>
            <p class="text-muted small mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Dokumentasikan tahapan fisik pengerjaan mebel ukir secara berurutan dan kirim bukti foto ke akun pelanggan.</p>
        </div>
        
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @if(isset($allOrders) && $allOrders->count() > 0)
                @php
                    $countWorkshop = $allOrders->whereIn('production_status', ['Antrean Produksi', 'Dalam Pengerjaan', 'Penyelesaian', 'Pengiriman'])->count();
                    $countDP = $allOrders->whereIn('payment_status', ['Menunggu Pembayaran DP', 'Menunggu Verifikasi DP', 'Bukti DP Ditolak'])->count();
                    $countSelesai = $allOrders->filter(function($it) {
                        return $it->order_status === 'Selesai' || $it->production_status === 'Selesai';
                    })->count();
                @endphp

                <div class="dropdown order-picker-dropdown" id="orderPickerDropdown">
                    <button class="order-picker-btn shadow-sm d-flex align-items-center justify-content-between gap-2"
                            type="button" 
                            id="orderSelectTrigger" 
                            data-bs-toggle="dropdown" 
                            data-bs-auto-close="true"
                            aria-expanded="false"
                            title="Klik untuk memilih pesanan atau mencari pelanggan">
                        <div class="d-flex align-items-center gap-2.5 text-truncate">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" 
                                 style="width: 34px; height: 34px; background-color: var(--primary-color); font-size: 0.85rem;">
                                <i class="fa-solid fa-receipt"></i>
                            </div>
                            <div class="text-truncate text-start">
                                @if(isset($progres))
                                    <div class="fw-bold text-dark small text-truncate">
                                        #{{ $progres->order_number }} - {{ $progres->recipient_name ?? $progres->user->name }}
                                    </div>
                                    <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                                        Tahap: <strong class="text-primary">{{ $progres->current_stage ?? 'Tahap 1' }}</strong>
                                    </div>
                                @else
                                    <div class="fw-bold text-dark small">Pilih Pesanan Pelanggan...</div>
                                    <div class="text-muted small" style="font-size: 0.72rem;">{{ $allOrders->count() }} pesanan tersedia</div>
                                @endif
                            </div>
                        </div>
                        <i class="fa-solid fa-chevron-down text-muted small ms-2"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end order-picker-menu p-3 shadow-lg" aria-labelledby="orderSelectTrigger">
                        <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom">
                            <span class="small fw-bold text-uppercase" style="color: var(--primary-color); font-size: 0.78rem;">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Cari & Pilih Pesanan
                            </span>
                            <span class="badge rounded-pill bg-light text-dark border" style="font-size: 0.72rem;">
                                {{ $allOrders->count() }} Total
                            </span>
                        </div>

                        <!-- INPUT PENCARIAN REAL-TIME -->
                        <div class="input-group input-group-sm mb-2 shadow-none">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" 
                                   id="orderSearchInput" 
                                   class="form-control border-start-0" 
                                   placeholder="Ketik no pesanan, nama, mebel..." 
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary btn-sm" type="button" id="btnOrderSearchReset" style="display: none;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <!-- FILTER CEPAT STATUS -->
                        <div class="d-flex gap-1 mb-2 pb-2 border-bottom overflow-x-auto" style="scrollbar-width: none;">
                            <button type="button" class="btn btn-xs btn-dark rounded-pill px-2.5 py-0.5 filter-order-btn active" data-filter="all" style="font-size: 0.72rem;">Semua ({{ $allOrders->count() }})</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-0.5 filter-order-btn" data-filter="workshop" style="font-size: 0.72rem;">Workshop ({{ $countWorkshop }})</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-0.5 filter-order-btn" data-filter="dp" style="font-size: 0.72rem;">Menunggu DP ({{ $countDP }})</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-0.5 filter-order-btn" data-filter="selesai" style="font-size: 0.72rem;">Selesai ({{ $countSelesai }})</button>
                        </div>

                        <!-- DAFTAR PESANAN DENGAN INSTANT SEARCH -->
                        <div class="order-picker-list" id="orderPickerList">
                            @foreach($allOrders as $o)
                                @php
                                    $isSelected = (isset($progres) && $progres->id == $o->id);
                                    $custName = $o->recipient_name ?? $o->user->name ?? 'Pelanggan';
                                    $custPhone = $o->recipient_phone ?? $o->user->whatsapp_number ?? '';
                                    $category = $o->customDesign->category ?? 'Mebel Custom';
                                    $stage = $o->current_stage ?? 'Tahap 1';
                                    
                                    // Tentukan kelompok status untuk filter
                                    $group = 'lainnya';
                                    if (in_array($o->production_status, ['Antrean Produksi', 'Dalam Pengerjaan', 'Penyelesaian', 'Pengiriman'])) {
                                        $group = 'workshop';
                                    } elseif (in_array($o->payment_status, ['Menunggu Pembayaran DP', 'Menunggu Verifikasi DP', 'Bukti DP Ditolak'])) {
                                        $group = 'dp';
                                    } elseif ($o->order_status === 'Selesai' || $o->production_status === 'Selesai') {
                                        $group = 'selesai';
                                    }
                                @endphp
                                <a href="{{ route('admin.progres.produksi', $o->id) }}" 
                                   class="order-picker-item {{ $isSelected ? 'active' : '' }}"
                                   data-group="{{ $group }}"
                                   data-search="{{ strtolower($o->order_number . ' ' . $custName . ' ' . $custPhone . ' ' . $category . ' ' . $stage . ' ' . $o->production_status) }}">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-dark small">#{{ $o->order_number }}</strong>
                                        @if($isSelected)
                                            <span class="badge bg-success text-white rounded-pill" style="font-size: 0.65rem;">
                                                <i class="fa-solid fa-check me-1"></i>Sedang Dibuka
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">
                                                {{ $o->created_at ? $o->created_at->format('d/m/y') : '-' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="small fw-semibold text-truncate text-dark mb-1" style="font-size: 0.82rem;">
                                        {{ $custName }}
                                        @if($custPhone)
                                            <span class="text-muted fw-normal" style="font-size: 0.72rem;">({{ $custPhone }})</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-1" style="font-size: 0.72rem;">
                                        <span class="text-muted text-truncate" style="max-width: 180px;">
                                            <i class="fa-solid fa-couch text-secondary me-1"></i>{{ $category }}
                                        </span>
                                        <span class="badge px-2 py-0.5 rounded-pill {{ $isSelected ? 'bg-primary text-white' : 'bg-warning-subtle text-warning-emphasis' }}">
                                            {{ $stage }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach

                            <div id="orderSearchEmpty" class="text-center py-4 text-muted small" style="display: none;">
                                <i class="fa-solid fa-magnifying-glass fa-2x mb-2 text-secondary d-block"></i>
                                Tidak ada pesanan yang sesuai dengan pencarian Anda.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-2 fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i> Pesanan Masuk
            </a>
        </div>
    </div>

    @if(!$progres)
        <div class="admin-card text-center py-5">
            <i class="fa-solid fa-hammer fa-3x text-muted mb-3"></i>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Pesanan yang Perlu Dikerjakan</h5>
            <p class="text-muted small mb-3">Pesanan yang telah disetujui dan dibayar uang mukanya (DP) akan otomatis siap diproses di sini.</p>
            <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-dark rounded-3 px-4 py-2" style="background-color: var(--primary-color); border: none;">
                <i class="fa-solid fa-inbox me-1"></i> Cek Pesanan Masuk
            </a>
        </div>
    @else
        @php
            $stageMap = [
                'Konfirmasi Pesanan' => 1,
                'Validasi Pembayaran' => 2,
                'Pesanan Diterima' => 3,
                'Menyiapkan Bahan' => 4,
                'Perakitan' => 5,
                'Penyelesaian' => 6,
                'Pengiriman' => 7,
                'Pesanan Selesai' => 8,
            ];

            $stageList = [
                1 => ['name' => 'Konfirmasi Pesanan', 'desc' => 'Admin meninjau dan menyetujui pesanan', 'icon' => 'fa-clipboard-check'],
                2 => ['name' => 'Validasi Pembayaran', 'desc' => 'Pelanggan transfer DP & admin memverifikasi', 'icon' => 'fa-receipt'],
                3 => ['name' => 'Pesanan Diterima', 'desc' => 'DP sah, pesanan masuk antrean workshop', 'icon' => 'fa-box-archive'],
                4 => ['name' => 'Menyiapkan Bahan', 'desc' => 'Pemotongan, oven pengeringan kayu jati solid', 'icon' => 'fa-tree'],
                5 => ['name' => 'Perakitan', 'desc' => 'Penyambungan purus kayu & ukiran khas Madura', 'icon' => 'fa-hammer'],
                6 => ['name' => 'Penyelesaian', 'desc' => 'Finishing amplas, melamin, busa & pelunasan', 'icon' => 'fa-spray-can-sparkles'],
                7 => ['name' => 'Pengiriman', 'desc' => 'Packing kayu/kardus tebal & muat armada kargo', 'icon' => 'fa-truck-fast'],
                8 => ['name' => 'Pesanan Selesai', 'desc' => 'Mebel diterima oleh pelanggan di lokasi', 'icon' => 'fa-circle-check'],
            ];

            $currentStageName = $progres->current_stage ?? 'Konfirmasi Pesanan';
            $currentStepNumber = $stageMap[$currentStageName] ?? 1;
            $isDpVerified = in_array($progres->payment_status, ['DP Terverifikasi', 'Lunas']);
            $nextStepNumber = $currentStepNumber < 8 ? $currentStepNumber + 1 : null;
            $nextStageName = $nextStepNumber ? $stageList[$nextStepNumber]['name'] : null;

            // Cari data OrderProgress untuk tahap aktif saat ini
            $activeProgressRecord = $progres->progresses->where('step_number', $currentStepNumber)->first();
            $existingMedia = $activeProgressRecord ? ($activeProgressRecord->media_files ?? []) : [];
        @endphp

        <div class="row g-4">
            
            <!-- ==============================================
                 KOLOM KIRI: DETAIL PESANAN, PEMESAN & FINANSIAL
                 ============================================== -->
            <div class="col-lg-4">
                
                <!-- 1. IDENTITAS PESANAN -->
                <div class="order-sidebar-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <span class="small fw-bold text-muted text-uppercase">No. Pesanan</span>
                        <span class="badge px-2.5 py-1 rounded-pill" style="background-color: var(--primary-color); color: #ffffff;">
                            #{{ $progres->order_number }}
                        </span>
                    </div>
                    <div class="small text-muted mb-1">Tanggal Pesan: {{ $progres->created_at ? $progres->created_at->format('d M Y H:i') : '-' }}</div>
                    <div class="small text-muted">Status Pesanan: <strong class="text-dark">{{ $progres->order_status }}</strong></div>
                </div>

                <!-- 2. PEMESAN & PENGIRIMAN -->
                <div class="order-sidebar-card shadow-sm">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: var(--primary-color) !important;">
                        <i class="fa-solid fa-user me-1"></i> Data Pemesan
                    </h6>
                    <div class="mb-2">
                        <span class="small text-muted d-block">Nama Lengkap:</span>
                        <strong class="text-dark fs-6">{{ $progres->recipient_name ?? $progres->user->name }}</strong>
                    </div>
                    <div class="mb-2">
                        <span class="small text-muted d-block">Kontak WhatsApp:</span>
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $progres->recipient_phone ?? $progres->user->whatsapp_number ?? '');
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                        @endphp
                        @if($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($progres->recipient_name ?? $progres->user->name) }},%20pembaruan%20progres%20pesanan%20mebel%20%23{{ $progres->order_number }}" 
                               target="_blank" 
                               class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 mt-1">
                                <i class="fa-brands fa-whatsapp me-1"></i> {{ $progres->recipient_phone ?? $progres->user->whatsapp_number }}
                            </a>
                        @else
                            <span class="small text-muted">-</span>
                        @endif
                    </div>
                    <div>
                        <span class="small text-muted d-block">Alamat Pengiriman:</span>
                        <p class="small text-dark mb-0 bg-light p-2 rounded-3 border">
                            {{ $progres->shipping_address ?? $progres->user->alamat ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- 3. SPESIFIKASI MEBEL -->
                <div class="order-sidebar-card shadow-sm">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: var(--primary-color) !important;">
                        <i class="fa-solid fa-couch me-1"></i> Spesifikasi Mebel
                    </h6>
                    @php
                        $thumbUrl = null;
                        if ($progres->customDesign && $progres->customDesign->sketch_image) {
                            $thumbUrl = asset('storage/' . $progres->customDesign->sketch_image);
                        } elseif ($progres->customDesign && $progres->customDesign->produk && $progres->customDesign->produk->foto_url) {
                            $thumbUrl = $progres->customDesign->produk->foto_url;
                        } elseif ($progres->items && $progres->items->first() && $progres->items->first()->image) {
                            $thumbUrl = asset($progres->items->first()->image);
                        }
                    @endphp
                    @if($thumbUrl)
                        <div class="text-center mb-3">
                            <img src="{{ $thumbUrl }}" alt="Mebel" class="img-fluid rounded-3 border shadow-sm" style="max-height: 140px; object-fit: cover; width: 100%;">
                        </div>
                    @endif

                    <div class="mb-2">
                        <span class="small text-muted d-block">Model / Kategori:</span>
                        <strong class="text-dark">{{ $progres->customDesign->category ?? 'Custom Mebel' }}</strong>
                    </div>
                    <div class="mb-2">
                        <span class="small text-muted d-block">Material Kayu Solid:</span>
                        <span class="badge bg-light text-dark border"><i class="fa-solid fa-tree text-secondary me-1"></i>{{ $progres->customDesign->wood_material ?? 'Kayu Jati' }}</span>
                    </div>
                    @if($progres->customDesign && $progres->customDesign->length_cm)
                        <div class="mb-2">
                            <span class="small text-muted d-block">Dimensi Presisi:</span>
                            <span class="small text-dark">{{ $progres->customDesign->length_cm }} cm (P) × {{ $progres->customDesign->width_cm }} cm (L) × {{ $progres->customDesign->height_cm }} cm (T)</span>
                        </div>
                    @endif
                    @if($progres->customDesign && $progres->customDesign->color_name)
                        <div class="mb-2">
                            <span class="small text-muted d-block">Warna Finishing:</span>
                            <span class="small text-dark fw-bold">{{ $progres->customDesign->color_name }}</span>
                        </div>
                    @endif
                    @php
                        $workshopNote = $progres->customer_notes ?? ($progres->customDesign->notes ?? null);
                    @endphp
                    @if($workshopNote)
                        <div class="mt-2 pt-2 border-top">
                            <div class="p-2.5 rounded-3 border border-warning" style="background-color: #fffbeb;">
                                <div class="d-flex align-items-center gap-1.5 mb-1 text-warning-emphasis fw-bold small">
                                    <i class="fa-solid fa-note-sticky text-warning"></i>
                                    <span>Catatan Khusus Pemesan:</span>
                                </div>
                                <p class="small text-dark mb-0 fst-italic fw-medium bg-white p-2 rounded border border-warning-subtle">
                                    "{{ $workshopNote }}"
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 4. STATUS FINANSIAL & DP -->
                <div class="order-sidebar-card shadow-sm">
                    <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="color: var(--primary-color) !important;">
                        <i class="fa-solid fa-wallet me-1"></i> Status Finansial
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Total Tagihan:</span>
                        <strong class="text-dark">Rp {{ number_format($progres->total_price, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Uang Muka (DP 50%):</span>
                        <strong class="text-success">Rp {{ number_format($progres->dp_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="small text-muted">Sisa Pelunasan:</span>
                        <strong class="text-danger">Rp {{ number_format($progres->remaining_payment, 0, ',', '.') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top mt-2">
                        <span class="small text-muted">Status Bayar:</span>
                        @if($progres->payment_status === 'Lunas')
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill small">Lunas</span>
                        @elseif($isDpVerified)
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill small">DP Terverifikasi</span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 rounded-pill small">{{ $progres->payment_status }}</span>
                        @endif
                    </div>

                    @if(!$isDpVerified)
                        <div class="alert alert-warning small p-2 mb-0 mt-3 border-0 rounded-3">
                            <i class="fa-solid fa-lock me-1"></i> Tahapan pengerjaan fisik kayu (Tahap 4 s/d 8) <strong>terkunci</strong> hingga pembayaran DP diverifikasi di Pesanan Masuk.
                        </div>
                    @endif
                </div>

            </div>

            <!-- ==============================================
                 KOLOM KANAN: STEPPER TIMELINE & KONSOL KONTROL
                 ============================================== -->
            <div class="col-lg-8">
                
                <!-- 1. TIMELINE STEPPER BERURUTAN (1 S/D 8) -->
                <div class="admin-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">
                                <i class="fa-solid fa-timeline me-1" style="color: var(--primary-color);"></i> Alur Tahapan Workshop
                            </h5>
                            <span class="small text-muted">Tahapan pengerjaan berjalan secara berurutan dan terdata rapi.</span>
                        </div>
                        <div>
                            <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: var(--wood-bg); color: var(--primary-color); border: 1px solid var(--wood-border);">
                                Tahap Aktif: {{ $currentStepNumber }}. {{ $currentStageName }}
                            </span>
                        </div>
                    </div>

                    <div class="workshop-stepper">
                        @foreach($stageList as $stepNo => $stageInfo)
                            @php
                                $isCompleted = $stepNo < $currentStepNumber;
                                $isActive = $stepNo == $currentStepNumber;
                                $isNext = $stepNo == $currentStepNumber + 1;
                                $isLocked = ($stepNo >= 4 && !$isDpVerified) || ($stepNo > $currentStepNumber + 1);
                                $progressRecord = $progres->progresses->where('step_number', $stepNo)->first();
                            @endphp

                            <div class="step-item">
                                <!-- NODE BULAT -->
                                @if($isCompleted)
                                    <div class="step-node completed" title="Tahap Selesai">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                @elseif($isActive)
                                    <div class="step-node active" title="Tahap Sedang Berjalan">
                                        {{ $stepNo }}
                                    </div>
                                @elseif($isNext && !$isLocked)
                                    <div class="step-node next" title="Tahap Selanjutnya">
                                        {{ $stepNo }}
                                    </div>
                                @else
                                    <div class="step-node locked" title="Terkunci">
                                        <i class="fa-solid fa-lock" style="font-size: 0.75rem;"></i>
                                    </div>
                                @endif

                                <!-- KONTEN CARD PER STEP -->
                                <div class="step-content {{ $isActive ? 'active' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa-solid {{ $stageInfo['icon'] }} {{ $isActive ? 'text-warning' : ($isCompleted ? 'text-success' : 'text-muted') }}"></i>
                                            <strong class="text-dark fs-6">{{ $stepNo }}. {{ $stageInfo['name'] }}</strong>
                                        </div>
                                        <div>
                                            @if($isCompleted)
                                                <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill small">
                                                    <i class="fa-solid fa-check me-1"></i>Selesai 
                                                    @if($progressRecord && $progressRecord->completed_at)
                                                        ({{ $progressRecord->completed_at->format('d/m H:i') }})
                                                    @endif
                                                </span>
                                            @elseif($isActive)
                                                <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill small fw-bold">
                                                    <i class="fa-solid fa-gear fa-spin me-1"></i>Sedang Berjalan
                                                </span>
                                            @elseif($isLocked)
                                                <span class="badge bg-light text-muted border px-2 py-0.5 rounded-pill small">
                                                    <i class="fa-solid fa-lock me-1"></i>Terkunci
                                                </span>
                                            @elseif($isNext)
                                                <span class="badge bg-primary-subtle text-primary px-2 py-0.5 rounded-pill small">
                                                    Tahap Selanjutnya
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="small text-muted mb-1 mt-1">{{ $stageInfo['desc'] }}</p>

                                    <!-- Foto dokumentasi di tahap ini jika ada -->
                                    @if($progressRecord && !empty($progressRecord->media_files))
                                        <div class="d-flex gap-2 mt-2 pt-2 border-top flex-wrap align-items-center">
                                            <span class="small text-muted" style="font-size: 0.75rem;"><i class="fa-solid fa-images me-1 text-primary"></i>Dokumentasi:</span>
                                            @foreach($progressRecord->media_files as $mFile)
                                                <a href="{{ asset('storage/' . $mFile) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $mFile) }}" alt="Bukti" class="rounded-2 border" style="width: 44px; height: 44px; object-fit: cover;">
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($progressRecord && $progressRecord->notes)
                                        <div class="small text-secondary mt-1 fst-italic" style="font-size: 0.78rem;">
                                            Catatan: "{{ $progressRecord->notes }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 2. KONSOL PENGELOLAAN TAHAP AKTIF SAAT INI -->
                <div class="admin-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                        <div>
                            <span class="badge bg-warning-subtle text-warning-emphasis px-2.5 py-1 rounded-pill small fw-bold mb-1">
                                KONSOL WORKSHOP AKTIF
                            </span>
                            <h5 class="fw-bold text-dark mb-0">
                                Tahap {{ $currentStepNumber }}: {{ $currentStageName }}
                            </h5>
                        </div>
                        <span class="small text-muted">
                            <i class="fa-brands fa-whatsapp text-success me-1"></i> Foto & catatan langsung disinkronkan ke pelanggan
                        </span>
                    </div>

                    <!-- Galeri Dokumentasi Tahap Ini -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark mb-2">Dokumentasi Terunggah Pada Tahap Ini:</label>
                        @if(empty($existingMedia))
                            <div class="p-3 bg-light rounded-3 text-center border text-muted small">
                                <i class="fa-solid fa-camera-retro fa-2x mb-1 d-block text-secondary"></i>
                                Belum ada berkas foto/video yang diunggah untuk tahap <strong>{{ $currentStageName }}</strong>.
                            </div>
                        @else
                            <div class="d-flex gap-2.5 flex-wrap p-2.5 bg-light rounded-3 border">
                                @foreach($existingMedia as $mediaItem)
                                    <a href="{{ asset('storage/' . $mediaItem) }}" target="_blank" title="Klik untuk memperbesar">
                                        <img src="{{ asset('storage/' . $mediaItem) }}" alt="Dokumentasi Progres" class="media-thumb-gallery shadow-sm">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Form Update Catatan & Upload Media Baru untuk Tahap Ini -->
                    <form action="{{ route('admin.progres.update', $progres->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="tahap" value="{{ $currentStageName }}">

                        <div class="row g-3 mb-4">
                            <div class="col-md-5">
                                <label class="form-label small fw-bold text-dark">Tambah Berkas Foto/Video Baru:</label>
                                <input type="file" name="media[]" class="form-control rounded-3" multiple accept="image/*,video/*">
                                <small class="text-muted" style="font-size: 0.75rem;">Bisa memilih beberapa file foto (JPG, PNG, WEBP) atau video pengerjaan.</small>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small fw-bold text-dark">Catatan Pengerjaan untuk Pelanggan:</label>
                                <textarea name="catatan" class="form-control rounded-3" rows="3" placeholder="Tuliskan catatan progres pengerjaan furniture...">{{ $activeProgressRecord->notes ?? $progres->admin_notes ?? 'Proses pengerjaan berjalan dengan lancar sesuai spesifikasi kayu jati solid.' }}</textarea>
                            </div>
                        </div>

                        <!-- TOMBOL KONTROL AKSI -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top flex-wrap gap-2">
                            <!-- Tombol 1: Simpan di Tahap Ini -->
                            <button type="submit" class="btn btn-outline-dark btn-sm rounded-3 px-3 py-2 fw-semibold">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Catatan & Foto Tahap Ini
                            </button>
                        </form>

                        <!-- Tombol 2: Maju ke Tahap Berikutnya -->
                        @if($nextStepNumber)
                            @php
                                $isNextLocked = ($nextStepNumber >= 4 && !$isDpVerified);
                            @endphp

                            @if($isNextLocked)
                                <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3 py-2 disabled" title="Terkunci: Pembayaran DP belum diverifikasi">
                                    <i class="fa-solid fa-lock me-1"></i> Lanjut: {{ $nextStageName }} (Terkunci DP)
                                </button>
                            @else
                                <button type="button" class="btn btn-success btn-sm rounded-3 px-4 py-2 fw-bold shadow-sm" 
                                        data-bs-toggle="modal" data-bs-target="#modalAdvanceStage">
                                    <i class="fa-solid fa-forward-step me-1"></i> Selesaikan & Lanjut: {{ $nextStageName }} →
                                </button>
                            @endif
                        @else
                            <span class="badge bg-success px-3 py-2 rounded-pill fs-6">
                                <i class="fa-solid fa-flag-checkered me-1"></i> Pesanan Telah Selesai
                            </span>
                        @endif
                    </div>
                </div>

            </div>

        </div>

        <!-- MODAL KONFIRMASI MAJU KE TAHAP BERIKUTNYA -->
        @if($nextStepNumber && !$isNextLocked)
            <div class="modal fade" id="modalAdvanceStage" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow-lg border-0">
                        <div class="modal-header bg-success text-white rounded-top-4">
                            <h5 class="modal-title fs-6 fw-bold">
                                <i class="fa-solid fa-forward-step me-2"></i> Lanjutkan ke Tahap {{ $nextStepNumber }}: {{ $nextStageName }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('admin.progres.update', $progres->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="tahap" value="{{ $nextStageName }}">

                            <div class="modal-body p-4 text-start">
                                <p class="text-dark small mb-3">
                                    Apakah Anda yakin ingin menyelesaikan tahap <strong>{{ $currentStageName }}</strong> dan memajukan pesanan <strong>#{{ $progres->order_number }}</strong> ke tahap <strong>{{ $nextStageName }}</strong>?
                                </p>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Unggah Bukti Dokumentasi untuk {{ $nextStageName }} (Opsional):</label>
                                    <input type="file" name="media[]" class="form-control rounded-3" multiple accept="image/*,video/*">
                                    <small class="text-muted" style="font-size: 0.75rem;">Dapat mengunggah foto proses kayu / perakitan / finishing.</small>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small fw-bold">Catatan Workshop Tahap {{ $nextStageName }}:</label>
                                    <textarea name="catatan" class="form-control rounded-3" rows="3" placeholder="Tuliskan catatan progres...">Memasuki tahap {{ strtolower($nextStageName) }}. Bahan dan pengerjaan dilakukan sesuai standar kualitas mebel solid.</textarea>
                                </div>
                            </div>
                            <div class="modal-footer bg-light rounded-bottom-4">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success btn-sm rounded-3 px-4 fw-bold">
                                    <i class="fa-solid fa-check me-1"></i> Ya, Lanjutkan Tahap
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggle = document.getElementById('orderSelectTrigger');
    const searchInput = document.getElementById('orderSearchInput');
    const searchReset = document.getElementById('btnOrderSearchReset');
    const orderItems = document.querySelectorAll('.order-picker-item');
    const emptyNotice = document.getElementById('orderSearchEmpty');
    const filterBtns = document.querySelectorAll('.filter-order-btn');

    let currentFilter = 'all';

    // Auto-focus input pencarian saat dropdown dibuka
    if (dropdownToggle && searchInput) {
        dropdownToggle.addEventListener('shown.bs.dropdown', function () {
            searchInput.focus();
        });
    }

    // Fungsi filter daftar pesanan secara realtime
    function filterOrderList() {
        const query = (searchInput.value || '').trim().toLowerCase();
        let visibleCount = 0;

        orderItems.forEach(item => {
            const searchText = item.getAttribute('data-search') || '';
            const itemGroup = item.getAttribute('data-group') || '';

            const matchesQuery = query === '' || searchText.includes(query);
            const matchesFilter = currentFilter === 'all' || itemGroup === currentFilter;

            if (matchesQuery && matchesFilter) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        if (emptyNotice) {
            emptyNotice.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        if (searchReset) {
            searchReset.style.display = query.length > 0 ? 'block' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterOrderList);
        
        // Mencegah dropdown tertutup saat mengklik input teks
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        searchInput.addEventListener('keydown', function(e) {
            e.stopPropagation();
        });
    }

    if (searchReset) {
        searchReset.addEventListener('click', function (e) {
            e.stopPropagation();
            searchInput.value = '';
            filterOrderList();
            searchInput.focus();
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.stopPropagation(); // Biarkan dropdown tetap terbuka saat klik pill filter
            filterBtns.forEach(b => {
                b.classList.remove('active', 'btn-dark');
                b.classList.add('btn-outline-secondary');
            });
            this.classList.add('active', 'btn-dark');
            this.classList.remove('btn-outline-secondary');

            currentFilter = this.getAttribute('data-filter') || 'all';
            filterOrderList();
        });
    });
});
</script>
@endsection