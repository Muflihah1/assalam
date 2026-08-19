@extends('admin.layout')

@section('content')
<style>
    .admin-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
        transition: transform 0.2s ease;
    }

    .admin-card:hover {
        transform: translateY(-4px);
        border-color: var(--primary-color);
    }

    .img-box-katalog {
        height: 180px;
        background-color: #fdfaf6;
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--light-border);
    }

    .img-box-katalog img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<div class="container-fluid px-0 py-2">
    
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1">MANAJEMEN KATALOG PRODUK</h4>
            <p class="text-muted small mb-0">Kelola daftar koleksi produk mebel yang tampil di katalog pelanggan.</p>
        </div>
        <button type="button" class="btn btn-dark px-4 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahProduk" style="background-color: var(--primary-color); border: none;">
            <i class="fa fa-plus me-1"></i> Tambah Produk Baru
        </button>
    </div>

    <div class="row g-4">
        @forelse($listProduk as $item)
        <div class="col-lg-4 col-md-6">
            <div class="admin-card p-3 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="img-box-katalog mb-3">
                        @if($item->foto)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($item->foto) }}" alt="{{ $item->nama }}">
                        @else
                            <div class="text-muted text-center">
                                <i class="fa-solid fa-couch fa-3x mb-1" style="color: var(--primary-color);"></i>
                                <span class="small d-block fw-bold text-dark">Tidak ada foto</span>
                            </div>
                        @endif
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ $item->nama }}</h5>
                    <p class="text-muted small mb-2">{{ Str::limit($item->deskripsi, 85) }}</p>
                    <h5 class="fw-extrabold mb-3" style="color: var(--primary-color);">Rp {{ number_format($item->harga, 0, ',', '.') }}</h5>
                </div>
                
                <div class="d-flex justify-content-between gap-2 border-top pt-3" style="border-color: var(--light-border) !important;">
                    <button type="button" class="btn btn-outline-dark btn-sm rounded-3 px-3 w-50 fw-semibold" 
                            data-bs-toggle="modal" data-bs-target="#modalEditProduk"
                            data-id="{{ $item->id }}" data-nama="{{ $item->nama }}" data-harga="{{ $item->harga }}" data-deskripsi="{{ $item->deskripsi }}">
                        <i class="fa fa-pen-to-square me-1"></i> Edit
                    </button>
                    
                    <form action="{{ route('admin.katalog.destroy', $item->id) }}" method="POST" class="w-50" onsubmit="return confirm('Yakin ingin menghapus produk ini dari katalog?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 px-3 w-100 fw-semibold">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="admin-card p-5">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3"></i>
                <h5 class="fw-bold text-dark mb-1">Belum Ada Produk di Katalog</h5>
                <p class="text-muted small mb-0">Klik tombol "Tambah Produk Baru" di atas untuk menambahkan koleksi mebel.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- MODAL TAMBAH PRODUK -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-plus-circle me-1"></i> Tambah Produk Katalog Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.katalog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Produk Mebel:</label>
                        <input type="text" name="nama" class="form-control rounded-3" placeholder="Contoh: Sofa Modern Luxury" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi Produk:</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3" placeholder="Rincian bahan kayu, kain, ukuran..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Standar (Rp):</label>
                        <input type="number" name="harga" class="form-control rounded-3" placeholder="Contoh: 4500000" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Foto Produk:</label>
                        <input type="file" name="foto" class="form-control rounded-3" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4" style="background-color: var(--primary-color); border: none;">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT PRODUK -->
<div class="modal fade" id="modalEditProduk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-pen-to-square me-1"></i> Edit Produk Katalog</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditProduk" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Produk:</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi:</label>
                        <textarea id="edit_deskripsi" name="deskripsi" class="form-control rounded-3" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Standar (Rp):</label>
                        <input type="number" id="edit_harga" name="harga" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Ganti Foto Produk (Opsional):</label>
                        <input type="file" name="foto" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted" style="font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengganti foto saat ini.</small>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark btn-sm rounded-3 px-4" style="background-color: var(--primary-color); border: none;">Perbarui Produk</button>
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
@endsection