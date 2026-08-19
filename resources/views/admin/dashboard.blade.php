@extends('admin.layout')

@section('content')
<style>
    .stat-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(93, 64, 55, 0.1);
        border-color: var(--primary-color);
    }

    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .admin-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
    }
</style>

<div class="container-fluid px-0 py-2">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">DASHBOARD ADMINISTRATOR</h4>
            <p class="text-muted small mb-0">Selamat datang kembali di panel manajemen Assalam Mebel.</p>
        </div>
        <div>
            <span class="badge px-3 py-2 rounded-pill fw-bold bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                <i class="fa-solid fa-circle-dot me-1"></i> Sistem Aktif & Sinkron
            </span>
        </div>
    </div>

    <!-- 1. Statistik Utama Dinamis -->
    <div class="row g-3 mb-4">
        <!-- Card Pesanan Baru -->
        <div class="col-md-4">
            <a href="{{ route('admin.pesanan.masuk') }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon-wrapper" style="background-color: rgba(217, 119, 6, 0.15); color: var(--accent-gold);">
                        <i class="fa-solid fa-cart-arrow-down fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block">Pesanan Baru</span>
                        <h3 class="fw-bold text-dark mb-0 my-1">{{ $newOrdersCount ?? 0 }}</h3>
                        <span class="small fw-bold" style="color: var(--accent-gold);">Verifikasi Antrean <i class="fa-solid fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Dalam Produksi -->
        <div class="col-md-4">
            <a href="{{ route('admin.progres.produksi') }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon-wrapper" style="background-color: rgba(93, 64, 55, 0.15); color: var(--primary-color);">
                        <i class="fa-solid fa-gears fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block">Dalam Produksi</span>
                        <h3 class="fw-bold text-dark mb-0 my-1">{{ $inProductionCount ?? 0 }}</h3>
                        <span class="small fw-bold" style="color: var(--primary-color);">Lacak Progres <i class="fa-solid fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Total Pelanggan -->
        <div class="col-md-4">
            <a href="{{ route('admin.data.pelanggan') }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon-wrapper" style="background-color: rgba(21, 128, 61, 0.15); color: #15803d;">
                        <i class="fa-solid fa-users fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-bold text-uppercase d-block">Total Pelanggan</span>
                        <h3 class="fw-bold text-dark mb-0 my-1">{{ $totalCustomersCount ?? 0 }}</h3>
                        <span class="small fw-bold text-success">Lihat Akun <i class="fa-solid fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- 2. Tabel Antrean Verifikasi Real -->
    <div class="admin-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-clipboard-check text-primary me-2"></i>ANTREAN VERIFIKASI PESANAN CUSTOM TERBARU
            </h6>
            <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-sm btn-outline-dark rounded-3 px-3">
                Kelola Semua Pesanan
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">No. Pesanan</th>
                        <th class="py-3">Nama Pelanggan</th>
                        <th class="py-3">Kategori Mebel</th>
                        <th class="py-3">Total / DP</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="fw-bold" style="color: var(--primary-color);">#{{ $order->order_number }}</td>
                            <td class="text-start">
                                <strong class="text-dark">{{ $order->recipient_name ?? $order->user->name }}</strong><br>
                                <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success"></i> {{ $order->recipient_phone ?? $order->user->whatsapp_number }}</span>
                            </td>
                            <td>{{ $order->customDesign->category ?? 'Custom Mebel' }}</td>
                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span><br>
                                <span class="small text-muted">DP: Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($order->payment_status === 'DP Terverifikasi')
                                    <span class="badge bg-success px-2.5 py-1.5 rounded-pill">{{ $order->payment_status }}</span>
                                @else
                                    <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill">{{ $order->payment_status }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-sm btn-outline-dark rounded-3">
                                    <i class="fa-solid fa-eye me-1"></i> Periksa
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">Belum ada pesanan yang masuk ke dalam antrean.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Ringkasan Produksi Aktif -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="admin-card h-100">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fa-solid fa-gears text-warning me-2"></i>PESANAN SEDANG DIKERJAKAN
                </h6>
                <div class="d-flex flex-column gap-3">
                    @forelse($inProgressOrders as $item)
                        <div class="p-3 rounded-3" style="background-color: var(--light-bg); border: 1px solid var(--light-border);">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark small">#{{ $item->order_number }} - {{ $item->customDesign->category ?? 'Mebel' }}</strong>
                                <span class="badge bg-warning text-dark small">{{ $item->current_stage }}</span>
                            </div>
                            <span class="text-muted small d-block mb-2">{{ $item->customDesign->wood_material ?? 'Kayu Jati' }} (Finishing: {{ $item->customDesign->color_name ?? 'Natural' }})</span>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">Pemesan: <strong>{{ $item->recipient_name ?? $item->user->name }}</strong></span>
                                <a href="{{ route('admin.progres.produksi', $item->id) }}" class="btn btn-sm btn-outline-dark rounded-3 px-2 py-0.5" style="font-size: 0.75rem;">
                                    Update Progres →
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Tidak ada pesanan yang sedang dalam proses produksi.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="admin-card h-100">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fa-brands fa-whatsapp text-success me-2"></i>INFORMASI NOTIFIKASI GATEWAY
                </h6>
                <div class="p-3 rounded-3 mb-3" style="background-color: var(--light-bg); border: 1px solid var(--light-border);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted fw-bold">Nomor Gateway Toko:</span>
                        <span class="badge bg-success">Aktif</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">0852-3456-7890</h5>
                    <span class="small text-muted">Setiap pembaruan progres di panel admin otomatis disiapkan untuk dikirimkan ke nomor WhatsApp pelanggan.</span>
                </div>
                <div class="text-end">
                    <a href="{{ route('admin.pengaturan') }}" class="btn btn-sm btn-outline-dark rounded-3">
                        <i class="fa-solid fa-sliders me-1"></i> Buka Pengaturan Gateway
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection