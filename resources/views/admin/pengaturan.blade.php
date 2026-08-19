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

    .nav-pills-custom .nav-link {
        border-radius: 12px;
        padding: 12px 20px;
        color: var(--text-dark);
        font-weight: 600;
        border: 1.5px solid transparent;
        background-color: var(--light-bg);
    }

    .nav-pills-custom .nav-link.active {
        background-color: var(--primary-color) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.25);
    }
</style>

<div class="container-fluid px-0 py-2">
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">PENGATURAN TOKO & SISTEM</h4>
        <p class="text-muted small mb-0">Kelola profil admin, konfigurasi WhatsApp Gateway, dan tarif ongkos kirim wilayah.</p>
    </div>

    <!-- NAVIGASI TAB -->
    <ul class="nav nav-pills nav-pills-custom gap-2 mb-4" id="settingTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profil-tab" data-bs-toggle="pill" data-bs-target="#profil" type="button" role="tab">
                <i class="fa-solid fa-user-gear me-1"></i> Profil Administrator
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="whatsapp-tab" data-bs-toggle="pill" data-bs-target="#whatsapp" type="button" role="tab">
                <i class="fa-brands fa-whatsapp me-1"></i> Gateway WhatsApp
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ongkir-tab" data-bs-toggle="pill" data-bs-target="#ongkir" type="button" role="tab">
                <i class="fa-solid fa-truck me-1"></i> Tarif Ongkos Kirim
            </button>
        </li>
    </ul>

    <div class="tab-content" id="settingTabContent">
        
        <!-- 1. TAB PENGATURAN PROFIL -->
        <div class="tab-pane fade show active" id="profil" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="admin-card h-100">
                        <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                            <i class="fa fa-user me-2" style="color: var(--primary-color);"></i> Informasi Akun Administrator
                        </h6>
                        <form action="{{ route('admin.pengaturan.profile') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Nama Admin:</label>
                                <input type="text" name="username" class="form-control rounded-3" value="{{ Auth::user()->name ?? 'Administrator' }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Alamat Email:</label>
                                <input type="email" name="email" class="form-control rounded-3" value="{{ Auth::user()->email ?? 'admin@assalammebel.com' }}" required>
                            </div>
                            <button type="submit" class="btn btn-dark rounded-3 px-4" style="background-color: var(--primary-color); border: none;">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="admin-card h-100">
                        <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                            <i class="fa fa-lock me-2" style="color: var(--primary-color);"></i> Perbarui Kata Sandi
                        </h6>
                        <form action="{{ route('admin.pengaturan.profile') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Password Lama:</label>
                                <input type="password" name="current_password" class="form-control rounded-3" placeholder="Masukkan password lama">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Password Baru:</label>
                                <input type="password" name="password" class="form-control rounded-3" placeholder="Min. 6 karakter">
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Konfirmasi Password Baru:</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-3" placeholder="Ulangi password baru">
                            </div>
                            <button type="submit" class="btn btn-outline-dark rounded-3 px-4">
                                <i class="fa-solid fa-key me-1"></i> Ganti Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TAB GATEWAY WHATSAPP -->
        <div class="tab-pane fade" id="whatsapp" role="tabpanel">
            <div class="admin-card">
                <h6 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                    <i class="fa-brands fa-whatsapp me-2 text-success"></i> Konfigurasi Notifikasi WhatsApp Gateway
                </h6>
                <form action="{{ route('admin.pengaturan.whatsapp') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nomor WhatsApp Toko (Pengirim):</label>
                            <input type="text" name="wa_number" class="form-control rounded-3" value="{{ $settings['wa_number'] ?? '085234567890' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Status Server WhatsApp API:</label>
                            <select name="wa_status" class="form-select rounded-3">
                                <option value="Terhubung / Aktif" {{ (isset($settings['wa_status']) && $settings['wa_status'] == 'Terhubung / Aktif') ? 'selected' : '' }}>Terhubung / Aktif</option>
                                <option value="Tidak Terhubung" {{ (isset($settings['wa_status']) && $settings['wa_status'] == 'Tidak Terhubung') ? 'selected' : '' }}>Tidak Terhubung</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Template Pesan Pembaruan Progres:</label>
                            <textarea name="wa_template" class="form-control rounded-3" rows="4">{{ $settings['wa_template'] ?? 'Halo *{nama}*, pembaruan untuk pesanan mebel custom Anda (*{produk}* - #{no_pesanan}) saat ini telah memasuki tahap: *{tahap}*. Silakan cek foto progres di aplikasi Assalam Mebel. Terima kasih!' }}</textarea>
                            <small class="text-muted" style="font-size: 0.75rem;">Variabel otomatis yang didukung: {nama}, {produk}, {no_pesanan}, {tahap}</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark rounded-3 px-4" style="background-color: var(--primary-color); border: none;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Konfigurasi WhatsApp
                    </button>
                </form>
            </div>
        </div>

        <!-- 3. TAB TARIF ONGKOS KIRIM -->
        <div class="tab-pane fade" id="ongkir" role="tabpanel">
            <div class="admin-card">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom flex-wrap gap-2">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-truck me-2" style="color: var(--primary-color);"></i> Manajemen Tarif Ongkir Wilayah
                    </h6>
                    <button type="button" class="btn btn-sm btn-dark rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#addShippingModal" style="background-color: var(--primary-color); border: none;">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Wilayah Baru
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover text-center align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 10%;">No</th>
                                <th style="width: 40%;">Wilayah Kecamatan / Kota</th>
                                <th style="width: 30%;">Biaya Ongkos Kirim</th>
                                <th style="width: 20%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shippingCosts as $index => $item)
                            <tr>
                                <td class="fw-bold">{{ $index + 1 }}</td>
                                <td class="text-start ps-4 fw-semibold text-dark">{{ $item->kecamatan }}</td>
                                <td>
                                    <strong style="color: var(--primary-color);">Rp {{ number_format($item->biaya, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-dark rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#editShippingModal{{ $item->id }}">
                                        <i class="fa-solid fa-pen-to-square me-1"></i> Ubah
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-muted py-4">Belum ada data tarif ongkir wilayah.</td>
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
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.pengaturan.shipping.store') }}" method="POST">
            @csrf
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-plus-circle me-1"></i> Tambah Wilayah Pengiriman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Wilayah Kecamatan / Kota:</label>
                        <input type="text" name="kecamatan" class="form-control rounded-3" placeholder="Contoh: Kota Sumenep" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tarif Ongkos Kirim (Rp):</label>
                        <input type="number" name="biaya" class="form-control rounded-3" placeholder="Contoh: 30000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Layanan:</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4" style="background-color: var(--primary-color); border: none;">Simpan Wilayah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Ubah Wilayah -->
@foreach($shippingCosts as $item)
<div class="modal fade" id="editShippingModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.pengaturan.shipping.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fa-solid fa-pen-to-square me-1"></i> Ubah Tarif Wilayah</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Wilayah Kecamatan / Kota:</label>
                        <input type="text" name="kecamatan" class="form-control rounded-3" value="{{ $item->kecamatan }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tarif Ongkos Kirim (Rp):</label>
                        <input type="number" name="biaya" class="form-control rounded-3" value="{{ $item->biaya }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Layanan:</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ $item->status == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4" style="background-color: var(--primary-color); border: none;">Perbarui Tarif</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection