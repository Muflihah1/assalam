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

    .pelanggan-row:hover {
        background-color: rgba(93, 64, 55, 0.04) !important;
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">DATA AKUN PELANGGAN</h4>
            <p class="text-muted small mb-0">Daftar seluruh akun pelanggan yang terdaftar di sistem Assalam Mebel.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge px-3 py-2 rounded-pill fw-bold text-white" style="background-color: var(--primary-color);">
                <i class="fa fa-users me-1"></i> Total: {{ $totalCustomers ?? $customers->total() }} Pelanggan
            </span>
        </div>
    </div>

    <!-- PENCARIAN PELANGGAN -->
    <div class="admin-card mb-3 py-3">
        <form action="{{ route('admin.data.pelanggan') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari berdasarkan nama, username, email, atau nomor WhatsApp...">
                </div>
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.data.pelanggan') }}" class="btn btn-outline-secondary rounded-3">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 22%;">Nama & Username</th>
                        <th style="width: 18%;">Nomor WhatsApp</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 12%;">Total Pesanan</th>
                        <th style="width: 23%;">Alamat Pengiriman</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $pelanggan)
                    <tr class="pelanggan-row">
                        <td class="fw-bold">{{ $customers->firstItem() + $index }}</td>
                        <td class="text-start ps-3">
                            <strong class="text-dark">{{ $pelanggan->name }}</strong>
                            @if($pelanggan->username)
                                <div class="small text-muted"><span class="badge bg-light text-dark border">@<span>{{ $pelanggan->username }}</span></span></div>
                            @endif
                        </td>
                        <td class="text-start">
                            <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success me-1"></i> {{ $pelanggan->whatsapp_number ?? '-' }}</span>
                        </td>
                        <td class="text-start small text-muted">
                            <i class="fa-solid fa-envelope text-secondary me-1"></i> {{ $pelanggan->email }}
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill fw-bold">
                                {{ $pelanggan->orders_count ?? 0 }} Pesanan
                            </span>
                        </td>
                        <td class="text-start small text-muted">
                            {{ $pelanggan->alamat ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-muted py-5">
                            <i class="fa-solid fa-user-slash fa-3x mb-2 text-muted"></i>
                            <p class="mb-0">Tidak ada data pelanggan yang cocok{{ request('q') ? ' dengan pencarian "' . request('q') . '"' : '' }}.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <div class="small text-muted">
                    Menampilkan {{ $customers->firstItem() }} - {{ $customers->lastItem() }} dari {{ $customers->total() }} pelanggan
                </div>
                <div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection