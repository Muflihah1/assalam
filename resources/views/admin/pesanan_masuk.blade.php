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
</style>

<div class="container-fluid px-0 py-2">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">KELOLA PESANAN MASUK & VERIFIKASI</h4>
            <p class="small text-muted mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Verifikasi rincian pesanan baru, cek pembayaran DP, serta masukkan ke antrean produksi.</p>
        </div>
    </div>

    <!-- TABEL PESANAN MASUK DINAMIS -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 12%;">No. Pesanan</th>
                        <th style="width: 20%;">Nama & Kontak</th>
                        <th style="width: 22%;">Mebel Custom</th>
                        <th style="width: 20%;">Total & DP</th>
                        <th style="width: 26%;">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listPesananMasuk as $item)
                    <tr>
                        <td class="fw-bold" style="color: var(--primary-color);">#{{ $item->order_number }}</td>
                        <td class="text-start ps-3">
                            <strong class="text-dark">{{ $item->recipient_name ?? $item->user->name }}</strong><br>
                            <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success"></i> {{ $item->recipient_phone ?? $item->user->whatsapp_number }}</span>
                        </td>
                        <td class="text-start">
                            <strong class="text-dark">{{ $item->customDesign->category ?? 'Custom Mebel' }}</strong><br>
                            <span class="small text-muted">{{ $item->customDesign->wood_material ?? 'Jati' }} ({{ $item->customDesign->length_cm ?? 0 }}x{{ $item->customDesign->width_cm ?? 0 }}x{{ $item->customDesign->height_cm ?? 0 }}cm)</span>
                        </td>
                        <td>
                            <strong class="text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong><br>
                            @if($item->payment_status === 'DP Terverifikasi')
                                <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill small">DP Terverifikasi</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-0.5 rounded-pill small">{{ $item->payment_status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <!-- Tombol Verifikasi DP -->
                                <button type="button" class="btn btn-sm btn-dark rounded-3 small fw-semibold"
                                        data-bs-toggle="modal" data-bs-target="#modalVerifikasiDP{{ $item->id }}"
                                        style="background-color: var(--primary-color); border: none;">
                                    <i class="fa-solid fa-money-bill-wave me-1"></i> Verifikasi Pembayaran DP
                                </button>
                                
                                <!-- Tombol Alihkan ke Progres Produksi -->
                                <a href="{{ route('admin.progres.produksi', $item->id) }}" class="btn btn-sm btn-outline-dark rounded-3 small fw-semibold">
                                    <i class="fa-solid fa-gears me-1"></i> Kelola Progres Produksi
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL VERIFIKASI PEMBAYARAN DP -->
                    <div class="modal fade" id="modalVerifikasiDP{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow-lg border-0">
                                <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                                    <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-money-bill-wave me-1"></i> Verifikasi Pembayaran DP #{{ $item->order_number }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.pesanan.verify_dp', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4 text-start">
                                        <div class="mb-3 border-bottom pb-2">
                                            <span class="small text-muted d-block">Nama Pelanggan:</span>
                                            <strong class="text-dark fs-6">{{ $item->recipient_name ?? $item->user->name }}</strong>
                                        </div>
                                        <div class="row mb-3 border-bottom pb-2">
                                            <div class="col-6">
                                                <span class="small text-muted d-block">Tagihan Total:</span>
                                                <strong class="text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <span class="small text-muted d-block">Wajib DP (50%):</span>
                                                <strong class="text-success">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                        <div class="mb-3 p-3 rounded-3" style="background-color: var(--light-bg); border: 1px solid var(--light-border);">
                                            <span class="small text-muted d-block mb-1">Rincian Spesifikasi:</span>
                                            <p class="small text-dark mb-0">
                                                {{ $item->customDesign->category ?? '-' }} | {{ $item->customDesign->wood_material ?? '-' }} | Tone: {{ $item->customDesign->color_name ?? '-' }}
                                            </p>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Ubah Status Pembayaran & Antrean:</label>
                                            <select name="status_progres" class="form-select rounded-3" required>
                                                <option value="DP Terverifikasi - Masuk Pengerjaan" {{ $item->payment_status == 'DP Terverifikasi' ? 'selected' : '' }}>DP Terverifikasi - Masuk Pengerjaan</option>
                                                <option value="Pembayaran DP Ditolak">Pembayaran DP Ditolak (Bukti Tidak Valid)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4" style="background-color: var(--primary-color); border: none;">Simpan & Update Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-muted py-5">
                            <i class="fa-solid fa-clipboard-check fa-3x mb-2 text-muted"></i>
                            <p class="mb-0">Belum ada pesanan masuk dalam antrean.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection