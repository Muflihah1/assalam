@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-3">
    <h4 class="fw-bold text-dark mb-4">HALAMAN ADMIN : PENGATURAN</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- NAVIGASI TOMBOL TAB -->
    <div class="row g-0 mb-4 border rounded-3 bg-white overflow-hidden shadow-sm" role="tablist">
        <div class="col-4 border-end">
            <button class="w-100 btn rounded-0 py-3 fw-semibold active" id="profil-tab" data-bs-toggle="pill" data-bs-target="#profil" type="button" role="tab">
                [Pengaturan Profil]
            </button>
        </div>
        <div class="col-4 border-end">
            <button class="w-100 btn rounded-0 py-3 fw-semibold" id="whatsapp-tab" data-bs-toggle="pill" data-bs-target="#whatsapp" type="button" role="tab">
                Gateway WhatsApp
            </button>
        </div>
        <div class="col-4">
            <button class="w-100 btn rounded-0 py-3 fw-semibold" id="ongkir-tab" data-bs-toggle="pill" data-bs-target="#ongkir" type="button" role="tab">
                Tarif Ongkos Kirim
            </button>
        </div>
    </div>

    <div class="tab-content" id="settingTabContent">
        
        <!-- 1. TAB PENGATURAN PROFIL -->
        <div class="tab-pane fade show active" id="profil" role="tabpanel">
            
            <!-- BAGIAN A : INFORMASI AKUN & FOTO PROFIL (PP) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa fa-user-gear me-2"></i> Informasi Akun & Foto Profil (PP)</h6>
                    <span class="badge bg-dark px-3 py-1 small">Mode Edit Akun</span>
                </div>

                <form action="{{ route('admin.pengaturan.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row align-items-center mb-4">
                        <div class="col-md-3 text-center mb-3 mb-md-0">
                            <!-- Pratinjau Foto Profil -->
                            <div class="position-relative d-inline-block">
                                <div class="bg-light border border-dark rounded-circle d-flex align-items-center justify-content-center overflow-hidden shadow-sm mx-auto" style="width: 100px; height: 100px;">
                                    <i class="fa-solid fa-user-tie fa-3x text-dark"></i>
                                </div>
                                <label for="input_pp" class="position-absolute bottom-0 end-0 bg-dark text-white rounded-circle p-2 shadow-sm" style="cursor: pointer; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;" title="Unggah Foto Profil">
                                    <i class="fa fa-camera fa-xs"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Unggah Foto Profil Baru (PP)</label>
                                <input type="file" name="foto_profil" id="input_pp" class="form-control form-control-sm" accept="image/*">
                                <div class="form-text text-muted small">Format yang diizinkan: JPG, JPEG, PNG (Maks. 2MB).</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small">Username Admin</label>
                        <input type="text" name="username" class="form-control" value="{{ Auth::user()->name ?? 'admin_assalam' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small">Email Admin</label>
                        <input type="email" name="email" class="form-control" value="{{ Auth::user()->email ?? 'Assalam.mebel@gmail.com' }}" required>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-dark px-4 py-2 rounded-0 shadow-sm">Simpan Perubahan Akun</button>
                    </div>
                </form>
            </div>

            <!-- BAGIAN B : PENGAMANAN AKUN (KATA SANDI) -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa fa-lock me-2"></i> Pengamanan Akun (Ganti Kata Sandi)</h6>
                    <span class="badge bg-warning text-dark px-3 py-1 small">Keamanan</span>
                </div>

                <form action="{{ route('admin.pengaturan.profile') }}" method="POST">
                    @csrf

                    <!-- Password Lama -->
                    <div class="mb-3">
                        <label class="form-label text-muted small">Password Lama</label>
                        <div class="input-group">
                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Masukkan password lama jika ingin mengubah">
                            <button type="button" class="btn btn-outline-secondary toggle-password" toggle="#current_password">
                                <i class="fa fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Password Baru -->
                    <div class="mb-3">
                        <label class="form-label text-muted small">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" placeholder="*********">
                            <button type="button" class="btn btn-outline-secondary toggle-password" toggle="#password">
                                <i class="fa fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div class="mb-3">
                        <label class="form-label text-muted small">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="*********">
                            <button type="button" class="btn btn-outline-secondary toggle-password" toggle="#password_confirmation">
                                <i class="fa fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-outline-dark px-4 py-2 rounded-0 shadow-sm bg-white">Perbarui Kata Sandi</button>
                    </div>
                </form>
            </div>

        </div>

        <!-- 2. TAB GATEWAY WHATSAPP -->
        <div class="tab-pane fade" id="whatsapp" role="tabpanel">
            <form action="{{ route('admin.pengaturan.whatsapp') }}" method="POST">
                @csrf
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h6 class="fw-bold text-dark mb-3">STATUS SERVER ADMIN WHATSAPP API</h6>
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="mb-2">
                            <label class="form-label small fw-bold">Nomor Terhubung :</label>
                            <input type="text" name="wa_number" class="form-control" value="{{ $settings['wa_number'] ?? '085xxxxx (WhatsApp Utama Toko)' }}">
                        </div>
                        <div>
                            <label class="form-label small fw-bold">Status Sistem :</label>
                            <select name="wa_status" class="form-select">
                                <option value="Terhubung / Aktif" {{ (isset($settings['wa_status']) && $settings['wa_status'] == 'Terhubung / Aktif') ? 'selected' : '' }}>Terhubung / Aktif</option>
                                <option value="Tidak Terhubung" {{ (isset($settings['wa_status']) && $settings['wa_status'] == 'Tidak Terhubung') ? 'selected' : '' }}>Tidak Terhubung</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h6 class="fw-bold text-dark mb-3">TEMPLATE TEKS NOTIFIKASI OTOMATIS</h6>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Teks Update Progres :</label>
                        <textarea name="wa_template" class="form-control" rows="3">{{ $settings['wa_template'] ?? 'Halo (nama), Mebel custom (produk) Anda saat ini masuk tahap : (tahap).' }}</textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-outline-dark px-5 py-2 rounded-0 shadow-sm bg-white">Simpan</button>
                </div>
            </form>
        </div>

        <!-- 3. TAB TARIF ONGKOS KIRIM -->
        <div class="tab-pane fade" id="ongkir" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-dark mb-0">MANAJEMEN LOGISTIK & TARIF WILAYAH</h6>
                    <button type="button" class="btn btn-sm btn-outline-dark rounded-0 px-3" data-bs-toggle="modal" data-bs-target="#addShippingModal">[+ Tambah Wilayah Baru]</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered border-dark text-center align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10%;">No</th>
                                <th style="width: 40%;">Wilayah Kecamatan</th>
                                <th style="width: 30%;">Biaya Ongkos Kirim</th>
                                <th style="width: 20%;">Status Kebijakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shippingCosts as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}.</td>
                                <td class="text-start ps-4">{{ $item->kecamatan }}</td>
                                <td>Rp. {{ number_format($item->biaya, 0, ',', '.') }}</td>
                                <td>
                                    <span class="me-2">{{ $item->status }}</span>
                                    <button type="button" class="btn btn-link p-0 text-dark text-decoration-underline small" data-bs-toggle="modal" data-bs-target="#editShippingModal{{ $item->id }}">Ubah</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-muted py-3">Belum ada data wilayah pengiriman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Tambah Wilayah Baru -->
<div class="modal fade" id="addShippingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.pengaturan.shipping.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Wilayah Pengiriman Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Wilayah Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" placeholder="Contoh: Bluto (Sumenep)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya Ongkos Kirim (Rp)</label>
                        <input type="number" name="biaya" class="form-control" placeholder="Contoh: 30000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Kebijakan</label>
                        <select name="status" class="form-select">
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Simpan Wilayah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ubah Wilayah untuk Setiap Baris -->
@foreach($shippingCosts as $item)
<div class="modal fade" id="editShippingModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.pengaturan.shipping.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Ubah Tarif Wilayah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Wilayah Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" value="{{ $item->kecamatan }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya Ongkos Kirim (Rp)</label>
                        <input type="number" name="biaya" class="form-control" value="{{ $item->biaya }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status Kebijakan</label>
                        <select name="status" class="form-select">
                            <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ $item->status == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Perbarui Tarif</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

<style>
    .row.g-0 .btn {
        background: transparent;
        color: #000;
        border: none;
    }
    .row.g-0 .btn.active {
        background: #f8f9fa !important;
        font-weight: bold;
        box-shadow: inset 0 2px 0 #000;
    }
</style>

<!-- Script untuk Fitur Ikon Mata (Show/Hide Password) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButtons = document.querySelectorAll(".toggle-password");
        
        toggleButtons.forEach(button => {
            button.addEventListener("click", function () {
                let targetId = this.getAttribute("toggle");
                let input = document.querySelector(targetId);
                let icon = this.querySelector("i");

                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                }
            });
        });
    });
</script>
@endsection