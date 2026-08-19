@extends('admin.layout')

@section('title', 'WhatsApp Gateway & Notifikasi - Assalam Mebel')

@section('content')
<div class="container-fluid px-0">
    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">
                <i class="fa-brands fa-whatsapp text-success me-2"></i> WhatsApp Gateway & Notifikasi
            </h4>
            <p class="text-muted small mb-0">Kelola koneksi live WhatsApp Web Sidecar, pairing QR/Code, template pesan otomatis, dan log pengiriman.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalHubungkanWA" id="btnOpenConnectModal">
                <i class="fa-solid fa-qrcode"></i>
                <span id="btnConnectLabel">Hubungkan WhatsApp</span>
            </button>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm fw-semibold d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalTestMessage">
                <i class="fa-solid fa-paper-plane"></i> Test Kirim
            </button>
        </div>
    </div>

    <!-- STATUS & STATISTIK CARDS -->
    <div class="row g-3 mb-4">
        <!-- Live Status Card -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 p-3" style="background: linear-gradient(135deg, #ffffff 0%, #fdfaf6 100%); border: 1.5px solid #f0e6db !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold text-muted text-uppercase">Status Gateway</span>
                    <span class="badge rounded-pill px-2.5 py-1 text-white {{ ($gatewayStatus['status'] ?? '') === 'ready' ? 'bg-success' : (($gatewayStatus['status'] ?? '') === 'qr' ? 'bg-warning text-dark' : 'bg-secondary') }} d-flex align-items-center gap-1.5 shadow-sm" id="badgeGatewayLive">
                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" style="width: 8px; height: 8px;"></span>
                        <span id="textGatewayStatus">{{ $gatewayStatus['status_label'] ?? 'Belum Terhubung' }}</span>
                    </span>
                </div>
                <h5 class="fw-bold text-dark mb-1" id="textGatewayNumber">{{ $gatewayStatus['phone_number'] ?? 'Belum Tertaut' }}</h5>
                <span class="text-muted small"><i class="fa-solid fa-server text-primary me-1"></i> Sidecar: <code class="text-dark">127.0.0.1:3000</code></span>
            </div>
        </div>

        <!-- Total Terkirim -->
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white" style="border: 1.5px solid #f0e6db !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold text-muted text-uppercase">Pesan Terkirim</span>
                    <div class="rounded-circle bg-success-subtle text-success p-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ number_format($totalSent) }}</h3>
                <span class="text-success small fw-semibold"><i class="fa-solid fa-check-double me-1"></i> Berhasil Terkirim</span>
            </div>
        </div>

        <!-- Total Gagal -->
        <div class="col-6 col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white" style="border: 1.5px solid #f0e6db !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold text-muted text-uppercase">Pesan Gagal</span>
                    <div class="rounded-circle bg-danger-subtle text-danger p-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>
                <div class="d-flex align-items-baseline gap-2">
                    <h3 class="fw-bold text-danger mb-0">{{ number_format($totalFailed) }}</h3>
                    @if($totalFailed > 0)
                        <form action="{{ route('admin.whatsapp.logs.retry_all') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill py-0 px-2 fw-bold" title="Kirim ulang semua pesan gagal">
                                <i class="fa-solid fa-rotate me-1"></i> Retry All
                            </button>
                        </form>
                    @endif
                </div>
                <span class="text-muted small">Memerlukan retry</span>
            </div>
        </div>

        <!-- Template Aktif -->
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card border-0 rounded-4 shadow-sm h-100 p-3 bg-white" style="border: 1.5px solid #f0e6db !important;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="small fw-bold text-muted text-uppercase">Template Otomatis</span>
                    <div class="rounded-circle bg-primary-subtle text-primary p-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $totalTemplates }} <span class="fs-6 text-muted fw-normal">/ {{ $templates->count() }} Event</span></h3>
                <span class="text-primary small fw-semibold"><i class="fa-solid fa-bolt me-1"></i> Trigger Aktif</span>
            </div>
        </div>
    </div>

    <!-- MAIN NAV TABS -->
    <div class="card border-0 rounded-4 shadow-sm p-4 bg-white" style="border: 1.5px solid #f0e6db !important;">
        <ul class="nav nav-pills gap-2 mb-4" id="waTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-4 fw-bold" id="logs-tab" data-bs-toggle="pill" data-bs-target="#tab-logs" type="button" role="tab">
                    <i class="fa-solid fa-clock-rotate-left me-1"></i> Riwayat & Log Pesan
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-4 fw-bold" id="templates-tab" data-bs-toggle="pill" data-bs-target="#tab-templates" type="button" role="tab">
                    <i class="fa-solid fa-file-lines me-1"></i> Template Pesan Dinamis
                </button>
            </li>
        </ul>

        <div class="tab-content" id="waTabsContent">
            <!-- TAB 1: LOG PESAN -->
            <div class="tab-pane fade show active" id="tab-logs" role="tabpanel">
                <!-- Filter & Search Bar -->
                <div class="row g-2 mb-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <form action="{{ route('admin.whatsapp.index') }}" method="GET" class="d-flex gap-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 rounded-start-pill"><i class="fa-solid fa-search text-muted"></i></span>
                                <input type="text" name="q" class="form-control border-start-0 rounded-end-pill" placeholder="Cari nama, no. HP, pesan..." value="{{ request('q') }}">
                            </div>
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                        </form>
                    </div>
                    <div class="col-12 col-md-6 col-lg-8 d-flex justify-content-md-end gap-2 flex-wrap">
                        <a href="{{ route('admin.whatsapp.index', array_merge(request()->except('status', 'page'), ['status' => ''])) }}" class="btn btn-sm rounded-pill {{ !request('status') ? 'btn-dark' : 'btn-outline-secondary' }} px-3">Semua</a>
                        <a href="{{ route('admin.whatsapp.index', array_merge(request()->except('status', 'page'), ['status' => 'Sent'])) }}" class="btn btn-sm rounded-pill {{ request('status') === 'Sent' ? 'btn-success' : 'btn-outline-success' }} px-3">Terkirim</a>
                        <a href="{{ route('admin.whatsapp.index', array_merge(request()->except('status', 'page'), ['status' => 'Pending'])) }}" class="btn btn-sm rounded-pill {{ request('status') === 'Pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }} px-3">Pending</a>
                        <a href="{{ route('admin.whatsapp.index', array_merge(request()->except('status', 'page'), ['status' => 'Failed'])) }}" class="btn btn-sm rounded-pill {{ request('status') === 'Failed' ? 'btn-danger' : 'btn-outline-danger' }} px-3">Gagal</a>
                    </div>
                </div>

                <!-- Table Logs -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th style="width: 14%;">Waktu</th>
                                <th style="width: 20%;">Penerima</th>
                                <th style="width: 16%;">Event / Template</th>
                                <th style="width: 32%;">Isi Pesan</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 8%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <div class="small fw-semibold text-dark">{{ $log->created_at->format('d M Y') }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->format('H:i:s') }} WIB</div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark small">{{ $log->recipient_name }}</div>
                                        <div class="small text-muted"><i class="fa-brands fa-whatsapp text-success me-1"></i> {{ $log->recipient_phone }}</div>
                                        @if($log->order)
                                            <span class="badge bg-light text-dark border px-2 py-0.5 rounded-pill" style="font-size: 0.7rem;">#{{ $log->order->order_number }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-dark px-2 py-1 rounded-pill small">
                                            {{ $log->template_code ? str_replace('_', ' ', strtoupper($log->template_code)) : 'CUSTOM MESSAGE' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate text-muted small" style="max-width: 300px;" title="{{ $log->message_body }}">
                                            {{ $log->message_body }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($log->status === 'Sent' || $log->status === 'Delivered')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1">
                                                <i class="fa-solid fa-check me-1"></i> Terkirim
                                            </span>
                                        @elseif($log->status === 'Pending')
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1">
                                                <i class="fa-solid fa-clock me-1"></i> Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2.5 py-1" title="{{ $log->response_payload }}">
                                                <i class="fa-solid fa-circle-xmark me-1"></i> Gagal
                                            </span>
                                        @endif
                                        @if($log->retry_count > 0)
                                            <div class="text-muted" style="font-size: 0.68rem;">Retry: {{ $log->retry_count }}x</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-light rounded-circle shadow-sm" data-bs-toggle="modal" data-bs-target="#modalLogDetail{{ $log->id }}" title="Lihat Detail">
                                                <i class="fa-solid fa-eye text-muted"></i>
                                            </button>
                                            @if($log->status === 'Failed')
                                                <form action="{{ route('admin.whatsapp.logs.retry', $log->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" title="Kirim Ulang (Retry)">
                                                        <i class="fa-solid fa-rotate-right"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL DETAIL LOG -->
                                <div class="modal fade" id="modalLogDetail{{ $log->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0">
                                                <h6 class="modal-title fw-bold text-dark">
                                                    <i class="fa-brands fa-whatsapp text-success me-2"></i> Detail Log Pesan #{{ $log->id }}
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="bg-light p-3 rounded-4 mb-3">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted small">Penerima:</span>
                                                        <span class="fw-bold text-dark small">{{ $log->recipient_name }} ({{ $log->recipient_phone }})</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted small">Status:</span>
                                                        <span class="fw-bold small {{ $log->status === 'Sent' ? 'text-success' : ($log->status === 'Failed' ? 'text-danger' : 'text-warning') }}">{{ $log->status }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-muted small">Waktu:</span>
                                                        <span class="small text-muted">{{ $log->created_at->format('d M Y, H:i:s') }} WIB</span>
                                                    </div>
                                                </div>

                                                <label class="form-label small fw-bold text-dark mb-1">Isi Pesan:</label>
                                                <div class="p-3 rounded-4 mb-3 border bg-white" style="white-space: pre-line; font-size: 0.85rem; background-color: #fcfcfc;">
                                                    {{ $log->message_body }}
                                                </div>

                                                @if($log->response_payload)
                                                    <label class="form-label small fw-bold text-dark mb-1">Payload Respon Gateway:</label>
                                                    <pre class="bg-dark text-light p-3 rounded-4 small mb-0" style="font-size: 0.75rem; max-height: 150px; overflow-y: auto;">{{ $log->response_payload }}</pre>
                                                @endif
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                                @if($log->status === 'Failed')
                                                    <form action="{{ route('admin.whatsapp.logs.retry', $log->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">
                                                            <i class="fa-solid fa-rotate-right me-1"></i> Kirim Ulang Sekarang
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-secondary opacity-50"></i>
                                        Belum ada riwayat pesan WhatsApp yang tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>

            <!-- TAB 2: TEMPLATE PESAN -->
            <div class="tab-pane fade" id="tab-templates" role="tabpanel">
                <!-- Info Cheatsheet Variabel -->
                <div class="alert alert-light border rounded-4 mb-4 p-3 d-flex align-items-start gap-3">
                    <div class="bg-primary-subtle text-primary p-2 rounded-3">
                        <i class="fa-solid fa-code"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Daftar Variabel Template Dinamis</h6>
                        <p class="small text-muted mb-2">Gunakan placeholder berikut di dalam template teks pesan. Sistem akan otomatis mengganti dengan data pesanan aktual:</p>
                        <div class="d-flex flex-wrap gap-1">
                            <code class="badge bg-light text-dark border px-2 py-1">{nama}</code>
                            <code class="badge bg-light text-dark border px-2 py-1">{no_pesanan}</code>
                            <code class="badge bg-light text-dark border px-2 py-1">{produk}</code>
                            <code class="badge bg-light text-dark border px-2 py-1">{tahap}</code>
                            <code class="badge bg-light text-dark border px-2 py-1">{catatan}</code>
                            <code class="badge bg-light text-dark border px-2 py-1">{total_harga}</code>
                            <code class="badge bg-light text-dark border px-2 py-1">{dp_amount}</code>
                            <code class="badge bg-light text-dark border px-2 py-1">{link_tracking}</code>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    @foreach($templates as $tmpl)
                        <div class="col-12 col-lg-6">
                            <div class="card h-100 rounded-4 border p-3 shadow-none bg-light">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-primary-subtle text-primary px-2.5 py-1 rounded-pill small fw-bold text-uppercase mb-1">
                                            {{ $tmpl->code }}
                                        </span>
                                        <h6 class="fw-bold text-dark mb-0">{{ $tmpl->name }}</h6>
                                    </div>
                                    <span class="badge {{ $tmpl->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill px-2.5 py-1">
                                        {{ $tmpl->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <span class="text-muted small mb-2"><i class="fa-solid fa-bolt text-warning me-1"></i> Trigger: {{ $tmpl->event_trigger }}</span>

                                <div class="bg-white p-3 rounded-3 border mb-3 flex-grow-1" style="white-space: pre-line; font-size: 0.82rem; font-family: inherit; color: #4a4a4a; max-height: 180px; overflow-y: auto;">
                                    {{ $tmpl->content }}
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#modalEditTemplate{{ $tmpl->id }}">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit Template
                                </button>
                            </div>
                        </div>

                        <!-- MODAL EDIT TEMPLATE -->
                        <div class="modal fade" id="modalEditTemplate{{ $tmpl->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h6 class="modal-title fw-bold text-dark">
                                            <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Template: {{ $tmpl->name }}
                                        </h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.whatsapp.templates.update', $tmpl->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-dark">Nama Template:</label>
                                                <input type="text" name="name" class="form-control rounded-3" value="{{ $tmpl->name }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-dark">Isi Pesan Template:</label>
                                                <textarea name="content" class="form-control rounded-3" rows="8" required style="font-size: 0.9rem;">{{ $tmpl->content }}</textarea>
                                                <small class="text-muted">Mendukung format tebal (*teks*), miring (_teks_), dan variabel placeholder.</small>
                                            </div>

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="activeCheck{{ $tmpl->id }}" {{ $tmpl->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label small fw-semibold text-dark" for="activeCheck{{ $tmpl->id }}">Aktifkan otomatis untuk event ini</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL HUBUNGKAN WHATSAPP (QR CODE & PAIRING CODE) -->
<div class="modal fade" id="modalHubungkanWA" tabindex="-1" aria-labelledby="modalHubungkanWALabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalHubungkanWALabel">
                    <i class="fa-brands fa-whatsapp text-success me-2"></i> Tautkan WhatsApp Web Sidecar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Nav Tabs Modal: QR Code vs Pairing Code -->
                <ul class="nav nav-pills nav-fill bg-light p-1 rounded-pill mb-4" id="pairingTypeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fw-bold small py-2" id="qr-sub-tab" data-bs-toggle="pill" data-bs-target="#sub-tab-qr" type="button" role="tab">
                            <i class="fa-solid fa-qrcode me-1"></i> Scan QR Code
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold small py-2" id="code-sub-tab" data-bs-toggle="pill" data-bs-target="#sub-tab-code" type="button" role="tab">
                            <i class="fa-solid fa-key me-1"></i> Kode Pairing (No. HP)
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pairingTypeTabsContent">
                    <!-- SUB-TAB 1: SCAN QR CODE -->
                    <div class="tab-pane fade show active text-center" id="sub-tab-qr" role="tabpanel">
                        <p class="text-muted small mb-3">Buka WhatsApp di ponsel Anda > Perangkat Tertaut > Tautkan Perangkat, lalu arahkan kamera ke QR berikut:</p>
                        
                        <div class="position-relative d-inline-block p-3 rounded-4 bg-white border shadow-sm mb-3">
                            <div id="qrLoadingSpinner" class="d-none position-absolute top-50 start-50 translate-middle">
                                <div class="spinner-border text-success" role="status">
                                    <span class="visually-hidden">Memuat QR...</span>
                                </div>
                            </div>
                            <img id="imgQrCode" src="" alt="WhatsApp QR Code" class="img-fluid rounded-3" style="width: 220px; height: 220px; object-fit: contain;">
                        </div>

                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnRefreshQr">
                                <i class="fa-solid fa-rotate me-1"></i> Refresh QR Code
                            </button>
                        </div>
                    </div>

                    <!-- SUB-TAB 2: PAIRING CODE -->
                    <div class="tab-pane fade" id="sub-tab-code" role="tabpanel">
                        <p class="text-muted small mb-3">Alternatif jika kamera ponsel bermasalah. Masukkan nomor WhatsApp toko untuk mendapatkan kode pairing 8 karakter:</p>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-dark">Nomor WhatsApp Pengirim:</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted fw-bold">+62</span>
                                <input type="tel" id="inputPairingPhone" class="form-control" placeholder="85234567890" value="{{ preg_replace('/^(62|0)/', '', $gatewayStatus['phone_number'] ?? '85234567890') }}">
                            </div>
                        </div>

                        <button type="button" class="btn btn-success rounded-pill w-100 fw-bold py-2 mb-3 shadow-sm" id="btnGetPairingCode">
                            <i class="fa-solid fa-key me-1"></i> Minta Kode Pairing
                        </button>

                        <div id="boxPairingResult" class="d-none text-center p-3 rounded-4 bg-light border">
                            <span class="small text-muted d-block mb-1">Masukkan kode 8-digit ini di WhatsApp HP:</span>
                            <div class="fs-3 fw-bold text-success tracking-widest my-2 font-monospace" id="textPairingCode">
                                ---- - ----
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" id="btnCopyPairingCode">
                                <i class="fa-solid fa-copy me-1"></i> Salin Kode
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Live Connection Badge In Modal -->
                <div class="mt-4 pt-3 border-top text-center">
                    <span class="badge bg-light text-muted border px-3 py-1.5 rounded-pill small" id="modalLiveStatusBadge">
                        <i class="fa-solid fa-circle-notch fa-spin text-success me-1"></i> Menunggu pemindaian ponsel...
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TEST KIRIM PESAN -->
<div class="modal fade" id="modalTestMessage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold text-dark">
                    <i class="fa-solid fa-paper-plane text-primary me-2"></i> Uji Coba Kirim Pesan WhatsApp
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.whatsapp.send_test') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Nomor WhatsApp Tujuan:</label>
                        <input type="text" name="test_phone" class="form-control rounded-3" placeholder="081234567890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Isi Pesan Uji Coba:</label>
                        <textarea name="test_message" class="form-control rounded-3" rows="4" required>Halo, ini adalah pesan uji coba dari sistem WhatsApp Gateway Assalam Mebel Jepara. Status server: READY ✅</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- LIVE STREAMING / POLLING SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalHubungkanWA = document.getElementById('modalHubungkanWA');
    const imgQrCode = document.getElementById('imgQrCode');
    const qrLoadingSpinner = document.getElementById('qrLoadingSpinner');
    const btnRefreshQr = document.getElementById('btnRefreshQr');
    const btnGetPairingCode = document.getElementById('btnGetPairingCode');
    const inputPairingPhone = document.getElementById('inputPairingPhone');
    const boxPairingResult = document.getElementById('boxPairingResult');
    const textPairingCode = document.getElementById('textPairingCode');
    const btnCopyPairingCode = document.getElementById('btnCopyPairingCode');
    const modalLiveStatusBadge = document.getElementById('modalLiveStatusBadge');
    const badgeGatewayLive = document.getElementById('badgeGatewayLive');
    const textGatewayStatus = document.getElementById('textGatewayStatus');

    let statusCheckInterval = null;

    // Fungsi load QR Code
    function loadQrCode() {
        if (!imgQrCode) return;
        qrLoadingSpinner.classList.remove('d-none');
        imgQrCode.style.opacity = '0.3';

        fetch("{{ route('admin.whatsapp.qr') }}")
            .then(res => res.json())
            .then(data => {
                qrLoadingSpinner.classList.add('d-none');
                imgQrCode.style.opacity = '1';
                if (data.qr) {
                    imgQrCode.src = data.qr;
                }
            })
            .catch(err => {
                qrLoadingSpinner.classList.add('d-none');
                imgQrCode.style.opacity = '1';
                console.error('Error fetching QR:', err);
            });
    }

    // Jalankan polling status sejak halaman dimuat
    startLiveStatusPolling();

    // Event saat modal dibuka
    modalHubungkanWA.addEventListener('show.bs.modal', function () {
        loadQrCode();
    });

    modalHubungkanWA.addEventListener('hide.bs.modal', function () {
        // Modal ditutup
    });

    if (btnRefreshQr) {
        btnRefreshQr.addEventListener('click', function () {
            loadQrCode();
        });
    }

    // Request Pairing Code
    if (btnGetPairingCode) {
        btnGetPairingCode.addEventListener('click', function () {
            const phone = inputPairingPhone.value.trim();
            if (!phone) {
                alert('Silakan masukkan nomor telepon.');
                return;
            }

            btnGetPairingCode.disabled = true;
            btnGetPairingCode.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Menghubungi Sidecar...';

            fetch("{{ route('admin.whatsapp.pairing') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone_number: phone })
            })
            .then(res => res.json())
            .then(data => {
                btnGetPairingCode.disabled = false;
                btnGetPairingCode.innerHTML = '<i class="fa-solid fa-key me-1"></i> Minta Kode Pairing';
                if (data.success && data.code) {
                    textPairingCode.innerText = data.code;
                    boxPairingResult.classList.remove('d-none');
                    modalLiveStatusBadge.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i> Kode aktif! Masukkan di WhatsApp HP.';
                }
            })
            .catch(err => {
                btnGetPairingCode.disabled = false;
                btnGetPairingCode.innerHTML = '<i class="fa-solid fa-key me-1"></i> Minta Kode Pairing';
                alert('Gagal mendapatkan kode pairing.');
            });
        });
    }

    // Salin Kode Pairing
    if (btnCopyPairingCode) {
        btnCopyPairingCode.addEventListener('click', function () {
            const code = textPairingCode.innerText;
            navigator.clipboard.writeText(code).then(() => {
                btnCopyPairingCode.innerHTML = '<i class="fa-solid fa-check me-1"></i> Tersalin!';
                setTimeout(() => {
                    btnCopyPairingCode.innerHTML = '<i class="fa-solid fa-copy me-1"></i> Salin Kode';
                }, 2000);
            });
        });
    }

    // Live Streaming / Polling Status
    function startLiveStatusPolling() {
        if (statusCheckInterval) clearInterval(statusCheckInterval);

        function checkStatus() {
            fetch("{{ route('admin.whatsapp.status') }}")
                .then(res => res.json())
                .then(data => {
                    const numberEl = document.getElementById('textGatewayNumber');
                    if (data.phone_number && numberEl) {
                        numberEl.innerText = data.phone_number;
                    }

                    if (data.status === 'ready' || data.status === 'authenticated') {
                        if (modalLiveStatusBadge) {
                            modalLiveStatusBadge.className = 'badge bg-success-subtle text-success border border-success px-3 py-1.5 rounded-pill small';
                            modalLiveStatusBadge.innerHTML = '<i class="fa-solid fa-circle-check text-success me-1"></i> Berhasil Terhubung!';
                        }
                        
                        if (badgeGatewayLive) {
                            badgeGatewayLive.className = 'badge rounded-pill px-2.5 py-1 text-white bg-success d-flex align-items-center gap-1.5 shadow-sm';
                        }
                        if (textGatewayStatus) {
                            textGatewayStatus.innerText = 'Terhubung (Ready)';
                        }
                        
                        // Auto-close modal jika sedang terbuka
                        const modalInstance = bootstrap.Modal.getInstance(modalHubungkanWA);
                        if (modalInstance && modalHubungkanWA.classList.contains('show')) {
                            setTimeout(() => {
                                modalInstance.hide();
                            }, 800);
                        }
                    } else if (data.status === 'qr') {
                        if (modalLiveStatusBadge) {
                            modalLiveStatusBadge.className = 'badge bg-warning-subtle text-dark border border-warning px-3 py-1.5 rounded-pill small';
                            modalLiveStatusBadge.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-warning me-1"></i> Menunggu pemindaian QR...';
                        }
                        
                        if (badgeGatewayLive) {
                            badgeGatewayLive.className = 'badge rounded-pill px-2.5 py-1 text-dark bg-warning d-flex align-items-center gap-1.5 shadow-sm';
                        }
                        if (textGatewayStatus) {
                            textGatewayStatus.innerText = 'Menunggu Scan QR';
                        }
                    } else {
                        if (modalLiveStatusBadge) {
                            modalLiveStatusBadge.className = 'badge bg-light text-muted border px-3 py-1.5 rounded-pill small';
                            modalLiveStatusBadge.innerHTML = '<i class="fa-solid fa-circle-xmark text-danger me-1"></i> Belum Terhubung';
                        }
                        
                        if (badgeGatewayLive) {
                            badgeGatewayLive.className = 'badge rounded-pill px-2.5 py-1 text-white bg-secondary d-flex align-items-center gap-1.5 shadow-sm';
                        }
                        if (textGatewayStatus) {
                            textGatewayStatus.innerText = 'Belum Terhubung';
                        }
                    }
                })
                .catch(e => console.log('Polling note:', e));
        }

        checkStatus();
        statusCheckInterval = setInterval(checkStatus, 3000);
    }
});
</script>
@endsection
