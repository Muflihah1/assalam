@extends('admin.layout')

@section('content')
<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0 tracking-wide">HALAMAN ADMIN : KATALOG PRODUK</h4>
        <button type="button" class="btn btn-dark btn-sm px-4 rounded-0 shadow-sm action-btn" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
            <i class="fa fa-plus me-1"></i> Tambah Produk Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @foreach($listProduk as $item)
        <div class="col-md-4 col-sm-6">
            <div class="card border border-dark rounded-4 p-3 bg-white shadow-sm h-100 product-card">
                
                <div class="border border-dark rounded-3 overflow-hidden mb-3 text-center bg-light" style="height: 160px; display: flex; align-items: center; justify-content: center;">
                    @if($item->foto)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($item->foto) }}" alt="{{ $item->nama }}" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="text-muted">
                            <i class="fa-solid fa-box fa-3x mb-2 d-block text-secondary"></i>
                            <span class="small fw-semibold">Tidak ada foto</span>
                        </div>
                    @endif
                </div>

                <div class="card-body p-0 text-center">
                    <h6 class="fw-bold text-dark mb-1">{{ $item->nama }}</h6>
                    <p class="text-muted small mb-2 px-2" style="font-size: 0.85rem;">{{ $item->deskripsi }}</p>
                    <p class="fw-bold text-primary mb-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    
                    <div class="d-flex justify-content-center gap-3 border-top pt-2">
                        <button type="button" class="btn btn-outline-dark btn-sm px-3 rounded-0 small fw-semibold" 
                                data-bs-toggle="modal" data-bs-target="#modalEditProduk"
                                data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-harga="{{ $item->harga }}" data-deskripsi="{{ $item->deskripsi }}">
                            <i class="fa fa-pen-to-square me-1"></i> Edit
                        </button>
                        
                        <form action="{{ url('admin/katalog/' . $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-0 small fw-semibold">
                                <i class="fa fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-plus-circle me-1"></i> Tambah Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.katalog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Produk :</label>
                        <input type="text" name="nama" class="form-control form-control-sm border-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi :</label>
                        <textarea name="deskripsi" class="form-control form-control-sm border-dark" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Harga (Rp) :</label>
                        <input type="number" name="harga" class="form-control form-control-sm border-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Foto Produk :</label>
                        <input type="file" name="foto" class="form-control form-control-sm border-dark" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="submit" class="btn btn-dark btn-sm rounded-0 px-4">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-dark rounded-4 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-pen-to-square me-1"></i> Edit Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditProduk" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Produk :</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control form-control-sm border-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Deskripsi :</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control form-control-sm border-dark" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Harga (Rp) :</label>
                        <input type="number" id="edit_harga" name="harga" class="form-control form-control-sm border-dark" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Ganti Foto Produk (Opsional) :</label>
                        <input type="file" name="foto" class="form-control form-control-sm border-dark" accept="image/*">
                        <small class="text-muted" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengganti foto.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="submit" class="btn btn-dark btn-sm rounded-0 px-4">Perbarui Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modalEdit = document.getElementById('modalEditProduk');
    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function (event) {
            let button = event.relatedTarget;
            document.getElementById('edit_nama').value = button.getAttribute('data-nama');
            document.getElementById('edit_deskripsi').value = button.getAttribute('data-deskripsi');
            document.getElementById('edit_harga').value = button.getAttribute('data-harga');
            document.getElementById('formEditProduk').action = '/admin/katalog/' + button.getAttribute('data-id');
        });
    }
});
</script>

<style>
    .product-card { transition: transform 0.2s ease; }
    .product-card:hover { transform: translateY(-5px); }
</style>
@endsection