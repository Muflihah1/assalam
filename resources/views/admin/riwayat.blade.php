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

    .riwayat-card {
        background-color: #ffffff;
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        transition: transform 0.2s ease;
    }

    .riwayat-card:hover {
        transform: translateY(-2px);
        border-color: var(--primary-color);
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">RIWAYAT SEMUA PESANAN</h4>
            <p class="small text-muted mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Rekapitulasi seluruh transaksi pesanan mebel custom yang telah masuk ke sistem.</p>
        </div>
    </div>

    <!-- KOTAK PENCARIAN RIWAYAT ADMIN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
        <form action="{{ route('admin.riwayat') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nomor pesanan, nama pemesan, kontak WA, kategori mebel, atau status...">
                </div>
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.riwayat') }}" class="btn btn-outline-secondary rounded-3" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        @if(request('q'))
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="small text-muted">
                    <i class="fa-solid fa-filter me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong> ({{ $listRiwayat->count() }} transaksi ditemukan)
                </div>
                <a href="{{ route('admin.riwayat') }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Riwayat
                </a>
            </div>
        @endif
    </div>

    <!-- DAFTAR KARTU RIWAYAT REAL DARI DATABASE -->
    <div class="row g-3">
        @forelse($listRiwayat as $item)
        <div class="col-12">
            <div class="riwayat-card">
                <div class="row align-items-center">
                    
                    <!-- INFORMASI DETAIL PESANAN -->
                    <div class="col-md-9">
                        <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                            <div>
                                <span class="small text-muted d-block mb-1">
                                    <i class="fa fa-user me-1"></i> Pelanggan: <strong>{{ $item->recipient_name ?? $item->user->name }}</strong>
                                </span>
                                <h5 class="fw-bold text-dark mb-1">
                                    #{{ $item->order_number }} - {{ $item->customDesign->category ?? 'Custom Mebel' }}
                                </h5>
                            </div>
                            <div>
                                @if($item->production_status === 'Selesai')
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="fa fa-check-circle me-1"></i> Pesanan Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="fa fa-spinner fa-spin me-1"></i> {{ $item->production_status }} ({{ $item->current_stage }})
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- SPESIFIKASI DALAM GRID -->
                        <div class="row text-muted small mb-3 p-3 rounded-3" style="background-color: var(--light-bg); border: 1px solid var(--light-border);">
                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                <span class="d-block text-secondary fw-bold">Material:</span>
                                {{ $item->customDesign->wood_material ?? '-' }}
                            </div>
                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                <span class="d-block text-secondary fw-bold">Ukuran:</span>
                                {{ $item->customDesign->length_cm ?? 0 }}x{{ $item->customDesign->width_cm ?? 0 }}x{{ $item->customDesign->height_cm ?? 0 }} cm
                            </div>
                            <div class="col-md-3 col-6">
                                <span class="d-block text-secondary fw-bold">Warna Tone:</span>
                                {{ $item->customDesign->color_name ?? '-' }}
                            </div>
                            <div class="col-md-3 col-6">
                                <span class="d-block text-secondary fw-bold">Total Nilai:</span>
                                <strong class="text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <!-- TANGGAL -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                <span class="me-3"><i class="fa fa-calendar-days text-primary me-1"></i> Dipesan: <strong>{{ $item->created_at->format('d M Y, H:i') }} WIB</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL AKSI HAPUS / DETAIL -->
                    <div class="col-md-3 text-md-end mt-3 mt-md-0">
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('admin.progres.produksi', $item->id) }}" class="btn btn-outline-dark btn-sm rounded-3">
                                <i class="fa-solid fa-gears me-1"></i> Kelola Progres
                            </a>
                            <form action="{{ route('admin.riwayat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat pesanan #{{ $item->order_number }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 w-100">
                                    <i class="fa fa-trash me-1"></i> Hapus Pesanan
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="admin-card py-5">
                <i class="fa-solid {{ request('q') ? 'fa-magnifying-glass' : 'fa-clock-rotate-left' }} fa-3x text-muted mb-3"></i>
                @if(request('q'))
                    <h5 class="fw-bold text-dark mb-1">Riwayat Tidak Ditemukan</h5>
                    <p class="text-muted small mb-3">Tidak ada riwayat transaksi yang cocok dengan pencarian "<strong>{{ request('q') }}</strong>".</p>
                    <a href="{{ route('admin.riwayat') }}" class="btn btn-outline-dark btn-sm rounded-3 px-3">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Pencarian
                    </a>
                @else
                    <h5 class="fw-bold text-dark mb-1">Belum Ada Riwayat Pesanan</h5>
                    <p class="text-muted small mb-0">Pesanan yang masuk akan terekam secara otomatis di halaman ini.</p>
                @endif
            </div>
        </div>
        @endforelse
    </div>

</div>
@endsection