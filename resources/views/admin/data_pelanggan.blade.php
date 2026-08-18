@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-3">
    
    <h4 class="fw-bold text-dark mb-4 tracking-wide">HALAMAN ADMIN : DATA AKUN PELANGGAN</h4>

    <!-- KARTU TOTAL AKUN (BISA DIKLIK UNTUK LIHAT SEMUA) -->
    <div class="card border border-dark p-3 mb-4 text-center bg-light shadow-sm total-akun-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalSemuaPelanggan">
        <span class="fw-bold text-dark">
            <i class="fa fa-users me-2 text-primary"></i> Total Akun Terdaftar : <span class="badge bg-dark fs-6 ms-1">59 Akun</span> 
            <span class="small text-muted ms-2 fw-normal">(Klik untuk lihat semua data)</span>
        </span>
    </div>

    <!-- ========================================== -->
    <!-- TABEL 1 : PELANGGAN BARU (TERDAFTAR TERBARU) -->
    <!-- ========================================== -->
    <div class="mb-4">
        <div class="d-flex align-items-center mb-2">
            <h6 class="fw-bold text-dark text-uppercase small mb-0"><i class="fa fa-user-plus text-success me-1"></i> Pelanggan Baru (Terdaftar Hari Ini)</h6>
            <span class="badge bg-success ms-2 small">Baru</span>
        </div>

        <div class="card border border-dark rounded-4 p-3 bg-white shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered border-dark text-center align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 32%;">Nama Pelanggan/Kontak</th>
                            <th style="width: 25%;">Mebel Custom</th>
                            <th style="width: 35%;">Alamat Pengiriman Utama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $listPelangganBaru = [
                                ['id' => '59', 'nama' => 'Muldiansyah', 'wa' => '081234567890', 'email' => 'muldi@gmail.com', 'mebel' => 'Pintu Rumah Jati', 'alamat' => 'Jl. Melati No. 12, Kab. Jepara, Prov. Jawa Tengah'],
                                ['id' => '58', 'nama' => 'Siti Aminah', 'wa' => '085987654321', 'email' => 'siti.aminah@yahoo.com', 'mebel' => 'Lemari Pakaian 3 Pintu', 'alamat' => 'Jl. Ahmad Yani No. 45, Kota Kudus, Prov. Jawa Tengah'],
                            ];
                        @endphp

                        @foreach($listPelangganBaru as $pelanggan)
                        <tr class="pelanggan-row" style="cursor: pointer;" 
                            data-bs-toggle="modal" data-bs-target="#modalDetailPelanggan"
                            data-id="{{ $pelanggan['id'] }}"
                            data-nama="{{ $pelanggan['nama'] }}"
                            data-wa="{{ $pelanggan['wa'] }}"
                            data-email="{{ $pelanggan['email'] }}"
                            data-mebel="{{ $pelanggan['mebel'] }}"
                            data-alamat="{{ $pelanggan['alamat'] }}">
                            <td class="fw-bold">#{{ $pelanggan['id'] }}</td>
                            <td class="text-start ps-4">
                                <strong class="text-dark">{{ $pelanggan['nama'] }}</strong><br>
                                <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success"></i> WA : {{ $pelanggan['wa'] }}</span><br>
                                <span class="small text-muted"><i class="fa fa-envelope text-secondary"></i> Email : {{ $pelanggan['email'] }}</span>
                            </td>
                            <td class="fw-semibold text-primary">{{ $pelanggan['mebel'] }}</td>
                            <td class="text-start ps-3 small text-muted">{{ $pelanggan['alamat'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TABEL 2 : PELANGGAN LAMA -->
    <!-- ========================================== -->
    <div class="mb-3">
        <div class="d-flex align-items-center mb-2">
            <h6 class="fw-bold text-dark text-uppercase small mb-0"><i class="fa fa-user-clock text-secondary me-1"></i> Pelanggan Lama (Terdaftar Sebelumnya)</h6>
        </div>

        <div class="card border border-dark rounded-4 p-3 bg-white shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered border-dark text-center align-middle mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 32%;">Nama Pelanggan/Kontak</th>
                            <th style="width: 25%;">Mebel Custom</th>
                            <th style="width: 35%;">Alamat Pengiriman Utama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $listPelangganLama = [
                                ['id' => '57', 'nama' => 'Budi Santoso', 'wa' => '081399887766', 'email' => 'budi_s@gmail.com', 'mebel' => 'Meja Makan Klasik', 'alamat' => 'Jl. Diponegoro No. 8, Kab. Pati, Prov. Jawa Tengah'],
                                ['id' => '56', 'nama' => 'Dewi Lestari', 'wa' => '081877665544', 'email' => 'dewi_l@yahoo.com', 'mebel' => 'Kursi Tamu Jati', 'alamat' => 'Jl. Kartini No. 19, Kab. Rembang, Prov. Jawa Tengah'],
                            ];
                        @endphp

                        @foreach($listPelangganLama as $pelanggan)
                        <tr class="pelanggan-row" style="cursor: pointer;" 
                            data-bs-toggle="modal" data-bs-target="#modalDetailPelanggan"
                            data-id="{{ $pelanggan['id'] }}"
                            data-nama="{{ $pelanggan['nama'] }}"
                            data-wa="{{ $pelanggan['wa'] }}"
                            data-email="{{ $pelanggan['email'] }}"
                            data-mebel="{{ $pelanggan['mebel'] }}"
                            data-alamat="{{ $pelanggan['alamat'] }}">
                            <td class="fw-bold">#{{ $pelanggan['id'] }}</td>
                            <td class="text-start ps-4">
                                <strong class="text-dark">{{ $pelanggan['nama'] }}</strong><br>
                                <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success"></i> WA : {{ $pelanggan['wa'] }}</span><br>
                                <span class="small text-muted"><i class="fa fa-envelope text-secondary"></i> Email : {{ $pelanggan['email'] }}</span>
                            </td>
                            <td class="fw-semibold text-primary">{{ $pelanggan['mebel'] }}</td>
                            <td class="text-start ps-3 small text-muted">{{ $pelanggan['alamat'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL : DETAIL INFORMASI PELANGGAN -->
<!-- ========================================== -->
<div class="modal fade" id="modalDetailPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-user-circle me-1"></i> Detail Akun Pelanggan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3 border-bottom pb-2">
                    <span class="small text-muted d-block">ID Pelanggan :</span>
                    <strong id="modal_id" class="text-primary fs-5"></strong>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <span class="small text-muted d-block">Nama Lengkap :</span>
                    <strong id="modal_nama" class="text-dark fs-6"></strong>
                </div>
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-6">
                        <span class="small text-muted d-block">Nomor WhatsApp :</span>
                        <span id="modal_wa" class="fw-semibold text-success"></span>
                    </div>
                    <div class="col-md-6">
                        <span class="small text-muted d-block">Alamat Email :</span>
                        <span id="modal_email" class="fw-semibold text-secondary"></span>
                    </div>
                </div>
                <div class="mb-3 border-bottom pb-2">
                    <span class="small text-muted d-block">Pesanan Mebel Custom :</span>
                    <strong id="modal_mebel" class="text-dark"></strong>
                </div>
                <div class="mb-2">
                    <span class="small text-muted d-block">Alamat Pengiriman Utama :</span>
                    <p id="modal_alamat" class="text-dark small mb-0 bg-light p-2 rounded-2 border"></p>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-dark btn-sm rounded-0 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL : SEMUA DATA AKUN (TERBUKA KETIKA KARTU TOTAL AKUN DIKLIK) -->
<!-- ========================================== -->
<div class="modal fade" id="modalSemuaPelanggan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-users me-1"></i> Daftar Seluruh Akun Pelanggan (59 Akun)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-bordered border-dark text-center align-middle mb-0 small">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>WhatsApp</th>
                                <th>Mebel Custom</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 59; $i >= 1; $i--)
                            <tr>
                                <td class="fw-bold">#{{ $i }}</td>
                                <td class="text-start ps-3">Pelanggan Ke-{{ $i }}</td>
                                <td>081234567{{ $i }}</td>
                                <td>Kursi / Meja / Pintu Jati</td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-dark btn-sm rounded-0 px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .total-akun-card {
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .total-akun-card:hover {
        transform: translateY(-2px);
        background-color: #f8f9fa !important;
        border-color: #000 !important;
    }
    .pelanggan-row {
        transition: background-color 0.1s ease;
    }
    .pelanggan-row:hover {
        background-color: #f1f3f5 !important;
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modalDetail = document.getElementById('modalDetailPelanggan');
        modalDetail.addEventListener('show.bs.modal', function (event) {
            let row = event.relatedTarget;
            document.getElementById('modal_id').textContent = '#' + row.getAttribute('data-id');
            document.getElementById('modal_nama').textContent = row.getAttribute('data-nama');
            document.getElementById('modal_wa').textContent = row.getAttribute('data-wa');
            document.getElementById('modal_email').textContent = row.getAttribute('data-email');
            document.getElementById('modal_mebel').textContent = row.getAttribute('data-mebel');
            document.getElementById('modal_alamat').textContent = row.getAttribute('data-alamat');
        });
    });
</script>
@endsection