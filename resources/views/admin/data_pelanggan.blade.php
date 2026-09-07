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

    .stat-card-customer {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        padding: 16px 20px;
        transition: all 0.25s ease;
    }

    .stat-card-customer:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(93, 64, 55, 0.08);
        border-color: var(--primary-color);
    }

    .stat-card-customer .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: rgba(93, 64, 55, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.95rem;
        flex-shrink: 0;
        border: 1px solid var(--wood-border);
    }

    .pelanggan-row:hover {
        background-color: rgba(93, 64, 55, 0.03) !important;
    }

    .table-customers thead th {
        background-color: var(--wood-bg) !important;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--wood-border);
        padding: 14px 12px;
    }

    .table-customers tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid var(--light-border);
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">DATA AKUN PELANGGAN</h4>
            <p class="text-muted small mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Informasi akun pelanggan terdaftar, nomor kontak WhatsApp, dan riwayat pesanan.</p>
        </div>
        <div>
            <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-outline-dark rounded-3 px-3 py-2 fw-semibold" style="border-color: var(--light-border);">
                <i class="fa-solid fa-cart-arrow-down me-1 text-warning"></i> Cek Pesanan Masuk
            </a>
        </div>
    </div>

    <!-- STATISTIK KARTU PELANGGAN -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card-customer d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Pelanggan Terdaftar</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $totalCustomers ?? 0 }}</h3>
                </div>
                <div class="icon-box" style="background-color: rgba(93, 64, 55, 0.1); color: var(--primary-color);">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-customer d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Pelanggan dengan Pesanan</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $customersWithOrders ?? 0 }}</h3>
                </div>
                <div class="icon-box bg-success-subtle text-success">
                    <i class="fa-solid fa-cart-shopping"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-customer d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Pelanggan Baru Bulan Ini</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $newCustomersThisMonth ?? 0 }}</h3>
                </div>
                <div class="icon-box bg-info-subtle text-info">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- PENCARIAN PELANGGAN -->
    <div class="admin-card mb-4 p-3">
        <form action="{{ route('admin.data.pelanggan') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari berdasarkan nama pelanggan, username, nomor WhatsApp, email, atau alamat...">
                </div>
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.data.pelanggan') }}" class="btn btn-outline-secondary rounded-3" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        @if(request('q'))
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="small text-muted">
                    <i class="fa-solid fa-filter me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong> ({{ $customers->total() }} pelanggan ditemukan)
                </div>
                <a href="{{ route('admin.data.pelanggan') }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Pelanggan
                </a>
            </div>
        @endif
    </div>

    <!-- TABEL DATA PELANGGAN -->
    <div class="admin-card p-0 overflow-hidden">
        <div class="table-responsive mb-0">
            <table class="table table-customers table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 5%;" class="text-center">No</th>
                        <th style="width: 25%;">Pelanggan</th>
                        <th style="width: 18%;">Kontak WhatsApp</th>
                        <th style="width: 22%;">Email</th>
                        <th style="width: 12%;" class="text-center">Pesanan</th>
                        <th style="width: 18%;">Alamat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $pelanggan)
                    @php
                        // Ambil inisial nama
                        $words = explode(' ', trim($pelanggan->name));
                        $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        
                        // Bersihkan nomor WhatsApp untuk link wa.me
                        $cleanPhone = preg_replace('/[^0-9]/', '', $pelanggan->whatsapp_number ?? '');
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                    @endphp
                    <tr class="pelanggan-row">
                        <td class="text-center fw-bold text-muted" style="font-size: 0.85rem;">
                            {{ $customers->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="customer-avatar" title="{{ $pelanggan->name }}">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $pelanggan->name }}</strong>
                                    @if($pelanggan->username)
                                        <span class="badge bg-light text-secondary border px-2 py-0.5" style="font-size: 0.75rem;">
                                            @<span>{{ $pelanggan->username }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($cleanPhone)
                                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($pelanggan->name) }},%20kami%20dari%20Assalam%20Mebel" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1 text-nowrap"
                                   style="font-size: 0.8rem;">
                                    <i class="fa-brands fa-whatsapp me-1"></i> {{ $pelanggan->whatsapp_number }}
                                </a>
                            @else
                                <span class="text-muted small fst-italic">-</span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            <i class="fa-solid fa-envelope text-secondary me-1"></i> {{ $pelanggan->email }}
                        </td>
                        <td class="text-center">
                            @if(($pelanggan->orders_count ?? 0) > 0)
                                <a href="{{ route('admin.pesanan.masuk', ['q' => $pelanggan->name]) }}" class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill fw-bold text-decoration-none" title="Lihat pesanan pelanggan ini">
                                    <i class="fa-solid fa-box-open me-1"></i> {{ $pelanggan->orders_count }} Pesanan
                                </a>
                            @else
                                <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill small">
                                    0 Pesanan
                                </span>
                            @endif
                        </td>
                        <td class="small text-muted">
                            @if($pelanggan->alamat)
                                <span title="{{ $pelanggan->alamat }}">
                                    {{ \Illuminate\Support\Str::limit($pelanggan->alamat, 45) }}
                                </span>
                            @else
                                <span class="fst-italic text-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted py-5 text-center">
                            <i class="fa-solid fa-user-slash fa-3x mb-2 text-muted"></i>
                            <h6 class="fw-bold text-dark mt-2 mb-1">Tidak Ditemukan</h6>
                            <p class="mb-3">Tidak ada data pelanggan yang cocok{{ request('q') ? ' dengan pencarian "' . request('q') . '"' : '' }}.</p>
                            @if(request('q'))
                                <a href="{{ route('admin.data.pelanggan') }}" class="btn btn-outline-dark btn-sm rounded-3 px-3">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Pencarian
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- FOOTER & PAGINASI (BERSIH & KONSISTEN) -->
        <div class="px-4 py-3 bg-light bg-opacity-50 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="small text-muted">
                Menampilkan <strong>{{ $customers->firstItem() ?? 0 }}</strong> - <strong>{{ $customers->lastItem() ?? 0 }}</strong> dari <strong>{{ $customers->total() }}</strong> akun pelanggan
            </div>
            @if($customers->hasPages())
                <div class="pagination-clean">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection