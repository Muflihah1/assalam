@extends('layouts.customer')

@section('content')
<style>
    .wireframe-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.06);
        padding: 24px;
        margin-bottom: 20px;
    }

    /* SHOPEE STYLE CONNECTED STEPPER */
    .shopee-stepper-wrapper {
        position: relative;
        padding: 24px 12px 16px 12px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .shopee-stepper {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        min-width: 780px;
        position: relative;
    }

    .stepper-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        cursor: pointer;
        padding: 0 4px;
        transition: transform 0.2s ease;
    }

    .stepper-item:hover {
        transform: translateY(-2px);
    }

    /* Connecting Line */
    .stepper-line {
        position: absolute;
        top: 22px;
        left: -50%;
        width: 100%;
        height: 4px;
        background-color: #e2e8f0;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .stepper-item:first-child .stepper-line {
        display: none;
    }

    .stepper-item.completed .stepper-line {
        background: linear-gradient(90deg, #15803d, #16a34a);
    }

    .stepper-item.active .stepper-line {
        background: linear-gradient(90deg, #16a34a, #d97706);
    }

    /* Stepper Circle / Node */
    .stepper-node {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1rem;
        z-index: 2;
        background-color: #ffffff;
        border: 3px solid #cbd5e1;
        color: #94a3b8;
        transition: all 0.3s ease;
        position: relative;
    }

    .stepper-item.completed .stepper-node {
        background: linear-gradient(135deg, #16a34a, #15803d);
        border-color: #ffffff;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(21, 128, 61, 0.25);
    }

    .stepper-item.active .stepper-node {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-color: #fef3c7;
        color: #ffffff;
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.2), 0 6px 15px rgba(217, 119, 6, 0.3);
        animation: pulse-ring 2s infinite;
    }

    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.4), 0 4px 12px rgba(217, 119, 6, 0.25); }
        70% { box-shadow: 0 0 0 8px rgba(217, 119, 6, 0), 0 4px 12px rgba(217, 119, 6, 0.25); }
        100% { box-shadow: 0 0 0 0 rgba(217, 119, 6, 0), 0 4px 12px rgba(217, 119, 6, 0.25); }
    }

    .stepper-item.pending .stepper-node {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #94a3b8;
    }

    .stepper-item.cancelled .stepper-node {
        background-color: #dc2626;
        border-color: #ffffff;
        color: #ffffff;
    }

    /* Stepper Content Below Circle */
    .stepper-content {
        margin-top: 10px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3px;
        width: 100%;
    }

    .stepper-title {
        font-size: 0.8rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.25;
        max-width: 95px;
    }

    .stepper-item.active .stepper-title {
        color: #b45309;
        font-weight: 900;
    }

    .stepper-item.pending .stepper-title {
        color: #94a3b8;
    }

    .stepper-date {
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748b;
    }

    .stepper-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        margin-top: 2px;
    }

    .btn-photo-proof {
        background-color: #fff7ed;
        border: 1px solid #fdba74;
        color: #c2410c;
        font-size: 0.68rem;
        font-weight: 700;
        border-radius: 20px;
        padding: 3px 8px;
        margin-top: 5px;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-photo-proof:hover {
        background-color: #ea580c;
        color: #ffffff;
        border-color: #ea580c;
        transform: scale(1.05);
    }

    .btn-orange-outline {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        font-weight: 700;
        border-radius: 30px;
        padding: 8px 30px;
        transition: all 0.2s;
    }

    .btn-orange-outline:hover {
        background: var(--primary-color);
        color: #ffffff;
    }

    .btn-action-dark {
        border: 1.5px solid var(--wood-border);
        background-color: var(--wood-bg);
        color: var(--text-dark);
        font-weight: 700;
        border-radius: 12px;
        padding: 12px 24px;
        transition: all 0.2s;
    }

    .btn-action-dark:hover {
        border-color: var(--primary-color);
        background-color: #dfcebc;
        color: var(--primary-color);
    }
</style>

<div class="container-xl">

    @if(!$order)
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
        <!-- ALERT STATUS PESANAN (KONFIRMASI / TOLAK / BATAL / PEMBAYARAN DP) -->
        @if($order->order_status === 'Menunggu Konfirmasi')
            <div class="alert alert-warning border-0 rounded-4 p-3 mb-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-hourglass-half fa-2x text-warning"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Pesanan Sedang Menunggu Konfirmasi Admin</h6>
                        <p class="small text-muted mb-0">Pesanan Anda telah kami terima dan sedang ditinjau oleh tim pengrajin & admin kami. Anda akan menerima instruksi pembayaran DP setelah pesanan disetujui.</p>
                    </div>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1.5 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalBatalkanPesanan">
                    <i class="fa-solid fa-ban me-1"></i> Batalkan Pesanan
                </button>
            </div>
        @elseif($order->order_status === 'Dibatalkan')
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-3 shadow-sm">
                <div class="d-flex align-items-start gap-3">
                    <i class="fa-solid fa-ban fa-2x text-danger mt-1"></i>
                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1 text-danger">Pesanan Dibatalkan</h6>
                        <p class="small mb-2 text-dark">Pesanan ini telah dibatalkan. {{ $order->rejection_reason ? 'Alasan: ' . $order->rejection_reason : '' }}</p>
                        <a href="{{ route('customer.design') }}" class="btn btn-sm btn-outline-danger rounded-3 fw-bold">
                            <i class="fa-solid fa-pen-ruler me-1"></i> Buat Pesanan Baru
                        </a>
                    </div>
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
        @elseif($order->payment_status === 'Bukti DP Ditolak')
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation fa-2x text-danger mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-danger">Bukti Transfer DP Belum Valid / Ditolak</h6>
                        <p class="small text-dark mb-1"><strong>Alasan dari Admin:</strong> {{ $order->rejection_reason ?? 'Nominal transfer tidak sesuai atau foto struk buram.' }}</p>
                        <p class="small text-muted mb-0">Silakan periksa mutasi rekening Anda dan unggah ulang bukti transfer DP yang sah.</p>
                    </div>
                </div>
                <button class="btn btn-danger rounded-3 fw-bold px-3 py-2" onclick="bukaModalUploadDP()">
                    <i class="fa-solid fa-rotate me-1"></i> Unggah Ulang Bukti DP
                </button>
            </div>
        @elseif($order->payment_status === 'Bukti Pelunasan Ditolak')
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation fa-2x text-danger mt-1"></i>
                    <div>
                        <h6 class="fw-bold mb-1 text-danger">Bukti Pelunasan Ditolak oleh Admin</h6>
                        <p class="small text-dark mb-1"><strong>Alasan dari Admin:</strong> {{ $order->rejection_reason ?? 'Bukti pelunasan tidak valid.' }}</p>
                        <p class="small text-muted mb-0">Silakan unggah ulang foto bukti transfer pelunasan yang sah.</p>
                    </div>
                </div>
                <button class="btn btn-danger rounded-3 fw-bold px-3 py-2" onclick="bukaModalUploadPelunasan()">
                    <i class="fa-solid fa-rotate me-1"></i> Unggah Ulang Bukti Pelunasan
                </button>
            </div>
        @elseif(in_array($order->order_status, ['Pesanan Diterima', 'Diterima']) && $order->payment_status === 'Menunggu Pembayaran DP')
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
        <div class="wireframe-card">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h4 class="fw-bold text-dark mb-0" style="color: var(--primary-color);">
                    <i class="fa-solid fa-file-lines me-2"></i>Spesifikasi Pesanan #{{ $order->order_number }}
                </h4>
                <div class="d-flex gap-2">
                    <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(93, 64, 55, 0.1); color: var(--primary-color); border: 1px solid var(--wood-border);">
                        Progres: {{ $order->production_status }}
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
                    @if($order->items && $order->items->count() > 0)
                        <strong class="text-dark d-block mb-2">Item Produk Katalog:</strong>
                        <ul class="list-unstyled mb-2">
                            @foreach($order->items as $it)
                                <li class="text-muted small mb-1">
                                    <i class="fa-solid fa-box text-secondary me-1"></i> <strong>{{ $it->product_name }}</strong> (x{{ $it->quantity }}) - Rp {{ number_format($it->subtotal, 0, ',', '.') }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($order->customDesign)
                        <strong class="text-dark d-block mb-1">Spesifikasi Mebel Custom:</strong>
                        <p class="mb-1 text-muted"><strong class="text-dark">Kategori:</strong> {{ $order->customDesign->category ?? 'Custom Furniture' }}</p>
                        <p class="mb-1 text-muted"><strong class="text-dark">Material Kayu:</strong> {{ $order->customDesign->wood_material ?? 'Kayu Jati Solid' }}</p>
                        <p class="mb-1 text-muted"><strong class="text-dark">Ukuran Presisi:</strong> {{ $order->customDesign->length_cm ?? 0 }} cm (P) x {{ $order->customDesign->width_cm ?? 0 }} cm (L) x {{ $order->customDesign->height_cm ?? 0 }} cm (T)</p>
                        <p class="mb-1 text-muted"><strong class="text-dark">Warna Finishing:</strong> {{ $order->customDesign->color_name ?? '-' }} ({{ $order->customDesign->color_hex ?? '-' }})</p>
                    @endif

                    @if($order->customer_notes)
                        <p class="mb-0 text-muted mt-2"><strong class="text-dark">Catatan Pelanggan:</strong> <em>{{ $order->customer_notes }}</em></p>
                    @endif
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="text-muted small d-block">Total Nilai Pesanan:</span>
                    <h5 class="fw-bold text-dark mb-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                    <span class="text-muted small d-block">Wajib DP (50%):</span>
                    <h6 class="fw-bold text-success mb-2">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</h6>
                    <span class="text-muted small d-block">Status Pembayaran:</span>
                    <span class="badge bg-secondary px-3 py-1.5 rounded-pill">{{ $order->payment_status }}</span>
                </div>
            </div>
        </div>

        <!-- 2. TIMELINE 8 TAHAPAN (SHOPEE STYLE CONNECTED STEPPER) -->
        <div class="wireframe-card">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-0 text-uppercase tracking-wider" style="color: var(--primary-color);">
                    <i class="fa-solid fa-route me-2"></i>TIMELINE PROGRES PRODUKSI
                </h5>
                <span class="small text-muted">
                    <i class="fa-solid fa-circle-info me-1 text-primary"></i> Klik tahapan untuk rincian &amp; bukti pengerjaan
                </span>
            </div>

            <div class="shopee-stepper-wrapper">
                <div class="shopee-stepper">
                    @foreach($order->progresses as $prog)
                        @php
                            $isActive = $prog->status === 'Sedang Berjalan';
                            $isDone = $prog->status === 'Selesai';
                            $isCancelled = $prog->status === 'Dibatalkan';
                            $isPending = !$isActive && !$isDone && !$isCancelled;
                            
                            $hasRealMedia = (!empty($prog->media_files) && count($prog->media_files) > 0);
                            $firstMediaUrl = $hasRealMedia ? \Illuminate\Support\Facades\Storage::url($prog->media_files[0]) : null;

                            $stepClass = $isDone ? 'completed' : ($isActive ? 'active' : ($isCancelled ? 'cancelled' : 'pending'));
                        @endphp

                        <div class="stepper-item {{ $stepClass }}" 
                             onclick="bukaModalTimeline('{{ $prog->stage_name }}', '{{ $prog->completed_at ? $prog->completed_at->format('d M Y, H:i') : ($isActive ? 'Sedang Diproses' : ($isCancelled ? 'Dibatalkan' : 'Menunggu Antrean')) }}', '{{ $prog->status }}', '{{ addslashes($prog->notes ?? 'Belum ada catatan khusus pada tahapan ini.') }}', '{{ $firstMediaUrl }}')">
                            
                            <!-- Connecting Line -->
                            <div class="stepper-line"></div>

                            <!-- Circle Node -->
                            <div class="stepper-node">
                                @if($isDone)
                                    <i class="fa-solid fa-check"></i>
                                @elseif($isActive)
                                    <i class="fa-solid fa-spinner fa-spin"></i>
                                @elseif($isCancelled)
                                    <i class="fa-solid fa-xmark"></i>
                                @else
                                    {{ $prog->step_number }}
                                @endif
                            </div>

                            <!-- Details Below Circle -->
                            <div class="stepper-content">
                                <span class="stepper-title">{{ $prog->stage_name }}</span>

                                @if($isDone)
                                    <span class="stepper-date text-success">
                                        <i class="fa-regular fa-circle-check me-1"></i>{{ $prog->completed_at ? $prog->completed_at->format('d M') : 'Selesai' }}
                                    </span>
                                @elseif($isActive)
                                    <span class="badge bg-warning text-dark stepper-badge">
                                        <i class="fa-solid fa-gear fa-spin me-1"></i>Proses
                                    </span>
                                @elseif($isCancelled)
                                    <span class="badge bg-danger stepper-badge">Dibatalkan</span>
                                @else
                                    <span class="stepper-date text-muted">Antrean</span>
                                @endif

                                <!-- Tombol Lihat Bukti Foto Hanya Jika Ada Foto Asli -->
                                @if($hasRealMedia)
                                    <span class="btn-photo-proof">
                                        <i class="fa-solid fa-camera"></i> Bukti Foto
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 3. BOTTOM ACTION BOX (PEMBAYARAN DP / PELUNASAN / KONFIRMASI SELESAI) -->
        <div class="row g-3">
            <!-- BOX KIRI: STATUS PEMBAYARAN & SISA PELUNASAN -->
            <div class="col-lg-7">
                <div class="wireframe-card d-flex flex-column align-items-center justify-content-center py-4 mb-0 h-100 text-center">
                    @if($order->order_status === 'Menunggu Konfirmasi')
                        <i class="fa-solid fa-hourglass-start fa-2x text-warning mb-2"></i>
                        <h5 class="fw-bold text-dark mb-1">Menunggu Persetujuan Admin</h5>
                        <p class="text-muted small mb-0">Pembayaran uang muka (DP) dapat dilakukan setelah admin menyetujui pesanan Anda.</p>
                    @elseif(in_array($order->payment_status, ['Menunggu Pembayaran DP', 'Bukti DP Ditolak']))
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
                            <i class="fa-solid fa-credit-card me-1"></i> Bayar Sisa Pelunasan
                        </button>
                    @else
                        <h4 class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-1"></i> Pembayaran Lunas</h4>
                        <p class="text-muted small mb-0">Terima kasih, seluruh tagihan pembayaran untuk pesanan ini telah lunas.</p>
                    @endif
                </div>
            </div>

            <!-- BOX KANAN: STATUS & KONFIRMASI PENERIMAAN MEBEL -->
            <div class="col-lg-5">
                <div class="wireframe-card d-flex flex-column align-items-center justify-content-center p-4 mb-0 h-100 text-center">
                    @php
                        $isPaidOff = ($order->payment_status === 'Lunas' || $order->remaining_payment <= 0);
                        $isDeliveredOrShipped = in_array($order->current_stage, ['Pengiriman', 'Pesanan Selesai']) || in_array($order->production_status, ['Pengiriman', 'Selesai']);
                        $isApproved = in_array($order->order_status, ['Diterima', 'Pesanan Diterima', 'Diproses', 'Dikirim', 'Selesai']);
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
                            <p class="text-muted small mb-3">Admin sedang memeriksa rincian pesanan Anda sebelum disetujui untuk pembayaran DP dan produksi.</p>
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
                        <div class="py-2 w-100">
                            <i class="fa-solid fa-truck-ramp-box text-success fa-3x mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Mebel Dalam Pengiriman</h5>
                            <p class="text-muted small mb-3">Periksa kondisi fisik mebel setelah tiba di lokasi Anda, lalu klik tombol di bawah untuk menyelesaikan pesanan.</p>
                            <button class="btn btn-action-dark w-100 py-3 fs-5 shadow-sm fw-bold" onclick="bukaModalSelesai()" style="background-color: var(--primary-color); color: white; border: none;">
                                <i class="fa-solid fa-box-open me-2"></i> Konfirmasi Pesanan Diterima
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>

@if($order)
    <!-- MODAL DETAIL FOTO / CATATAN TIMELINE -->
    <div class="modal fade" id="modalTimelineDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 shadow-lg border-0" style="background-color: var(--light-card);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0" id="modalTimelineTitle">Detail Progress</h5>
                        <p class="text-muted small mb-0">Status: <span id="modalTimelineStatus" class="fw-bold"></span></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div id="modalTimelineImgBox" class="rounded-3 overflow-hidden mb-3 border bg-light text-center" style="display: none; max-height: 300px;">
                    <img id="modalTimelineImg" src="" alt="Bukti Foto Pengerjaan" class="w-100 h-auto" style="object-fit: cover; max-height: 300px;">
                </div>

                <div class="p-3 rounded-3 mb-3" style="background-color: var(--wood-bg); border: 1px solid var(--wood-border);">
                    <span class="small text-muted fw-bold d-block mb-1"><i class="fa-solid fa-clipboard-check me-1 text-primary"></i>Catatan Pengrajin / Admin:</span>
                    <p class="small text-dark mb-0" id="modalTimelineNotes">-</p>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UPLOAD BUKTI TRANSFER DP (DANA & QRIS) -->
    <div class="modal fade" id="modalUploadDP" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
                <form action="{{ route('customer.progress.upload_dp', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-wallet text-primary me-2"></i>Pembayaran Uang Muka (DP)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <p class="text-muted small mb-3 text-start">Gunakan aplikasi <strong>DANA</strong> atau scan QR Code di bawah ini untuk membayar DP pesanan <strong>#{{ $order->order_number }}</strong>.</p>

                    <!-- KARTU INFORMASI TAGIHAN DP -->
                    <div class="p-3 border rounded-3 mb-3 bg-white text-start shadow-2xs">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">Total Nilai Pesanan:</span>
                            <strong class="text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Wajib Bayar DP (50%):</span>
                            <strong class="text-success fs-5">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- KARTU QR CODE DANA RESMI -->
                    <div class="p-3 border rounded-4 mb-3 bg-white shadow-sm text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <span class="badge px-3 py-1.5 rounded-pill fw-bold text-white" style="background-color: #118eea;">
                                <i class="fa-solid fa-qrcode me-1"></i> QR Code DANA Resmi
                            </span>
                        </div>

                        <!-- Gambar Kartu QR Code DANA -->
                        <div class="mx-auto my-2" style="max-width: 220px;">
                            <img src="{{ \App\Models\Setting::getDanaQrUrl() }}" alt="QR Code DANA Assalam Mebel" class="img-fluid rounded-3 shadow-2xs border">
                        </div>

                        <!-- Info Nomor DANA dengan Tombol Salin -->
                        <div class="p-2 rounded-3 mt-2 bg-light border d-flex justify-content-between align-items-center text-start">
                            <div>
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Nomor Akun DANA:</span>
                                <strong class="text-dark fs-6" id="danaNumDP">{{ \App\Models\Setting::get('payment_dana_number', '0852-3456-7890') }}</strong>
                                <span class="text-muted d-block small" style="font-size: 0.68rem;">a.n {{ \App\Models\Setting::get('payment_dana_name', 'Assalam Mebel Official') }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 py-1" onclick="salinNomorDANA('{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('payment_dana_number', '085234567890')) }}', this)">
                                <i class="fa-regular fa-copy me-1"></i>Salin
                            </button>
                        </div>

                        <div class="mt-2 text-start small text-muted" style="font-size: 0.74rem;">
                            <i class="fa-solid fa-circle-check text-success me-1"></i>Buka aplikasi <strong>DANA</strong> &gt; Tekan <strong>Pindai / Pay</strong> &gt; Scan QR Code di atas &gt; Masukkan nominal DP: <strong>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</strong>.
                        </div>
                    </div>

                    <!-- FORM UPLOAD BUKTI TRANSFER -->
                    <div class="text-start mb-3">
                        <label class="form-label fw-bold small text-dark">
                            <i class="fa-solid fa-receipt me-1 text-primary"></i> Unggah Bukti Transfer / Screenshot DANA <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="dp_receipt_proof" class="form-control rounded-3" accept="image/jpeg,image/png,image/jpg" required>
                        <div class="form-text small">Format gambar JPG/PNG, ukuran berkas maksimal 3 MB.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);">
                            <i class="fa-solid fa-upload me-1"></i> Kirim Bukti DP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL PELUNASAN FORM REAL (DANA & QRIS) -->
    <div class="modal fade" id="modalPelunasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
                <form action="{{ route('customer.progress.pay_remaining', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fa-solid fa-wallet text-success me-2"></i>Pembayaran Sisa Pelunasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <p class="text-muted small mb-3 text-start">Gunakan aplikasi <strong>DANA</strong> atau scan QR Code di bawah ini untuk pelunasan sisa tagihan mebel.</p>

                    <div class="p-3 border rounded-3 mb-3 bg-white text-start shadow-2xs">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Sisa Tagihan yang Harus Dilunasi:</span>
                            <strong class="text-danger fs-4">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <!-- KARTU QR CODE DANA RESMI -->
                    <div class="p-3 border rounded-4 mb-3 bg-white shadow-sm text-center">
                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                            <span class="badge px-3 py-1.5 rounded-pill fw-bold text-white" style="background-color: #118eea;">
                                <i class="fa-solid fa-qrcode me-1"></i> QR Code DANA Resmi
                            </span>
                        </div>

                        <!-- Gambar Kartu QR Code DANA -->
                        <div class="mx-auto my-2" style="max-width: 220px;">
                            <img src="{{ \App\Models\Setting::getDanaQrUrl() }}" alt="QR Code DANA Assalam Mebel" class="img-fluid rounded-3 shadow-2xs border">
                        </div>

                        <!-- Info Nomor DANA dengan Tombol Salin -->
                        <div class="p-2 rounded-3 mt-2 bg-light border d-flex justify-content-between align-items-center text-start">
                            <div>
                                <span class="text-muted d-block" style="font-size: 0.7rem;">Nomor Akun DANA:</span>
                                <strong class="text-dark fs-6" id="danaNumPelunasan">{{ \App\Models\Setting::get('payment_dana_number', '0852-3456-7890') }}</strong>
                                <span class="text-muted d-block small" style="font-size: 0.68rem;">a.n {{ \App\Models\Setting::get('payment_dana_name', 'Assalam Mebel Official') }}</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary fw-bold rounded-pill px-3 py-1" onclick="salinNomorDANA('{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('payment_dana_number', '085234567890')) }}', this)">
                                <i class="fa-regular fa-copy me-1"></i>Salin
                            </button>
                        </div>

                        <div class="mt-2 text-start small text-muted" style="font-size: 0.74rem;">
                            <i class="fa-solid fa-circle-check text-success me-1"></i>Buka aplikasi <strong>DANA</strong> &gt; Tekan <strong>Pindai / Pay</strong> &gt; Scan QR Code di atas &gt; Masukkan nominal sisa: <strong>Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</strong>.
                        </div>
                    </div>

                    <div class="text-start mb-3">
                        <label class="form-label fw-bold small text-dark">
                            <i class="fa-solid fa-receipt me-1 text-success"></i> Unggah Foto Bukti Pelunasan / Screenshot DANA <span class="text-danger">*</span>
                        </label>
                        <input type="file" name="final_receipt_proof" class="form-control rounded-3" accept="image/jpeg,image/png,image/jpg" required>
                        <div class="form-text small">Format JPG/PNG, maksimal 3 MB. Pembayaran akan diverifikasi oleh admin.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);">
                            <i class="fa-solid fa-upload me-1"></i> Kirim Bukti Pelunasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI SELESAI -->
    <div class="modal fade" id="modalSelesai" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
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

    <!-- MODAL BATALKAN PESANAN OLEH PELANGGAN -->
    <div class="modal fade" id="modalBatalkanPesanan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-ban me-1"></i> Batalkan Pengajuan Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('customer.progress.cancel', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 text-start">
                        <p class="small text-dark mb-3">Apakah Anda yakin ingin membatalkan pengajuan pesanan <strong>#{{ $order->order_number }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alasan Pembatalan (Opsional):</label>
                            <textarea name="reason" class="form-control rounded-3" rows="3" placeholder="Contoh: Ingin mengubah spesifikasi ukuran atau jadwal pengiriman."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-danger btn-sm rounded-3 px-4 fw-bold">Ya, Batalkan Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<script>
    function bukaModalTimeline(title, date, status, notes, imgUrl) {
        document.getElementById('modalTimelineTitle').innerText = title;
        document.getElementById('modalTimelineStatus').innerText = status + " (" + date + ")";
        document.getElementById('modalTimelineNotes').innerText = notes || "Belum ada catatan khusus pada tahapan ini.";
        
        const imgBox = document.getElementById('modalTimelineImgBox');
        const imgEl = document.getElementById('modalTimelineImg');
        
        if (imgUrl && imgUrl.trim() !== '') {
            imgEl.src = imgUrl;
            imgBox.style.display = 'block';
        } else {
            imgEl.src = '';
            imgBox.style.display = 'none';
        }
        
        let modal = new bootstrap.Modal(document.getElementById('modalTimelineDetail'));
        modal.show();
    }

    function bukaModalUploadDP() {
        let modal = new bootstrap.Modal(document.getElementById('modalUploadDP'));
        modal.show();
    }

    function bukaModalPelunasan() {
        let modal = new bootstrap.Modal(document.getElementById('modalPelunasan'));
        modal.show();
    }

    function bukaModalSelesai() {
        let modal = new bootstrap.Modal(document.getElementById('modalSelesai'));
        modal.show();
    }

    function salinNomorDANA(text, btnElement) {
        navigator.clipboard.writeText(text).then(function() {
            const originalHTML = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fa-solid fa-check me-1 text-success"></i>Tersalin!';
            btnElement.classList.remove('btn-outline-primary');
            btnElement.classList.add('btn-success', 'text-white');
            setTimeout(function() {
                btnElement.innerHTML = originalHTML;
                btnElement.classList.remove('btn-success', 'text-white');
                btnElement.classList.add('btn-outline-primary');
            }, 2000);
        }).catch(function() {
            prompt('Salin nomor secara manual:', text);
        });
    }
</script>
@endsection