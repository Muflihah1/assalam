@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-3">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1 tracking-wide">HALAMAN ADMIN : RIWAYAT PEMESANAN</h4>
            <p class="small text-muted mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Data di bawah ini masuk secara otomatis setelah pelanggan mengonfirmasi "Pesanan Selesai".</p>
        </div>
        <!-- Tombol Tambah Manual (Opsional jika admin ingin memasukkan secara manual) -->
        <button type="button" class="btn btn-dark btn-sm px-4 rounded-0 shadow-sm action-btn" data-bs-toggle="modal" data-bs-target="#modalTambahRiwayat">
            <i class="fa fa-plus me-1"></i> Tambah Riwayat Manual
        </button>
    </div>

    <!-- NOTIFIKASI BERHASIL -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        // Simulasi data riwayat yang masuk otomatis saat pelanggan klik "Pesanan Selesai"
        $listRiwayatOtomatis = [
            [
                'id' => 101,
                'pelanggan' => 'Muldiansyah',
                'produk' => 'Pintu Rumah Jati Ukir Jepara',
                'warna' => 'Natural Glossy',
                'ukuran' => '200 x 90 cm',
                'material' => 'Kayu Jati TPKS',
                'jumlah' => '2 Unit',
                'tgl_pesan' => '12-01-2026',
                'tgl_selesai' => '25-01-2026',
                'status_konfirmasi' => 'Dikonfirmasi Selesai oleh Pelanggan'
            ],
            [
                'id' => 102,
                'pelanggan' => 'Siti Aminah',
                'produk' => 'Lemari Pakaian 3 Pintu',
                'warna' => 'Duco Putih',
                'ukuran' => '180 x 60 x 200 cm',
                'material' => 'Kayu Mahoni',
                'jumlah' => '1 Unit',
                'tgl_pesan' => '10-02-2026',
                'tgl_selesai' => '28-02-2026',
                'status_konfirmasi' => 'Dikonfirmasi Selesai oleh Pelanggan'
            ],
        ];
    @endphp

    <!-- DAFTAR KARTU RIWAYAT OTOMATIS -->
    <div class="row g-4">
        @foreach($listRiwayatOtomatis as $item)
        <div class="col-12">
            <div class="card border border-dark rounded-4 p-4 bg-white shadow-sm riwayat-card">
                <div class="row align-items-center">
                    
                    <!-- GAMBAR PRODUK (Bisa diklik untuk melihat detail riwayat via modal) -->
                    <div class="col-md-3 text-center border border-dark border-dashed py-5 bg-light rounded-3 position-relative mb-3 mb-md-0 riwayat-img-clickable" 
                         style="cursor: pointer; display: flex; flex-direction: column; align-items: center; justify-content: center;"
                         data-bs-toggle="modal" data-bs-target="#modalDetailRiwayat"
                         data-pelanggan="{{ $item['pelanggan'] }}"
                         data-produk="{{ $item['produk'] }}"
                         data-warna="{{ $item['warna'] }}"
                         data-ukuran="{{ $item['ukuran'] }}"
                         data-material="{{ $item['material'] }}"
                         data-jumlah="{{ $item['jumlah'] }}"
                         data-tglpesan="{{ $item['tgl_pesan'] }}"
                         data-tglselesai="{{ $item['tgl_selesai'] }}"
                         data-status="{{ $item['status_konfirmasi'] }}">
                        <i class="fa-solid fa-image fa-2x text-secondary mb-2"></i>
                        <span class="small fw-semibold text-muted">Foto Produk</span>
                        <span class="badge bg-dark mt-2 small">Klik Detail</span>
                    </div>

                    <!-- INFORMASI DETAIL PESANAN -->
                    <div class="col-md-9 ps-md-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="small text-muted d-block mb-1"><i class="fa fa-user me-1"></i> Pelanggan : <strong>{{ $item['pelanggan'] }}</strong></span>
                                <h5 class="fw-bold text-dark mb-1">{{ $item['produk'] }}</h5>
                            </div>
                            <span class="badge bg-success px-3 py-2">
                                <i class="fa fa-check-circle me-1"></i> {{ $item['status_konfirmasi'] }}
                            </span>
                        </div>

                        <!-- SPESIFIKASI DALAM GRID -->
                        <div class="row text-muted small mb-3 bg-light p-3 rounded-3 border border-dark">
                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                <span class="d-block text-secondary fw-bold">Warna:</span>
                                {{ $item['warna'] }}
                            </div>
                            <div class="col-md-3 col-6 mb-2 mb-md-0">
                                <span class="d-block text-secondary fw-bold">Ukuran:</span>
                                {{ $item['ukuran'] }}
                            </div>
                            <div class="col-md-3 col-6">
                                <span class="d-block text-secondary fw-bold">Material:</span>
                                {{ $item['material'] }}
                            </div>
                            <div class="col-md-3 col-6">
                                <span class="d-block text-secondary fw-bold">Jumlah:</span>
                                {{ $item['jumlah'] }}
                            </div>
                        </div>

                        <!-- TANGGAL & TOMBOL HAPUS -->
                        <div class="d-flex justify-content-between align-items-center border-top pt-2">
                            <div class="small text-dark">
                                <span class="me-3"><i class="fa fa-calendar-days text-primary me-1"></i> Dipesan: <strong>{{ $item['tgl_pesan'] }}</strong></span>
                                <span><i class="fa fa-calendar-check text-success me-1"></i> Selesai Dikonfirmasi: <strong>{{ $item['tgl_selesai'] }}</strong></span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-0 px-3 small fw-semibold" 
                                        data-bs-toggle="modal" data-bs-target="#modalHapusRiwayat" data-produk="{{ $item['produk'] }}">
                                    <i class="fa fa-trash me-1"></i> Hapus Riwayat
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL : TAMBAH RIWAYAT MANUAL (OPSIONAL) -->
<!-- ========================================== -->
<div class="modal fade" id="modalTambahRiwayat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-plus-circle me-1"></i> Tambah Riwayat Pemesanan Manual</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Pelanggan :</label>
                        <input type="text" name="pelanggan" class="form-control form-control-sm border-dark" required placeholder="Nama pelanggan...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Jenis / Nama Produk Mebel :</label>
                        <input type="text" name="produk" class="form-control form-control-sm border-dark" required placeholder="Contoh: Kursi Tamu Jati">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Warna Produk :</label>
                            <input type="text" name="warna" class="form-control form-control-sm border-dark" required placeholder="Contoh: Walnut">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Ukuran :</label>
                            <input type="text" name="ukuran" class="form-control form-control-sm border-dark" required placeholder="Contoh: 150x80 cm">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Material / Bahan :</label>
                            <input type="text" name="material" class="form-control form-control-sm border-dark" required placeholder="Contoh: Kayu Jati">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jumlah :</label>
                            <input type="text" name="jumlah" class="form-control form-control-sm border-dark" required placeholder="Contoh: 4 Unit">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Dipesan :</label>
                            <input type="date" name="tgl_pesan" class="form-control form-control-sm border-dark" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tanggal Selesai :</label>
                            <input type="date" name="tgl_selesai" class="form-control form-control-sm border-dark" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Foto Dokumentasi Produk :</label>
                        <input type="file" name="foto" class="form-control form-control-sm border-dark" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-dark btn-sm rounded-0 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-0 px-4">Simpan Riwayat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL : DETAIL RIWAYAT PEMESANAN -->
<!-- ========================================== -->
<div class="modal fade" id="modalDetailRiwayat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-info-circle me-1"></i> Detail Riwayat & Konfirmasi Pelanggan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="border border-dark py-4 bg-light rounded-3 text-center mb-3">
                    <i class="fa-solid fa-image fa-3x text-secondary mb-2"></i>
                    <p class="small text-muted mb-0">Pratinjau Foto Produk Selesai</p>
                </div>
                <div class="mb-2 border-bottom pb-2">
                    <span class="small text-muted d-block">Nama Pelanggan :</span>
                    <strong id="det_pelanggan" class="text-dark"></strong>
                </div>
                <div class="mb-2 border-bottom pb-2">
                    <span class="small text-muted d-block">Jenis Produk :</span>
                    <strong id="det_produk" class="text-primary"></strong>
                </div>
                <div class="row mb-2 border-bottom pb-2">
                    <div class="col-6">
                        <span class="small text-muted d-block">Warna :</span>
                        <span id="det_warna" class="fw-semibold"></span>
                    </div>
                    <div class="col-6">
                        <span class="small text-muted d-block">Ukuran :</span>
                        <span id="det_ukuran" class="fw-semibold"></span>
                    </div>
                </div>
                <div class="row mb-2 border-bottom pb-2">
                    <div class="col-6">
                        <span class="small text-muted d-block">Material :</span>
                        <span id="det_material" class="fw-semibold"></span>
                    </div>
                    <div class="col-6">
                        <span class="small text-muted d-block">Jumlah :</span>
                        <span id="det_jumlah" class="fw-semibold"></span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-6">
                        <span class="small text-muted d-block">Tanggal Dipesan :</span>
                        <span id="det_tglpesan" class="fw-semibold text-primary"></span>
                    </div>
                    <div class="col-6">
                        <span class="small text-muted d-block">Tanggal Selesai :</span>
                        <span id="det_tglselesai" class="fw-semibold text-success"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-dark btn-sm rounded-0 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL : KONFIRMASI HAPUS RIWAYAT -->
<!-- ========================================== -->
<div class="modal fade" id="modalHapusRiwayat" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-dark rounded-4 shadow text-center p-3">
            <div class="modal-body">
                <i class="fa-solid fa-triangle-exclamation text-danger fa-3x mb-3"></i>
                <h6 class="fw-bold mb-2">Hapus Riwayat?</h6>
                <p class="small text-muted mb-4">Apakah Anda yakin ingin menghapus riwayat pesanan <strong id="hapus_nama_produk" class="text-dark"></strong>?</p>
                <form action="#" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-dark btn-sm rounded-0 px-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-sm rounded-0 px-3">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .riwayat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .riwayat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.08) !important;
    }
    .action-btn {
        transition: transform 0.15s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .riwayat-img-clickable:hover {
        background-color: #e9ecef !important;
    }
</style>

<!-- JavaScript untuk Mengisi Data Dinamis ke Modal Detail & Hapus -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modalDetail = document.getElementById('modalDetailRiwayat');
        modalDetail.addEventListener('show.bs.modal', function (event) {
            let card = event.relatedTarget;
            document.getElementById('det_pelanggan').textContent = card.getAttribute('data-pelanggan');
            document.getElementById('det_produk').textContent = card.getAttribute('data-produk');
            document.getElementById('det_warna').textContent = card.getAttribute('data-warna');
            document.getElementById('det_ukuran').textContent = card.getAttribute('data-ukuran');
            document.getElementById('det_material').textContent = card.getAttribute('data-material');
            document.getElementById('det_jumlah').textContent = card.getAttribute('data-jumlah');
            document.getElementById('det_tglpesan').textContent = card.getAttribute('data-tglpesan');
            document.getElementById('det_tglselesai').textContent = card.getAttribute('data-tglselesai');
        });

        const modalHapus = document.getElementById('modalHapusRiwayat');
        modalHapus.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            let produk = button.getAttribute('data-produk');
            document.getElementById('hapus_nama_produk').textContent = produk;
        });
    });
</script>
@endsection