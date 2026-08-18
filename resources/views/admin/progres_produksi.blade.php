@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-3">
    <h4 class="fw-bold text-dark mb-4 tracking-wide">HALAMAN ADMIN : KELOLA PROGRES PRODUKSI</h4>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa fa-exclamation-triangle me-2"></i> Periksa kembali inputan Anda:
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.progres.update', $progres->id ?? 1) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            
            <!-- INFORMASI PESANAN -->
            <div class="border border-dark p-3 rounded-3 mb-3 bg-light">
                <p class="fw-bold small mb-3 text-uppercase text-secondary"><i class="fa fa-user-pen me-1"></i> INFORMASI PESANAN & PELANGGAN</p>
                
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">ID Pesanan :</label>
                        <input type="text" name="id_pesanan" class="form-control form-control-sm border-dark" value="{{ $progres->id_pesanan ?? '10021' }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-muted">Nama Pelanggan :</label>
                        <input type="text" name="pelanggan" class="form-control form-control-sm border-dark" value="{{ $progres->pelanggan ?? 'Ardi' }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Nomor WhatsApp Pelanggan :</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-dark">+62</span>
                            <input type="text" name="no_wa" class="form-control form-control-sm border-dark" value="{{ $progres->no_wa ?? '85123456789' }}" placeholder="8xxxxxxxxxx" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Produk :</label>
                        <input type="text" name="produk" class="form-control form-control-sm border-dark" value="{{ $progres->produk ?? 'Kursi' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-muted">Alamat Pengiriman :</label>
                        <input type="text" name="alamat" class="form-control form-control-sm border-dark" value="{{ $progres->alamat ?? 'Jl. Melati No. 5' }}" required>
                    </div>
                </div>
            </div>

            <!-- TAHAP PRODUKSI (HANYA BISA PILIH SATU WALAUPUN BENTUKNYA KOTAK) -->
            <div class="border border-dark p-3 rounded-3 mb-3">
                <p class="fw-bold small mb-3 text-uppercase text-secondary">PILIH TAHAP PRODUKSI SAAT INI (PILIH SATU) :</p>
                
                @php
                    $tahapList = [
                        'Pesanan Diterima', 
                        'Validasi', 
                        'Pemotongan', 
                        'Perakitan', 
                        'Penyelesaian', 
                        'Pengiriman', 
                        'Selesai'
                    ];
                    $currentTahap = $progres->tahap ?? 'Pemotongan';
                @endphp

                <div class="row g-2">
                    @foreach($tahapList as $tahap)
                        @php
                            $isChecked = ($currentTahap == $tahap);
                        @endphp
                        <div class="col-md-3 col-6">
                            <div class="form-check border p-2 rounded-2 {{ $isChecked ? 'bg-dark text-white shadow-sm' : 'bg-white' }}">
                                <!-- Gunakan name="tahap" tanpa kurung siku agar dikirim sebagai string tunggal -->
                                <input class="form-check-input ms-1 custom-checkbox tahap-checkbox" type="checkbox" name="tahap" id="tahap_{{ $loop->index }}" value="{{ $tahap }}" {{ $isChecked ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold ms-2 w-100" for="tahap_{{ $loop->index }}" style="cursor: pointer;">
                                    {{ $tahap }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- UPLOAD DOKUMENTASI & CATATAN -->
            <div class="border border-dark p-3 rounded-3 mb-3">
                <p class="fw-bold small mb-3 text-uppercase text-secondary">UPLOAD DOKUMENTASI PROGRES (FOTO/VIDEO)</p>
                <div class="row align-items-center">
                    <div class="col-md-3 text-center border border-dark border-dashed py-4 bg-light rounded-3 position-relative mb-3 mb-md-0" style="cursor: pointer;" onclick="document.getElementById('mediaInput').click();">
                        <i class="fa-solid fa-camera fa-2x mb-2 text-secondary"></i>
                        <p class="small mb-0 fw-semibold">Upload Foto/Video</p>
                        <input type="file" name="media[]" id="mediaInput" class="d-none" multiple accept="image/*,video/*">
                    </div>
                    
                    <div class="col-md-9">
                        <div class="mb-2">
                            <span class="small badge bg-secondary me-2">[Nama_file_foto1.jpg]</span> 
                            <a href="#" class="text-danger text-decoration-none small fw-semibold">(Hapus)</a>
                        </div>
                        <div class="mb-3">
                            <span class="small badge bg-secondary me-2">[Nama_file_foto2.jpg]</span> 
                            <a href="#" class="text-danger text-decoration-none small fw-semibold">(Hapus)</a>
                        </div>
                        
                        <label class="form-label small fw-semibold text-muted">Catatan Progres Untuk Pelanggan :</label>
                        <textarea name="catatan" class="form-control border-dark form-control-sm" rows="2" required>Catatan: Proses Pemotongan kayu jati untuk alas meja sudah selesai 60%</textarea>
                    </div>
                </div>
            </div>

            <!-- PILIHAN AKSI -->
            <div class="border border-dark p-3 rounded-3 bg-light">
                <p class="fw-bold small mb-1 text-uppercase">PILIHAN AKSI :</p>
                <p class="small text-muted mb-3"><i class="fa-brands fa-whatsapp text-success me-1"></i> Kirimkan Pembaruan Progres Otomatis ke WhatsApp Pelanggan</p>
                
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.progres.produksi') }}" class="btn btn-outline-dark btn-sm px-4 rounded-0 action-btn">Batalkan Perubahan</a>
                    <button type="submit" class="btn btn-dark btn-sm px-4 rounded-0 shadow-sm action-btn">
                        <i class="fa-solid fa-paper-plane me-1"></i> Simpan & Kirim WA
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<style>
    .custom-checkbox {
        border: 2px solid #333 !important;
        width: 1.2rem;
        height: 1.2rem;
        margin-top: 0.15rem;
    }
    .action-btn {
        transition: transform 0.15s ease, background-color 0.15s ease;
    }
    .action-btn:hover {
        transform: translateY(-1px);
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd !important;
    }
    .form-check-input:checked ~ .form-check-label {
        color: #fff !important;
    }
</style>

<!-- Script agar checkbox bertindak seperti radio button (hanya 1 yang bisa dicentang) -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const checkboxes = document.querySelectorAll(".tahap-checkbox");

        checkboxes.forEach(cb => {
            cb.addEventListener("change", function () {
                if (this.checked) {
                    // Jika salah satu dicentang, uncheck semua checkbox yang lain dan ubah kembali stylenya ke putih
                    checkboxes.forEach(otherCb => {
                        if (otherCb !== this) {
                            otherCb.checked = false;
                            let otherParent = otherCb.closest(".form-check");
                            otherParent.classList.remove("bg-dark", "text-white", "shadow-sm");
                            otherParent.classList.add("bg-white");
                        }
                    });

                    // Ubah style kotak yang sedang dipilih menjadi aktif (gelap)
                    let parentDiv = this.closest(".form-check");
                    parentDiv.classList.add("bg-dark", "text-white", "shadow-sm");
                    parentDiv.classList.remove("bg-white");
                } else {
                    // Mencegah user membatalkan centang jika itu satu-satunya yang terpilih (opsional, agar minimal ada 1 yang terpilih)
                    this.checked = true;
                }
            });
        });
    });
</script>
@endsection