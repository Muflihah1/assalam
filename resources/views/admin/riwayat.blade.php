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

    .stat-card-riwayat {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        padding: 16px 20px;
        transition: all 0.25s ease;
    }

    .stat-card-riwayat:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(93, 64, 55, 0.08);
        border-color: var(--primary-color);
    }

    .stat-card-riwayat .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .table-riwayat thead th {
        background-color: #faf6f0 !important;
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--light-border);
        padding: 14px 14px;
    }

    .table-riwayat tbody td {
        padding: 14px 14px;
        vertical-align: middle;
        border-bottom: 1px solid var(--light-border);
    }

    .table-riwayat tbody tr:hover {
        background-color: rgba(93, 64, 55, 0.02) !important;
    }

    /* Action dots button (3-dots) */
    .action-dots-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #ffffff;
        border: 1.5px solid var(--light-border);
        color: var(--text-dark);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .action-dots-btn:hover, .action-dots-btn:focus {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    .order-avatar {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(93, 64, 55, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">RIWAYAT SEMUA TRANSAKSI</h4>
            <p class="small text-muted mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Rekapitulasi seluruh transaksi pesanan mebel kayu jati, pembayaran DP/Pelunasan, dan status pengerjaan.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-outline-dark px-3 py-2 rounded-3 fw-semibold shadow-sm" style="border-color: var(--light-border);">
                <i class="fa-solid fa-inbox me-1 text-warning"></i> Cek Pesanan Aktif
            </a>
            <a href="{{ route('admin.katalog') }}" class="btn btn-dark px-3 py-2 rounded-3 fw-bold shadow-sm" style="background-color: var(--primary-color); border: none;">
                <i class="fa-solid fa-couch me-1"></i> Katalog Mebel
            </a>
        </div>
    </div>

    <!-- STATISTIK RINGKAS RIWAYAT -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card-riwayat d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Transaksi Terekam</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $listRiwayat->count() }} <span class="fs-6 fw-normal text-muted">Pesanan</span></h3>
                </div>
                <div class="icon-box" style="background-color: rgba(93, 64, 55, 0.1); color: var(--primary-color);">
                    <i class="fa-solid fa-receipt"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-riwayat d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Pesanan Selesai / Tuntas</span>
                    @php
                        $selesaiCount = $listRiwayat->where('production_status', 'Selesai')->count();
                    @endphp
                    <h3 class="fw-bold text-success mb-0">{{ $selesaiCount }} <span class="fs-6 fw-normal text-muted">Selesai</span></h3>
                </div>
                <div class="icon-box" style="background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-riwayat d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1">Total Akumulasi Transaksi</span>
                    @php
                        $totalOmzet = $listRiwayat->sum('total_price');
                    @endphp
                    <h5 class="fw-bold text-dark mb-0" style="color: var(--primary-color) !important;">
                        Rp {{ number_format($totalOmzet, 0, ',', '.') }}
                    </h5>
                    <small class="text-muted" style="font-size: 0.75rem;">DP & Pelunasan via DANA</small>
                </div>
                <div class="icon-box" style="background-color: rgba(217, 119, 6, 0.1); color: #d97706;">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- KOTAK PENCARIAN RIWAYAT ADMIN -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
        <form action="{{ route('admin.riwayat') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9 col-lg-10">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0 ps-0" placeholder="Cari nomor pesanan (#...), nama pemesan, kontak WA, kategori mebel, atau status...">
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

    <!-- TABEL MODERN RIWAYAT TRANSAKSI DENGAN AKSI TITIK TIGA -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background-color: var(--light-card); border: 1.5px solid var(--light-border) !important;">
        <div class="table-responsive">
            <table class="table table-hover table-riwayat align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 150px;">No. Pesanan</th>
                        <th>Pelanggan & Kontak</th>
                        <th>Spesifikasi Mebel Jati</th>
                        <th>Status Produksi & Pembayaran</th>
                        <th>Total Nilai & Pembayaran</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($listRiwayat as $item)
                    @php
                        $cleanPhone = preg_replace('/[^0-9]/', '', $item->recipient_phone ?? $item->user->whatsapp_number ?? '');
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $words = explode(' ', trim($item->recipient_name ?? $item->user->name ?? 'User'));
                        $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                    @endphp
                    <tr>
                        <!-- 1. NO PESANAN & TANGGAL -->
                        <td>
                            <strong class="text-dark d-block fs-6" style="color: var(--primary-color) !important;">
                                #{{ $item->order_number }}
                            </strong>
                            <span class="small text-muted" style="font-size: 0.78rem;">
                                {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }} WIB
                            </span>
                            <div class="mt-1">
                                <span class="badge px-2 py-0.5 rounded-pill small border" style="background-color: var(--wood-bg); color: var(--text-dark); font-size: 0.72rem;">
                                    {{ $item->current_stage ?? 'Tahap 1' }}
                                </span>
                            </div>
                        </td>

                        <!-- 2. PELANGGAN & KONTAK -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="order-avatar">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <strong class="text-dark d-block" style="font-size: 0.95rem;">
                                        {{ $item->recipient_name ?? $item->user->name }}
                                    </strong>
                                    @if($item->user && $item->user->username)
                                        <span class="small text-muted d-block" style="font-size: 0.75rem;">@<span>{{ $item->user->username }}</span></span>
                                    @endif
                                    @if($cleanPhone)
                                        <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($item->recipient_name ?? $item->user->name) }},%20kami%20dari%20Assalam%20Mebel%20mengenai%20pesanan%20%23{{ $item->order_number }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success rounded-pill px-2 py-0.5 mt-1" 
                                           style="font-size: 0.75rem;">
                                            <i class="fa-brands fa-whatsapp me-1"></i>{{ $item->recipient_phone ?? $item->user->whatsapp_number }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- 3. SPESIFIKASI MEBEL JATI -->
                        <td>
                            <strong class="text-dark d-block">{{ $item->customDesign->category ?? 'Custom Mebel' }}</strong>
                            <div class="small text-muted" style="font-size: 0.8rem;">
                                <span><i class="fa-solid fa-tree me-1 text-success"></i>Kayu Jati Solid</span>
                                @if($item->customDesign && $item->customDesign->length_cm)
                                    <span class="ms-1">({{ $item->customDesign->length_cm }}×{{ $item->customDesign->width_cm }}×{{ $item->customDesign->height_cm }} cm)</span>
                                @endif
                            </div>
                            @if($item->customDesign && $item->customDesign->color_name)
                                <div class="small text-muted" style="font-size: 0.78rem;">
                                    <span>Tone Finishing: <strong>{{ $item->customDesign->color_name }}</strong></span>
                                </div>
                            @endif
                            @php
                                $riwayatRowNote = $item->customer_notes ?? ($item->customDesign->notes ?? null);
                            @endphp
                            @if($riwayatRowNote)
                                <div class="mt-2 p-1.5 rounded-2 border border-warning-subtle" style="background-color: #fffbeb; font-size: 0.76rem;">
                                    <span class="fw-bold text-dark"><i class="fa-solid fa-comment-dots text-warning me-1"></i>Catatan:</span>
                                    <span class="text-secondary fst-italic">"{{ Str::limit($riwayatRowNote, 50) }}"</span>
                                </div>
                            @endif
                        </td>

                        <!-- 4. STATUS PRODUKSI & PEMBAYARAN -->
                        <td>
                            <div class="d-flex flex-column gap-1 align-items-start">
                                @if($item->production_status === 'Selesai')
                                    <span class="badge bg-success-subtle text-success px-2.5 py-1 rounded-pill small border border-success">
                                        <i class="fa-solid fa-circle-check me-1"></i> Produksi Selesai
                                    </span>
                                @elseif($item->production_status === 'Sedang Dikerjakan')
                                    <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill small border border-primary">
                                        <i class="fa-solid fa-hammer me-1"></i> Sedang Dikerjakan
                                    </span>
                                @else
                                    <span class="badge bg-light text-dark px-2.5 py-1 rounded-pill small border">
                                        {{ $item->production_status ?? 'Menunggu' }}
                                    </span>
                                @endif

                                @if($item->payment_status === 'Lunas')
                                    <span class="badge bg-success text-white px-2 py-0.5 rounded-pill small" style="font-size: 0.72rem;">
                                        <i class="fa-solid fa-check-double me-1"></i>Lunas (100%)
                                    </span>
                                @elseif($item->payment_status === 'DP Terverifikasi')
                                    <span class="badge bg-info text-white px-2 py-0.5 rounded-pill small" style="font-size: 0.72rem;">
                                        DP Terverifikasi (50%)
                                    </span>
                                @elseif($item->payment_status === 'Menunggu Verifikasi Pelunasan')
                                    <span class="badge bg-warning text-dark px-2 py-0.5 rounded-pill small" style="font-size: 0.72rem;">
                                        Verif Pelunasan
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary px-2 py-0.5 rounded-pill small" style="font-size: 0.72rem;">
                                        {{ $item->payment_status }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- 5. TOTAL NILAI & RINCIAN DP/PELUNASAN -->
                        <td>
                            <div>
                                <strong class="text-dark fs-6">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                <div class="small text-muted" style="font-size: 0.75rem;">
                                    <span>DP: <strong class="text-success">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong></span>
                                    @if($item->remaining_payment > 0)
                                        <span class="ms-1">| Sisa: <strong class="text-danger">Rp {{ number_format($item->remaining_payment, 0, ',', '.') }}</strong></span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- 6. AKSI DENGAN TITIK TIGA -->
                        <td class="text-center pe-3">
                            <div class="dropdown">
                                <button class="action-dots-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Menu Aksi">
                                    <i class="fa-solid fa-ellipsis-vertical fs-6"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-3 border py-1.5" style="min-width: 220px; font-size: 0.85rem;">
                                    <li>
                                        <button class="dropdown-item py-2 d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalDetailRiwayat{{ $item->id }}">
                                            <i class="fa-solid fa-circle-info text-primary" style="width: 18px;"></i>
                                            <span>Lihat Rincian Pesanan</span>
                                        </button>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('admin.progres.produksi', $item->id) }}">
                                            <i class="fa-solid fa-gears text-warning" style="width: 18px;"></i>
                                            <span>Kelola Progres Produksi</span>
                                        </a>
                                    </li>
                                    @if($cleanPhone)
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center gap-2 text-success" href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($item->recipient_name ?? $item->user->name) }},%20kami%20dari%20Assalam%20Mebel%20mengenai%20pesanan%20%23{{ $item->order_number }}" target="_blank">
                                            <i class="fa-brands fa-whatsapp text-success" style="width: 18px;"></i>
                                            <span>Chat WhatsApp Pemesan</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form action="{{ route('admin.riwayat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data riwayat pesanan #{{ $item->order_number }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger">
                                                <i class="fa-solid fa-trash text-danger" style="width: 18px;"></i>
                                                <span class="fw-semibold">Hapus Riwayat Pesanan</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fa-solid {{ request('q') ? 'fa-magnifying-glass' : 'fa-clock-rotate-left' }} fa-3x mb-3 text-secondary"></i>
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
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL DETAIL RIWAYAT TRANSAKSI -->
@foreach($listRiwayat as $item)
<div class="modal fade" id="modalDetailRiwayat{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header border-0 pb-0">
                <div>
                    <span class="badge bg-primary-subtle text-primary border px-2.5 py-1 rounded-pill small mb-1">
                        Transaksi #{{ $item->order_number }}
                    </span>
                    <h5 class="modal-title fw-bold text-dark">{{ $item->customDesign->category ?? 'Custom Mebel Jati' }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-user me-1 text-primary"></i> Data Pemesan</h6>
                            <p class="small mb-1"><strong>Nama:</strong> {{ $item->recipient_name ?? $item->user->name }}</p>
                            <p class="small mb-1"><strong>WhatsApp:</strong> {{ $item->recipient_phone ?? $item->user->whatsapp_number ?? '-' }}</p>
                            <p class="small mb-1"><strong>Alamat Pengiriman:</strong></p>
                            <p class="small text-muted bg-white p-2 rounded border mb-0">{{ $item->shipping_address ?? 'Tidak ada alamat tercatat' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border h-100">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-tree me-1 text-success"></i> Spesifikasi Kayu Jati</h6>
                            <p class="small mb-1"><strong>Bahan:</strong> 100% Kayu Jati Solid Grade A</p>
                            <p class="small mb-1"><strong>Ukuran P×L×T:</strong> {{ $item->customDesign->length_cm ?? 0 }} × {{ $item->customDesign->width_cm ?? 0 }} × {{ $item->customDesign->height_cm ?? 0 }} cm</p>
                            <p class="small mb-0"><strong>Warna Finishing:</strong> {{ $item->customDesign->color_name ?? 'Natural Jati' }}</p>
                        </div>
                    </div>

                    @php
                        $riwayatModalNote = $item->customer_notes ?? ($item->customDesign->notes ?? null);
                    @endphp
                    @if($riwayatModalNote)
                        <div class="col-12">
                            <div class="p-3 rounded-3 border border-warning" style="background-color: #fffbeb;">
                                <div class="d-flex align-items-center justify-content-between mb-1.5 flex-wrap gap-1">
                                    <span class="badge bg-warning text-dark px-2.5 py-1 fw-bold">
                                        <i class="fa-solid fa-note-sticky me-1"></i> CATATAN KHUSUS PELANGGAN
                                    </span>
                                    <span class="badge bg-white text-secondary border border-warning-subtle small px-2 py-0.5">Workshop Request</span>
                                </div>
                                <div class="p-2.5 bg-white rounded border border-warning-subtle text-dark fw-medium" style="font-size: 0.9rem; line-height: 1.45;">
                                    "{{ $riwayatModalNote }}"
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold text-dark mb-2"><i class="fa-solid fa-receipt me-1 text-warning"></i> Rincian Finansial & Pembayaran</h6>
                            <div class="row text-center g-2">
                                <div class="col-md-4">
                                    <div class="bg-white p-2.5 rounded border">
                                        <small class="text-muted d-block">Total Nilai Tagihan</small>
                                        <strong class="fs-6 text-dark">Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-white p-2.5 rounded border">
                                        <small class="text-muted d-block">Uang Muka (DP 50%)</small>
                                        <strong class="fs-6 text-success">Rp {{ number_format($item->dp_amount, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="bg-white p-2.5 rounded border">
                                        <small class="text-muted d-block">Sisa Pelunasan (50%)</small>
                                        <strong class="fs-6 text-danger">Rp {{ number_format($item->remaining_payment, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-3" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('admin.progres.produksi', $item->id) }}" class="btn btn-dark btn-sm px-4 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                        <i class="fa-solid fa-gears me-1"></i> Buka Workshop Progres
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection