@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-3">
    
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1 tracking-wide">KELOLA PESANAN MASUK & VERIFIKASI</h4>
            <p class="small text-muted mb-0"><i class="fa fa-info-circle text-primary me-1"></i> Kelola pesanan baru, verifikasi rincian pesanan, cek DP, serta atur antrean produksi.</p>
        </div>
    </div>

    <!-- NOTIFIKASI BERHASIL -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        // Simulasi pesanan masuk dari pelanggan
        $listPesananMasuk = [
            [
                'id' => '10025',
                'pelanggan' => 'Rina Wijaya',
                'wa' => '08123456789',
                'mebel' => 'Meja Makan Minimalis Jati',
                'warna' => 'Natural Oak',
                'ukuran' => '180 x 90 cm',
                'material' => 'Kayu Jati',
                'jumlah' => '1 Set',
                'catatan' => 'Tolong buatkan finishing doff ya min.',
                'status' => 'Menunggu Konfirmasi Admin', // Status awal saat pelanggan pesan
                'dp_status' => 'Belum Dibayar',
                'total_harga' => 'Rp 4.500.000',
                'jumlah_dp' => 'Rp 1.500.000'
            ],
            [
                'id' => '10026',
                'pelanggan' => 'Ahmad Fauzi',
                'wa' => '08598765432',
                'mebel' => 'Pintu Utama Ukir Jepara',
                'warna' => 'Dark Walnut',
                'ukuran' => '210 x 90 cm',
                'material' => 'Kayu Jati TPKS',
                'jumlah' => '2 Unit',
                'catatan' => 'Ukirannya diperjelas motif bunga melati.',
                'status' => 'Menunggu Konfirmasi Admin',
                'dp_status' => 'Belum Dibayar',
                'total_harga' => 'Rp 6.000.000',
                'jumlah_dp' => 'Rp 2.000.000'
            ]
        ];
    @endphp

    <!-- TABEL PESANAN MASUK -->
    <div class="card border border-dark rounded-4 p-4 bg-white shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered border-dark align-middle text-center mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 10%;">ID Pesanan</th>
                        <th style="width: 22%;">Nama & Kontak</th>
                        <th style="width: 23%;">Mebel Custom</th>
                        <th style="width: 20%;">Status & DP</th>
                        <th style="width: 25%;">Aksi Verifikasi & Pemberitahuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listPesananMasuk as $item)
                    <tr>
                        <td class="fw-bold text-primary">#{{ $item['id'] }}</td>
                        <td class="text-start ps-3">
                            <strong class="text-dark">{{ $item['pelanggan'] }}</strong><br>
                            <span class="small text-muted"><i class="fa-brands fa-whatsapp text-success"></i> {{ $item['wa'] }}</span>
                        </td>
                        <td class="fw-semibold text-dark">{{ $item['mebel'] }}</td>
                        <td>
                            <span class="badge bg-warning text-dark mb-1 px-2 py-1">{{ $item['status'] }}</span><br>
                            <span class="small text-secondary">DP: <strong>{{ $item['dp_status'] }}</strong></span>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <!-- 1. Tombol Kirim Detail / Cek Kecocokan Rincian -->
                                <button type="button" class="btn btn-outline-dark btn-sm rounded-0 small fw-semibold"
                                        data-bs-toggle="modal" data-bs-target="#modalKirimDetail"
                                        data-id="{{ $item['id'] }}"
                                        data-pelanggan="{{ $item['pelanggan'] }}"
                                        data-mebel="{{ $item['mebel'] }}"
                                        data-warna="{{ $item['warna'] }}"
                                        data-ukuran="{{ $item['ukuran'] }}"
                                        data-material="{{ $item['material'] }}"
                                        data-jumlah="{{ $item['jumlah'] }}"
                                        data-catatan="{{ $item['catatan'] }}">
                                    <i class="fa fa-file-lines me-1"></i> Kirim Rincian & Cocokkan
                                </button>

                                <!-- 2. Tombol Pemberitahuan Antrean / Kapasitas Penuh ("Apakah anda bisa menunggu?") -->
                                <button type="button" class="btn btn-outline-warning text-dark btn-sm rounded-0 small fw-semibold"
                                        data-bs-toggle="modal" data-bs-target="#modalAntreanPenuh"
                                        data-id="{{ $item['id'] }}"
                                        data-pelanggan="{{ $item['pelanggan'] }}"
                                        data-mebel="{{ $item['mebel'] }}">
                                    <i class="fa fa-clock me-1"></i> Info Antrean Penuh?
                                </button>

                                <!-- 3. Tombol Cek Pembayaran DP & Ubah Status Progres -->
                                <button type="button" class="btn btn-success btn-sm rounded-0 small fw-semibold"
                                        data-bs-toggle="modal" data-bs-target="#modalVerifikasiDP"
                                        data-id="{{ $item['id'] }}"
                                        data-pelanggan="{{ $item['pelanggan'] }}"
                                        data-dp="{{ $item['jumlah_dp'] }}"
                                        data-total="{{ $item['total_harga'] }}">
                                    <i class="fa fa-money-bill-wave me-1"></i> Cek Pembayaran DP
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ========================================== -->
<!-- MODAL 1 : KIRIM & COCOKKAN RINCIAN PESANAN -->
<!-- ========================================== -->
<div class="modal fade" id="modalKirimDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-clipboard-check me-1"></i> Kirim & Verifikasi Kecocokan Detail</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Admin wajib mengirimkan detail ini kepada pelanggan untuk memastikan pesanan sudah cocok sebelum masuk proses pembayaran DP.</p>
                    
                    <div class="bg-light p-3 border rounded-3 mb-3 small">
                        <div class="mb-1">ID Pesanan : <strong id="det_id_pesanan" class="text-primary"></strong></div>
                        <div class="mb-1">Nama Pemesan : <strong id="det_nama_pelanggan"></strong></div>
                        <div class="mb-1">Produk Mebel : <strong id="det_nama_mebel"></strong></div>
                        <hr class="my-2">
                        <div class="row">
                            <div class="col-6 mb-1">Warna: <span id="det_warna" class="fw-semibold"></span></div>
                            <div class="col-6 mb-1">Ukuran: <span id="det_ukuran" class="fw-semibold"></span></div>
                            <div class="col-6">Material: <span id="det_material" class="fw-semibold"></span></div>
                            <div class="col-6">Jumlah: <span id="det_jumlah" class="fw-semibold"></span></div>
                        </div>
                        <div class="mt-2 text-muted">Catatan Pelanggan: <em id="det_catatan"></em></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pesan Konfirmasi ke Pelanggan :</label>
                        <textarea class="form-control form-control-sm border-dark" rows="3" required>Halo, berikut adalah rincian pesanan mebel custom Anda. Apakah spesifikasi di atas sudah cocok dan sesuai keinginan Anda?</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-dark btn-sm rounded-0 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-0 px-4">Kirim Detail ke Pelanggan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2 : PEMBERITAHUAN ANTREAN PENUH ("Apakah anda bisa menunggu?") -->
<!-- ========================================== -->
<div class="modal fade" id="modalAntreanPenuh" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-warning text-dark rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-clock me-1"></i> Konfirmasi Kapasitas Penuh / Antrean</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-dark small mb-3">
                        <i class="fa fa-triangle-exclamation me-1"></i> Gunakan opsi ini jika bengkel/tukang sedang penuh orderan dan pelanggan harus masuk daftar tunggu antrean.
                    </div>
                    
                    <div class="mb-3">
                        <span class="small text-muted d-block">Kepada Pelanggan :</span>
                        <strong id="antrean_pelanggan" class="text-dark fs-6"></strong> (<span id="antrean_mebel" class="text-primary"></span>)
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Pesan Otomatis ke Pelanggan :</label>
                        <textarea class="form-control form-control-sm border-dark" rows="4" required>Mohon maaf, saat ini antrean produksi pesanan mebel kami sedang penuh. Perkiraan pengerjaan akan dimulai dalam waktu 1-2 minggu ke depan. Apakah anda bisa menunggu?</textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Estimasi Waktu Tunggu :</label>
                        <input type="text" class="form-control form-control-sm border-dark" placeholder="Contoh: 14 Hari Kerja" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-dark btn-sm rounded-0 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning btn-sm rounded-0 px-4 fw-semibold">Kirim Pesan Antrean</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 3 : VERIFIKASI PEMBAYARAN DP & UBAH PROGRES -->
<!-- ========================================== -->
<div class="modal fade" id="modalVerifikasiDP" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-money-bill-wave me-1"></i> Verifikasi Pembayaran DP & Ubah Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3 border-bottom pb-2">
                        <span class="small text-muted d-block">Pelanggan :</span>
                        <strong id="dp_pelanggan" class="text-dark"></strong>
                    </div>
                    <div class="row mb-3 border-bottom pb-2">
                        <div class="col-6">
                            <span class="small text-muted d-block">Tagihan Total :</span>
                            <strong id="dp_total" class="text-primary"></strong>
                        </div>
                        <div class="col-6">
                            <span class="small text-muted d-block">Wajib DP (50%) :</span>
                            <strong id="dp_jumlah" class="text-success"></strong>
                        </div>
                    </div>

                    <div class="mb-3 text-center bg-light p-3 border rounded-3">
                        <span class="small text-muted d-block mb-1">Bukti Transfer DP dari Pelanggan :</span>
                        <div class="border border-dark border-dashed py-3 bg-white rounded-2">
                            <i class="fa fa-receipt fa-2x text-secondary mb-1"></i>
                            <p class="small text-muted mb-0">Pratinjau Bukti Transfer DP</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Ubah Status / Progres Pesanan :</label>
                        <select name="status_progres" class="form-select form-select-sm border-dark" required>
                            <option value="DP Lunas - Masuk Antrean Produksi">DP Lunas - Masuk Antrean Produksi</option>
                            <option value="Sedang Diproses / Dikerjakan">Sedang Diproses / Dikerjakan</option>
                            <option value="Pembayaran DP Ditolak / Belum Valid">Pembayaran DP Ditolak (Bukti Tidak Valid)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-dark btn-sm rounded-0 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm rounded-0 px-4">Simpan & Perbarui Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .border-dashed {
        border-style: dashed !important;
    }
</style>

<!-- JavaScript untuk Mengisi Data Dinamis ke Masing-Masing Modal -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Modal 1 : Kirim Detail
        const modalDetail = document.getElementById('modalKirimDetail');
        modalDetail.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            document.getElementById('det_id_pesanan').textContent = '#' + button.getAttribute('data-id');
            document.getElementById('det_nama_pelanggan').textContent = button.getAttribute('data-pelanggan');
            document.getElementById('det_nama_mebel').textContent = button.getAttribute('data-mebel');
            document.getElementById('det_warna').textContent = button.getAttribute('data-warna');
            document.getElementById('det_ukuran').textContent = button.getAttribute('data-ukuran');
            document.getElementById('det_material').textContent = button.getAttribute('data-material');
            document.getElementById('det_jumlah').textContent = button.getAttribute('data-jumlah');
            document.getElementById('det_catatan').textContent = button.getAttribute('data-catatan');
        });

        // Modal 2 : Antrean Penuh
        const modalAntrean = document.getElementById('modalAntreanPenuh');
        modalAntrean.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            document.getElementById('antrean_pelanggan').textContent = button.getAttribute('data-pelanggan');
            document.getElementById('antrean_mebel').textContent = button.getAttribute('data-mebel');
        });

        // Modal 3 : Verifikasi DP
        const modalDP = document.getElementById('modalVerifikasiDP');
        modalDP.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            document.getElementById('dp_pelanggan').textContent = button.getAttribute('data-pelanggan');
            document.getElementById('dp_total').textContent = button.getAttribute('data-total');
            document.getElementById('dp_jumlah').textContent = button.getAttribute('data-dp');
        });
    });
</script>
@endsection