@extends('layouts.customer')

@section('content')
<style>
    /* Studio Card */
    .studio-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(93, 64, 55, 0.05);
        transition: all 0.3s ease;
    }

    .form-control-custom, .form-select-custom {
        background-color: #fdfbf7 !important;
        border: 1.5px solid var(--wood-border) !important;
        color: var(--text-main) !important;
        border-radius: 12px;
        padding: 10px 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.12) !important;
        background-color: #ffffff !important;
    }

    .form-label-custom {
        color: var(--text-main);
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 6px;
    }

    .dimension-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    /* Modern Dimension Box & Stepper Styling */
    .dimension-section-container {
        background: #ffffff;
        border: 2px solid var(--wood-border);
        border-radius: 20px;
        padding: 22px 24px;
        box-shadow: 0 4px 20px rgba(93, 64, 55, 0.05);
        margin-bottom: 24px;
    }

    .dim-card {
        background: #faf7f2;
        border: 2px solid #dfd2c4;
        border-radius: 14px;
        padding: 14px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .dim-card:hover, .dim-card:focus-within {
        background: #ffffff;
        border-color: var(--primary-color);
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.1);
        transform: translateY(-2px);
    }

    .dim-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 28px;
        margin-bottom: 10px;
    }

    .dim-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: var(--primary-color);
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 800;
        box-shadow: 0 2px 5px rgba(93, 64, 55, 0.2);
        flex-shrink: 0;
    }

    .dim-name {
        font-size: 0.88rem;
        font-weight: 700;
        color: #2d241e;
        letter-spacing: -0.01em;
    }

    .dim-diff-tag {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        background: #f0ebe4;
        color: #7d6b5c;
        border: 1px solid #d9cbbe;
        transition: all 0.2s ease;
    }

    /* Stepper Component with Distinct Buttons & Clear Borders */
    .dim-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 8px;
    }

    .dim-btn {
        width: 36px;
        height: 38px;
        border-radius: 10px;
        border: 2px solid #d4c2b0;
        background: #ffffff;
        color: #5d4037;
        font-size: 0.82rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(93, 64, 55, 0.05);
        flex-shrink: 0;
    }

    .dim-btn:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(93, 64, 55, 0.2);
    }

    .dim-btn:active {
        transform: translateY(1px);
        box-shadow: 0 1px 2px rgba(93, 64, 55, 0.1);
    }

    .dim-input-wrap {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        background: #ffffff;
        border: 2px solid #d4c2b0;
        border-radius: 10px;
        padding: 0 6px;
        box-shadow: inset 0 1px 3px rgba(93, 64, 55, 0.04);
        transition: all 0.2s ease;
    }

    .dim-card:focus-within .dim-input-wrap {
        border-color: var(--accent-gold);
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
    }

    .dim-input {
        width: 100%;
        max-width: 54px;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: transparent !important;
        text-align: center;
        font-weight: 800;
        font-size: 1.15rem;
        color: #2d241e;
        padding: 0;
        -moz-appearance: textfield;
    }

    .dim-input::-webkit-outer-spin-button,
    .dim-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .dim-unit {
        font-size: 0.78rem;
        font-weight: 700;
        color: #8c6d52;
        user-select: none;
        background: #faf4ed;
        border: 1px solid #ebdcd0;
        border-radius: 6px;
        padding: 2px 6px;
        margin-left: 2px;
        flex-shrink: 0;
    }

    .dim-limits {
        display: flex;
        justify-content: space-between;
        font-size: 0.7rem;
        color: #8c7664;
        padding: 0 2px;
        font-weight: 600;
    }

    /* Craftsman Notes Card Styling */
    .craftsman-note-card {
        background: #ffffff;
        border: 2px solid var(--wood-border);
        border-left: 5px solid var(--accent-gold);
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 4px 18px rgba(93, 64, 55, 0.04);
        transition: all 0.25s ease;
    }

    .craftsman-note-card:hover, .craftsman-note-card:focus-within {
        box-shadow: 0 6px 22px rgba(93, 64, 55, 0.09);
        border-color: var(--primary-color);
        border-left-color: var(--accent-gold);
    }

    .craftsman-note-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #2d241e;
    }

    .craftsman-note-badge {
        background: #faf4ed;
        color: var(--primary-color);
        border: 1.5px solid var(--wood-border);
        font-size: 0.72rem;
    }

    .craftsman-textarea {
        background: #faf7f2 !important;
        border: 2px solid #dfd2c4 !important;
        border-radius: 12px !important;
        padding: 12px 16px !important;
        font-size: 0.88rem !important;
        font-weight: 500 !important;
        color: #2d241e !important;
        line-height: 1.55 !important;
        transition: all 0.2s ease !important;
        resize: vertical;
        box-shadow: inset 0 1px 3px rgba(93, 64, 55, 0.03);
    }

    .craftsman-textarea:focus {
        background: #ffffff !important;
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15) !important;
    }

    .craftsman-textarea::placeholder {
        color: #a39282 !important;
        font-style: italic;
    }

    /* Color Swatch Box */
    .color-swatch-box {
        background: linear-gradient(135deg, #fdfbf7 0%, var(--wood-bg) 100%);
        border: 1.5px solid var(--wood-border);
        border-radius: 20px;
        padding: 20px;
    }

    .swatch-group-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 8px;
        margin-top: 14px;
    }

    .color-swatch-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .color-swatch-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2.5px solid #ffffff;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

    .color-swatch-btn:hover { 
        transform: scale(1.2); 
    }
    
    .color-swatch-btn.active {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.4), 0 4px 10px rgba(0,0,0,0.15);
        transform: scale(1.15);
    }

    .custom-color-picker-wrapper {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
        background: conic-gradient(from 0deg, #ff0000, #ff8000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);
        border: 2px solid #ffffff;
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

    .custom-color-input {
        position: absolute;
        top: -10px; left: -10px;
        width: 60px; height: 60px;
        opacity: 0; cursor: pointer;
    }

    .live-preview-circle {
        width: 48px; height: 48px;
        border-radius: 50%;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: background-color 0.3s ease;
    }

    /* Buttons */
    .btn-orange {
        background: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border: none;
        border-radius: 14px;
        padding: 14px 24px;
        box-shadow: 0 8px 20px rgba(93, 64, 55, 0.2);
        transition: all 0.3s ease;
    }

    .btn-orange:hover {
        background: var(--secondary-color);
        color: #ffffff;
        transform: translateY(-2px);
    }

    .price-display {
        color: var(--primary-color);
        font-weight: 800;
        font-size: 1.45rem;
    }

    .teak-guarantee-card {
        background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%);
        border: 1.5px solid #f59e0b;
        border-radius: 16px;
        padding: 16px 20px;
    }

    .product-select-modal-card {
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .product-select-modal-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.1);
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- HEADER INTERAKTIF -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-3 border-bottom gap-3" style="border-color: var(--light-border) !important;">
        <div>
            <span class="badge px-3 py-1 rounded-pill fw-bold mb-2 shadow-2xs" style="background-color: rgba(217, 119, 6, 0.15); color: var(--accent-gold); font-size: 0.75rem;">
                <i class="fa-solid fa-tree me-1"></i> 100% KAYU JATI SOLID PERHUTANI
            </span>
            <h3 class="fw-bold mb-1 text-dark">Studio Custom Mebel Kayu Jati</h3>
            <p class="text-muted small mb-0">Pilih model produk mebel jati favorit Anda, sesuaikan ukuran presisi dan pilihan warna finishing sesuai selera ruangan.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold small shadow-2xs" data-bs-toggle="modal" data-bs-target="#modalSelectProduct">
                <i class="fa-solid fa-arrows-rotate me-1 text-warning"></i> Ganti Model Produk Dasar
            </button>
            <a href="{{ route('customer.katalog') }}" class="btn btn-light border rounded-pill px-3 py-2 small fw-semibold">
                <i class="fa-solid fa-bag-shopping me-1"></i> Katalog
            </a>
        </div>
    </div>

    <!-- FORM CUSTOM UTAMA -->
    <form id="formCustomMebel" action="{{ route('customer.design.order') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- KARTU PRODUK DASAR KATALOG -->
        @if(isset($selectedProduct) && $selectedProduct)
            <div class="mb-4 p-3.5 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3 shadow-sm" style="background: linear-gradient(135deg, rgba(217, 119, 6, 0.1) 0%, rgba(93, 64, 55, 0.08) 100%); border: 1.5px solid var(--accent-gold);">
                <div class="d-flex align-items-center gap-3">
                    @if($selectedProduct->foto_url)
                        <img src="{{ $selectedProduct->foto_url }}" alt="{{ $selectedProduct->nama }}" style="width: 78px; height: 78px; border-radius: 14px; object-fit: cover; border: 2.5px solid #ffffff;" class="shadow-2xs">
                    @else
                        <div class="bg-white rounded-3 d-flex align-items-center justify-content-center border" style="width: 78px; height: 78px;">
                            <i class="fa-solid fa-couch fa-2x text-warning"></i>
                        </div>
                    @endif
                    <div>
                        <span class="badge px-2.5 py-1 rounded-pill fw-bold bg-warning text-dark mb-1" style="font-size: 0.7rem;">
                            MODEL PRODUK DASAR KATALOG
                        </span>
                        <h5 class="fw-bold text-dark mb-0">{{ $selectedProduct->nama }}</h5>
                        <div class="small text-muted mt-0.5">
                            Kategori: <strong class="text-dark">{{ $selectedProduct->kategori ?? 'Furniture Kayu Jati' }}</strong> | 
                            Harga Dasar: <strong style="color: var(--primary-color);">Rp {{ number_format($selectedProduct->harga, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-dark rounded-pill px-3 py-1.5 shadow-2xs fw-bold" data-bs-toggle="modal" data-bs-target="#modalSelectProduct" style="background-color: var(--primary-color); border: none;">
                        <i class="fa-solid fa-shuffle me-1"></i> Ubah Pilihan Produk
                    </button>
                </div>
            </div>
            <input type="hidden" name="product_id" id="inputProductId" value="{{ $selectedProduct->id }}">
            <input type="hidden" id="rawBasePrice" value="{{ $selectedProduct->harga }}">
            <input type="hidden" name="category" id="inputKategori" value="{{ $selectedProduct->kategori ?? 'Furniture Kayu Jati' }}">
        @endif

        <div id="validationAlert" class="alert alert-danger d-none rounded-4 mb-4 shadow-sm"></div>

        <div class="row g-4">
            <!-- SISI KIRI: FORM PARAMETER (UKURAN & WARNA) -->
            <div class="col-lg-7">
                <div class="studio-card p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--light-border) !important;">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-sliders me-2" style="color: var(--accent-gold);"></i>Penyesuaian Dimensi & Finishing
                        </h5>
                        <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill">Khusus Kayu Jati</span>
                    </div>

                    <!-- 1. MATERIAL KAYU JATI (HANYA SATU PILIHAN: KAYU JATI) -->
                    <div class="mb-4">
                        <label class="form-label-custom">Pilihan Material Kayu Utama</label>
                        <div class="teak-guarantee-card d-flex align-items-start gap-3 shadow-2xs">
                            <div class="rounded-circle p-2 bg-warning text-dark flex-shrink-0 mt-0.5 shadow-sm">
                                <i class="fa-solid fa-award fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-1 mb-1">
                                    <h6 class="fw-bold text-dark mb-0">Kayu Jati Solid Grade A (Perhutani)</h6>
                                    <span class="badge bg-dark text-white rounded-pill px-2.5 py-0.5 small" style="font-size: 0.7rem;">PILIHAN TUNGGAL TERBAIK</span>
                                </div>
                                <p class="text-muted small mb-0" style="font-size: 0.8rem; line-height: 1.45;">
                                    Seluruh pesanan mebel custom di Assalam dikerjakan secara eksklusif menggunakan <strong>100% Kayu Jati Solid Grade A</strong> dari Perhutani. Memiliki serat emas alami yang mewah, kaya minyak alami (anti rayap & anti bubuk), kokoh, dan bergaransi bertahan puluhan tahun.
                                </p>
                            </div>
                        </div>
                        <input type="hidden" name="wood_material" id="inputMaterial" value="Kayu Jati Solid Grade A (Perhutani)">
                    </div>

                    <!-- 2. DIMENSI UKURAN PRESISI (P, L, T) -->
                    @php
                        $defaultDim = (isset($selectedProduct) && $selectedProduct) ? $selectedProduct->default_dimensions : ['length' => 180, 'width' => 80, 'height' => 75];
                        $stdP = $defaultDim['length'];
                        $stdL = $defaultDim['width'];
                        $stdT = $defaultDim['height'];
                    @endphp
                    <div class="dimension-section-container">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3 pb-2 border-bottom" style="border-color: #ebdcd0 !important;">
                            <div>
                                <label class="form-label-custom mb-0 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-ruler-combined" style="color: var(--accent-gold);"></i>
                                    <span>Dimensi & Ukuran Presisi (cm)</span>
                                </label>
                                <div class="small text-muted mt-0.5" style="font-size: 0.78rem;">
                                    Ukuran standar model: <strong id="labelStandardDim" class="text-dark">{{ $stdP }} × {{ $stdL }} × {{ $stdT }} cm</strong>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-3 fw-semibold" onclick="resetToStandardDim()" title="Kembalikan nilai ke ukuran standar produk ini" style="font-size: 0.78rem; border-color: #cfbeae;">
                                <i class="fa-solid fa-rotate-left me-1 text-warning"></i> Reset Standar
                            </button>
                        </div>

                        <div class="row g-3">
                            <!-- PANJANG -->
                            <div class="col-md-4">
                                <div class="dim-card">
                                    <div class="dim-card-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="dim-badge">P</span>
                                            <span class="dim-name">Panjang</span>
                                        </div>
                                        <span id="diffP" class="dim-diff-tag">Standar</span>
                                    </div>
                                    <div class="dim-stepper">
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputPanjang', -5)" title="Kurangi 5 cm">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <div class="dim-input-wrap">
                                            <input type="number" name="length_cm" id="inputPanjang" class="dim-input" value="{{ $stdP }}" oninput="onDimInput()" min="20" max="600" required>
                                            <span class="dim-unit">cm</span>
                                        </div>
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputPanjang', 5)" title="Tambah 5 cm">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="dim-limits">
                                        <span>Min: 20 cm</span>
                                        <span>Maks: 600 cm</span>
                                    </div>
                                </div>
                            </div>

                            <!-- LEBAR -->
                            <div class="col-md-4">
                                <div class="dim-card">
                                    <div class="dim-card-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="dim-badge">L</span>
                                            <span class="dim-name">Lebar</span>
                                        </div>
                                        <span id="diffL" class="dim-diff-tag">Standar</span>
                                    </div>
                                    <div class="dim-stepper">
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputLebar', -5)" title="Kurangi 5 cm">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <div class="dim-input-wrap">
                                            <input type="number" name="width_cm" id="inputLebar" class="dim-input" value="{{ $stdL }}" oninput="onDimInput()" min="4" max="500" required>
                                            <span class="dim-unit">cm</span>
                                        </div>
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputLebar', 5)" title="Tambah 5 cm">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="dim-limits">
                                        <span>Min: 4 cm</span>
                                        <span>Maks: 500 cm</span>
                                    </div>
                                </div>
                            </div>

                            <!-- TINGGI / TEBAL -->
                            <div class="col-md-4">
                                <div class="dim-card">
                                    <div class="dim-card-header">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="dim-badge">T</span>
                                            <span class="dim-name">Tinggi <small class="text-muted fw-normal" style="font-size: 0.72rem;">/ Tebal</small></span>
                                        </div>
                                        <span id="diffT" class="dim-diff-tag">Standar</span>
                                    </div>
                                    <div class="dim-stepper">
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputTinggi', -5)" title="Kurangi 5 cm">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <div class="dim-input-wrap">
                                            <input type="number" name="height_cm" id="inputTinggi" class="dim-input" value="{{ $stdT }}" oninput="onDimInput()" min="2" max="500" required>
                                            <span class="dim-unit">cm</span>
                                        </div>
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputTinggi', 5)" title="Tambah 5 cm">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="dim-limits">
                                        <span>Min: 2 cm</span>
                                        <span>Maks: 500 cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 pt-2.5 border-top d-flex align-items-center gap-2 text-muted" style="border-color: #eeddcc !important; font-size: 0.75rem;">
                            <i class="fa-solid fa-circle-check text-success flex-shrink-0"></i>
                            <span>Ukuran otomatis disinkronkan dengan model mebel jati. Geser dengan tombol <strong>[-] / [+]</strong> kelipatan 5 cm atau ketik angka langsung.</span>
                        </div>
                    </div>

                    <!-- 3. PEMILIHAN WARNA SWATCHES & SLIDER FINISHING JATI -->
                    <div class="mb-4">
                        <label class="form-label-custom">Pilihan Warna Finishing Kayu Jati</label>

                        <div class="color-swatch-box">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--wood-border) !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="live-preview-circle" id="live-color-preview" style="background-color: #d97706;"></div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" id="selected-color-name">Amber Gold (Jati Alami)</h6>
                                        <small class="text-muted fw-semibold" id="selected-color-hex">HEX: #D97706</small>
                                    </div>
                                </div>
                                <span class="badge fw-bold px-3 py-1.5 rounded-pill shadow-sm" style="background-color: #ffffff; color: var(--text-main); border: 1.5px solid var(--wood-border);" id="brightness-badge">Tone: 100%</span>
                            </div>

                            <div class="mb-3 px-1">
                                <label class="form-label text-muted small fw-bold mb-1">Sesuaikan Kecerahan Tone Finishing (Doff - Glossy)</label>
                                <input type="range" class="form-range" id="brightness-slider" min="40" max="160" value="100" oninput="adjustBrightness(this.value)" style="accent-color: var(--primary-color);">
                            </div>

                            <input type="hidden" name="color_hex" id="final_color_hex_input" value="#d97706">
                            <input type="hidden" name="color_name" id="color_name_input" value="Amber Gold (Jati Alami)">
                            <input type="hidden" name="tone_percent" id="tone_percent_input" value="100">

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="swatch-group-title m-0"><i class="fa-solid fa-palette text-warning me-1"></i> Custom Color Picker</span>
                                <div class="custom-color-picker-wrapper" title="Klik untuk memilih warna kustom bebas">
                                    <input type="color" class="custom-color-input" id="customColorPicker" value="#d97706" onchange="selectCustomColor(this.value)">
                                </div>
                            </div>

                            <div class="swatch-group-title"><i class="fa-solid fa-tree text-warning me-1"></i> Finishing Klasik Kayu Jati</div>
                            <div class="color-swatch-container">
                                <button type="button" class="color-swatch-btn" style="background-color: #fde68a;" onclick="selectBaseColor('#fde68a', 'Natural Pine Jati', this)" title="Natural Pine Jati"></button>
                                <button type="button" class="color-swatch-btn active" style="background-color: #d97706;" onclick="selectBaseColor('#d97706', 'Amber Gold (Jati Alami)', this)" title="Amber Gold"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #c85a32;" onclick="selectBaseColor('#c85a32', 'Terracotta Jati', this)" title="Terracotta Jati"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #8d6e63;" onclick="selectBaseColor('#8d6e63', 'Salak Brown Classic', this)" title="Salak Brown Classic"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #4a2c2a;" onclick="selectBaseColor('#4a2c2a', 'Dark Walnut Teak', this)" title="Dark Walnut Teak"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #2c221e;" onclick="selectBaseColor('#2c221e', 'Espresso Dark Jati', this)" title="Espresso Dark Jati"></button>
                            </div>

                            <div class="swatch-group-title"><i class="fa-solid fa-swatchbook text-warning me-1"></i> Finishing Duco Modern</div>
                            <div class="color-swatch-container">
                                <button type="button" class="color-swatch-btn" style="background-color: #ffffff;" onclick="selectBaseColor('#ffffff', 'Duco Pure White', this)" title="Duco Pure White"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #cbd5e1;" onclick="selectBaseColor('#cbd5e1', 'Light Platinum Grey', this)" title="Light Grey"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1e293b;" onclick="selectBaseColor('#1e293b', 'Charcoal Matte Black', this)" title="Charcoal Matte"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #15803d;" onclick="selectBaseColor('#15803d', 'Emerald Forest', this)" title="Emerald Forest"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1e3a8a;" onclick="selectBaseColor('#1e3a8a', 'Royal Navy', this)" title="Royal Navy"></button>
                            </div>
                        </div>
                    </div>

                    <!-- 4. UPLOAD SKETSA & CATATAN PENGRAJIN -->
                    <div class="mb-4">
                        <label class="form-label-custom d-flex align-items-center gap-2">
                            <i class="fa-solid fa-cloud-arrow-up" style="color: var(--accent-gold);"></i>
                            <span>Upload Referensi Gambar/Sketsa (Opsional)</span>
                        </label>
                        <input type="file" name="sketch_image" class="form-control form-control-custom" accept="image/*">
                        <small class="text-muted mt-1.5 d-block" style="font-size: 0.75rem;">Mendukung foto sketsa atau denah ruangan (JPG, PNG, WEBP Maks. 5MB)</small>
                    </div>

                    <div class="craftsman-note-card">
                        <div class="d-flex align-items-center justify-content-between mb-2.5 flex-wrap gap-2">
                            <label class="craftsman-note-title mb-0 d-flex align-items-center gap-2">
                                <i class="fa-solid fa-clipboard-list" style="color: var(--accent-gold); font-size: 1.05rem;"></i>
                                <span>Catatan</span>
                            </label>
                        </div>
                        <textarea name="notes" id="inputCatatan" class="form-control craftsman-textarea" rows="3" placeholder="Tuliskan instruksi kustom Anda di sini (contoh: ukiran kaki model lengkung Jepara, sandaran dilapisi busa empuk, finishing natural semi-gloss, dll.)..."></textarea>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN: ESTIMASI BIAYA, METODE DANA & AKSI PESAN -->
            <div class="col-lg-5">
                <div class="studio-card p-4 p-md-5 position-sticky" style="top: 20px;">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--light-border) !important;">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-receipt me-2" style="color: var(--accent-gold);"></i>Estimasi & Skema Biaya
                        </h5>
                    </div>

                    <div class="d-flex justify-content-between mb-2.5">
                        <span class="text-muted fw-medium small">Harga Dasar Model Produk:</span>
                        <span class="fw-bold text-dark small" id="displayHargaDasarKatalog">
                            Rp {{ isset($selectedProduct) ? number_format($selectedProduct->harga, 0, ',', '.') : '0' }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-2.5">
                        <span class="text-muted fw-medium small">Faktor Ukuran & Kayu Jati:</span>
                        <span class="fw-bold text-dark small" id="displayHargaMebel">Rp 0</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted fw-medium small">Ongkos Kirim Standar:</span>
                        <span class="fw-bold text-dark small">Rp 50.000</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <span class="text-dark fw-bold">Total Tagihan Mebel:</span>
                        <strong class="text-dark fs-5" id="displayTotalPesanan">Rp 0</strong>
                    </div>

                    <!-- BOX SKEMA DP 50% -->
                    <div class="d-flex align-items-center justify-content-between mb-3 p-3 rounded-4" style="background-color: var(--wood-bg); border: 1.5px solid var(--wood-border);">
                        <div>
                            <span class="text-muted d-block small fw-bold text-uppercase">Wajib Pembayaran DP (50%)</span>
                            <span class="fw-bold text-dark small">Untuk Memulai Produksi</span>
                        </div>
                        <span class="price-display" id="displayDP">Rp 0</span>
                    </div>

                    <!-- BOX SISA PELUNASAN 50% -->
                    <div class="p-3 rounded-4 mb-4 border bg-white shadow-2xs">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="text-muted small fw-bold">Sisa Pelunasan (50%):</span>
                            <strong class="text-danger fs-6" id="displaySisaPelunasan">Rp 0</strong>
                        </div>
                        <p class="text-muted small mb-0" style="font-size: 0.72rem; line-height: 1.35;">
                            <i class="fa-solid fa-circle-info text-primary me-1"></i>
                            Sisa pelunasan 50% dibayarkan saat mebel telah <strong>selesai diproduksi di workshop (Tahap 6 Finishing & QC)</strong> dengan bukti foto hasil jadi sebelum dikirim.
                        </p>
                    </div>

                    <!-- HANYA SATU METODE RESMI: DANA -->
                    <div class="mb-4">
                        <label class="form-label-custom d-block mb-1">Metode Pembayaran Resmi</label>
                        <div class="p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="p-1.5 bg-white rounded-2 border shadow-2xs">
                                    <span class="badge text-white px-2 py-1 fw-bold" style="background-color: #118eea;">DANA</span>
                                </div>
                                <div>
                                    <strong class="text-dark d-block small">{{ \App\Models\Setting::get('payment_dana_name', 'Assalam Mebel Official') }}</strong>
                                    <span class="text-muted font-monospace small">{{ \App\Models\Setting::get('payment_dana_number', '0852-3456-7890') }}</span>
                                </div>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2.5 py-1 small">
                                <i class="fa-solid fa-check me-1"></i> Metode Resmi
                            </span>
                        </div>
                        <input type="hidden" name="payment_method" value="dana">
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-orange py-3 fs-6" type="button" onclick="bukaModalReview()">
                            <i class="fa-solid fa-paper-plane me-2"></i> Ajukan Desain & Pesan Sekarang
                        </button>
                        <div class="text-center mt-2">
                            <small class="text-muted"><i class="fa-solid fa-shield-halved me-1 text-success"></i> Transaksi Terverifikasi & Bergaransi Kayu Jati Asli</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL KONFIRMASI REVIEW SEBELUM SUBMIT -->
        <div class="modal fade" id="modalReviewDesain" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg" style="background-color: var(--light-card);">
                    <div class="mb-3">
                        <span class="badge px-3 py-1.5 rounded-pill fw-bold" style="background-color: rgba(217, 119, 6, 0.15); color: var(--accent-gold);">
                            KONFIRMASI PENGAJUAN PESANAN
                        </span>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Rincian Desain Mebel Custom</h4>
                    <p class="text-muted small mb-4">Pastikan data spesifikasi kayu jati & dimensi berikut sudah sesuai sebelum dikirim ke pengrajin.</p>

                    <div class="text-start mb-4 mx-auto p-4 rounded-4 w-100" style="max-width: 550px; background-color: var(--wood-bg); border: 1.5px solid var(--wood-border);">
                        <p class="mb-1 small text-muted">Model Produk : <span class="text-dark fw-bold" id="rev_model">-</span></p>
                        <p class="mb-1 small text-muted">Kategori : <span class="text-dark fw-bold" id="rev_kategori">-</span></p>
                        <p class="mb-1 small text-muted">Dimensi : <span class="text-dark fw-bold" id="rev_dimensi">-</span></p>
                        <p class="mb-1 small text-muted">Material Kayu : <span class="text-success fw-bold" id="rev_material">Kayu Jati Solid Grade A (Perhutani)</span></p>
                        <p class="mb-1 small text-muted">Finishing Warna : <span class="text-dark fw-bold" id="rev_warna">-</span></p>
                        <p class="mb-1 small text-muted" id="rev_notes_box" style="display: none;">Catatan Khusus : <span class="text-warning-emphasis fw-bold" id="rev_notes">-</span></p>
                        <p class="mb-1 small text-muted">Metode Bayar : <span class="badge bg-primary text-white">DANA</span></p>
                        <hr class="my-2" style="border-color: var(--wood-border);">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Total Tagihan :</span>
                            <strong class="text-dark" id="rev_total">-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Wajib DP (50%) :</span>
                            <strong class="text-success" id="rev_dp">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Sisa Pelunasan (50%) :</span>
                            <strong class="text-danger" id="rev_sisa">-</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-orange px-5 py-2">
                            <i class="fa-solid fa-check me-1"></i> Konfirmasi & Ajukan Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<!-- MODAL PILIH PRODUK DASAR KATALOG -->
<div class="modal fade" id="modalSelectProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa-solid fa-couch me-2"></i> Pilih Model Produk Dasar dari Katalog
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Klik salah satu produk mebel di bawah ini untuk dijadikan model dasar kustomisasi Anda:</p>
                <div class="row g-3">
                    @forelse($katalogs as $prod)
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 product-select-modal-card h-100 d-flex flex-column justify-content-between {{ (isset($selectedProduct) && $selectedProduct->id == $prod->id) ? 'border-warning bg-warning-subtle' : 'bg-white' }}"
                                 onclick="window.location.href='{{ route('customer.design', ['product_id' => $prod->id]) }}'">
                                <div>
                                    <div class="rounded-3 overflow-hidden mb-2 border text-center bg-light" style="height: 120px;">
                                        @if($prod->foto_url)
                                            <img src="{{ $prod->foto_url }}" alt="{{ $prod->nama }}" class="w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="fa-solid fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">{{ $prod->nama }}</h6>
                                    <span class="badge bg-light text-muted border small mb-2" style="font-size: 0.7rem;">{{ $prod->kategori ?? 'Mebel Jati' }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <strong class="text-dark small">Rp {{ number_format($prod->harga, 0, ',', '.') }}</strong>
                                    @if(isset($selectedProduct) && $selectedProduct->id == $prod->id)
                                        <span class="badge bg-success text-white small">Aktif</span>
                                    @else
                                        <span class="btn btn-xs btn-outline-dark rounded-pill py-0.5 px-2 fw-semibold" style="font-size: 0.72rem;">Pilih</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-4 text-muted">Belum ada katalog mebel tersedia.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentBaseHex = "#d97706";
    let currentColorName = "Amber Gold (Jati Alami)";

    function selectBaseColor(hex, name, element) {
        document.querySelectorAll('.color-swatch-btn').forEach(btn => btn.classList.remove('active'));
        if (element) element.classList.add('active');

        currentBaseHex = hex;
        currentColorName = name;
        document.getElementById('brightness-slider').value = 100;
        document.getElementById('customColorPicker').value = hex;

        updateColorOutput(hex, 100);
    }

    function selectCustomColor(hex) {
        document.querySelectorAll('.color-swatch-btn').forEach(btn => btn.classList.remove('active'));
        currentBaseHex = hex;
        currentColorName = "Custom Selection";
        document.getElementById('brightness-slider').value = 100;

        updateColorOutput(hex, 100);
    }

    function adjustBrightness(value) {
        updateColorOutput(currentBaseHex, value);
    }

    function updateColorOutput(hex, brightnessPercent) {
        const adjustedHex = applyBrightness(hex, brightnessPercent);

        document.getElementById('live-color-preview').style.backgroundColor = adjustedHex;
        document.getElementById('selected-color-name').innerText = currentColorName;
        document.getElementById('selected-color-hex').innerText = "HEX: " + adjustedHex.toUpperCase();
        document.getElementById('brightness-badge').innerText = "Tone: " + brightnessPercent + "%";

        document.getElementById('final_color_hex_input').value = adjustedHex;
        document.getElementById('color_name_input').value = currentColorName;
        document.getElementById('tone_percent_input').value = brightnessPercent;
    }

    function applyBrightness(hex, percent) {
        let num = parseInt(hex.replace("#", ""), 16),
            amt = Math.round(2.55 * (percent - 100)),
            R = (num >> 16) + amt,
            G = (num >> 8 & 0x00FF) + amt,
            B = (num & 0x0000FF) + amt;

        return "#" + (0x1000000 + 
            (R < 255 ? (R < 1 ? 0 : R) : 255) * 0x10000 + 
            (G < 255 ? (G < 1 ? 0 : G) : 255) * 0x100 + 
            (B < 255 ? (B < 1 ? 0 : B) : 255)
        ).toString(16).slice(1);
    }

    const stdLength = {{ $stdP }};
    const stdWidth = {{ $stdL }};
    const stdHeight = {{ $stdT }};

    function stepDimension(inputId, delta) {
        const input = document.getElementById(inputId);
        let val = parseFloat(input.value) || 0;
        val += delta;
        let min = parseFloat(input.min) || 1;
        let max = parseFloat(input.max) || 1000;
        if (val < min) val = min;
        if (val > max) val = max;
        input.value = val;
        onDimInput();
    }

    function resetToStandardDim() {
        document.getElementById('inputPanjang').value = stdLength;
        document.getElementById('inputLebar').value = stdWidth;
        document.getElementById('inputTinggi').value = stdHeight;
        onDimInput();
    }

    function onDimInput() {
        updateDimDiffBadges();
        hitungHargaReal();
    }

    function updateDimDiffBadges() {
        let p = parseFloat(document.getElementById('inputPanjang').value) || stdLength;
        let l = parseFloat(document.getElementById('inputLebar').value) || stdWidth;
        let t = parseFloat(document.getElementById('inputTinggi').value) || stdHeight;

        updateSingleBadge('diffP', p - stdLength);
        updateSingleBadge('diffL', l - stdWidth);
        updateSingleBadge('diffT', t - stdHeight);
    }

    function updateSingleBadge(badgeId, diff) {
        const badge = document.getElementById(badgeId);
        if (!badge) return;
        if (diff === 0) {
            badge.className = 'dim-diff-tag';
            badge.innerText = 'Standar';
        } else if (diff > 0) {
            badge.className = 'dim-diff-tag bg-success-subtle text-success border border-success';
            badge.innerText = '+' + diff + ' cm';
        } else {
            badge.className = 'dim-diff-tag bg-warning-subtle text-warning-emphasis border border-warning';
            badge.innerText = diff + ' cm';
        }
    }

    function hitungHargaReal() {
        let p = parseFloat(document.getElementById('inputPanjang').value) || stdLength;
        let l = parseFloat(document.getElementById('inputLebar').value) || stdWidth;
        let t = parseFloat(document.getElementById('inputTinggi').value) || stdHeight;

        // Ambil harga dasar katalog
        let rawBase = parseFloat(document.getElementById('rawBasePrice')?.value) || 3500000;
        let standardVolume = (stdLength * stdWidth * stdHeight) / 1000000;
        if (standardVolume <= 0) standardVolume = 1;
        let currentVolume = (p * l * t) / 1000000;
        let ratio = Math.max(0.65, Math.min(3.0, currentVolume / standardVolume));

        let hargaMebel = Math.round((rawBase * ratio) / 10000) * 10000;
        let ongkir = 50000;
        let total = hargaMebel + ongkir;
        let dp = Math.round(total * 0.5);
        let sisa = total - dp;

        document.getElementById('displayHargaMebel').innerText = 'Rp ' + hargaMebel.toLocaleString('id-ID');
        document.getElementById('displayTotalPesanan').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('displayDP').innerText = 'Rp ' + dp.toLocaleString('id-ID');
        document.getElementById('displaySisaPelunasan').innerText = 'Rp ' + sisa.toLocaleString('id-ID');
    }

    function bukaModalReview() {
        let alertBox = document.getElementById('validationAlert');
        alertBox.classList.add('d-none');
        alertBox.innerHTML = '';

        let errors = [];
        let p = parseFloat(document.getElementById('inputPanjang').value);
        let l = parseFloat(document.getElementById('inputLebar').value);
        let t = parseFloat(document.getElementById('inputTinggi').value);
        let warna = document.getElementById('color_name_input').value.trim();
        let hex = document.getElementById('final_color_hex_input').value.trim();
        let notes = document.getElementById('inputCatatan').value.trim();

        if (isNaN(p) || p < 20 || p > 600) {
            errors.push('Panjang mebel harus antara 20 cm - 600 cm.');
        }
        if (isNaN(l) || l < 4 || l > 500) {
            errors.push('Lebar mebel harus antara 4 cm - 500 cm.');
        }
        if (isNaN(t) || t < 2 || t > 500) {
            errors.push('Tinggi mebel harus antara 2 cm - 500 cm.');
        }
        if (!warna || !hex) {
            errors.push('Warna finishing mebel wajib dipilih.');
        }

        if (errors.length > 0) {
            alertBox.innerHTML = '<strong class="d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Mohon periksa kembali input ukuran & warna:</strong><ul class="mb-0 ps-3">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            alertBox.classList.remove('d-none');
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        let modelTitle = "{{ isset($selectedProduct) ? addslashes($selectedProduct->nama) : 'Custom Furniture' }}";
        let kategori = "{{ isset($selectedProduct) ? addslashes($selectedProduct->kategori ?? 'Mebel Jati') : 'Furniture' }}";

        document.getElementById('rev_model').innerText = modelTitle;
        document.getElementById('rev_kategori').innerText = kategori;
        document.getElementById('rev_dimensi').innerText = `${p} × ${l} × ${t} cm`;
        document.getElementById('rev_warna').innerText = warna + " (" + hex + ")";
        document.getElementById('rev_total').innerText = document.getElementById('displayTotalPesanan').innerText;
        document.getElementById('rev_dp').innerText = document.getElementById('displayDP').innerText;
        document.getElementById('rev_sisa').innerText = document.getElementById('displaySisaPelunasan').innerText;

        let notesBox = document.getElementById('rev_notes_box');
        if (notesBox) {
            if (notes) {
                document.getElementById('rev_notes').innerText = `"${notes}"`;
                notesBox.style.display = 'block';
            } else {
                notesBox.style.display = 'none';
            }
        }

        let modal = new bootstrap.Modal(document.getElementById('modalReviewDesain'));
        modal.show();
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateDimDiffBadges();
        hitungHargaReal();
    });
</script>
@endsection