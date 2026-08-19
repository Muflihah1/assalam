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

    .tahap-card {
        border: 1.5px solid var(--light-border);
        border-radius: 12px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }

    .tahap-card:hover {
        border-color: var(--primary-color);
        background-color: var(--light-bg);
    }

    .tahap-card.active {
        border-color: var(--primary-color);
        background-color: rgba(93, 64, 55, 0.08);
    }
</style>

<div class="container-fluid px-0 py-2">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">MANAJEMEN PROGRES PRODUKSI</h4>
            <p class="text-muted small mb-0">Pilih tahapan pengerjaan saat ini, unggah dokumentasi foto/video, dan kirimkan pembaruan ke pelanggan.</p>
        </div>
        @if(isset($allOrders) && $allOrders->count() > 1)
            <div>
                <select class="form-select rounded-3 shadow-sm" onchange="window.location.href='/admin/progres-produksi/' + this.value">
                    <option value="">-- Pilih Pesanan Lain --</option>
                    @foreach($allOrders as $o)
                        <option value="{{ $o->id }}" {{ (isset($progres) && $progres->id == $o->id) ? 'selected' : '' }}>
                            #{{ $o->order_number }} - {{ $o->recipient_name ?? $o->user->name }} ({{ $o->customDesign->category ?? 'Mebel' }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    @if(!$progres)
        <div class="admin-card text-center py-5">
            <i class="fa-solid fa-gears fa-3x text-muted mb-3"></i>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Pesanan yang Perlu Diproses</h5>
            <p class="text-muted small mb-0">Pesanan yang telah diverifikasi DP akan otomatis muncul di halaman ini.</p>
        </div>
    @else
        <form action="{{ route('admin.progres.update', $progres->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="admin-card mb-4">
                
                <!-- INFORMASI PESANAN -->
                <div class="p-3.5 rounded-3 mb-4" style="background-color: var(--light-bg); border: 1px solid var(--light-border);">
                    <p class="fw-bold small mb-2 text-uppercase" style="color: var(--primary-color);">
                        <i class="fa fa-user-pen me-1"></i> INFORMASI PESANAN & PEMESAN
                    </p>
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold text-muted">No. Pesanan:</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="#{{ $progres->order_number }}" readonly>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">Nama Pelanggan:</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $progres->recipient_name ?? $progres->user->name }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Nomor WhatsApp:</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $progres->recipient_phone ?? $progres->user->whatsapp_number }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Spesifikasi Mebel:</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $progres->customDesign->category ?? 'Mebel' }} - {{ $progres->customDesign->wood_material ?? 'Kayu Jati' }} ({{ $progres->customDesign->length_cm ?? 0 }}x{{ $progres->customDesign->width_cm ?? 0 }}x{{ $progres->customDesign->height_cm ?? 0 }}cm)" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Alamat Pengiriman:</label>
                            <input type="text" class="form-control form-control-sm bg-white" value="{{ $progres->shipping_address ?? $progres->user->alamat ?? '-' }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- TAHAP PRODUKSI -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-uppercase d-block mb-3" style="color: var(--primary-color);">
                        PILIH TAHAPAN PENGERJAAN SAAT INI :
                    </label>
                    
                    @php
                        $tahapList = [
                            'Konfirmasi Pesanan', 
                            'Validasi Pembayaran', 
                            'Pesanan Diterima', 
                            'Menyiapkan Bahan', 
                            'Perakitan', 
                            'Penyelesaian', 
                            'Pengiriman', 
                            'Pesanan Selesai'
                        ];
                        $currentTahap = $progres->current_stage ?? 'Pesanan Diterima';
                    @endphp

                    <div class="row g-2">
                        @foreach($tahapList as $index => $tahap)
                            @php $isChecked = ($currentTahap == $tahap); @endphp
                            <div class="col-md-3 col-6">
                                <label class="tahap-card w-100 d-flex align-items-center gap-2 {{ $isChecked ? 'active' : '' }}">
                                    <input class="form-check-input mt-0" type="radio" name="tahap" value="{{ $tahap }}" {{ $isChecked ? 'checked' : '' }} required>
                                    <span class="small fw-bold text-dark">{{ $index + 1 }}. {{ $tahap }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- UPLOAD DOKUMENTASI & CATATAN -->
                <div class="p-3.5 rounded-3 mb-4" style="border: 1px solid var(--light-border);">
                    <p class="fw-bold small mb-3 text-uppercase" style="color: var(--primary-color);">
                        UPLOAD DOKUMENTASI PROGRES (FOTO / VIDEO)
                    </p>
                    <div class="row align-items-center g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold text-muted">Pilih Berkas Foto/Video Baru:</label>
                            <input type="file" name="media[]" class="form-control rounded-3" multiple accept="image/*,video/*">
                            <small class="text-muted" style="font-size: 0.75rem;">Dapat mengunggah beberapa file foto/video pengerjaan.</small>
                        </div>
                        
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold text-muted">Catatan Progres Untuk Pelanggan :</label>
                            <textarea name="catatan" class="form-control rounded-3" rows="3" placeholder="Tuliskan catatan progres pengerjaan furniture...">{{ $progres->admin_notes ?? 'Proses pengerjaan berjalan dengan lancar sesuai spesifikasi.' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- PILIHAN AKSI -->
                <div class="d-flex justify-content-between align-items-center pt-2 flex-wrap gap-2">
                    <span class="small text-muted">
                        <i class="fa-brands fa-whatsapp text-success me-1"></i> Pembaruan progres akan langsung tersinkronisasi ke akun pelanggan.
                    </span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.pesanan.masuk') }}" class="btn btn-outline-secondary btn-sm px-4 rounded-3">Kembali</a>
                        <button type="submit" class="btn btn-dark btn-sm px-4 rounded-3 shadow-sm" style="background-color: var(--primary-color); border: none;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan & Perbarui Progres
                        </button>
                    </div>
                </div>

            </div>
        </form>
    @endif
</div>
@endsection