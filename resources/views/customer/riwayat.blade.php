@extends('layouts.customer')

@section('content')
<style>
    .history-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--wood-border);
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.06);
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.25s ease-in-out;
    }

    .history-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .badge-status-done {
        background-color: #d1fae5;
        color: #15803d;
        border: 1px solid #a7f3d0;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .badge-status-process {
        background-color: #ffedd5;
        color: #b45309;
        border: 1px solid #fed7aa;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .product-img-box {
        width: 90px;
        height: 90px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #ffffff;
        border: 1.5px solid var(--light-border);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-orange-outline {
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
        background: transparent;
        font-weight: 700;
        border-radius: 30px;
        padding: 8px 24px;
        font-size: 0.875rem;
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
        padding: 8px 20px;
        font-size: 0.875rem;
        transition: all 0.2s;
    }

    .btn-action-dark:hover {
        border-color: var(--primary-color);
        background-color: #dfcebc;
        color: var(--primary-color);
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- HEADER / JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Riwayat Pemesanan</h3>
            <p class="text-muted small mb-0">Daftar semua transaksi dan status pesanan mebel custom Anda.</p>
        </div>
        <div>
            <a href="{{ route('customer.design') }}" class="btn btn-outline-dark px-4 py-2 rounded-3 fw-bold">
                <i class="fa-solid fa-plus me-1"></i> Pesan Custom Baru
            </a>
        </div>
    </div>

    <!-- KOTAK PENCARIAN RIWAYAT PELANGGAN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: var(--light-card); border: 1.5px solid var(--wood-border) !important;">
        <form action="{{ route('customer.riwayat') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari no. pesanan (#...), model mebel, bahan kayu, atau warna...">
                </div>
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('customer.riwayat') }}" class="btn btn-outline-secondary rounded-3" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        @if(request('q'))
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="small text-muted">
                    <i class="fa-solid fa-filter me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong> ({{ $orders->count() }} pesanan ditemukan)
                </div>
                <a href="{{ route('customer.riwayat') }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Riwayat
                </a>
            </div>
        @endif
    </div>

    <!-- LIST PESANAN DINAMIS -->
    @forelse($orders as $item)
        <div class="history-card">
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom flex-wrap gap-2" style="border-color: var(--light-border) !important;">
                <div class="d-flex align-items-center gap-3">
                    <i class="fa-solid fa-bag-shopping fs-5" style="color: var(--primary-color);"></i>
                    <span class="fw-bold text-dark small">{{ $item->created_at->format('d M Y') }}</span>
                    <span class="text-muted small">| #{{ $item->order_number }}</span>
                </div>
                <div>
                    @if($item->order_status === 'Ditolak')
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill fw-bold small">
                            <i class="fa-solid fa-xmark me-1"></i> Pesanan Ditolak
                        </span>
                    @elseif($item->order_status === 'Menunggu Konfirmasi')
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1.5 rounded-pill fw-bold small">
                            <i class="fa-regular fa-clock me-1"></i> Menunggu Konfirmasi Admin
                        </span>
                    @elseif($item->production_status === 'Selesai')
                        <span class="badge-status-done"><i class="fa-solid fa-circle-check me-1"></i> Pesanan Selesai</span>
                    @else
                        <span class="badge-status-process"><i class="fa-solid fa-spinner fa-spin me-1"></i> {{ $item->production_status }}</span>
                    @endif
                </div>
            </div>

            @if($item->order_status === 'Ditolak' && $item->rejection_reason)
                <div class="alert alert-danger py-2 px-3 small rounded-3 mb-3">
                    <strong><i class="fa-solid fa-circle-exclamation me-1"></i> Alasan Penolakan Admin:</strong> {{ $item->rejection_reason }}
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between">
                <div class="d-flex gap-3 align-items-center">
                    <div class="product-img-box">
                        @if($item->customDesign && $item->customDesign->sketch_image)
                            @php
                                $sketchImg = (str_starts_with($item->customDesign->sketch_image, 'http://') || str_starts_with($item->customDesign->sketch_image, 'https://'))
                                    ? $item->customDesign->sketch_image
                                    : \Illuminate\Support\Facades\Storage::url($item->customDesign->sketch_image);
                            @endphp
                            <img src="{{ $sketchImg }}" alt="Foto Mebel">
                        @elseif($item->items && $item->items->first() && $item->items->first()->image)
                            @php
                                $itemImg = (str_starts_with($item->items->first()->image, 'http://') || str_starts_with($item->items->first()->image, 'https://'))
                                    ? $item->items->first()->image
                                    : \Illuminate\Support\Facades\Storage::url($item->items->first()->image);
                            @endphp
                            <img src="{{ $itemImg }}" alt="Foto Produk">
                        @else
                            <i class="fa-solid fa-couch fa-2x" style="color: var(--primary-color);"></i>
                        @endif
                    </div>
                    <div>
                        @if($item->customDesign)
                            <h5 class="fw-bold text-dark mb-1">{{ $item->customDesign->category ?? 'Custom Furniture' }}</h5>
                            <p class="text-muted small mb-1">{{ $item->customDesign->wood_material ?? 'Kayu Jati Solid' }} - Finishing {{ $item->customDesign->color_name ?? 'Natural' }}</p>
                            <p class="text-muted small mb-0">Ukuran: {{ $item->customDesign->length_cm ?? 0 }} x {{ $item->customDesign->width_cm ?? 0 }} x {{ $item->customDesign->height_cm ?? 0 }} cm</p>
                        @elseif($item->items && $item->items->count() > 0)
                            <h5 class="fw-bold text-dark mb-1">{{ $item->items->first()->product_name }} @if($item->items->count() > 1) <span class="small text-muted">(+{{ $item->items->count() - 1 }} produk lain)</span> @endif</h5>
                            <p class="text-muted small mb-0">Total {{ $item->items->sum('quantity') }} unit barang dipesan.</p>
                        @else
                            <h5 class="fw-bold text-dark mb-1">Pesanan Mebel Assalam</h5>
                        @endif
                    </div>
                </div>

                <div class="text-md-end mt-3 mt-md-0">
                    <span class="text-muted small d-block mb-1">Total Nilai Pesanan</span>
                    <h4 class="fw-bold mb-3" style="color: var(--accent-gold);">Rp {{ number_format($item->total_price, 0, ',', '.') }}</h4>
                    <div class="d-flex gap-2 justify-content-md-end">
                        <button class="btn btn-action-dark" onclick="bukaModalDetail('{{ $item->order_number }}', '{{ $item->production_status }}', '{{ $item->payment_method }}', 'Rp {{ number_format($item->dp_amount, 0, ',', '.') }}', 'Rp {{ number_format($item->total_price, 0, ',', '.') }}')">
                            <i class="fa-solid fa-circle-info me-1"></i> Detail
                        </button>
                        <a href="{{ route('customer.progress') }}" class="btn btn-orange-outline">
                            <i class="fa-solid fa-chart-line me-1"></i> Cek Progres
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="history-card text-center py-5">
            <i class="fa-solid {{ request('q') ? 'fa-magnifying-glass' : 'fa-clock-rotate-left' }} fa-3x text-muted mb-3"></i>
            @if(request('q'))
                <h5 class="fw-bold text-dark mb-2">Pesanan Tidak Ditemukan</h5>
                <p class="text-muted small mb-4">Tidak ada riwayat pesanan yang cocok dengan pencarian "<strong>{{ request('q') }}</strong>".</p>
                <a href="{{ route('customer.riwayat') }}" class="btn btn-outline-dark px-4 py-2 rounded-3 fw-bold small">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Riwayat
                </a>
            @else
                <h5 class="fw-bold text-dark mb-2">Belum Ada Riwayat Pesanan</h5>
                <p class="text-muted small mb-4">Anda belum pernah melakukan pemesanan mebel sebelumnya.</p>
                <a href="{{ route('customer.design') }}" class="btn btn-dark px-4 py-2 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-pen-ruler me-1"></i> Mulai Pesanan Custom Sekarang
                </a>
            @endif
        </div>
    @endforelse

</div>

<!-- MODAL DETAIL PESANAN -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 p-4 shadow-lg border-0" style="background-color: var(--light-card);">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">Detail Rincian Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="p-3 mb-3 rounded-3" style="background-color: var(--wood-bg); border: 1px solid var(--wood-border);">
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">No. Pesanan:</span>
                    <span class="fw-bold text-dark" id="modalOrderNo">-</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Status Produksi:</span>
                    <span class="fw-bold text-success" id="modalStatus">-</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">Metode Pembayaran:</span>
                    <span class="text-dark fw-bold text-uppercase" id="modalMetode">-</span>
                </div>
                <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted">DP Terverifikasi:</span>
                    <span class="fw-bold text-primary" id="modalDP">-</span>
                </div>
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Total Nilai:</span>
                    <span class="fw-bold text-dark" id="modalTotal">-</span>
                </div>
            </div>
            <button type="button" class="btn btn-secondary w-100 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>

<script>
    function bukaModalDetail(orderNo, status, method, dp, total) {
        document.getElementById('modalOrderNo').innerText = '#' + orderNo;
        document.getElementById('modalStatus').innerText = status;
        document.getElementById('modalMetode').innerText = method;
        document.getElementById('modalDP').innerText = dp;
        document.getElementById('modalTotal').innerText = total;

        let modal = new bootstrap.Modal(document.getElementById('modalDetail'));
        modal.show();
    }
</script>
@endsection