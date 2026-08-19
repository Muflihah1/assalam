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
        border: 1.5px solid var(--wood-border);
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
        border-color: var(--primary-color);
        background-color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(93, 64, 55, 0.12);
    }

    .timeline-step-card.active-step {
        border-color: var(--primary-color);
        background-color: #ffffff;
        box-shadow: 0 0 0 2px var(--primary-color), 0 6px 15px rgba(93, 64, 55, 0.15);
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
        border: 1px solid var(--light-border);
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
        color: var(--text-dark);
        line-height: 1.2;
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

<div class="container-fluid px-2 px-md-4 py-2">

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
        <!-- 1. SPESIFIKASI PELANGGAN DINAMIS -->
        <div class="wireframe-card">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h4 class="fw-bold text-dark mb-0" style="color: var(--primary-color);">
                    <i class="fa-solid fa-file-lines me-2"></i>Spesifikasi Pesanan #{{ $order->order_number }}
                </h4>
                <div>
                    <span class="badge px-3 py-2 rounded-pill fw-bold" style="background-color: rgba(93, 64, 55, 0.1); color: var(--primary-color); border: 1px solid var(--wood-border);">
                        Status: {{ $order->production_status }}
                    </span>
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
                    <span class="text-muted small d-block">Status Pembayaran:</span>
                    <span class="badge bg-success px-3 py-1.5 rounded-pill">{{ $order->payment_status }}</span>
                </div>
            </div>
        </div>

        <!-- 2. TIMELINE 8 TAHAPAN OTOMATIS -->
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

                @foreach($order->progresses as $prog)
                    @php
                        $isActive = $prog->status === 'Sedang Berjalan';
                        $isDone = $prog->status === 'Selesai';
                        $imgUrl = null;
                        if (!empty($prog->media_files) && count($prog->media_files) > 0) {
                            $imgUrl = \Illuminate\Support\Facades\Storage::url($prog->media_files[0]);
                        } elseif ($isDone || $isActive) {
                            $imgUrl = $defaultImages[$prog->step_number] ?? null;
                        }
                    @endphp

                    <div class="timeline-step-card {{ $isActive ? 'active-step' : '' }}" 
                         onclick="bukaModalTimeline('{{ $prog->stage_name }}', '{{ $prog->completed_at ? $prog->completed_at->format('d M Y') : ($isActive ? 'Sedang Berjalan' : 'Pending') }}', '{{ $prog->status }}', '{{ $prog->notes ?? 'Belum ada catatan' }}', '{{ $imgUrl }}')">
                        <div class="upload-date-label">
                            @if($isDone)
                                <i class="fa-solid fa-circle-check text-success"></i>
                                <span class="text-success">{{ $prog->completed_at ? $prog->completed_at->format('d M') : 'Selesai' }}</span>
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
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. BOTTOM ACTION BOX (PELUNASAN & PESANAN SELESAI) -->
        <div class="row g-3">
            <!-- BOX KIRI: SISA PELUNASAN -->
            <div class="col-lg-7">
                <div class="wireframe-card d-flex flex-column align-items-center justify-content-center py-4 mb-0 h-100 text-center">
                    @if($order->remaining_payment > 0)
                        <h4 class="fw-bold text-dark mb-3">Sisa Pelunasan: <span style="color: var(--accent-gold);">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</span></h4>
                        <button class="btn btn-orange-outline" onclick="bukaModalPelunasan()">
                            <i class="fa-solid fa-credit-card me-1"></i> Bayar Sisa Pelunasan
                        </button>
                    @else
                        <h4 class="fw-bold text-success mb-2"><i class="fa-solid fa-circle-check me-1"></i> Pembayaran Lunas</h4>
                        <p class="text-muted small mb-0">Terima kasih, seluruh tagihan pembayaran untuk pesanan ini telah lunas.</p>
                    @endif
                </div>
            </div>

            <!-- BOX KANAN: PESANAN SELESAI -->
            <div class="col-lg-5">
                <div class="wireframe-card d-flex align-items-center justify-content-center py-4 mb-0 h-100">
                    @if($order->production_status === 'Selesai')
                        <button class="btn btn-success w-100 py-3 fs-5 shadow-sm fw-bold" disabled>
                            <i class="fa-solid fa-check-double me-2"></i> Pesanan Selesai Diterima
                        </button>
                    @else
                        <button class="btn btn-action-dark w-100 py-3 fs-5 shadow-sm" onclick="bukaModalSelesai()">
                            <i class="fa-solid fa-box-check me-2"></i> Konfirmasi Pesanan Selesai
                        </button>
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

                <div class="rounded-3 overflow-hidden mb-3 border bg-light text-center" style="max-height: 280px;">
                    <img id="modalTimelineImg" src="" alt="Pratinjau Foto" class="w-100 h-auto" style="object-fit: cover; max-height: 280px;">
                </div>

                <div class="p-3 rounded-3 mb-3" style="background-color: var(--wood-bg); border: 1px solid var(--wood-border);">
                    <span class="small text-muted fw-bold d-block mb-1">Catatan Pengrajin/Admin:</span>
                    <p class="small text-dark mb-0" id="modalTimelineNotes">-</p>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PELUNASAN FORM REAL -->
    <div class="modal fade" id="modalPelunasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
                <form action="{{ route('customer.progress.pay_remaining', $order->id) }}" method="POST">
                    @csrf
                    <h5 class="fw-bold text-dark mb-1">PEMBAYARAN SISA PELUNASAN</h5>
                    <p class="text-muted small mb-3">Selesaikan sisa tagihan mebel sebelum barang dikirim.</p>

                    <p class="fw-bold fs-3 mb-3" style="color: var(--accent-gold);">Rp {{ number_format($order->remaining_payment, 0, ',', '.') }}</p>

                    <div class="text-start mb-3">
                        <label class="form-label fw-bold small text-dark">Pilih Metode Pembayaran:</label>
                        <select class="form-select border-2">
                            <option value="qris">QRIS (Semua E-Wallet & M-Banking)</option>
                            <option value="transfer">Transfer Bank BCA (8830-1289-44)</option>
                            <option value="dana">E-Wallet DANA</option>
                        </select>
                    </div>

                    <div class="p-3 border rounded-4 mb-3 bg-white shadow-sm" style="border-color: var(--light-border) !important;">
                        <div class="border p-2 rounded-3 mb-2 mx-auto bg-light d-flex align-items-center justify-content-center" style="width: 140px; height: 140px;">
                            <span class="text-muted fw-bold small">[ QRIS CODE ]</span>
                        </div>
                        <span class="small text-muted">Scan QRIS menggunakan aplikasi M-Banking atau E-Wallet apa saja.</span>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);">Konfirmasi Bayar</button>
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
@endif

<script>
    function bukaModalTimeline(title, date, status, notes, imgUrl) {
        document.getElementById('modalTimelineTitle').innerText = title;
        document.getElementById('modalTimelineStatus').innerText = status + " (" + date + ")";
        document.getElementById('modalTimelineNotes').innerText = notes;
        document.getElementById('modalTimelineImg').src = imgUrl || 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=400&auto=format&fit=crop';
        
        let modal = new bootstrap.Modal(document.getElementById('modalTimelineDetail'));
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
</script>
@endsection