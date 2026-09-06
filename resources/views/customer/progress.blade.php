@extends('layouts.customer')

@section('content')
<style>
    .wireframe-card {
        background-color: var(--light-card, #ffffff);
        border: 1.5px solid var(--light-border, #e5e7eb);
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.06);
        padding: 24px;
        margin-bottom: 20px;
    }

    /* HORIZONTAL TIMELINE */
    .timeline-scroll-container {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        gap: 12px;
        overflow-x: auto;
        padding-bottom: 10px;
    }

    .timeline-step-card {
        background-color: #fdfaf6;
        border: 1.5px solid var(--wood-border, #d1d5db);
        border-radius: 14px;
        padding: 12px 8px;
        flex: 1;
        min-width: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .timeline-step-card:hover {
        border-color: var(--primary-color, #2563eb);
        background-color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(93, 64, 55, 0.12);
    }

    .timeline-step-card.active-step {
        border-color: var(--primary-color, #2563eb);
        background-color: #ffffff;
        box-shadow: 0 0 0 2px var(--primary-color, #2563eb), 0 6px 15px rgba(93, 64, 55, 0.15);
    }

    .upload-date-label {
        font-size: 0.7rem;
        font-weight: 700;
        margin-bottom: 6px;
        min-height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }

    .step-img-box {
        width: 100%;
        height: 100px;
        background-color: #ffffff;
        border: 1px solid var(--light-border, #e5e7eb);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 8px;
        position: relative;
    }

    .step-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .step-title-label {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--text-dark, #1f2937);
        line-height: 1.2;
    }

    .btn-orange-outline {
        border: 2px solid var(--primary-color, #d97706);
        color: var(--primary-color, #d97706);
        background: transparent;
        font-weight: 700;
        border-radius: 30px;
        padding: 8px 30px;
        transition: all 0.2s;
    }

    .btn-orange-outline:hover {
        background: var(--primary-color, #d97706);
        color: #ffffff;
    }

    .btn-action-dark {
        border: 1.5px solid var(--wood-border, #d1d5db);
        background-color: var(--wood-bg, #f3f4f6);
        color: var(--text-dark, #1f2937);
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: all 0.2s;
    }

    .btn-action-dark:hover {
        border-color: var(--primary-color, #2563eb);
        background-color: #e5e7eb;
        color: var(--primary-color, #2563eb);
    }
</style>

<div class="container-fluid px-2 px-md-4 py-3">

    @if(!$order)
        <!-- JIKA BELUM ADA PESANAN -->
        <div class="wireframe-card text-center py-5">
            <div class="mb-3">
                <i class="fa-solid fa-clock-rotate-left fa-3x text-muted"></i>
            </div>
            <h4 class="fw-bold text-dark mb-2">Belum Ada Progres Pesanan Aktif</h4>
            <p class="text-muted small mb-4">Anda belum memiliki pesanan mebel yang sedang diproses. Silakan rancang furniture impian Anda di Studio Custom.</p>
            <a href="{{ route('customer.design') }}" class="btn btn-dark px-4 py-2.5 rounded-3 fw-bold">
                <i class="fa-solid fa-pen-ruler me-1"></i> Buat Desain Custom Sekarang
            </a>
        </div>
    @else
<<<<<<< Updated upstream
        <!-- ALERT STATUS PESANAN (KONFIRMASI / TOLAK / PEMBAYARAN DP) -->
        @if($order->order_status === 'Menunggu Konfirmasi')
            <div class="alert alert-warning border-0 rounded-4 p-3 mb-3 shadow-sm d-flex align-items-center gap-3">
                <i class="fa-solid fa-hourglass-half fa-2x text-warning"></i>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Pesanan Sedang Menunggu Konfirmasi Admin</h6>
                    <p class="small text-muted mb-0">Pesanan custom Anda telah kami terima dan sedang ditinjau oleh tim kami. Anda akan menerima notifikasi dan instruksi pembayaran DP setelah pesanan disetujui.</p>
                </div>
            </div>
        @elseif($order->order_status === 'Ditolak')
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-3 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <i class="fa-solid fa-circle-xmark fa-2x text-danger mt-1"></i>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1 text-danger">Pesanan Tidak Dapat Diterima</h6>
                        <p class="small mb-2 text-dark"><strong>Alasan Penolakan dari Admin:</strong> {{ $order->rejection_reason ?? 'Kapasitas produksi / stok bahan kayu saat ini tidak mencukupi.' }}</p>
                        <a href="{{ route('customer.design') }}" class="btn btn-sm btn-outline-danger rounded-3 fw-bold">
                            <i class="fa-solid fa-pen-ruler me-1"></i> Ajukan Desain Baru
                        </a>
                    </div>
                </div>
            </div>
        @elseif($order->order_status === 'Diterima' && $order->payment_status === 'Menunggu Pembayaran DP')
            <div class="alert alert-info border-0 rounded-4 p-3 mb-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-circle-check fa-2x text-info"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Pesanan Diterima! Silakan Bayar Uang Muka (DP)</h6>
                        <p class="small text-muted mb-0">Admin telah menyetujui pesanan Anda. Silakan bayar DP sebesar <strong>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</strong> untuk memulai produksi.</p>
                    </div>
                </div>
                <button class="btn btn-primary rounded-3 fw-bold px-3 py-2" onclick="bukaModalUploadDP()" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-upload me-1"></i> Unggah Bukti Transfer DP
                </button>
            </div>
        @elseif($order->payment_status === 'Menunggu Verifikasi DP')
            <div class="alert alert-warning border-0 rounded-4 p-3 mb-3 shadow-sm d-flex align-items-center gap-3">
                <i class="fa-solid fa-spinner fa-spin fa-2x text-warning"></i>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Bukti Pembayaran DP Sedang Diverifikasi</h6>
                    <p class="small text-muted mb-0">Bukti transfer DP Anda telah tersimpan dan sedang diverifikasi oleh admin. Begitu disetujui, pengerjaan mebel akan segera dimulai.</p>
                </div>
            </div>
        @elseif($order->payment_status === 'Menunggu Verifikasi Pelunasan')
            <div class="alert alert-info border-0 rounded-4 p-3 mb-3 shadow-sm d-flex align-items-center gap-3">
                <i class="fa-solid fa-receipt fa-2x text-info"></i>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">Bukti Pelunasan Sedang Diverifikasi</h6>
                    <p class="small text-muted mb-0">Bukti transfer pelunasan Anda telah kami terima dan sedang diverifikasi oleh admin.</p>
                </div>
            </div>
        @endif

        <!-- 1. SPESIFIKASI PELANGGAN DINAMIS -->
=======
        <!-- 1. SPESIFIKASI PESANAN AKURAT & DINAMIS -->
>>>>>>> Stashed changes
        <div class="wireframe-card">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h4 class="fw-bold text-dark mb-0" style="color: var(--primary-color);">
                    <i class="fa-solid fa-file-lines me-2"></i>Spesifikasi Pesanan #{{ $order->order_number ?? $order->id }}
                </h4>
                <div class="d-flex gap-2">
                    <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(93, 64, 55, 0.1); color: var(--primary-color); border: 1px solid var(--wood-border);">
<<<<<<< Updated upstream
                        Progres: {{ $order->production_status }}
=======
                        Status Produksi: {{ $order->production_status ?? 'Dalam Proses' }}
>>>>>>> Stashed changes
                    </span>
                    @if($order->order_status === 'Ditolak')
                        <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">Ditolak</span>
                    @elseif($order->order_status === 'Menunggu Konfirmasi')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Menunggu Konfirmasi</span>
                    @endif
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-8">
                    {{-- 1A. JIKA PESANAN ADALAH CUSTOM DESIGN --}}
                  @if($order->customDesign)
    <strong class="text-dark d-block mb-1">Spesifikasi Mebel Custom:</strong>
    
    <!-- Kategori -->
    <p class="mb-1 text-muted">
        <strong class="text-dark">Kategori:</strong> 
        {{ $order->customDesign->category ?? $order->customDesign->product_type ?? $order->customDesign->name ?? '-' }}
    </p>

    <!-- Material Kayu -->
    <p class="mb-1 text-muted">
        <strong class="text-dark">Material Kayu:</strong> 
        {{ $order->customDesign->wood_material ?? $order->customDesign->material ?? $order->customDesign->wood_type ?? '-' }}
    </p>

    <!-- Ukuran -->
    <p class="mb-1 text-muted">
        <strong class="text-dark">Ukuran Presisi:</strong> 
        {{ $order->customDesign->length_cm ?? $order->customDesign->length ?? 0 }} cm (P) x 
        {{ $order->customDesign->width_cm ?? $order->customDesign->width ?? 0 }} cm (L) x 
        {{ $order->customDesign->height_cm ?? $order->customDesign->height ?? 0 }} cm (T)
    </p>

    <!-- Warna Finishing -->
    <p class="mb-1 text-muted">
        <strong class="text-dark">Warna Finishing:</strong> 
        {{ $order->customDesign->color_name ?? $order->customDesign->color ?? '-' }}
        @if(!empty($order->customDesign->color_hex))
            <span class="d-inline-block rounded-circle border ms-1" style="width: 12px; height: 12px; background-color: {{ $order->customDesign->color_hex }}; vertical-align: middle;"></span>
        @endif
    </p>
@endif

                    {{-- 1B. JIKA PESANAN ADALAH ITEM KATALOG --}}
                    @if($order->items && $order->items->count() > 0)
                        <strong class="text-dark d-block mt-2 mb-2"><i class="fa-solid fa-boxes-packing me-1"></i> Item Produk Katalog:</strong>
                        <ul class="list-group list-group-flush mb-2">
                            @foreach($order->items as $item)
                                <li class="list-group-item bg-transparent px-0 py-1 text-muted small d-flex justify-content-between align-items-center">
                                    <span><i class="fa-solid fa-box text-secondary me-2"></i><strong>{{ $item->product_name ?? $item->product->name ?? 'Produk Mebel' }}</strong> (x{{ $item->quantity }})</span>
                                    <span class="fw-bold text-dark">Rp {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- CATATAN TAMBAHAN --}}
                    @if(!empty($order->customer_notes) || !empty($order->notes))
                        <div class="mt-3 p-2 rounded bg-light border">
                            <strong class="text-dark small d-block"><i class="fa-solid fa-note-sticky me-1"></i> Catatan Khusus:</strong>
                            <span class="text-muted small"><em>"{{ $order->customer_notes ?? $order->notes }}"</em></span>
                        </div>
                    @endif
                </div>

                <div class="col-md-4 text-md-end border-start-md">
                    <span class="text-muted small d-block">Total Nilai Pesanan:</span>
<<<<<<< Updated upstream
                    <h5 class="fw-bold text-dark mb-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                    <span class="text-muted small d-block">Wajib DP (50%):</span>
                    <h6 class="fw-bold text-success mb-2">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</h6>
                    <span class="text-muted small d-block">Status Pembayaran:</span>
                    <span class="badge bg-secondary px-3 py-1.5 rounded-pill">{{ $order->payment_status }}</span>
=======
                    <h4 class="fw-bold text-dark mb-3">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>

                    <span class="text-muted small d-block">Status Pembayaran:</span>
                    @if($order->payment_status == 'paid' || $order->payment_status == 'Lunas')
                        <span class="badge bg-success px-3 py-2 rounded-pill fs-6"><i class="fa-solid fa-circle-check me-1"></i> Lunas</span>
                    @elseif($order->payment_status == 'pending_verification')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fs-6"><i class="fa-solid fa-clock me-1"></i> Verifikasi Pelunasan</span>
                    @else
                        <span class="badge bg-secondary px-3 py-2 rounded-pill fs-6"><i class="fa-solid fa-hourglass-half me-1"></i> Belum Lunas</span>
                    @endif
>>>>>>> Stashed changes
                </div>
            </div>
        </div>

        <!-- 2. TIMELINE PROGRES PRODUKSI -->
        <div class="wireframe-card">
            <h5 class="fw-bold text-dark mb-4 text-uppercase tracking-wider" style="color: var(--primary-color);">
                <i class="fa-solid fa-timeline me-2"></i>TIMELINE PROGRES PRODUKSI
            </h5>

            <div class="timeline-scroll-container">
                @php
                    $defaultImages = [
                        1 => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=400&auto=format&fit=crop',
                        2 => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=400&auto=format&fit=crop',
                        3 => 'https://images.unsplash.com/photo-1504148455328-c376907d081c?w=400&auto=format&fit=crop',
                        4 => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=400&auto=format&fit=crop',
                        5 => 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?w=400&auto=format&fit=crop',
                        6 => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=400&auto=format&fit=crop',
                        7 => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=400&auto=format&fit=crop',
                        8 => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=400&auto=format&fit=crop',
                    ];
                @endphp

<<<<<<< Updated upstream
                @foreach($order->progresses as $prog)
                    @php
                        $isActive = $prog->status === 'Sedang Berjalan';
                        $isDone = $prog->status === 'Selesai';
                        $isCancelled = $prog->status === 'Dibatalkan';
                        $imgUrl = null;
                        if (!empty($prog->media_files) && count($prog->media_files) > 0) {
                            $imgUrl = \Illuminate\Support\Facades\Storage::url($prog->media_files[0]);
                        } elseif ($isDone || $isActive) {
                            $imgUrl = $defaultImages[$prog->step_number] ?? null;
                        }
                    @endphp

                    <div class="timeline-step-card {{ $isActive ? 'active-step' : '' }}" 
                         onclick="bukaModalTimeline('{{ $prog->stage_name }}', '{{ $prog->completed_at ? $prog->completed_at->format('d M Y') : ($isActive ? 'Sedang Berjalan' : ($isCancelled ? 'Dibatalkan' : 'Pending')) }}', '{{ $prog->status }}', '{{ $prog->notes ?? 'Belum ada catatan' }}', '{{ $imgUrl }}')">
                        <div class="upload-date-label">
                            @if($isDone)
                                <i class="fa-solid fa-circle-check text-success"></i>
                                <span class="text-success">{{ $prog->completed_at ? $prog->completed_at->format('d M') : 'Selesai' }}</span>
                            @elseif($isCancelled)
                                <i class="fa-solid fa-circle-xmark text-danger"></i>
                                <span class="text-danger">Dibatalkan</span>
                            @elseif($isActive)
                                <i class="fa-solid fa-spinner fa-spin text-warning"></i>
                                <span class="text-warning">Proses</span>
                            @else
                                <i class="fa-regular fa-circle text-muted"></i>
                                <span class="text-muted">Pending</span>
                            @endif
=======
                @if($order->progresses && $order->progresses->count() > 0)
                    @foreach($order->progresses as $prog)
                        @php
                            $isActive = $prog->status === 'Sedang Berjalan' || $prog->status === 'In Progress';
                            $isDone = $prog->status === 'Selesai' || $prog->status === 'Completed';
                            $imgUrl = null;

                            if (!empty($prog->media_files) && is_array($prog->media_files) && count($prog->media_files) > 0) {
                                $imgUrl = \Illuminate\Support\Facades\Storage::url($prog->media_files[0]);
                            } elseif ($isDone || $isActive) {
                                $imgUrl = $defaultImages[$prog->step_number] ?? $defaultImages[1];
                            }
                        @endphp

                        <div class="timeline-step-card {{ $isActive ? 'active-step' : '' }}" 
                             onclick="bukaModalTimeline('{{ $prog->stage_name }}', '{{ $prog->completed_at ? \Carbon\Carbon::parse($prog->completed_at)->format('d M Y') : ($isActive ? 'Sedang Berjalan' : 'Pending') }}', '{{ $prog->status }}', '{{ addslashes($prog->notes ?? 'Belum ada catatan progres.') }}', '{{ $imgUrl }}')">
                            
                            <div class="upload-date-label">
                                @if($isDone)
                                    <i class="fa-solid fa-circle-check text-success"></i>
                                    <span class="text-success">{{ $prog->completed_at ? \Carbon\Carbon::parse($prog->completed_at)->format('d M') : 'Selesai' }}</span>
                                @elseif($isActive)
                                    <i class="fa-solid fa-spinner fa-spin text-warning"></i>
                                    <span class="text-warning">Proses</span>
                                @else
                                    <i class="fa-regular fa-circle text-muted"></i>
                                    <span class="text-muted">Pending</span>
                                @endif
                            </div>

                            <div class="step-img-box">
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $prog->stage_name }}">
                                @else
                                    <span class="text-muted small text-center px-1"><i class="fa-solid fa-image fa-lg d-block mb-1" style="color: var(--primary-color);"></i>Foto Progres</span>
                                @endif
                            </div>

                            <div class="step-title-label">{{ $prog->stage_name }}</div>
>>>>>>> Stashed changes
                        </div>
                    @endforeach
                @else
                    <p class="text-muted small mb-0 py-3 text-center">Belum ada tahapan progres yang dimasukkan oleh admin/pengrajin.</p>
                @endif
            </div>
        </div>

<<<<<<< Updated upstream
        <!-- 3. BOTTOM ACTION BOX (PEMBAYARAN DP / PELUNASAN / KONFIRMASI SELESAI) -->
        <div class="row g-3">
            <!-- BOX KIRI: STATUS PEMBAYARAN & SISA PELUNASAN -->
            <div class="col-lg-7">
                <div class="wireframe-card d-flex flex-column align-items-center justify-content-center py-4 mb-0 h-100 text-center">
                    @if($order->order_status === 'Menunggu Konfirmasi')
                        <i class="fa-solid fa-hourglass-start fa-2x text-warning mb-2"></i>
                        <h5 class="fw-bold text-dark mb-1">Menunggu Persetujuan Admin</h5>
                        <p class="text-muted small mb-0">Pembayaran uang muka (DP) dapat dilakukan setelah admin menyetujui pesanan Anda.</p>
                    @elseif($order->order_status === 'Diterima' && $order->payment_status === 'Menunggu Pembayaran DP')
                        <h5 class="fw-bold text-dark mb-1">Wajib Pembayaran DP:</h5>
                        <h3 class="fw-bold mb-3" style="color: var(--primary-color);">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</h3>
                        <button class="btn btn-dark px-4 py-2 rounded-3 fw-bold" onclick="bukaModalUploadDP()" style="background-color: var(--primary-color); border: none;">
                            <i class="fa-solid fa-upload me-1"></i> Unggah Bukti Transfer DP
                        </button>
                    @elseif($order->payment_status === 'Menunggu Verifikasi DP')
                        <i class="fa-solid fa-file-invoice-dollar fa-2x text-warning mb-2"></i>
                        <h5 class="fw-bold text-dark mb-1">Bukti DP Terkirim</h5>
                        <p class="text-muted small mb-0">Menunggu verifikasi admin untuk memulai produksi.</p>
                    @elseif($order->payment_status === 'Menunggu Verifikasi Pelunasan')
                        <i class="fa-solid fa-clock-rotate-left fa-2x text-info mb-2"></i>
                        <h5 class="fw-bold text-dark mb-1">Bukti Pelunasan Terkirim</h5>
                        <p class="text-muted small mb-0">Admin sedang memverifikasi pembayaran pelunasan Anda.</p>
                    @elseif($order->remaining_payment > 0)
                        <h4 class="fw-bold text-dark mb-1">Sisa Pelunasan: <span style="color: var(--accent-gold);">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</span></h4>
                        <p class="text-muted small mb-3">Lakukan pelunasan sebelum pesanan dikirimkan ke alamat Anda.</p>
                        <button class="btn btn-orange-outline" onclick="bukaModalPelunasan()">
=======
        <!-- 3. TOMBOL PELUNASAN & PESANAN SELESAI -->
        <div class="row g-3">
            <!-- BOX KIRI: PELUNASAN -->
            <div class="col-lg-7">
                <div class="wireframe-card d-flex flex-column align-items-center justify-content-center py-4 mb-0 h-100 text-center">
                    @if(($order->remaining_payment ?? 0) > 0)
                        <h4 class="fw-bold text-dark mb-3">
                            Sisa Pelunasan: <span style="color: var(--accent-gold, #d97706);">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</span>
                        </h4>
                        <!-- MENGIRIM NOMINAL SECARA PASTI KE FUNGSI JAVASCRIPT -->
                        <button type="button" class="btn btn-orange-outline" onclick="bukaModalPelunasan({{ $order->remaining_payment }})">
>>>>>>> Stashed changes
                            <i class="fa-solid fa-credit-card me-1"></i> Bayar Sisa Pelunasan
                        </button>
                    @else
                        <h4 class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-1"></i> Pembayaran Lunas</h4>
                        <p class="text-muted small mb-0">Terima kasih, seluruh tagihan pembayaran untuk pesanan ini telah lunas.</p>
                    @endif
                </div>
            </div>

<<<<<<< Updated upstream
            <!-- BOX KANAN: STATUS & KONFIRMASI PENERIMAAN MEBEL -->
=======
            <!-- BOX KANAN: KONFIRMASI DITERIMA -->
>>>>>>> Stashed changes
            <div class="col-lg-5">
                <div class="wireframe-card d-flex flex-column align-items-center justify-content-center p-4 mb-0 h-100 text-center">
                    @php
                        $isPaidOff = ($order->payment_status === 'Lunas' || $order->remaining_payment <= 0);
                        $isDeliveredOrShipped = in_array($order->current_stage, ['Pengiriman', 'Pesanan Selesai']) || in_array($order->production_status, ['Pengiriman', 'Selesai']);
                        $isApproved = ($order->order_status === 'Diterima');
                        $canConfirmCompleted = $isApproved && $isPaidOff && $isDeliveredOrShipped && ($order->production_status !== 'Selesai');
                    @endphp

                    @if($order->production_status === 'Selesai')
                        <div class="py-2">
                            <i class="fa-solid fa-circle-check text-success fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Pesanan Telah Selesai</h5>
                            <p class="text-muted small mb-3">Produk mebel custom telah diterima dan transaksi selesai tercatat di riwayat.</p>
                            <button class="btn btn-success w-100 py-3 fs-6 shadow-sm fw-bold rounded-3" disabled>
                                <i class="fa-solid fa-check-double me-2"></i> Pesanan Selesai Diterima
                            </button>
                        </div>
                    @elseif($order->order_status === 'Ditolak')
                        <div class="py-2">
                            <i class="fa-solid fa-ban text-danger fa-3x mb-2"></i>
                            <h5 class="fw-bold text-danger mb-1">Pesanan Dibatalkan</h5>
                            <p class="small text-muted mb-0">{{ $order->rejection_reason ?? 'Pesanan tidak dapat diproses oleh admin.' }}</p>
                        </div>
                    @elseif($order->order_status === 'Menunggu Konfirmasi')
                        <div class="py-2 w-100">
                            <i class="fa-solid fa-hourglass-half text-warning fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Menunggu Persetujuan Admin</h5>
                            <p class="text-muted small mb-3">Admin sedang memeriksa kelayakan desain & bahan mebel Anda sebelum pesanan diproses.</p>
                            <button class="btn btn-secondary w-100 py-2.5 rounded-3 fw-semibold small" disabled style="opacity: 0.65;">
                                <i class="fa-solid fa-lock me-1"></i> Konfirmasi Selesai Belum Tersedia
                            </button>
                        </div>
                    @elseif($order->payment_status === 'Menunggu Pembayaran DP')
                        <div class="py-2 w-100">
                            <i class="fa-solid fa-receipt text-warning fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Menunggu Pembayaran DP</h5>
                            <p class="text-muted small mb-3">Silakan bayar uang muka (DP) 50% di panel sebelah kiri agar pesanan mulai dikerjakan pengrajin.</p>
                            <button class="btn btn-secondary w-100 py-2.5 rounded-3 fw-semibold small" disabled style="opacity: 0.65;">
                                <i class="fa-solid fa-lock me-1"></i> Bayar DP Terlebih Dahulu
                            </button>
                        </div>
                    @elseif($order->payment_status === 'Menunggu Verifikasi DP')
                        <div class="py-2 w-100">
                            <i class="fa-solid fa-spinner fa-spin text-info fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Verifikasi Pembayaran DP</h5>
                            <p class="text-muted small mb-3">Bukti transfer DP Anda sedang diverifikasi oleh admin sebelum masuk antrean workshop.</p>
                            <button class="btn btn-secondary w-100 py-2.5 rounded-3 fw-semibold small" disabled style="opacity: 0.65;">
                                <i class="fa-solid fa-lock me-1"></i> Verifikasi DP Berjalan
                            </button>
                        </div>
                    @elseif(!$isDeliveredOrShipped)
                        <div class="py-2 w-100">
                            <i class="fa-solid fa-hammer text-primary fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Mebel Dalam Produksi</h5>
                            <p class="text-muted small mb-3">Pesanan sedang dibuat di workshop (Tahap: <strong>{{ $order->current_stage }}</strong>). Tombol konfirmasi akan aktif setelah produk dikirim.</p>
                            <button class="btn btn-secondary w-100 py-2.5 rounded-3 fw-semibold small" disabled style="opacity: 0.65;">
                                <i class="fa-solid fa-lock me-1"></i> Produksi Sedang Berjalan
                            </button>
                        </div>
                    @elseif(!$isPaidOff)
                        <div class="py-2 w-100">
                            <i class="fa-solid fa-wallet text-warning fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Menunggu Pelunasan Sisa</h5>
                            <p class="text-muted small mb-3">Mebel telah siap/dikirim. Harap lunasi sisa tagihan di panel sebelah kiri sebelum mengonfirmasi penerimaan barang.</p>
                            <button class="btn btn-secondary w-100 py-2.5 rounded-3 fw-semibold small" disabled style="opacity: 0.65;">
                                <i class="fa-solid fa-lock me-1"></i> Lunasi Sisa Tagihan Dahulu
                            </button>
                        </div>
                    @else
<<<<<<< Updated upstream
                        <div class="py-2 w-100">
                            <i class="fa-solid fa-truck-ramp-box text-success fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Mebel Dalam Pengiriman</h5>
                            <p class="text-muted small mb-3">Periksa kondisi fisik mebel setelah tiba di lokasi Anda, lalu klik tombol di bawah untuk menyelesaikan pesanan.</p>
                            <button class="btn btn-action-dark w-100 py-3 fs-5 shadow-sm fw-bold" onclick="bukaModalSelesai()" style="background-color: var(--primary-color); color: white; border: none;">
                                <i class="fa-solid fa-box-open me-2"></i> Konfirmasi Pesanan Diterima
                            </button>
                        </div>
=======
                        <button type="button" class="btn btn-action-dark w-100 py-3 fs-5 shadow-sm" onclick="bukaModalSelesai()">
                            <i class="fa-solid fa-box-check me-2"></i> Konfirmasi Pesanan Selesai
                        </button>
>>>>>>> Stashed changes
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>

@if($order)
    <!-- MODAL DETAIL TIMELINE -->
    <div class="modal fade" id="modalTimelineDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 shadow-lg border-0" style="background-color: var(--light-card, #fff);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0" id="modalTimelineTitle">Detail Progress</h5>
                        <p class="text-muted small mb-0">Status: <span id="modalTimelineStatus" class="fw-bold text-primary"></span></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="rounded-3 overflow-hidden mb-3 border bg-light text-center" style="max-height: 280px;">
                    <img id="modalTimelineImg" src="" alt="Pratinjau Foto" class="w-100 h-auto" style="object-fit: cover; max-height: 280px;">
                </div>

                <div class="p-3 rounded-3 mb-3" style="background-color: var(--wood-bg, #f3f4f6); border: 1px solid var(--wood-border, #e5e7eb);">
                    <span class="small text-muted fw-bold d-block mb-1">Catatan Pengrajin/Admin:</span>
                    <p class="small text-dark mb-0" id="modalTimelineNotes">-</p>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

<<<<<<< Updated upstream
    <!-- MODAL UPLOAD BUKTI TRANSFER DP -->
    <div class="modal fade" id="modalUploadDP" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
                <form action="{{ route('customer.progress.upload_dp', $order->id) }}" method="POST" enctype="multipart/form-data">
=======
    <!-- MODAL PELUNASAN -->
    <div class="modal fade" id="modalPelunasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card, #fff);">
                <form action="{{ route('customer.progress.pay_remaining', $order->id) }}" method="POST">
>>>>>>> Stashed changes
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-money-bill-transfer text-success me-2"></i>Pembayaran Uang Muka (DP)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <p class="text-muted small mb-3 text-start">Silakan lakukan transfer uang muka 50% untuk pesanan <strong>#{{ $order->order_number }}</strong>.</p>

<<<<<<< Updated upstream
                    <div class="p-3 border rounded-3 mb-3 bg-white text-start">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">Total Tagihan:</span>
                            <strong class="text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small text-muted">Uang Muka Wajib (50%):</span>
                            <strong class="text-success fs-5">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</strong>
                        </div>
                        <hr class="my-2">
                        <div class="small">
                            <strong class="text-dark d-block mb-1">Transfer Bank / E-Wallet:</strong>
                            <div class="text-muted mb-1"><i class="fa-solid fa-building-columns me-1"></i> Bank BCA: <strong>8830-1289-44</strong> (a.n PT Assalam Mebel)</div>
                            <div class="text-muted"><i class="fa-solid fa-qrcode me-1"></i> QRIS Tersedia di toko / CS WhatsApp</div>
                        </div>
                    </div>

                    <div class="text-start mb-3">
                        <label class="form-label fw-bold small text-dark">Unggah Foto Bukti Transfer DP <span class="text-danger">*</span></label>
                        <input type="file" name="dp_receipt_proof" class="form-control rounded-3" accept="image/jpeg,image/png,image/jpg" required>
                        <div class="form-text small">Format gambar JPG/PNG, ukuran berkas maksimal 3 MB.</div>
=======
                    <!-- ELEMENT TARGET HARGA PELUNASAN -->
                    <p class="fw-bold fs-3 mb-3" style="color: var(--accent-gold, #d97706);" id="modal_pay_dp_amount">Rp 0</p>

                    <div id="boxDANA" class="p-3 border rounded-4 mb-3 bg-white shadow-sm" style="border-color: var(--light-border, #e5e7eb) !important;">
                        <div class="mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill fw-bold mb-2">DANA Instant Pay</span>
                            <p class="mb-1 small text-muted">Nomor DANA Usaha:</p>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <h5 class="fw-bold text-dark mb-0" id="noDana">087872859262</h5>
                                <button type="button" class="btn btn-sm btn-light border" onclick="copyDanaNumber()">📋 Salin</button>
                            </div>
                            <small class="text-muted d-block mt-1">a.n. <strong>Mebel Assalam</strong></small>
                        </div>
                        <hr class="my-2">
                        <div class="p-2 rounded-3 mb-2 mx-auto d-flex align-items-center justify-content-center" style="width: 170px; height: 170px;">
                            <img src="{{ asset('images/dana.jpeg') }}" alt="QRIS DANA Mebel Assalam" class="img-fluid rounded-3">
                        </div>
                        <span class="small text-muted d-block">Atau scan QRIS DANA di atas menggunakan aplikasi DANA / M-Banking Anda.</span>
>>>>>>> Stashed changes
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
<<<<<<< Updated upstream
                        <button type="submit" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);">
                            <i class="fa-solid fa-upload me-1"></i> Kirim Bukti DP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL PELUNASAN FORM REAL -->
    <div class="modal fade" id="modalPelunasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
                <form action="{{ route('customer.progress.pay_remaining', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-receipt text-success me-2"></i>Pembayaran Sisa Pelunasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <p class="text-muted small mb-3 text-start">Selesaikan sisa tagihan mebel sebelum barang dikirimkan.</p>

                    <div class="p-3 border rounded-3 mb-3 bg-white text-start">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">Sisa Tagihan:</span>
                            <strong class="text-danger fs-4">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</strong>
                        </div>
                        <hr class="my-2">
                        <div class="small">
                            <strong class="text-dark d-block mb-1">Transfer Bank / E-Wallet:</strong>
                            <div class="text-muted mb-1"><i class="fa-solid fa-building-columns me-1"></i> Bank BCA: <strong>8830-1289-44</strong> (a.n PT Assalam Mebel)</div>
                            <div class="text-muted"><i class="fa-solid fa-qrcode me-1"></i> QRIS: Silakan scan QRIS di bawah ini</div>
                        </div>
                    </div>

                    <div class="p-3 border rounded-4 mb-3 bg-white shadow-sm" style="border-color: var(--light-border) !important;">
                        <div class="border p-2 rounded-3 mb-2 mx-auto bg-light d-flex align-items-center justify-content-center" style="width: 140px; height: 140px;">
                            <i class="fa-solid fa-qrcode fa-5x text-secondary"></i>
                        </div>
                        <span class="small text-muted">Scan QRIS menggunakan aplikasi M-Banking atau E-Wallet apa saja.</span>
                    </div>

                    <div class="text-start mb-3">
                        <label class="form-label fw-bold small text-dark">Unggah Foto Bukti Pelunasan <span class="text-danger">*</span></label>
                        <input type="file" name="final_receipt_proof" class="form-control rounded-3" accept="image/jpeg,image/png,image/jpg" required>
                        <div class="form-text small">Format JPG/PNG, maksimal 3 MB. Pembayaran akan diverifikasi oleh admin.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);">
                            <i class="fa-solid fa-upload me-1"></i> Kirim Bukti Pelunasan
                        </button>
=======
                        <button type="submit" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color, #2563eb);">Konfirmasi Pelunasan</button>
>>>>>>> Stashed changes
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI SELESAI -->
    <div class="modal fade" id="modalSelesai" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card, #fff);">
                <form action="{{ route('customer.progress.confirm_completed', $order->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <i class="fa-solid fa-circle-check text-success" style="font-size: 3.5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Konfirmasi Pesanan Selesai</h4>
                    <p class="text-muted small mb-4">Apakah Anda telah menerima produk mebel custom dalam kondisi baik dan lengkap sesuai pesanan?</p>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn fw-bold w-50 py-2 rounded-3 text-white btn-success">Ya, Pesanan Selesai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
    // Membuka Modal Detail Progres
    function bukaModalTimeline(title, date, status, notes, imgUrl) {
        document.getElementById('modalTimelineTitle').innerText = title;
        document.getElementById('modalTimelineStatus').innerText = status + " (" + date + ")";
        document.getElementById('modalTimelineNotes').innerText = notes;
        document.getElementById('modalTimelineImg').src = imgUrl || 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=400&auto=format&fit=crop';
        
        let modalEl = document.getElementById('modalTimelineDetail');
        let modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

<<<<<<< Updated upstream
    function bukaModalUploadDP() {
        let modal = new bootstrap.Modal(document.getElementById('modalUploadDP'));
        modal.show();
    }

    function bukaModalPelunasan() {
        let modal = new bootstrap.Modal(document.getElementById('modalPelunasan'));
=======
    // Membuka Modal Pelunasan dan Mengisi Nominal Rupiah secara Dinamis
    function bukaModalPelunasan(amount) {
        if (amount !== undefined && amount !== null) {
            let formattedAmount = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(amount);

            document.getElementById('modal_pay_dp_amount').innerText = formattedAmount;
        }

        let modalEl = document.getElementById('modalPelunasan');
        let modal = bootstrap.Modal.getOrCreateInstance(modalEl);
>>>>>>> Stashed changes
        modal.show();
    }

    // Membuka Modal Konfirmasi Selesai
    function bukaModalSelesai() {
        let modalEl = document.getElementById('modalSelesai');
        let modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    // Salin Nomor DANA
    function copyDanaNumber() {
        let noDana = document.getElementById('noDana').innerText;
        navigator.clipboard.writeText(noDana).then(() => {
            alert('Nomor DANA berhasil disalin: ' + noDana);
        });
    }
</script>
@endsection