@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">HALAMAN ADMIN : DASHBOARD</h4>
            <p class="text-muted small mb-0">Selamat datang kembali, Administrator Assalam Mebel.</p>
        </div>
        <div>
            <span class="badge bg-success p-2 shadow-sm"><i class="fa-solid fa-circle-dot me-1"></i> Sistem Aktif</span>
        </div>
    </div>

    <!-- 1. Statistik Utama (Bisa diklik seluruh card-nya) -->
    <div class="row g-4 mb-4">
        <!-- Card Pesanan Baru -->
        <div class="col-md-4">
            <a href="{{ route('admin.pesanan.masuk') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 transition-card bg-white border-start border-primary border-4">
                    <div class="card-body">
                        <div class="text-primary mb-2"><i class="fa-solid fa-cart-shopping fa-2x"></i></div>
                        <h6 class="text-muted fw-semibold">Pesanan Baru</h6>
                        <h3 class="fw-bold text-dark my-2">12</h3>
                        <span class="small text-primary fw-bold">Lihat Detail <i class="fa-solid fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Dalam Produksi -->
        <div class="col-md-4">
            <a href="{{ route('admin.progres.produksi') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 transition-card bg-white border-start border-warning border-4">
                    <div class="card-body">
                        <div class="text-warning mb-2"><i class="fa-solid fa-gears fa-2x"></i></div>
                        <h6 class="text-muted fw-semibold">Dalam Produksi</h6>
                        <h3 class="fw-bold text-dark my-2">18</h3>
                        <span class="small text-warning fw-bold">Lacak Progres <i class="fa-solid fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Card Total Pelanggan -->
        <div class="col-md-4">
            <a href="{{ route('admin.data.pelanggan') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 p-3 text-center h-100 transition-card bg-white border-start border-success border-4">
                    <div class="card-body">
                        <div class="text-success mb-2"><i class="fa-solid fa-users fa-2x"></i></div>
                        <h6 class="text-muted fw-semibold">Total Pelanggan</h6>
                        <h3 class="fw-bold text-dark my-2">59</h3>
                        <span class="small text-success fw-bold">Lihat Semua <i class="fa-solid fa-arrow-right ms-1"></i></span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- 2. Tabel Antrean Verifikasi -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark mb-0"><i class="fa-solid fa-clipboard-check text-primary me-2"></i>ANTREAN VERIFIKASI ALAMAT & PEMBAYARAN CUSTOM (TERBARU)</h6>
            <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Kelola Semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="py-3">ID Pesanan</th>
                        <th class="py-3">Nama Pelanggan</th>
                        <th class="py-3">Mebel Custom</th>
                        <th class="py-3">Status Lokasi/Alamat</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold text-primary">#10025</td>
                        <td>Rina</td>
                        <td>Meja Makan</td>
                        <td><span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Terverifikasi</span></td>
                        <td>
                            <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-sm btn-light text-primary"><i class="fa-solid fa-eye"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 3. Bagian Bawah (Log Notifikasi & Ringkasan Produksi) -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <h6 class="fw-bold text-dark mb-3"><i class="fa-brands fa-whatsapp text-success me-2"></i>LOG NOTIFIKASI WHATSAPP TERKIRIM</h6>
                <ul class="list-group list-group-flush small">
                    <li class="list-group-item px-0 text-muted d-flex justify-content-between align-items-center">
                        <span><strong class="text-dark">#10025:</strong> Tagihan QR code dikirim</span>
                        <span class="badge bg-light text-secondary">Baru saja</span>
                    </li>
                    <li class="list-group-item px-0 text-muted d-flex justify-content-between align-items-center">
                        <span><strong class="text-dark">#10022:</strong> Progres Perakitan dikirim</span>
                        <span class="badge bg-light text-secondary">1 jam lalu</span>
                    </li>
                    <li class="list-group-item px-0 text-muted d-flex justify-content-between align-items-center">
                        <span><strong class="text-dark">#10019:</strong> Pesanan Siap Dikirim</span>
                        <span class="badge bg-light text-secondary">3 jam lalu</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa-solid chart-pie text-warning me-2"></i>RINGKASAN PRODUKSI (PROGRES)</h6>
                    <a href="{{ route('admin.progres.produksi') }}" class="small text-decoration-none fw-bold">Detail <i class="fa-solid fa-chevron-right small"></i></a>
                </div>
                <div class="d-flex flex-column gap-3 justify-content-center h-100">
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Pemotongan Kayu</span>
                            <span class="text-muted">8 Pesanan</span>
                        </div>
                        <div class="progress" style="height: 8px;"><div class="progress-bar bg-danger" style="width: 30%"></div></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Tahap Perakitan</span>
                            <span class="text-muted">12 Pesanan</span>
                        </div>
                        <div class="progress" style="height: 8px;"><div class="progress-bar bg-warning" style="width: 50%"></div></div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1">
                            <span>Tahap Finishing</span>
                            <span class="text-muted">8 Pesanan</span>
                        </div>
                        <div class="progress" style="height: 8px;"><div class="progress-bar bg-success" style="width: 20%"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan Styling CSS agar Interaktif / Bisa Diklik -->
<style>
    .transition-card {
        transition: all 0.3s ease;
    }
    .transition-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection