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

    <!-- KOTAK PENCARIAN PESANAN MASUK -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
        <form action="{{ route('admin.pesanan.masuk') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nomor pesanan, nama pemesan, nomor WhatsApp, kategori mebel, atau status...">
                </div>
            </div>
            <div class="col-md-3 col-lg-2 d-flex gap-2">
                <button type="submit" class="btn btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if(request('q'))
                    <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-outline-secondary rounded-3" title="Reset Pencarian">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
        </form>

        @if(request('q'))
            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top flex-wrap gap-2">
                <div class="small text-muted">
                    <i class="fa-solid fa-filter me-1 text-primary"></i> Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong> ({{ $listPesananMasuk->count() }} pesanan ditemukan)
                </div>
                <a href="{{ route('admin.pesanan.masuk') }}" class="small text-decoration-none fw-bold" style="color: var(--primary-color);">
                    <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Pesanan
                </a>
            </div>
        @endif
    </div>

    <!-- TABEL PESANAN MASUK DINAMIS -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 12%;">No. Pesanan</th>
                        <th style="width: 18%;">Nama & Kontak</th>
                        <th style="width: 22%;">Mebel Custom</th>
                        <th style="width: 18%;">Status & Pembayaran</th>
                        <th style="width: 30%;">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listPesananMasuk as $item)
                    <tr>
                        <td class="fw-bold" style="color: var(--primary-color);">
                            #{{ $item->order_number }}
                            <div class="small text-muted fw-normal">{{ $item->created_at ? $item->created_at->format('d M Y H:i') : '-' }}</div>
                        </td>
                        <td class="text-start ps-3">
                            <strong class="text-dark">{{ $item->recipient_name ?? $item->user->name }}</strong><br>
                            <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success"></i> {{ $item->recipient_phone ?? $item->user->whatsapp_number }}</span>
                            @if($item->user && $item->user->username)
                                <div class="small text-muted">@<span>{{ $item->user->username }}</span></div>
                            @endif
                        </td>
                        <td class="text-start">
                            <strong class="text-dark">{{ $item->customDesign->category ?? 'Custom Mebel' }}</strong>
                            @if($item->customDesign && $item->customDesign->produk)
                                <span class="badge bg-light text-secondary border ms-1">{{ $item->customDesign->produk->name }}</span>
                            @endif
                            <br>
                            <span class="small text-muted">{{ $item->customDesign->wood_material ?? 'Jati' }} ({{ $item->customDesign->length_cm ?? 0 }}x{{ $item->customDesign->width_cm ?? 0 }}x{{ $item->customDesign->height_cm ?? 0 }}cm)</span>
                            <div class="small text-muted">Warna: {{ $item->customDesign->color_name ?? '-' }}</div>
                        </td>
                        <td>
                            <strong class="text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong><br>
                            
                            <!-- Order Status Badge -->
                            @if($item->order_status === 'Menunggu Konfirmasi')
                                <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-1 rounded-pill small mb-1">
                                    <i class="fa-regular fa-clock me-1"></i>Menunggu Konfirmasi
                                </span><br>
                            @elseif($item->order_status === 'Diterima')
                                <span class="badge bg-info-subtle text-info px-2 py-1 rounded-pill small mb-1">
                                    <i class="fa-solid fa-check me-1"></i>Pesanan Diterima
                                </span><br>
                            @elseif($item->order_status === 'Ditolak')
                                <span class="badge bg-danger-subtle text-danger px-2 py-1 rounded-pill small mb-1" title="{{ $item->rejection_reason }}">
                                    <i class="fa-solid fa-xmark me-1"></i>Ditolak
                                </span><br>
                            @else
                                <span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill small mb-1">
                                    {{ $item->order_status ?? 'Diproses' }}
                                </span><br>
                            @endif

                            <!-- Payment Status Badge -->
                            @if($item->payment_status === 'Lunas')
                                <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill small">
                                    <i class="fa-solid fa-check-double me-1"></i>Lunas
                                </span>
                            @elseif($item->payment_status === 'Menunggu Verifikasi Pelunasan')
                                <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-0.5 rounded-pill small">
                                    <i class="fa-solid fa-receipt me-1"></i>Verif. Pelunasan
                                </span>
                            @elseif($item->payment_status === 'DP Terverifikasi')
                                <span class="badge bg-success-subtle text-success px-2 py-0.5 rounded-pill small">
                                    <i class="fa-solid fa-check me-1"></i>DP Terverifikasi
                                </span>
                            @elseif($item->payment_status === 'Menunggu Verifikasi DP')
                                <span class="badge bg-warning-subtle text-warning-emphasis px-2 py-0.5 rounded-pill small">
                                    <i class="fa-solid fa-receipt me-1"></i>Verif. Bukti DP
                                </span>
                            @elseif($item->payment_status === 'Menunggu Pembayaran DP')
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-0.5 rounded-pill small">
                                    Menunggu DP
                                </span>
                            @else
                                <span class="badge bg-light text-dark px-2 py-0.5 rounded-pill small border">{{ $item->payment_status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <!-- 1. JIKA STATUS MENUNGGU KONFIRMASI: TOMBOL TERIMA / TOLAK -->
                                @if($item->order_status === 'Menunggu Konfirmasi')
                                    <div class="d-flex gap-1 justify-content-center">
                                        <form action="{{ route('admin.pesanan.confirm', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA pesanan #{{ $item->order_number }}?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-3 small fw-semibold">
                                                <i class="fa-solid fa-check me-1"></i> Terima
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-3 small fw-semibold"
                                                data-bs-toggle="modal" data-bs-target="#modalTolakPesanan{{ $item->id }}">
                                            <i class="fa-solid fa-xmark me-1"></i> Tolak
                                        </button>
                                    </div>
                                @endif

                                <!-- 2. JIKA ADA BUKTI DP / MENUNGGU VERIFIKASI DP -->
                                @if($item->payment_status === 'Menunggu Verifikasi DP' || ($item->order_status === 'Diterima' && $item->dp_receipt_proof))
                                    <button type="button" class="btn btn-sm btn-warning text-dark rounded-3 small fw-semibold"
                                            data-bs-toggle="modal" data-bs-target="#modalVerifikasiDP{{ $item->id }}">
                                        <i class="fa-solid fa-receipt me-1"></i> Verifikasi Bukti DP
                                    </button>
                                @endif

                                <!-- 3. JIKA MENUNGGU VERIFIKASI PELUNASAN -->
                                @if($item->payment_status === 'Menunggu Verifikasi Pelunasan' || $item->final_receipt_proof)
                                    <button type="button" class="btn btn-sm btn-info text-white rounded-3 small fw-semibold"
                                            data-bs-toggle="modal" data-bs-target="#modalVerifikasiPelunasan{{ $item->id }}">
                                        <i class="fa-solid fa-file-invoice-dollar me-1"></i> Verifikasi Pelunasan
                                    </button>
                                @endif
                                
                                <!-- 4. TOMBOL PROGRES PRODUKSI -->
                                @if($item->order_status !== 'Ditolak')
                                    <a href="{{ route('admin.progres.produksi', $item->id) }}" class="btn btn-sm btn-outline-dark rounded-3 small fw-semibold">
                                        <i class="fa-solid fa-gears me-1"></i> Kelola Progres
                                    </a>
                                @endif

                                @if($item->order_status === 'Ditolak' && $item->rejection_reason)
                                    <div class="alert alert-danger p-1 mb-0 small text-start">
                                        <strong>Alasan Ditolak:</strong> {{ $item->rejection_reason }}
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- MODAL TOLAK PESANAN -->
                    <div class="modal fade" id="modalTolakPesanan{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow-lg border-0">
                                <div class="modal-header bg-danger text-white rounded-top-4">
                                    <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-triangle-exclamation me-1"></i> Tolak Pesanan #{{ $item->order_number }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.pesanan.reject', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4 text-start">
                                        <p class="text-muted small mb-3">Harap berikan alasan penolakan yang jelas agar pelanggan memahami mengapa pesanannya belum dapat diproses.</p>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                                            <textarea name="rejection_reason" class="form-control rounded-3" rows="3" required placeholder="Contoh: Stok bahan kayu jati kualitas A saat ini sedang kosong, atau dimensi yang diajukan melebihi kapasitas pengiriman."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-danger btn-sm rounded-3 px-4">Tolak Pesanan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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

                                        <!-- Bukti Transfer DP -->
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Bukti Transfer DP Pelanggan:</label>
                                            @if($item->dp_receipt_proof)
                                                <div class="p-2 border rounded-3 text-center bg-light">
                                                    <a href="{{ asset('storage/' . $item->dp_receipt_proof) }}" target="_blank" title="Klik untuk memperbesar">
                                                        <img src="{{ asset('storage/' . $item->dp_receipt_proof) }}" alt="Bukti DP" class="img-fluid rounded-3" style="max-height: 220px; object-fit: contain;">
                                                    </a>
                                                    <div class="mt-1 small text-muted"><i class="fa-solid fa-magnifying-glass me-1"></i> Klik gambar untuk ukuran penuh</div>
                                                </div>
                                            @else
                                                <div class="alert alert-warning small p-2 mb-0">
                                                    <i class="fa-solid fa-info-circle me-1"></i> Pelanggan belum mengunggah foto bukti transfer DP.
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Tindakan Admin:</label>
                                            <p class="small text-muted mb-0">Dengan memverifikasi pembayaran DP, status pesanan akan otomatis dialihkan menjadi <strong>DP Terverifikasi</strong> dan masuk ke antrean pengerjaan.</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4" style="background-color: var(--primary-color); border: none;">
                                            <i class="fa-solid fa-check me-1"></i> Verifikasi & Masuk Produksi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL VERIFIKASI PEMBAYARAN PELUNASAN -->
                    <div class="modal fade" id="modalVerifikasiPelunasan{{ $item->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow-lg border-0">
                                <div class="modal-header bg-success text-white rounded-top-4">
                                    <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-receipt me-1"></i> Verifikasi Pelunasan #{{ $item->order_number }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.pesanan.verify_pelunasan', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body p-4 text-start">
                                        <div class="mb-3 border-bottom pb-2">
                                            <span class="small text-muted d-block">Nama Pelanggan:</span>
                                            <strong class="text-dark fs-6">{{ $item->recipient_name ?? $item->user->name }}</strong>
                                        </div>
                                        <div class="row mb-3 border-bottom pb-2">
                                            <div class="col-6">
                                                <span class="small text-muted d-block">Sisa Tagihan Pelunasan:</span>
                                                <strong class="text-danger">Rp {{ number_format($item->remaining_payment, 0, ',', '.') }}</strong>
                                            </div>
                                            <div class="col-6">
                                                <span class="small text-muted d-block">Status Saat Ini:</span>
                                                <span class="badge bg-warning-subtle text-warning-emphasis">{{ $item->payment_status }}</span>
                                            </div>
                                        </div>

                                        <!-- Bukti Transfer Pelunasan -->
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Bukti Transfer Pelunasan:</label>
                                            @if($item->final_receipt_proof)
                                                <div class="p-2 border rounded-3 text-center bg-light">
                                                    <a href="{{ asset('storage/' . $item->final_receipt_proof) }}" target="_blank" title="Klik untuk memperbesar">
                                                        <img src="{{ asset('storage/' . $item->final_receipt_proof) }}" alt="Bukti Pelunasan" class="img-fluid rounded-3" style="max-height: 220px; object-fit: contain;">
                                                    </a>
                                                    <div class="mt-1 small text-muted"><i class="fa-solid fa-magnifying-glass me-1"></i> Klik gambar untuk ukuran penuh</div>
                                                </div>
                                            @else
                                                <div class="alert alert-warning small p-2 mb-0">
                                                    <i class="fa-solid fa-info-circle me-1"></i> Pelanggan belum mengunggah foto bukti transfer pelunasan.
                                                </div>
                                            @endif
                                        </div>

                                        <p class="small text-muted mb-0">Verifikasi bahwa dana sisa pelunasan telah masuk ke rekening usaha sebelum menandai lunas.</p>
                                    </div>
                                    <div class="modal-footer bg-light rounded-bottom-4">
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-success btn-sm rounded-3 px-4">
                                            <i class="fa-solid fa-check-double me-1"></i> Verifikasi Lunas
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="5" class="text-muted py-5">
                            <i class="fa-solid {{ request('q') ? 'fa-magnifying-glass' : 'fa-clipboard-check' }} fa-3x mb-2 text-muted"></i>
                            @if(request('q'))
                                <h6 class="fw-bold text-dark mt-2 mb-1">Tidak Ditemukan</h6>
                                <p class="mb-3">Tidak ada pesanan masuk yang cocok dengan pencarian "<strong>{{ request('q') }}</strong>".</p>
                                <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-outline-dark btn-sm rounded-3 px-3">
                                    <i class="fa-solid fa-rotate-left me-1"></i> Reset Pencarian
                                </a>
                            @else
                                <p class="mb-0">Belum ada pesanan masuk dalam antrean.</p>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection