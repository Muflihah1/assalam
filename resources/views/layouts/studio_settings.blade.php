@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <h3 class="fw-bold mb-4 text-white">Kelola Konten Studio Custom Design</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card bg-dark text-white p-4 border-secondary" style="border-radius: 20px;">
        <form action="{{ route('admin.studio.update') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold">Judul Studio (Header)</label>
                <input type="text" name="studio_title" class="form-control bg-secondary text-white border-0" value="{{ $settings['studio_title'] ?? 'Studio Custom Design' }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Subjudul Studio</label>
                <input type="text" name="studio_subtitle" class="form-control bg-secondary text-white border-0" value="{{ $settings['studio_subtitle'] ?? 'Rancang ukuran, bahan, dan warna furniture impianmu secara presisi' }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Harga Dasar Default Produk (Rp)</label>
                <input type="number" name="base_price" class="form-control bg-secondary text-white border-0" value="{{ $settings['base_price'] ?? '3000000' }}">
            </div>

            <button type="submit" class="btn btn-warning fw-bold px-4">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection