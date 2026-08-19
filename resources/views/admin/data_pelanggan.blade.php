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
        <div>
            <span class="badge px-3 py-2 rounded-pill fw-bold text-white" style="background-color: var(--primary-color);">
                <i class="fa fa-users me-1"></i> Total: {{ $customers->count() }} Pelanggan
            </span>
        </div>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 8%;">No</th>
                        <th style="width: 25%;">Nama Lengkap</th>
                        <th style="width: 20%;">Nomor WhatsApp</th>
                        <th style="width: 22%;">Email</th>
                        <th style="width: 25%;">Alamat Pengiriman</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $index => $pelanggan)
                    <tr class="pelanggan-row">
                        <td class="fw-bold">{{ $index + 1 }}</td>
                        <td class="text-start ps-3">
                            <strong class="text-dark">{{ $pelanggan->name }}</strong>
                        </td>
                        <td class="text-start">
                            <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success me-1"></i> {{ $pelanggan->whatsapp_number ?? '-' }}</span>
                        </td>
                        <td class="text-start small text-muted">
                            <i class="fa-solid fa-envelope text-secondary me-1"></i> {{ $pelanggan->email }}
                        </td>
                        <td class="text-start small text-muted">
                            {{ $pelanggan->alamat ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-muted py-4">Belum ada data akun pelanggan terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection