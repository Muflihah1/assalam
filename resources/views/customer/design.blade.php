@extends('layouts.customer')

@section('content')
<style>
    /* ==========================================================================
       STUDIO CUSTOM MEBEL KAYU JATI - CLEAN, MODERN & RESPONSIVE
       ========================================================================== */

    .custom-workbench-card {
        background: #ffffff;
        border: 2px solid var(--border-color);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 18px rgba(59, 35, 20, 0.04);
        transition: all 0.25s ease;
    }

    .custom-workbench-card:hover {
        box-shadow: 0 8px 26px rgba(59, 35, 20, 0.08);
    }

    .custom-section-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-dark);
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }

    /* Dimension Stepper Component */
    .dim-card {
        background: #FAF8F5;
        border: 2px solid var(--border-color);
        border-radius: 16px;
        padding: 16px;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .dim-card:focus-within, .dim-card:hover {
        background: #ffffff;
        border-color: var(--primary-color);
        box-shadow: 0 6px 18px rgba(59, 35, 20, 0.08);
        transform: translateY(-2px);
    }

    .dim-stepper {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 10px;
        margin-bottom: 6px;
    }

    .dim-btn {
        width: 38px;
        height: 40px;
        border-radius: 10px;
        border: 2px solid #dfd2c4;
        background: #ffffff;
        color: var(--primary-color);
        font-size: 0.95rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .dim-btn:hover {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    .dim-input-wrap {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        background: #ffffff;
        border: 2px solid #dfd2c4;
        border-radius: 10px;
        padding: 0 8px;
    }

    .dim-card:focus-within .dim-input-wrap {
        border-color: var(--accent-gold);
    }

    .dim-input {
        width: 100%;
        max-width: 65px;
        border: none !important;
        outline: none !important;
        background: transparent !important;
        text-align: center;
        font-weight: 800;
        font-size: 1.25rem;
        color: var(--text-dark);
        padding: 0;
        -moz-appearance: textfield;
    }

    .dim-input::-webkit-outer-spin-button,
    .dim-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* 2D Schematic Blueprint Card */
    .schematic-card {
        background: linear-gradient(135deg, #24140B 0%, #3B2314 100%);
        border-radius: 18px;
        padding: 20px;
        color: #ffffff;
    }

    /* Color Swatch Box */
    .color-swatch-box {
        background: #FAF8F5;
        border: 2px solid var(--border-color);
        border-radius: 18px;
        padding: 20px;
    }

    .color-swatch-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2.5px solid #ffffff;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.14);
    }

    .color-swatch-btn:hover {
        transform: scale(1.18);
    }

    .color-swatch-btn.active {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.4), 0 4px 10px rgba(0,0,0,0.2);
        transform: scale(1.15);
    }

    .custom-color-picker-wrapper {
        position: relative;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
        background: conic-gradient(from 0deg, #ff0000, #ff8000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);
        border: 2.5px solid #ffffff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.14);
    }

    .custom-color-input {
        position: absolute;
        top: -10px;
        left: -10px;
        width: 60px;
        height: 60px;
        opacity: 0;
        cursor: pointer;
    }

    /* File Upload Drop Area */
    .file-upload-dropzone {
        background: #FAF8F5;
        border: 2px dashed #cfbeae;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .file-upload-dropzone:hover {
        background: #ffffff;
        border-color: var(--primary-color);
    }

    /* Sticky Pricing Sidebar */
    .pricing-summary-card {
        background: #ffffff;
        border: 2px solid var(--border-color);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 28px rgba(59, 35, 20, 0.06);
    }

    @media (min-width: 992px) {
        .pricing-summary-card {
            position: sticky;
            top: 24px;
        }
    }
</style>

<div class="container-xl">

    <!-- 1. HEADER HALAMAN CUSTOM MEBEL -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-3 border-bottom gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <span class="badge px-3 py-1.5 rounded-pill fw-bold" style="background-color: var(--primary-subtle); color: var(--primary-color); font-size: 0.75rem;">
                    <i class="fa-solid fa-tree me-1 text-warning"></i> 100% KAYU JATI SOLID PERHUTANI
                </span>
                <span class="badge px-3 py-1.5 rounded-pill fw-bold bg-warning text-dark" style="font-size: 0.75rem;">
                    BEBAS KUSTOMISASI UKURAN & FINISHING
                </span>
            </div>
            <h2 class="fw-bold text-dark mb-1 fs-3">Pemesanan Custom Mebel Kayu Jati</h2>
            <p class="text-muted small mb-0">
                Pilih model mebel favorit Anda, sesuaikan ukuran presisi (Panjang, Lebar, Tinggi), tentukan warna finishing kayu jati, dan sertakan gambar referensi untuk diproduksi langsung oleh pengrajin ukir Karduluk Sumenep.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="button" class="btn btn-outline-dark rounded-pill px-3 py-2 fw-semibold small shadow-2xs" data-bs-toggle="modal" data-bs-target="#modalSelectProduct">
                <i class="fa-solid fa-arrows-rotate me-1 text-warning"></i> Ganti Model Produk
            </button>
            <a href="{{ route('customer.workshop') }}" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold small">
                <i class="fa-solid fa-store me-1 text-warning"></i> Profil Workshop Karduluk
            </a>
        </div>
    </div>

    <!-- 2. FORM UTAMA CUSTOM MEBEL (POST TO CUSTOMER.DESIGN.ORDER) -->
    <form id="formCustomMebel" action="{{ route('customer.design.order') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Error / Validation Alert Container -->
        <div id="validationAlert" class="alert alert-danger d-none rounded-4 mb-4 shadow-sm"></div>

        @php
            $defaultDim = (isset($selectedProduct) && $selectedProduct) ? $selectedProduct->default_dimensions : ['length' => 180, 'width' => 80, 'height' => 75];
            $stdP = $defaultDim['length'] ?? 180;
            $stdL = $defaultDim['width'] ?? 80;
            $stdT = $defaultDim['height'] ?? 75;
            $productCategory = (isset($selectedProduct) && $selectedProduct) ? ($selectedProduct->kategori ?? 'Furniture Kayu Jati') : 'Furniture Kayu Jati';
        @endphp

        <!-- Hidden Form Fields Required by OrderController::store -->
        <input type="hidden" name="product_id" id="inputProductId" value="{{ isset($selectedProduct) ? $selectedProduct->id : ($katalogs->first()->id ?? 1) }}">
        <input type="hidden" name="category" id="inputCategory" value="{{ $productCategory }}">
        <input type="hidden" name="wood_material" id="inputWoodMaterial" value="Kayu Jati Solid Grade A (Perhutani)">
        <input type="hidden" name="color_name" id="color_name_input" value="Amber Gold (Jati Alami)">
        <input type="hidden" name="color_hex" id="final_color_hex_input" value="#d97706">
        <input type="hidden" name="tone_percent" id="tone_percent_input" value="100">
        <input type="hidden" id="rawBasePrice" value="{{ isset($selectedProduct) ? $selectedProduct->harga : 3500000 }}">

        <div class="row g-4 mb-5">
            
            <!-- SISI KIRI: PARAMETER CUSTOM (MODEL, UKURAN, WARNA, SKETSA, CATATAN) -->
            <div class="col-lg-7">
                <div class="d-flex flex-column gap-4">

                    <!-- KARTU 1: MODEL DASAR KATALOG TERPILIH -->
                    @if(isset($selectedProduct) && $selectedProduct)
                        <div class="custom-workbench-card p-3 p-md-4" style="border-left: 5px solid var(--accent-gold);">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $selectedProduct->foto_url ?? asset('images/no-image.jpg') }}" alt="{{ $selectedProduct->nama }}" style="width: 76px; height: 76px; border-radius: 14px; object-fit: cover;" class="border shadow-2xs">
                                    <div>
                                        <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill mb-1" style="font-size: 0.68rem;">
                                            MODEL PRODUK DASAR KATALOG
                                        </span>
                                        <h5 class="fw-bold text-dark mb-0 fs-6">{{ $selectedProduct->nama }}</h5>
                                        <div class="small text-muted mt-0.5" style="font-size: 0.8rem;">
                                            Kategori: <strong class="text-dark">{{ $selectedProduct->kategori ?? 'Furniture Kayu Jati' }}</strong> • 
                                            Harga Dasar: <strong class="text-dark">Rp {{ number_format($selectedProduct->harga, 0, ',', '.') }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 py-1.5 fw-bold" data-bs-toggle="modal" data-bs-target="#modalSelectProduct" style="font-size: 0.78rem;">
                                    <i class="fa-solid fa-shuffle me-1"></i> Ganti Model Lain
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- KARTU 2: PENYESUAIAN UKURAN / DIMENSI PRESISI (cm) -->
                    <div class="custom-workbench-card">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div>
                                <h5 class="custom-section-title">
                                    <i class="fa-solid fa-ruler-combined text-warning"></i> 1. Dimensi & Ukuran Presisi (cm)
                                </h5>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    Ukuran standar model: <strong id="labelStandardDim" class="text-dark">{{ $stdP }} × {{ $stdL }} × {{ $stdT }} cm</strong>
                                </small>
                            </div>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill px-3 py-1 small fw-semibold" onclick="resetToStandardDim()" title="Kembalikan nilai ke ukuran standar produk">
                                <i class="fa-solid fa-rotate-left me-1 text-warning"></i> Reset Standar
                            </button>
                        </div>

                        <!-- 3 Kartu Stepper Ukuran -->
                        <div class="row g-3 mb-3">
                            <!-- PANJANG -->
                            <div class="col-md-4">
                                <div class="dim-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="badge bg-dark text-white rounded-2 px-1.5 py-0.5 fw-bold" style="font-size: 0.72rem;">P</span>
                                            <strong class="small text-dark">Panjang</strong>
                                        </div>
                                        <span id="diffP" class="badge bg-light text-muted border" style="font-size: 0.68rem;">Standar</span>
                                    </div>
                                    <div class="dim-stepper">
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputPanjang', -5)" title="Kurangi 5 cm">-</button>
                                        <div class="dim-input-wrap">
                                            <input type="number" name="length_cm" id="inputPanjang" class="dim-input" value="{{ $stdP }}" min="20" max="600" oninput="onDimInput()" required>
                                            <small class="text-muted fw-bold" style="font-size: 0.75rem;">cm</small>
                                        </div>
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputPanjang', 5)" title="Tambah 5 cm">+</button>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.68rem;">
                                        <span>Min: 20 cm</span>
                                        <span>Maks: 600 cm</span>
                                    </div>
                                </div>
                            </div>

                            <!-- LEBAR -->
                            <div class="col-md-4">
                                <div class="dim-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="badge bg-dark text-white rounded-2 px-1.5 py-0.5 fw-bold" style="font-size: 0.72rem;">L</span>
                                            <strong class="small text-dark">Lebar</strong>
                                        </div>
                                        <span id="diffL" class="badge bg-light text-muted border" style="font-size: 0.68rem;">Standar</span>
                                    </div>
                                    <div class="dim-stepper">
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputLebar', -5)" title="Kurangi 5 cm">-</button>
                                        <div class="dim-input-wrap">
                                            <input type="number" name="width_cm" id="inputLebar" class="dim-input" value="{{ $stdL }}" min="4" max="500" oninput="onDimInput()" required>
                                            <small class="text-muted fw-bold" style="font-size: 0.75rem;">cm</small>
                                        </div>
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputLebar', 5)" title="Tambah 5 cm">+</button>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.68rem;">
                                        <span>Min: 4 cm</span>
                                        <span>Maks: 500 cm</span>
                                    </div>
                                </div>
                            </div>

                            <!-- TINGGI -->
                            <div class="col-md-4">
                                <div class="dim-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <span class="badge bg-dark text-white rounded-2 px-1.5 py-0.5 fw-bold" style="font-size: 0.72rem;">T</span>
                                            <strong class="small text-dark">Tinggi / Tebal</strong>
                                        </div>
                                        <span id="diffT" class="badge bg-light text-muted border" style="font-size: 0.68rem;">Standar</span>
                                    </div>
                                    <div class="dim-stepper">
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputTinggi', -5)" title="Kurangi 5 cm">-</button>
                                        <div class="dim-input-wrap">
                                            <input type="number" name="height_cm" id="inputTinggi" class="dim-input" value="{{ $stdT }}" min="2" max="500" oninput="onDimInput()" required>
                                            <small class="text-muted fw-bold" style="font-size: 0.75rem;">cm</small>
                                        </div>
                                        <button type="button" class="dim-btn" onclick="stepDimension('inputTinggi', 5)" title="Tambah 5 cm">+</button>
                                    </div>
                                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.68rem;">
                                        <span>Min: 2 cm</span>
                                        <span>Maks: 500 cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Diagram Skematik Ukuran Ruangan -->
                        <div class="schematic-card">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-cube text-warning fs-5"></i>
                                    <div>
                                        <strong class="d-block small text-white">Ringkasan Kubikasi Ruang Kayu Jati</strong>
                                        <small class="text-white-50" style="font-size: 0.7rem;">Dihitung otomatis untuk menentukan kebutuhan bahan balok kayu jati solid</small>
                                    </div>
                                </div>
                                <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill" id="badgeTotalVolume" style="font-size: 0.75rem;">
                                    1.08 m³
                                </span>
                            </div>
                            <div class="row g-2 text-center pt-2 border-top border-secondary border-opacity-50">
                                <div class="col-4">
                                    <small class="text-white-50 d-block" style="font-size: 0.7rem;">Panjang (X)</small>
                                    <strong class="text-warning fs-6" id="schemP">{{ $stdP }} cm</strong>
                                </div>
                                <div class="col-4 border-start border-end border-secondary border-opacity-50">
                                    <small class="text-white-50 d-block" style="font-size: 0.7rem;">Lebar (Z)</small>
                                    <strong class="text-warning fs-6" id="schemL">{{ $stdL }} cm</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-white-50 d-block" style="font-size: 0.7rem;">Tinggi (Y)</small>
                                    <strong class="text-warning fs-6" id="schemT">{{ $stdT }} cm</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU 3: PILIHAN WARNA & FINISHING KAYU JATI ASLI -->
                    <div class="custom-workbench-card">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                            <div>
                                <h5 class="custom-section-title">
                                    <i class="fa-solid fa-palette text-warning"></i> 2. Pilihan Warna Finishing Kayu Jati
                                </h5>
                                <small class="text-muted" style="font-size: 0.75rem;">Menggunakan cat melamine & wood stain khusus kayu jati solid</small>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 small">
                                <i class="fa-solid fa-certificate me-1"></i> Jati Grade A
                            </span>
                        </div>

                        <!-- Jaminan Material Kayu Jati -->
                        <div class="p-3 rounded-4 mb-3 d-flex align-items-center gap-3" style="background: linear-gradient(135deg, #fefce8 0%, #fef3c7 100%); border: 1.5px solid #f59e0b;">
                            <div class="rounded-circle p-2 bg-warning text-dark flex-shrink-0">
                                <i class="fa-solid fa-award fa-lg"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong class="d-block text-dark small">100% Kayu Jati Solid Grade A (Perhutani)</strong>
                                <small class="text-muted" style="font-size: 0.75rem; line-height: 1.4;">
                                    Seluruh pesanan custom diproduksi secara eksklusif menggunakan kayu jati solid legal Perhutani, kaya minyak alami (anti rayap & anti bubuk), dan diproses oven kering standar ekspor.
                                </small>
                            </div>
                        </div>

                        <div class="color-swatch-box">
                            <!-- Live Preview Dot & Color Name -->
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-3">
                                    <div id="liveColorDot" style="width: 44px; height: 44px; border-radius: 50%; background-color: #d97706; border: 3px solid #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.18);"></div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 fs-6" id="displayColorName">Amber Gold (Jati Alami)</h6>
                                        <small class="text-muted font-monospace fw-semibold" id="displayColorHex">HEX: #D97706</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="small text-muted fw-bold d-none d-sm-inline">Warna Kustom:</span>
                                    <div class="custom-color-picker-wrapper" title="Klik untuk memilih warna bebas">
                                        <input type="color" class="custom-color-input" id="customColorPicker" value="#d97706" onchange="selectCustomColor(this.value)">
                                    </div>
                                </div>
                            </div>

                            <!-- Slider Kilau Finishing (Doff - Glossy) -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted fw-bold" style="font-size: 0.75rem;">Tingkat Kilau Finishing (Doff - Glossy):</small>
                                    <span class="badge bg-white text-dark border small shadow-2xs" id="badgeGlossiness">Semi-Gloss (100%)</span>
                                </div>
                                <input type="range" class="form-range" id="brightnessSlider" min="40" max="160" value="100" oninput="adjustBrightness(this.value)" style="accent-color: var(--primary-color);">
                            </div>

                            <!-- Preset Palet: Finishing Klasik Jati -->
                            <small class="text-muted fw-bold d-block mb-1.5" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                <i class="fa-solid fa-tree text-warning me-1"></i> Finishing Tradisional Natural Wood
                            </small>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <button type="button" class="color-swatch-btn" style="background-color: #f5deb3;" onclick="selectBaseColor('#f5deb3', 'Natural Jati Muda (Bleached)', this)" title="Natural Jati Muda"></button>
                                <button type="button" class="color-swatch-btn active" style="background-color: #d97706;" onclick="selectBaseColor('#d97706', 'Amber Gold (Jati Alami)', this)" title="Amber Gold (Jati Alami)"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #b45309;" onclick="selectBaseColor('#b45309', 'Salak Brown Classic', this)" title="Salak Brown Classic"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #78350f;" onclick="selectBaseColor('#78350f', 'Dark Walnut Teak', this)" title="Dark Walnut Teak"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #3b2314;" onclick="selectBaseColor('#3b2314', 'Deep Teak Charcoal', this)" title="Deep Teak Charcoal"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1f140e;" onclick="selectBaseColor('#1f140e', 'Espresso Dark Black', this)" title="Espresso Dark Black"></button>
                            </div>

                            <!-- Preset Palet: Finishing Duco Modern -->
                            <small class="text-muted fw-bold d-block mb-1.5" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                <i class="fa-solid fa-swatchbook text-warning me-1"></i> Finishing Duco Mewah
                            </small>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="color-swatch-btn" style="background-color: #ffffff;" onclick="selectBaseColor('#ffffff', 'Duco Pure White', this)" title="Duco Pure White"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #cbd5e1;" onclick="selectBaseColor('#cbd5e1', 'Light Platinum Grey', this)" title="Light Platinum Grey"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1e293b;" onclick="selectBaseColor('#1e293b', 'Matte Navy Charcoal', this)" title="Matte Navy Charcoal"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #166534;" onclick="selectBaseColor('#166534', 'Emerald Royal Green', this)" title="Emerald Royal Green"></button>
                            </div>
                        </div>
                    </div>

                    <!-- KARTU 4: UPLOAD BERKAS SKETSA / FOTO REFERENSI RUANGAN -->
                    <div class="custom-workbench-card">
                        <h5 class="custom-section-title mb-2">
                            <i class="fa-solid fa-cloud-arrow-up text-warning"></i> 3. Upload Sketsa / Foto Referensi Ruangan (Opsional)
                        </h5>
                        <p class="text-muted small mb-3" style="font-size: 0.78rem;">
                            Anda dapat melampirkan gambar sketsa coretan tangan, denah arsitek, atau foto referensi mebel dari internet untuk memudahkan pengrajin kami menyesuaikan detail konstruksi.
                        </p>

                        <div class="file-upload-dropzone" onclick="document.getElementById('inputSketchFile').click()">
                            <i class="fa-solid fa-image fa-2x text-warning mb-2"></i>
                            <strong class="d-block text-dark small mb-1" id="fileUploadLabel">Klik untuk memilih file foto / sketsa</strong>
                            <small class="text-muted d-block" style="font-size: 0.72rem;">Mendukung format JPG, PNG, WEBP (Maksimal 5MB)</small>
                            <div id="imagePreviewBox" class="mt-2 d-none">
                                <img id="imagePreviewImg" src="#" alt="Pratinjau Sketsa" style="max-height: 140px; border-radius: 10px;" class="border shadow-sm">
                            </div>
                        </div>
                        <input type="file" name="sketch_image" id="inputSketchFile" class="d-none" accept="image/*" onchange="handleFileSelected(this)">
                    </div>

                    <!-- KARTU 5: CATATAN KHUSUS UNTUK PENGRAJIN KARDULUK -->
                    <div class="custom-workbench-card">
                        <h5 class="custom-section-title mb-2">
                            <i class="fa-solid fa-clipboard-list text-warning"></i> 4. Catatan & Instruksi Khusus Pengrajin
                        </h5>
                        <textarea name="notes" id="inputCatatan" class="form-control rounded-3" rows="3" placeholder="Tuliskan permintaan khusus Anda di sini (contoh: ukiran kaki model melengkung khas ukir Karduluk, bevel daun meja dibuat rounded 2cm, busa dudukan empuk royal foam, dll.)..."></textarea>
                    </div>

                </div>
            </div>

            <!-- SISI KANAN: RINGKASAN PESANAN, ESTIMASI BIAYA & SKEMA DP 50% (STICKY) -->
            <div class="col-lg-5">
                <div class="pricing-summary-card">
                    
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <h5 class="fw-bold text-dark mb-0 fs-6">
                            <i class="fa-solid fa-receipt text-warning me-2"></i>Ringkasan & Biaya Pesanan
                        </h5>
                        <span class="badge bg-light text-muted border px-2.5 py-1 small">Estimasi Otomatis</span>
                    </div>

                    <!-- Parameter Terpilih Preview -->
                    <div class="p-3 bg-light rounded-4 border mb-3 small">
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-muted">Model Mebel:</span>
                            <strong class="text-dark text-truncate" style="max-width: 170px;">{{ $selectedProduct->nama ?? 'Custom Mebel' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-muted">Dimensi:</span>
                            <strong class="text-dark" id="summaryDimDisplay">{{ $stdP }} × {{ $stdL }} × {{ $stdT }} cm</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1.5">
                            <span class="text-muted">Finishing:</span>
                            <strong class="text-dark" id="summaryColorDisplay">Amber Gold (Jati Alami)</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Bahan Kayu:</span>
                            <strong class="text-success">100% Jati Solid Grade A</strong>
                        </div>
                    </div>

                    <!-- Breakdown Biaya Transparan -->
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Harga Dasar Katalog:</span>
                        <strong class="text-dark" id="dispBasePrice">Rp 0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Penyesuaian Kubikasi Jati:</span>
                        <strong class="text-dark" id="dispHargaMebel">Rp 0</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 pb-2 border-bottom small">
                        <span class="text-muted">Ongkos Kirim Standar:</span>
                        <strong class="text-dark">Rp 50.000</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark">Total Tagihan Mebel:</span>
                        <span class="fw-bold text-dark fs-5" id="dispTotalTagihan">Rp 0</span>
                    </div>

                    <!-- Skema DP 50% Box Wajib -->
                    <div class="p-3 rounded-4 mb-3" style="background-color: var(--primary-subtle); border: 1.5px solid var(--accent-gold);">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <span class="badge bg-warning text-dark fw-bold px-2 py-0.5 rounded-pill mb-0.5" style="font-size: 0.68rem;">WAJIB PEMBAYARAN</span>
                                <strong class="d-block text-dark small">Uang Muka Produksi (DP 50%)</strong>
                            </div>
                            <span class="fw-bold text-success fs-5" id="dispDP">Rp 0</span>
                        </div>
                        <small class="text-muted d-block" style="font-size: 0.72rem; line-height: 1.4;">
                            Uang muka 50% diperlukan agar kayu jati dapat dipotong dan masuk antrean pengerjaan pengrajin di workshop Karduluk Sumenep.
                        </small>
                    </div>

                    <!-- Sisa Pelunasan 50% Info Box -->
                    <div class="p-2.5 bg-white rounded-3 border mb-3 small d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted d-block" style="font-size: 0.72rem;">Sisa Pelunasan (50%):</span>
                            <strong class="text-danger fs-6" id="dispSisaPelunasan">Rp 0</strong>
                        </div>
                        <span class="badge bg-light text-muted border" style="font-size: 0.68rem;">Saat Siap Kirim</span>
                    </div>

                    <!-- Rekening Resmi DANA -->
                    <div class="p-2.5 rounded-3 border bg-white d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-white px-2 py-1 fw-bold" style="background-color: #118eea;">DANA</span>
                            <div>
                                <strong class="d-block small text-dark">{{ \App\Models\Setting::get('payment_dana_name', 'Assalam Mebel Official') }}</strong>
                                <span class="text-muted font-monospace small" style="font-size: 0.75rem;">{{ \App\Models\Setting::get('payment_dana_number', '0852-3456-7890') }}</span>
                            </div>
                        </div>
                        <span class="badge bg-success-subtle text-success border border-success rounded-pill px-2 py-0.5 small">Metode Resmi</span>
                    </div>
                    <input type="hidden" name="payment_method" value="dana">

                    <!-- Tombol Aksi Submit Pesanan -->
                    <button type="button" class="btn btn-store-primary w-100 py-3 rounded-4 fw-bold fs-6 shadow-sm" onclick="bukaModalReview()">
                        <i class="fa-solid fa-paper-plane me-2"></i> Ajukan Pesanan Custom Mebel
                    </button>
                    <div class="text-center mt-2.5">
                        <small class="text-muted" style="font-size: 0.74rem;">
                            <i class="fa-solid fa-shield-halved text-success me-1"></i> Transaksi Terverifikasi & Garansi Kayu Solid Sentra Karduluk Madura
                        </small>
                    </div>

                </div>
            </div>

        </div>

        <!-- MODAL KONFIRMASI REVIEW SPESIFIKASI SEBELUM SUBMIT -->
        <div class="modal fade" id="modalReviewDesain" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 p-4 text-center border-0 shadow-lg">
                    <div class="mb-2">
                        <span class="badge px-3 py-1.5 rounded-pill fw-bold bg-warning text-dark">
                            KONFIRMASI PENGAJUAN PESANAN CUSTOM
                        </span>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Rincian Spesifikasi Desain</h4>
                    <p class="text-muted small mb-4">Pastikan seluruh data ukuran & warna finishing berikut sudah sesuai sebelum diajukan ke pengrajin.</p>

                    <div class="text-start mb-4 mx-auto p-4 rounded-4 w-100" style="max-width: 580px; background-color: var(--bg-warm); border: 2px solid var(--border-color);">
                        <p class="mb-1.5 small text-muted">Model Produk Dasar : <span class="text-dark fw-bold" id="rev_model">-</span></p>
                        <p class="mb-1.5 small text-muted">Kategori Mebel : <span class="text-dark fw-bold" id="rev_kategori">-</span></p>
                        <p class="mb-1.5 small text-muted">Dimensi Ukuran Presisi : <span class="text-dark fw-bold" id="rev_dimensi">-</span></p>
                        <p class="mb-1.5 small text-muted">Material Kayu : <span class="text-success fw-bold">100% Kayu Jati Solid Grade A (Perhutani)</span></p>
                        <p class="mb-1.5 small text-muted">Finishing Warna : <span class="text-dark fw-bold" id="rev_warna">-</span></p>
                        <p class="mb-1.5 small text-muted" id="rev_notes_box" style="display: none;">Catatan Khusus : <span class="text-warning-emphasis fw-bold" id="rev_notes">-</span></p>
                        <p class="mb-1.5 small text-muted">Metode Pembayaran : <span class="badge bg-primary text-white">DANA Official</span></p>
                        <hr class="my-2.5">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Total Tagihan:</span>
                            <strong class="text-dark" id="rev_total">-</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Wajib DP (50%):</span>
                            <strong class="text-success" id="rev_dp">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Sisa Pelunasan (50%):</span>
                            <strong class="text-danger" id="rev_sisa">-</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-store-primary px-5 py-2 rounded-pill fw-bold">
                            <i class="fa-solid fa-check me-1 text-warning"></i> Konfirmasi & Ajukan Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<!-- MODAL PILIH PRODUK DASAR DARI KATALOG -->
<div class="modal fade" id="modalSelectProduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header text-white rounded-top-4" style="background-color: var(--primary-color);">
                <h5 class="modal-title fs-6 fw-bold">
                    <i class="fa-solid fa-couch me-2 text-warning"></i> Pilih Model Produk Dasar dari Katalog
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Pilih salah satu produk mebel asli dari katalog kami untuk dijadikan dasar kustomisasi dimensi & warna:</p>
                <div class="row g-3">
                    @forelse($katalogs as $prod)
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 bg-white border rounded-4 h-100 d-flex flex-column justify-content-between {{ (isset($selectedProduct) && $selectedProduct->id == $prod->id) ? 'border-warning shadow-sm' : '' }}"
                                 style="cursor: pointer; transition: all 0.2s;"
                                 onclick="window.location.href='{{ route('customer.design', ['product_id' => $prod->id]) }}'">
                                <div>
                                    <div class="rounded-3 overflow-hidden mb-2 border text-center bg-light" style="height: 120px;">
                                        @if($prod->foto_url)
                                            <img src="{{ $prod->foto_url }}" alt="{{ $prod->nama }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="fa-solid fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1 small">{{ $prod->nama }}</h6>
                                    <span class="badge bg-light text-muted border small mb-2" style="font-size: 0.7rem;">{{ $prod->kategori ?? 'Mebel Jati' }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <strong class="text-dark small">Rp {{ number_format($prod->harga, 0, ',', '.') }}</strong>
                                    @if(isset($selectedProduct) && $selectedProduct->id == $prod->id)
                                        <span class="badge bg-success text-white small">Aktif</span>
                                    @else
                                        <span class="btn btn-xs btn-outline-dark rounded-pill py-0.5 px-2.5 fw-semibold" style="font-size: 0.72rem;">Pilih</span>
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

<!-- CLIENT JAVASCRIPT LOGIC -->
<script>
    let currentBaseHex = '#d97706';
    let currentColorName = 'Amber Gold (Jati Alami)';
    let currentTonePercent = 100;

    const stdLength = {{ $stdP }};
    const stdWidth = {{ $stdL }};
    const stdHeight = {{ $stdT }};

    /**
     * Pilihan Warna Finishing
     */
    function selectBaseColor(hex, name, element) {
        document.querySelectorAll('.color-swatch-btn').forEach(btn => btn.classList.remove('active'));
        if (element) element.classList.add('active');

        currentBaseHex = hex;
        currentColorName = name;
        document.getElementById('customColorPicker').value = hex;

        updateColorDisplay(hex);
    }

    function selectCustomColor(hex) {
        document.querySelectorAll('.color-swatch-btn').forEach(btn => btn.classList.remove('active'));
        currentBaseHex = hex;
        currentColorName = 'Custom Selection';

        updateColorDisplay(hex);
    }

    function adjustBrightness(value) {
        currentTonePercent = value;
        const badge = document.getElementById('badgeGlossiness');
        if (value < 80) {
            badge.innerText = `Doff / Matte (${value}%)`;
        } else if (value > 120) {
            badge.innerText = `High-Gloss (${value}%)`;
        } else {
            badge.innerText = `Semi-Gloss (${value}%)`;
        }

        updateColorDisplay(currentBaseHex);
    }

    function updateColorDisplay(hex) {
        const adjustedHex = applyToneToHex(hex, currentTonePercent);

        document.getElementById('liveColorDot').style.backgroundColor = adjustedHex;
        document.getElementById('displayColorName').innerText = currentColorName;
        document.getElementById('displayColorHex').innerText = 'HEX: ' + adjustedHex.toUpperCase();
        document.getElementById('summaryColorDisplay').innerText = `${currentColorName} (${adjustedHex.toUpperCase()})`;

        document.getElementById('final_color_hex_input').value = adjustedHex;
        document.getElementById('color_name_input').value = currentColorName;
        document.getElementById('tone_percent_input').value = currentTonePercent;
    }

    function applyToneToHex(hex, percent) {
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

    /**
     * Penyesuaian Dimensi & Perhitungan Biaya
     */
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

        // Update Schematic Blueprint Display
        document.getElementById('schemP').innerText = p + ' cm';
        document.getElementById('schemL').innerText = l + ' cm';
        document.getElementById('schemT').innerText = t + ' cm';
        document.getElementById('summaryDimDisplay').innerText = `${p} × ${l} × ${t} cm`;

        let volume = (p * l * t) / 1000000;
        document.getElementById('badgeTotalVolume').innerText = volume.toFixed(2) + ' m³';
    }

    function updateSingleBadge(badgeId, diff) {
        const badge = document.getElementById(badgeId);
        if (!badge) return;
        if (diff === 0) {
            badge.className = 'badge bg-light text-muted border';
            badge.innerText = 'Standar';
        } else if (diff > 0) {
            badge.className = 'badge bg-success-subtle text-success border border-success';
            badge.innerText = '+' + diff + ' cm';
        } else {
            badge.className = 'badge bg-warning-subtle text-warning-emphasis border border-warning';
            badge.innerText = diff + ' cm';
        }
    }

    function hitungHargaReal() {
        let p = parseFloat(document.getElementById('inputPanjang').value) || stdLength;
        let l = parseFloat(document.getElementById('inputLebar').value) || stdWidth;
        let t = parseFloat(document.getElementById('inputTinggi').value) || stdHeight;

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

        document.getElementById('dispBasePrice').innerText = 'Rp ' + rawBase.toLocaleString('id-ID');
        document.getElementById('dispHargaMebel').innerText = 'Rp ' + hargaMebel.toLocaleString('id-ID');
        document.getElementById('dispTotalTagihan').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('dispDP').innerText = 'Rp ' + dp.toLocaleString('id-ID');
        document.getElementById('dispSisaPelunasan').innerText = 'Rp ' + sisa.toLocaleString('id-ID');
    }

    /**
     * File Upload Handler dengan Pratinjau
     */
    function handleFileSelected(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            document.getElementById('fileUploadLabel').innerHTML = `<i class="fa-solid fa-file-check text-success me-1"></i> File dipilih: <strong>${file.name}</strong> (${(file.size / 1024).toFixed(0)} KB)`;

            const reader = new FileReader();
            reader.onload = function(e) {
                const previewBox = document.getElementById('imagePreviewBox');
                const previewImg = document.getElementById('imagePreviewImg');
                previewImg.src = e.target.result;
                previewBox.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    /**
     * Validasi & Buka Modal Review
     */
    function bukaModalReview() {
        const alertBox = document.getElementById('validationAlert');
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
            alertBox.innerHTML = '<strong class="d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Mohon periksa kembali formulir kustom:</strong><ul class="mb-0 ps-3">' + errors.map(e => `<li>${e}</li>`).join('') + '</ul>';
            alertBox.classList.remove('d-none');
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        let modelTitle = "{{ isset($selectedProduct) ? addslashes($selectedProduct->nama) : 'Custom Mebel' }}";
        let kategori = "{{ isset($selectedProduct) ? addslashes($selectedProduct->kategori ?? 'Furniture Kayu Jati') : 'Furniture Kayu Jati' }}";

        document.getElementById('rev_model').innerText = modelTitle;
        document.getElementById('rev_kategori').innerText = kategori;
        document.getElementById('rev_dimensi').innerText = `${p} × ${l} × ${t} cm`;
        document.getElementById('rev_warna').innerText = `${warna} (${hex})`;
        document.getElementById('rev_total').innerText = document.getElementById('dispTotalTagihan').innerText;
        document.getElementById('rev_dp').innerText = document.getElementById('dispDP').innerText;
        document.getElementById('rev_sisa').innerText = document.getElementById('dispSisaPelunasan').innerText;

        const notesBox = document.getElementById('rev_notes_box');
        if (notesBox) {
            if (notes) {
                document.getElementById('rev_notes').innerText = `"${notes}"`;
                notesBox.style.display = 'block';
            } else {
                notesBox.style.display = 'none';
            }
        }

        const modal = new bootstrap.Modal(document.getElementById('modalReviewDesain'));
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateDimDiffBadges();
        hitungHargaReal();
    });
</script>
@endsection
