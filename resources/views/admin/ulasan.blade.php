@extends('admin.layout')

@section('content')
<style>
    .review-moderation-card {
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        background: var(--light-card);
    }
    .review-stars i { color: #f59e0b; }
    .review-stars .star-empty i { color: #d1d5db; }
    .filter-chip {
        border-radius: 999px;
        padding: 6px 16px;
        font-weight: 600;
        font-size: 0.8rem;
        text-decoration: none;
        border: 1.5px solid var(--light-border);
        background: #ffffff;
        color: var(--text-main);
        transition: 0.15s;
    }
    .filter-chip.active, .filter-chip:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }
    .review-avatar {
        width: 40px; height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary-color);
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- TITLE -->
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">
                <i class="fa-solid fa-comments me-2"></i>Moderasi Ulasan Produk
            </h3>
            <p class="text-muted small mb-0">Setujui, tolak, atau hapus ulasan pelanggan sebelum tampil di halaman publik produk.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <form action="{{ route('admin.ulasan') }}" method="GET" class="d-flex gap-2">
                @if($activeTab !== 'all')
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                @endif
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm rounded-3" placeholder="Cari komentar / produk / pelanggan..." style="min-width: 220px;">
                <button type="submit" class="btn btn-dark btn-sm rounded-3 fw-bold"><i class="fa-solid fa-magnifying-glass"></i></button>
                @if(request('q'))
                    <a href="{{ $activeTab !== 'all' ? route('admin.ulasan', ['tab' => $activeTab]) : route('admin.ulasan') }}" class="btn btn-outline-secondary btn-sm rounded-3"><i class="fa-solid fa-xmark"></i></a>
                @endif
            </form>
        </div>
    </div>

    <!-- TAB FILTER -->
    <div class="d-flex gap-2 flex-wrap mb-4">
        @foreach([
            'all' => ['label' => 'Semua', 'icon' => 'fa-layer-group'],
            'menunggu' => ['label' => 'Menunggu Persetujuan', 'icon' => 'fa-hourglass-half'],
            'disetujui' => ['label' => 'Disetujui', 'icon' => 'fa-circle-check'],
            'ditolak' => ['label' => 'Ditolak', 'icon' => 'fa-circle-xmark'],
        ] as $key => $tab)
            <a href="{{ route('admin.ulasan', ['tab' => $key]) }}"
               class="filter-chip {{ $activeTab === $key ? 'active' : '' }} d-flex align-items-center gap-2">
                <i class="fa-solid {{ $tab['icon'] }}"></i>
                {{ $tab['label'] }}
                <span class="badge rounded-pill {{ $activeTab === $key ? 'bg-light text-dark' : 'bg-secondary-subtle text-secondary' }}">{{ $tabCounts[$key] }}</span>
            </a>
        @endforeach
    </div>

    <!-- DAFTAR ULASAN -->
    <div class="row g-3">
        @forelse($reviews as $review)
            <div class="col-lg-6">
                <div class="review-moderation-card p-3 p-md-4 h-100">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <div class="d-flex gap-2">
                            @php
                                $statusBadge = match($review->status) {
                                    'Disetujui' => ['bg-success-subtle text-success border border-success', 'fa-circle-check'],
                                    'Ditolak' => ['bg-danger-subtle text-danger border border-danger', 'fa-circle-xmark'],
                                    default => ['bg-warning-subtle text-warning-emphasis border border-warning', 'fa-hourglass-half'],
                                };
                            @endphp
                            <span class="badge rounded-pill {{ $statusBadge[0] }} d-flex align-items-center gap-1">
                                <i class="fa-solid {{ $statusBadge[1] }}"></i> {{ $review->status }}
                            </span>
                            @if($review->is_verified_buyer)
                                <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info" style="font-size: 0.65rem;">
                                    <i class="fa-solid fa-circle-check me-1"></i>Pembeli Terverifikasi
                                </span>
                            @endif
                        </div>
                        <small class="text-muted" style="font-size: 0.72rem;">{{ $review->created_at->translatedFormat('d M Y, H:i') }}</small>
                    </div>

                    <div class="d-flex gap-3">
                        <img src="{{ $review->author_avatar }}" alt="{{ $review->author_name }}" class="review-avatar flex-shrink-0">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fw-bold text-dark small">{{ $review->author_name }}</span>
                                <span class="text-muted" style="font-size: 0.72rem;">{{ $review->user->email ?? '' }}</span>
                            </div>

                            <div class="review-stars my-1" style="font-size: 0.8rem;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid {{ $i <= $review->rating ? 'fa-star' : 'fa-star star-empty' }}"></i>
                                @endfor
                            </div>

                            @if($review->title)
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.88rem;">{{ $review->title }}</h6>
                            @endif

                            <p class="text-dark mb-2" style="font-size: 0.84rem; line-height: 1.6;">{{ $review->comment }}</p>

                            @if($review->url_photo)
                                <img src="{{ $review->url_photo }}" alt="Foto ulasan" class="rounded-3 border mb-2" style="max-height: 110px; border-color: var(--light-border) !important;">
                            @endif

                            @if($review->status === 'Ditolak' && $review->admin_note)
                                <div class="small text-danger mb-2"><i class="fa-solid fa-circle-exclamation me-1"></i>Alasan: {{ $review->admin_note }}</div>
                            @endif

                            <div class="d-flex align-items-center gap-2 flex-wrap pt-2 border-top" style="border-color: var(--light-border) !important;">
                                <i class="fa-solid fa-couch text-muted small"></i>
                                <a href="{{ route('customer.produk.detail', $review->produk_id) }}" target="_blank" class="small fw-bold text-decoration-none" style="color: var(--primary-color);">
                                    {{ $review->produk->nama ?? 'Produk dihapus' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- AKSI MODERASI -->
                    <div class="d-flex gap-2 flex-wrap mt-3">
                        @if($review->status !== 'Disetujui')
                            <form action="{{ route('admin.ulasan.approve', $review->id) }}" method="POST" class="flex-grow-1">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100 rounded-3 fw-bold">
                                    <i class="fa-solid fa-check me-1"></i> Setujui
                                </button>
                            </form>
                        @endif
                        @if($review->status !== 'Ditolak')
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-3 fw-bold flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalTolakUlasan{{ $review->id }}">
                                <i class="fa-solid fa-ban me-1"></i> Tolak
                            </button>
                        @endif
                        <form action="{{ route('admin.ulasan.destroy', $review->id) }}" method="POST" class="flex-grow-1"
                              onsubmit="return confirm('Hapus permanen ulasan ini? Tindakan tidak dapat dibatalkan.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary btn-sm w-100 rounded-3 fw-bold">
                                <i class="fa-solid fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MODAL TOLAK ULASAN -->
            <div class="modal fade" id="modalTolakUlasan{{ $review->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 p-4 border-0 shadow-lg">
                        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-ban text-danger me-2"></i>Tolak Ulasan</h5>
                        <p class="text-muted small mb-3">Ulasan dari <strong>{{ $review->author_name }}</strong> akan disembunyikan dari halaman publik.</p>
                        <form action="{{ route('admin.ulasan.reject', $review->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="admin_note" rows="3" class="form-control form-control-sm rounded-3" placeholder="Contoh: mengandung kata-kata tidak sopan / spam" required></textarea>
                            </div>
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger btn-sm rounded-3 px-4 fw-bold">Tolak Ulasan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="review-moderation-card p-5 text-center">
                    <i class="fa-solid fa-comments fa-3x text-muted mb-3" style="color: var(--secondary-color) !important;"></i>
                    <h5 class="fw-bold text-dark mb-2">Tidak Ada Ulasan</h5>
                    <p class="text-muted small mb-3">
                        @if(request('q'))
                            Tidak ada ulasan yang cocok dengan pencarian "<strong>{{ request('q') }}</strong>".
                        @else
                            Belum ada ulasan pada kategori ini.
                        @endif
                    </p>
                    <a href="{{ route('admin.ulasan') }}" class="btn btn-outline-dark btn-sm rounded-3 px-3">
                        <i class="fa-solid fa-rotate-left me-1"></i> Tampilkan Semua Ulasan
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection
