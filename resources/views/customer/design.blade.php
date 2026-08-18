@extends('layouts.customer')

@section('content')
<style>
    /* Theme Palette Variables - Premium Mebel Aesthetic */
    :root {
        --primary-color: #4a2c2a;     /* Deep Mahogany */
        --secondary-color: #6b4423;   /* Warm Walnut */
        --accent-orange: #d97706;     /* Amber Gold */
        --accent-terracotta: #c85a32; /* Rich Terracotta */
        
        --light-bg: #faf6f0;          /* Warm Alabaster Background */
        --light-card: #ffffff;        /* Pure White Card */
        --light-border: #e6dfd5;      /* Soft Border */
        --text-dark: #2c221e;         /* Rich Dark Espresso */
        --text-muted: #7c7067;        /* Muted Taupe */
        
        --wood-bg: #f4ece1;           /* Warm Wood Cream Accent */
        --wood-border: #d4c5b5;       /* Distinct Wood Border */
    }

    body {
        background-color: var(--light-bg) !important;
        color: var(--text-dark);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Studio Card dengan Efek Floating & Border Halus */
    .studio-card {
        background-color: var(--light-card);
        border: 1px solid var(--light-border);
        border-radius: 24px;
        box-shadow: 0 12px 35px rgba(74, 44, 42, 0.05);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .studio-card:hover {
        box-shadow: 0 20px 45px rgba(74, 44, 42, 0.08);
    }

    /* Form Controls yang Lebih Hidup */
    .form-control-dark, .form-select-dark {
        background-color: #fdfbf7 !important;
        border: 1.5px solid var(--wood-border) !important;
        color: var(--text-dark) !important;
        border-radius: 14px;
        padding: 14px 18px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .form-control-dark:focus, .form-select-dark:focus {
        border-color: var(--accent-orange) !important;
        box-shadow: 0 0 0 4px rgba(217, 119, 6, 0.12) !important;
        background-color: #ffffff !important;
    }

    .form-label-dark {
        color: var(--text-dark);
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .dimension-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .input-group-dark-text {
        background-color: var(--wood-bg);
        border: 1.5px solid var(--wood-border);
        color: var(--primary-color);
        border-radius: 0 14px 14px 0 !important;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0 18px;
    }

    .input-group-dark .form-control-dark {
        border-radius: 14px 0 0 14px !important;
    }

    /* Color Swatch Box Premium */
    .color-swatch-box {
        background: linear-gradient(135deg, #fdfbf7 0%, var(--wood-bg) 100%);
        border: 1.5px solid var(--wood-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }

    .swatch-group-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
        margin-bottom: 10px;
        margin-top: 16px;
    }

    .color-swatch-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .color-swatch-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #ffffff;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        padding: 0;
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

    .color-swatch-btn:hover { 
        transform: scale(1.25); 
        box-shadow: 0 5px 12px rgba(0,0,0,0.2);
    }
    
    .color-swatch-btn.active {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.3), 0 4px 10px rgba(0,0,0,0.15);
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
        transition: transform 0.2s;
    }
    .custom-color-picker-wrapper:hover {
        transform: scale(1.15);
    }

    .custom-color-input {
        position: absolute;
        top: -10px; left: -10px;
        width: 60px; height: 60px;
        opacity: 0; cursor: pointer;
    }

    .live-preview-circle {
        width: 50px; height: 50px;
        border-radius: 50%;
        border: 3px solid #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: background-color 0.3s ease;
    }

    /* Tombol Utama Premium */
    .btn-orange {
        background: linear-gradient(135deg, var(--accent-orange) 0%, var(--accent-terracotta) 100%);
        color: #ffffff;
        font-weight: 700;
        border: none;
        border-radius: 14px;
        padding: 16px 28px;
        box-shadow: 0 8px 20px rgba(217, 119, 6, 0.25);
        transition: all 0.3s ease;
        letter-spacing: 0.02em;
    }

    .btn-orange:hover {
        background: linear-gradient(135deg, #c26905, #b24d27);
        color: #ffffff;
        box-shadow: 0 12px 25px rgba(217, 119, 6, 0.35);
        transform: translateY(-2px);
    }

    .price-text {
        background: linear-gradient(135deg, var(--accent-orange), var(--accent-terracotta));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        font-size: 1.75rem;
    }

    .modal-dark {
        background-color: var(--light-card);
        color: var(--text-dark);
        border: 1px solid var(--light-border);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(74, 44, 42, 0.15);
    }

    .form-control-dark::placeholder {
        color: var(--text-muted) !important; 
        opacity: 0.7 !important;    
        font-weight: 400;        
    }
</style>

<div class="container-fluid w-100 px-3 px-md-5 py-4">

    <!-- HEADER INTERAKTIF -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-3 border-bottom gap-3" style="border-color: var(--light-border) !important;">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge px-3 py-1 rounded-pill fw-bold" style="background-color: rgba(217, 119, 6, 0.12); color: var(--accent-orange); font-size: 0.75rem; letter-spacing: 0.05em;">INTERACTIVE 3D/CUSTOM WORKBENCH</span>
            </div>
            <h2 class="fw-extrabold mb-1 text-dark" style="letter-spacing: -0.02em;">
                Studio Custom Desain Mebel
            </h2>
            <p class="text-muted mb-0">Rancang ukuran presisi, jenis kayu grade A, dan tone warna finishing eksklusif langsung dari perangkat Anda</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="p-3 rounded-20 d-flex align-items-center gap-3" style="background-color: var(--wood-bg); border: 1px solid var(--wood-border);">
                <div style="width: 12px; height: 12px; border-radius: 50%; background-color: #10b981; box-shadow: 0 0 8px #10b981;"></div>
                <span class="text-dark fw-bold small">Live Calculator Aktif</span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- SISI KIRI: FORM PARAMETER -->
        <div class="col-lg-7">
            <div class="studio-card p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--light-border) !important;">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-sliders me-2" style="color: var(--accent-orange);"></i>Parameter Desain Furniture
                    </h5>
                    <span class="text-muted small">Langkah 1 dari 3</span>
                </div>

                <form id="furnitureForm">
                    @csrf

                    <!-- 1. Kategori & Model -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label-dark">Kategori Furniture Impian</label>
                            <select class="form-select form-select-dark" id="inputKategori">
                                <option value="Sofa & Kursi Tamu Mewah" selected>🛋️ Sofa & Kursi Tamu Mewah</option>
                                <option value="Meja Makan Minimalis Modern">🪑 Meja Makan Minimalis Modern</option>
                                <option value="Lemari & Wardrobe Custom">🚪 Lemari & Wardrobe Custom</option>
                                <option value="Tempat Tidur Estetik">🛏️ Tempat Tidur Estetik</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2. Dimensi Ukuran -->
                    <div class="mb-4 p-4 rounded-20" style="background-color: #fdfbf7; border: 1.5px solid var(--wood-border);">
                        <label class="form-label-dark d-block mb-3">
                            <i class="fa-solid fa-ruler-combined me-2" style="color: var(--accent-orange);"></i>Ukuran Presisi Furniture (cm)
                        </label>
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="dimension-label">Panjang (P)</div>
                                <div class="input-group input-group-dark">
                                    <input type="number" id="inputPanjang" class="form-control form-control-dark" value="180">
                                    <span class="input-group-text input-group-dark-text">cm</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="dimension-label">Lebar (L)</div>
                                <div class="input-group input-group-dark">
                                    <input type="number" id="inputLebar" class="form-control form-control-dark" value="80">
                                    <span class="input-group-text input-group-dark-text">cm</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="dimension-label">Tinggi (T)</div>
                                <div class="input-group input-group-dark">
                                    <input type="number" id="inputTinggi" class="form-control form-control-dark" value="75">
                                    <span class="input-group-text input-group-dark-text">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Material -->
                    <div class="mb-4">
                        <label class="form-label-dark">Pilihan Material Utama Kayu</label>
                        <select class="form-select form-select-dark" id="inputMaterial">
                            <option value="Kayu Jati Perhutani (Grade A)" selected>🪵 Kayu Jati Perhutani (Grade A - Anti Rayap)</option>
                            <option value="Kayu Mahoni Oven Premium">🪵 Kayu Mahoni Oven Premium (Finishing Halus)</option>
                            <option value="Kayu Sungkai Solid">🪵 Kayu Sungkai Solid (Serat Eksotis)</option>
                        </select>
                    </div>

                    <!-- 4. PEMILIHAN WARNA SWATCHES & SLIDER -->
                    <div class="mb-4">
                        <label class="form-label-dark">Pilihan Warna & Tone Finishing</label>

                        <div class="color-swatch-box">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom" style="border-color: var(--wood-border) !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="live-preview-circle" id="live-color-preview" style="background-color: #d97706;"></div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" id="selected-color-name">Amber Gold</h6>
                                        <small class="text-muted fw-semibold" id="selected-color-hex">HEX: #D97706</small>
                                    </div>
                                </div>
                                <span class="badge fw-bold px-3 py-2 rounded-pill shadow-sm" style="background-color: #ffffff; color: var(--text-dark); border: 1.5px solid var(--wood-border);" id="brightness-badge">Tone: 100%</span>
                            </div>

                            <div class="mb-4 px-2">
                                <label class="form-label text-muted small fw-bold mb-1">Sesuaikan Intensitas / Kecerahan Warna (Shade Control)</label>
                                <input type="range" class="form-range custom-range" id="brightness-slider" min="30" max="170" value="100" oninput="adjustBrightness(this.value)" style="accent-color: var(--accent-orange);">
                            </div>

                            <input type="hidden" name="final_color_hex" id="final_color_hex_input" value="#d97706">
                            <input type="hidden" name="color_name" id="color_name_input" value="Amber Gold">

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="swatch-group-title m-0">🎨 Custom Color Picker (Bebas Pilih Warna)</span>
                                <div class="custom-color-picker-wrapper" title="Klik untuk pilih warna kustom">
                                    <input type="color" class="custom-color-input" id="customColorPicker" value="#d97706" onchange="selectCustomColor(this.value)">
                                </div>
                            </div>

                            <div class="swatch-group-title">🪵 Kayu & Tone Natural Klasik</div>
                            <div class="color-swatch-container">
                                <button type="button" class="color-swatch-btn" style="background-color: #fde68a;" onclick="selectBaseColor('#fde68a', 'Light Pine', this)" title="Light Pine"></button>
                                <button type="button" class="color-swatch-btn active" style="background-color: #d97706;" onclick="selectBaseColor('#d97706', 'Amber Gold', this)" title="Amber Gold"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #c85a32;" onclick="selectBaseColor('#c85a32', 'Terracotta Warm', this)" title="Terracotta Warm"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #8d6e63;" onclick="selectBaseColor('#8d6e63', 'Cokelat Sedang', this)" title="Cokelat Sedang"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #4a2c2a;" onclick="selectBaseColor('#4a2c2a', 'Deep Mahogany', this)" title="Deep Mahogany"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #2c221e;" onclick="selectBaseColor('#2c221e', 'Espresso Dark', this)" title="Espresso Dark"></button>
                            </div>

                            <div class="swatch-group-title">🎨 Warna Solid & Modern Estetik</div>
                            <div class="color-swatch-container">
                                <button type="button" class="color-swatch-btn" style="background-color: #ffffff;" onclick="selectBaseColor('#ffffff', 'Pure White', this)" title="Pure White"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #f1f5f9;" onclick="selectBaseColor('#f1f5f9', 'Soft Ivory', this)" title="Soft Ivory"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #cbd5e1;" onclick="selectBaseColor('#cbd5e1', 'Light Grey', this)" title="Light Grey"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #64748b;" onclick="selectBaseColor('#64748b', 'Slate Grey', this)" title="Slate Grey"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1e293b;" onclick="selectBaseColor('#1e293b', 'Charcoal Matte', this)" title="Charcoal Matte"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #991b1b;" onclick="selectBaseColor('#991b1b', 'Maroon Red', this)" title="Maroon Red"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #c2410c;" onclick="selectBaseColor('#c2410c', 'Rust Orange', this)" title="Rust Orange"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #15803d;" onclick="selectBaseColor('#15803d', 'Forest Green', this)" title="Forest Green"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #047857;" onclick="selectBaseColor('#047857', 'Emerald Green', this)" title="Emerald Green"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #0369a1;" onclick="selectBaseColor('#0369a1', 'Ocean Blue', this)" title="Ocean Blue"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1e3a8a;" onclick="selectBaseColor('#1e3a8a', 'Navy Blue', this)" title="Navy Blue"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #581c87;" onclick="selectBaseColor('#581c87', 'Deep Purple', this)" title="Deep Purple"></button>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Upload Sketsa & Catatan -->
                    <div class="mb-4">
                        <label class="form-label-dark">Upload Referensi Gambar/Sketsa (Opsional)</label>
                        <input type="file" class="form-control form-control-dark" id="inputGambar">
                        <small class="text-muted mt-1 d-block">Format didukung: JPG, PNG, WEBP (Maks. 10MB)</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-dark">Catatan Khusus Pertukangan</label>
                        <textarea class="form-control form-control-dark" id="inputCatatan" rows="3" placeholder="Contoh: Model kaki meja bubut ukir klasik, sandaran sofa dibuat empuk dobel busa..."></textarea>
                    </div>

                </form>
            </div>
        </div>

        <!-- SISI KANAN: ESTIMASI BIAYA & AKSI PESAN -->
        <div class="col-lg-5">
            <div class="studio-card p-4 p-md-5 position-sticky" style="top: 20px;">
                <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--light-border) !important;">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fa-solid fa-receipt me-2" style="color: var(--accent-orange);"></i>Ringkasan & Estimasi
                    </h5>
                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2.5 py-1 rounded-pill small">Valid Real-Time</span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-medium">Total Estimasi Produk</span>
                    <span class="fw-bold text-dark fs-6">Rp 3.000.000</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-medium">Estimasi Ongkos Kirim</span>
                    <span class="fw-bold text-dark fs-6">Rp 50.000</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted fw-medium">Uang Muka (DP 50%)</span>
                    <span class="fw-bold text-dark fs-6">Rp 1.550.000</span>
                </div>

                <hr class="my-4" style="border-color: var(--light-border);">

                <div class="d-flex align-items-center justify-content-between mb-4 p-3 rounded-20" style="background-color: var(--wood-bg); border: 1.5px solid var(--wood-border);">
                    <div>
                        <span class="text-muted d-block small fw-bold text-uppercase">Total Bayar DP</span>
                        <span class="fw-extrabold text-dark" style="font-size: 0.85rem;">Mulai Produksi Sekarang</span>
                    </div>
                    <span class="price-text">Rp 1.550.000</span>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-orange shadow-lg py-3" type="button" onclick="kirimDesainKePenjual()">
                        <i class="fa-solid fa-paper-plane me-2"></i> Kirim Desain ke Penjual 
                    </button>
                    <div class="text-center mt-2">
                        <small class="text-muted"><i class="fa-solid fa-shield-halved me-1 text-success"></i> Transaksi Aman & Bergaransi Mebel Assalam</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL POP-UP ALUR PENGIRIMAN DESAIN UNTUK REVIEW PENJUAL -->
<!-- ========================================================================= -->

<!-- 1. MODAL KONFIRMASI PENGIRIMAN DESAIN -->
<div class="modal fade" id="modalKirimDesain" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-dark p-4 p-md-5 text-center">
            <div class="mb-3">
                <span class="badge px-3 py-1.5 rounded-pill fw-bold" style="background-color: rgba(217, 119, 6, 0.15); color: var(--accent-orange);">ASSALAM MEBEL WORKBENCH</span>
            </div>
            <h4 class="fw-extrabold text-dark mb-1">PENGAJUAN DESAIN CUSTOM</h4>
            <p class="text-muted small mb-4">Pastikan rincian spesifikasi mebel pilihan Anda sudah sesuai sebelum diteruskan ke pengrajin.</p>

            <div class="text-start mb-4 mx-auto p-4 rounded-20 w-100" style="max-width: 550px; background-color: var(--wood-bg); border: 1.5px solid var(--wood-border);">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Nomor Pesanan :</span>
                    <span class="fw-bold text-dark">#REQ-8821</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Nama Pemesan :</span>
                    <span class="fw-bold text-dark">Customer Assalam</span>
                </div>
                <div class="d-flex justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--wood-border) !important;">
                    <span class="text-muted small">Tanggal Pengajuan :</span>
                    <span class="fw-bold text-dark">{{ date('d/m/Y') }}</span>
                </div>

                <h6 class="fw-bold text-dark mb-2">Draft Spesifikasi Pesanan:</h6>
                <p class="mb-1 small text-muted">Kategori Produk : <span class="text-dark fw-bold" id="review_jenis">-</span></p>
                <p class="mb-1 small text-muted">Finishing Warna : <span class="text-dark fw-bold" id="review_warna">-</span></p>
                <p class="mb-1 small text-muted">Ukuran Presisi : <span class="text-dark fw-bold" id="review_ukuran">-</span></p>
                <p class="mb-3 small text-muted">Material Utama : <span class="text-dark fw-bold" id="review_material">-</span></p>

                <div class="alert text-start small mb-0 d-flex align-items-start gap-2" style="background-color: #ffffff; border-color: var(--wood-border); color: var(--text-dark);">
                    <i class="fa-solid fa-circle-info text-warning mt-0.5"></i>
                    <span>Penjual akan meninjau desain ini dan membuatkan kalkulasi & rancangan desain kerja final sebelum Anda melakukan pembayaran DP.</span>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <button class="btn px-4 py-2.5 rounded-3 fw-bold text-dark border shadow-sm" style="background-color: #ffffff; border-color: var(--wood-border) !important;" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-orange px-5 py-2.5" onclick="prosesKirimKePenjual()">Kirim ke Penjual Sekarang</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. MODAL MENUNGGU REVIU PENJUAL -->
<div class="modal fade" id="modalBerhasilDikirim" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark p-4 p-md-5 text-center">
            <div class="mb-3">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: rgba(217, 119, 6, 0.15);">
                    <i class="fa-solid fa-paper-plane fa-2x" style="color: var(--accent-orange);"></i>
                </div>
            </div>
            <h4 class="fw-extrabold text-dark mb-2">Desain Berhasil Dikirim!</h4>
            <p class="text-muted small mb-4">
                Desain Anda sedang ditinjau oleh pengrajin/penjual mebel kami. Silakan cek halaman <b>Progress Pesanan</b> secara berkala untuk memantau status persetujuan desain & rincian pembayaran.
            </p>
            <button class="btn btn-orange py-3" onclick="selesai()">Lihat Progress Pesanan</button>
        </div>
    </div>
</div>

<!-- 3. MODAL PILIH METODE PEMBAYARAN DP -->
<div class="modal fade" id="modalPilihPembayaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark p-4 p-md-5 text-center">
            <h5 class="fw-extrabold text-dark mb-1">PILIH METODE PEMBAYARAN</h5>
            <h6 class="fw-bold mb-3" style="color: var(--accent-orange);">UANG MUKA (DP 50%)</h6>
            <p class="text-muted small mb-3">Total Tagihan DP : <span class="fw-bold text-dark fs-6">Rp 1.550.000</span></p>

            <p class="fw-bold fs-3 mb-3" style="color: var(--accent-amber);">Rp 1.550.000</p>

            <!-- PILIHAN METODE PEMBAYARAN -->
            <div class="text-start mb-3">
                <label class="form-label fw-bold small text-dark">Pilih Metode Pembayaran:</label>
                <select id="metodePembayaran" class="form-select border-2" style="border-color: var(--light-border);" onchange="gantiMetodeBayar()">
                    <option value="qris">QRIS (Semua E-Wallet & M-Banking)</option>
                    <option value="dana">E-Wallet : DANA</option>
                    <option value="gopay">E-Wallet : GoPay</option>
                    <option value="transfer">Transfer Bank (BCA)</option>
                </select>
            </div>

            <!-- KONTEN INFO PEMBAYARAN BERDASARKAN PILIHAN -->
            <div id="infoQris" class="p-3 border rounded-4 mb-3 bg-white shadow-sm" style="border-color: var(--light-border) !important;">
                <div class="border p-3 rounded-3 mb-2 mx-auto bg-light" style="width: 150px; height: 150px;">
                    <div class="d-flex h-100 align-items-center justify-content-center text-dark fw-bold">
                        [ QRIS CODE ]
                    </div>
                </div>
                <span class="small text-muted">Scan menggunakan aplikasi bank atau e-wallet apa saja.</span>
            </div>

            <div id="infoDana" class="p-3 border rounded-4 mb-3 bg-white shadow-sm d-none" style="border-color: var(--light-border) !important;">
                <div class="text-primary fw-bold fs-5 mb-1">DANA</div>
                <p class="mb-1 small text-muted">Nomor Akun DANA Mebel:</p>
                <h5 class="fw-bold text-dark mb-2">0812-3456-7890</h5>
                <span class="small text-muted">Atas Nama: Assalam Mebel Official</span>
            </div>

            <div id="infoGopay" class="p-3 border rounded-4 mb-3 bg-white shadow-sm d-none" style="border-color: var(--light-border) !important;">
                <div class="text-success fw-bold fs-5 mb-1">GoPay</div>
                <p class="mb-1 small text-muted">Nomor Akun GoPay Mebel:</p>
                <h5 class="fw-bold text-dark mb-2">0812-3456-7890</h5>
                <span class="small text-muted">Atas Nama: Assalam Mebel Official</span>
            </div>

            <div id="infoTransfer" class="text-start mx-auto small mb-4 text-muted d-none" style="max-width: 280px;">
                <p class="mb-1">🏦 BCA : <span class="fw-bold text-dark">8830-xx-xxx</span></p>
                <span class="d-block mt-2 text-center text-muted" style="font-size: 0.75rem;">Atas Nama: CV. Assalam Mebel Indonesia</span>
            </div>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary w-50 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn fw-bold w-50 py-2 rounded-3 text-white" style="background-color: var(--primary-color);" onclick="prosesLunas()">Konfirmasi Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<!-- 4. MODAL PEMBAYARAN BERHASIL -->
<div class="modal fade" id="modalPembayaranSukses" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-dark p-4 p-md-5 text-center">
            <div class="mb-3">
                <i class="fa-solid fa-circle-check fa-4x text-success mb-2"></i>
            </div>
            <h4 class="fw-extrabold text-dark mb-2">Pembayaran DP Berhasil!</h4>
            <p class="text-muted small mb-4">
                Terima kasih, pembayaran uang muka (DP) Anda telah terverifikasi sistem secara otomatis. Pesanan mebel custom Anda kini resmi masuk ke tahap produksi pengrajin.
            </p>
            <button class="btn btn-orange py-3" onclick="selesai()">Lihat Progress Pesanan</button>
        </div>
    </div>
</div>

<!-- SCRIPT UTAMA -->
<script>
    let currentBaseHex = "#d97706";
    let currentColorName = "Amber Gold";

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
        document.getElementById('color_name_input').value = currentColorName + " (" + brightnessPercent + "%)";
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

    function kirimDesainKePenjual() {
        document.getElementById('review_jenis').innerText = document.getElementById('inputKategori').value;
        document.getElementById('review_warna').innerText = document.getElementById('color_name_input').value + " (" + document.getElementById('final_color_hex_input').value + ")";
        
        let p = document.getElementById('inputPanjang').value || '0';
        let l = document.getElementById('inputLebar').value || '0';
        let t = document.getElementById('inputTinggi').value || '0';
        document.getElementById('review_ukuran').innerText = `${p} x ${l} x ${t} cm`;
        
        document.getElementById('review_material').innerText = document.getElementById('inputMaterial').value;

        let modal1 = new bootstrap.Modal(document.getElementById('modalKirimDesain'));
        modal1.show();
    }

    function prosesKirimKePenjual() {
        let modalKirim = bootstrap.Modal.getInstance(document.getElementById('modalKirimDesain'));
        if (modalKirim) {
            modalKirim.hide();
        }
        
        let modalBayar = new bootstrap.Modal(document.getElementById('modalPilihPembayaran'));
        modalBayar.show();
    }

    function prosesKonfirmasiPembayaran(buttonElement) {
        let originalText = buttonElement.innerHTML;
        buttonElement.innerHTML = `<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses Validasi Bank...`;
        buttonElement.disabled = true;

        setTimeout(function() {
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;

            let modalPilihEl = document.getElementById('modalPilihPembayaran');
            let modalPilihInstance = bootstrap.Modal.getInstance(modalPilihEl);
            if (modalPilihInstance) {
                modalPilihInstance.hide();
            }

            let modalSuksesEl = document.getElementById('modalPembayaranSukses');
            let modalSukses = new bootstrap.Modal(modalSuksesEl);
            modalSukses.show();

        }, 3000);
    }

    function selesai() {
        window.location.href = "{{ route('customer.progress') }}";
    }
</script>
@endsection