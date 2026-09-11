@extends('layouts.customer')

@section('content')
<style>
    .order-history-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
        padding: 22px;
        margin-bottom: 20px;
        transition: all 0.25s ease-in-out;
    }

    .order-history-card:hover {
        border-color: var(--wood-border);
        box-shadow: 0 8px 25px rgba(93, 64, 55, 0.08);
        transform: translateY(-2px);
    }

    .product-thumb-frame {
        width: 95px;
        height: 95px;
        border-radius: 14px;
        overflow: hidden;
        background-color: #fdfaf6;
        border: 1.5px solid var(--light-border);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-thumb-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .nav-tabs-riwayat {
        border-bottom: 2px solid var(--light-border);
        gap: 8px;
        overflow-x: auto;
        flex-wrap: nowrap;
        scrollbar-width: none;
        margin-bottom: 20px;
    }

    .nav-tabs-riwayat .nav-link {
        border: none;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.875rem;
        padding: 10px 16px;
        border-radius: 12px 12px 0 0;
        position: relative;
        white-space: nowrap;
        transition: all 0.2s;
        text-decoration: none;
    }

    .nav-tabs-riwayat .nav-link:hover {
        color: var(--primary-color);
        background-color: rgba(93, 64, 55, 0.04);
    }

    .nav-tabs-riwayat .nav-link.active {
        color: var(--primary-color);
        background-color: transparent;
        font-weight: 700;
    }

    .nav-tabs-riwayat .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: var(--primary-color);
        border-radius: 3px 3px 0 0;
    }

    .btn-action-primary {
        background-color: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border-radius: 12px;
        padding: 8px 20px;
        font-size: 0.875rem;
        border: none;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-action-primary:hover {
        background-color: var(--secondary-color);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.2);
    }
</style>

<div class="container-xl">

    <!-- HEADER / JUDUL HALAMAN -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Riwayat Transaksi & Pesanan</h3>
            <p class="text-muted small mb-0">Lacak status verifikasi DP, pengerjaan di workshop, dan pelunasan mebel custom Anda.</p>
        </div>
        <div>
            <a href="{{ route('customer.design') }}" class="btn btn-dark px-4 py-2.5 rounded-3 fw-bold shadow-2xs" style="background-color: var(--primary-color); border: none;">
                <i class="fa-solid fa-plus me-1"></i> Pesan Custom Baru
            </a>
        </div>
    </div>

    <!-- TABS FILTER STATUS PESANAN -->
    <ul class="nav nav-tabs-riwayat pb-1">
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? 'all') === 'all' ? 'active' : '' }}" href="{{ route('customer.riwayat', array_merge(request()->except('tab'), ['tab' => 'all'])) }}">
                Semua Pesanan <span class="badge rounded-pill bg-light text-dark ms-1 border">{{ $tabCounts['all'] ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? '') === 'menunggu_konfirmasi' ? 'active' : '' }}" href="{{ route('customer.riwayat', array_merge(request()->except('tab'), ['tab' => 'menunggu_konfirmasi'])) }}">
                Menunggu Konfirmasi
                @if(($tabCounts['menunggu_konfirmasi'] ?? 0) > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-1">{{ $tabCounts['menunggu_konfirmasi'] }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? '') === 'menunggu_bayar' ? 'active' : '' }}" href="{{ route('customer.riwayat', array_merge(request()->except('tab'), ['tab' => 'menunggu_bayar'])) }}">
                Perlu Bayar (DP/Pelunasan)
                @if(($tabCounts['menunggu_bayar'] ?? 0) > 0)
                    <span class="badge rounded-pill bg-danger text-white ms-1">{{ $tabCounts['menunggu_bayar'] }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? '') === 'diproses' ? 'active' : '' }}" href="{{ route('customer.riwayat', array_merge(request()->except('tab'), ['tab' => 'diproses'])) }}">
                Dalam Produksi <span class="badge rounded-pill bg-light text-dark ms-1 border">{{ $tabCounts['diproses'] ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? '') === 'dikirim' ? 'active' : '' }}" href="{{ route('customer.riwayat', array_merge(request()->except('tab'), ['tab' => 'dikirim'])) }}">
                Pengiriman <span class="badge rounded-pill bg-light text-dark ms-1 border">{{ $tabCounts['dikirim'] ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? '') === 'selesai' ? 'active' : '' }}" href="{{ route('customer.riwayat', array_merge(request()->except('tab'), ['tab' => 'selesai'])) }}">
                Selesai <span class="badge rounded-pill bg-light text-dark ms-1 border">{{ $tabCounts['selesai'] ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ ($activeTab ?? '') === 'batal' ? 'active' : '' }}" href="{{ route('customer.riwayat', array_merge(request()->except('tab'), ['tab' => 'batal'])) }}">
                Dibatalkan <span class="badge rounded-pill bg-light text-dark ms-1 border">{{ $tabCounts['batal'] ?? 0 }}</span>
            </a>
        </li>
    </ul>

    <!-- KOTAK PENCARIAN RIWAYAT PELANGGAN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
        <form action="{{ route('customer.riwayat') }}" method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="tab" value="{{ $activeTab ?? 'all' }}">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nomor pesanan (#...), model mebel, ukuran, warna...">
                </div>
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('customer.riwayat', ['tab' => $activeTab ?? 'all']) }}" class="btn btn-outline-secondary rounded-3" title="Reset Pencarian">
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
                <a href="{{ route('customer.riwayat', ['tab' => $activeTab ?? 'all']) }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua
                </a>
            </div>
        @endif
    </div>

    <!-- LIST PESANAN -->
    @forelse($orders as $item)
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

        <div class="order-history-card">
            <!-- Header Kartu Pesanan -->
            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge px-3 py-1 rounded-pill small fw-bold" style="background-color: var(--wood-bg); color: var(--text-dark);">
                        #{{ $item->order_number }}
                    </span>
                    <span class="small text-muted">
                        <i class="fa-regular fa-clock me-1"></i> {{ $item->created_at ? $item->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB
                    </span>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Status Produksi Badge -->
                    <span class="badge px-2.5 py-1 rounded-pill small border" style="background-color: #fdfbf7; color: var(--primary-color); border-color: var(--wood-border) !important;">
                        <i class="fa-solid fa-hammer me-1"></i> {{ $item->current_stage ?? 'Tahap 1' }}
                    </span>

                    <!-- Payment Status Badge -->
                    @if($item->payment_status === 'Lunas')
                        <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill small border border-success">
                            <i class="fa-solid fa-check-double me-1"></i> Lunas
                        </span>
                    @elseif($item->payment_status === 'Menunggu Verifikasi Pelunasan')
                        <span class="badge bg-info-subtle text-info px-2.5 py-1 rounded-pill small border border-info">
                            Verif. Pelunasan
                        </span>
                    @elseif($item->payment_status === 'DP Terverifikasi')
                        <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill small">
                            DP Terverifikasi
                        </span>
                    @elseif($item->payment_status === 'Menunggu Verifikasi DP')
                        <span class="badge bg-warning-subtle text-warning-emphasis px-2.5 py-1 rounded-pill small border border-warning">
                            Verif. DP
                        </span>
                    @elseif(in_array($item->payment_status, ['Menunggu Pembayaran DP', 'Bukti DP Ditolak']))
                        <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill small border border-danger">
                            Wajib DP 50%
                        </span>
                    @else
                        <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small border">{{ $item->payment_status }}</span>
                    @endif
                </div>
            </div>

            <!-- Konten Mebel Custom -->
            <div class="d-flex align-items-start gap-3 flex-wrap flex-md-nowrap mb-3">
                <div class="product-thumb-frame shadow-2xs">
                    @if($itemThumb)
                        <img src="{{ $itemThumb }}" alt="Mebel">
                    @else
                        <i class="fa-solid fa-couch fa-2x" style="color: var(--primary-color);"></i>
                    @endif
                </div>

                <div class="flex-grow-1">
                    <h5 class="fw-bold text-dark mb-1">
                        {{ $item->customDesign->category ?? ($item->items->first()->product_name ?? 'Mebel Custom Jati') }}
                    </h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                        <span class="badge bg-success-subtle text-success border border-success small">
                            <i class="fa-solid fa-tree me-1"></i> Kayu Jati Solid Grade A
                        </span>
                        @if($item->customDesign && $item->customDesign->length_cm)
                            <span class="badge bg-light text-dark border small">
                                Ukuran: {{ $item->customDesign->length_cm }} × {{ $item->customDesign->width_cm }} × {{ $item->customDesign->height_cm }} cm
                            </span>
                        @endif
                        @if($item->customDesign && $item->customDesign->color_name)
                            <span class="badge bg-light text-dark border small d-inline-flex align-items-center gap-1">
                                <span class="rounded-circle border" style="width: 10px; height: 10px; background-color: {{ $item->customDesign->color_hex ?? '#d97706' }};"></span>
                                Tone: {{ $item->customDesign->color_name }}
                            </span>
                        @endif
                    </div>
                    @if($item->shipping_address)
                        <small class="text-muted d-block" style="font-size: 0.78rem;">
                            <i class="fa-solid fa-location-dot me-1 text-danger"></i> Tujuan: {{ \Illuminate\Support\Str::limit($item->shipping_address, 80) }}
                        </small>
                    @endif
                </div>
            </div>

            <!-- Footer Biaya & Tombol Aksi -->
            <div class="d-flex justify-content-between align-items-center pt-3 border-top flex-wrap gap-3">
                <div>
                    <span class="text-muted small d-block" style="font-size: 0.75rem;">Total Tagihan Pesanan:</span>
                    <h5 class="fw-bold mb-0 text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</h5>
                    <div class="small text-muted" style="font-size: 0.75rem;">
                        DP (50%): <strong class="text-success">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong>
                        @if($item->remaining_payment > 0)
                            | Sisa (50%): <strong class="text-danger">Rp {{ number_format($item->remaining_payment, 0, ',', '.') }}</strong>
                        @endif
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @php
                        $cleanAdminPhone = preg_replace('/[^0-9]/', '', \App\Models\Setting::get('wa_number', '085234567890'));
                    @endphp
                    <a href="https://wa.me/{{ $cleanAdminPhone }}?text=Halo%20Assalam%20Mebel,%20saya%20ingin%20menanyakan%20pesanan%20%23{{ $item->order_number }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                        <i class="fa-brands fa-whatsapp me-1"></i> Tanya CS
                    </a>

                    <!-- Tombol Aksi Kontekstual -->
                    @if(in_array($item->payment_status, ['Menunggu Pembayaran DP', 'Bukti DP Ditolak']))
                        <a href="{{ route('customer.progress') }}" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 shadow-2xs">
                            <i class="fa-solid fa-upload me-1"></i> Bayar DP (DANA)
                        </a>
                    @elseif($item->remaining_payment > 0 && in_array($item->current_stage, ['Penyelesaian', 'Pengiriman']))
                        <a href="{{ route('customer.progress') }}" class="btn btn-sm btn-info text-white fw-bold rounded-pill px-3 shadow-2xs">
                            <i class="fa-solid fa-credit-card me-1"></i> Pelunasan Sisa
                        </a>
                    @endif

                    <a href="{{ route('customer.progress') }}" class="btn-action-primary shadow-2xs">
                        <i class="fa-solid fa-chart-line me-1"></i> Pantau Progres
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="p-5 text-center bg-white rounded-4 border shadow-sm">
            <i class="fa-solid {{ request('q') ? 'fa-magnifying-glass' : 'fa-clipboard-list' }} fa-3x text-muted mb-3"></i>
            @if(request('q'))
                <h5 class="fw-bold text-dark mb-1">Tidak Ditemukan</h5>
                <p class="text-muted small mb-3">Tidak ada riwayat pesanan yang cocok dengan pencarian "<strong>{{ request('q') }}</strong>".</p>
                <a href="{{ route('customer.riwayat', ['tab' => $activeTab ?? 'all']) }}" class="btn btn-outline-dark btn-sm rounded-pill px-4">
                    Reset Pencarian
                </a>
            @else
                <h5 class="fw-bold text-dark mb-1">Belum Ada Pesanan di Kategori Ini</h5>
                <p class="text-muted small mb-3">Jelajahi ragam mebel jati ukir premium atau sesuaikan ukuran mebel impian Anda di Studio Custom.</p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('customer.katalog') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-basket-shopping me-1"></i> Buka Katalog
                    </a>
                    <a href="{{ route('customer.design') }}" class="btn btn-action-primary btn-sm rounded-pill px-3">
                        <i class="fa-solid fa-pen-ruler me-1"></i> Studio Custom Jati
                    </a>
                </div>
            @endif
        </div>
    @endforelse

</div>
@endsection