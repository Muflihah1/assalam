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
        padding: 12px 16px;
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

    .input-group-custom-text {
        background-color: var(--wood-bg);
        border: 1.5px solid var(--wood-border);
        color: var(--primary-color);
        border-radius: 0 12px 12px 0 !important;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0 14px;
    }

    .input-group-custom .form-control-custom {
        border-radius: 12px 0 0 12px !important;
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
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 2px solid #ffffff;
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
        width: 34px;
        height: 34px;
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
        width: 45px; height: 45px;
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
        font-size: 1.5rem;
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">

    <!-- HEADER INTERAKTIF -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-3 border-bottom gap-3" style="border-color: var(--light-border) !important;">
        <div>
            <span class="badge px-3 py-1 rounded-pill fw-bold mb-2" style="background-color: rgba(217, 119, 6, 0.12); color: var(--accent-gold); font-size: 0.75rem;">
                INTERACTIVE 3D & CUSTOM WORKBENCH
            </span>
            <h2 class="fw-bold mb-1 text-dark">Studio Custom Desain Mebel</h2>
            <p class="text-muted small mb-0">Rancang ukuran presisi, jenis kayu solid grade A, dan tone warna finishing eksklusif langsung dari perangkat Anda.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="p-2.5 px-3 rounded-4 d-flex align-items-center gap-2" style="background-color: var(--wood-bg); border: 1px solid var(--wood-border);">
                <div style="width: 10px; height: 10px; border-radius: 50%; background-color: #10b981; box-shadow: 0 0 6px #10b981;"></div>
                <span class="text-dark fw-bold small">Live Calculator Aktif</span>
            </div>
        </div>
    </div>

    <!-- FORM CUSTOM UTAMA -->
    <form id="formCustomMebel" action="{{ route('customer.design.order') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- SISI KIRI: FORM PARAMETER -->
            <div class="col-lg-7">
                <div class="studio-card p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--light-border) !important;">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-sliders me-2" style="color: var(--accent-gold);"></i>Parameter Desain Furniture
                        </h5>
                        <span class="text-muted small">Langkah 1 dari 2</span>
                    </div>

                    <!-- 1. Kategori & Model -->
                    <div class="mb-4">
                        <label class="form-label-custom">Kategori Furniture</label>
                        <select class="form-select form-select-custom" name="category" id="inputKategori" onchange="hitungHargaReal()">
                            <option value="Sofa & Kursi Tamu Mewah" selected>🛋️ Sofa & Kursi Tamu Mewah</option>
                            <option value="Meja Makan Minimalis Modern">🪑 Meja Makan Minimalis Modern</option>
                            <option value="Lemari & Wardrobe Custom">🚪 Lemari & Wardrobe Custom</option>
                            <option value="Tempat Tidur Estetik">🛏️ Tempat Tidur Estetik</option>
                            <option value="Pintu Rumah & Gebyok">🚪 Pintu Rumah & Gebyok</option>
                            <option value="Credenza & Buffet TV">📺 Credenza & Buffet TV</option>
                        </select>
                    </div>

                    <!-- 2. Dimensi Ukuran -->
                    <div class="mb-4 p-3.5 rounded-4" style="background-color: #fdfbf7; border: 1.5px solid var(--wood-border);">
                        <label class="form-label-custom d-block mb-2">
                            <i class="fa-solid fa-ruler-combined me-2" style="color: var(--accent-gold);"></i>Ukuran Presisi Furniture (cm)
                        </label>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="dimension-label">Panjang (P)</div>
                                <div class="input-group input-group-custom">
                                    <input type="number" name="length_cm" id="inputPanjang" class="form-control form-control-custom" value="180" oninput="hitungHargaReal()" min="30" max="400" required>
                                    <span class="input-group-text input-group-custom-text">cm</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="dimension-label">Lebar (L)</div>
                                <div class="input-group input-group-custom">
                                    <input type="number" name="width_cm" id="inputLebar" class="form-control form-control-custom" value="80" oninput="hitungHargaReal()" min="20" max="300" required>
                                    <span class="input-group-text input-group-custom-text">cm</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="dimension-label">Tinggi (T)</div>
                                <div class="input-group input-group-custom">
                                    <input type="number" name="height_cm" id="inputTinggi" class="form-control form-control-custom" value="75" oninput="hitungHargaReal()" min="20" max="300" required>
                                    <span class="input-group-text input-group-custom-text">cm</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Material -->
                    <div class="mb-4">
                        <label class="form-label-custom">Pilihan Material Kayu Utama</label>
                        <select class="form-select form-select-custom" name="wood_material" id="inputMaterial" onchange="hitungHargaReal()">
                            <option value="Kayu Jati Perhutani (Grade A)" selected>🪵 Kayu Jati Perhutani (Grade A - Anti Rayap & Tahan Puluhan Tahun)</option>
                            <option value="Kayu Mahoni Oven Premium">🪵 Kayu Mahoni Oven Premium (Serat Halus & Sangat Rapih)</option>
                            <option value="Kayu Sungkai Solid">🪵 Kayu Sungkai Solid (Serat Cerah & Estetik Modern)</option>
                        </select>
                    </div>

                    <!-- 4. PEMILIHAN WARNA SWATCHES & SLIDER -->
                    <div class="mb-4">
                        <label class="form-label-custom">Pilihan Warna Finishing</label>

                        <div class="color-swatch-box">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: var(--wood-border) !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="live-preview-circle" id="live-color-preview" style="background-color: #d97706;"></div>
                                    <div>
                                        <h6 class="fw-bold mb-0 text-dark" id="selected-color-name">Amber Gold</h6>
                                        <small class="text-muted fw-semibold" id="selected-color-hex">HEX: #D97706</small>
                                    </div>
                                </div>
                                <span class="badge fw-bold px-3 py-1.5 rounded-pill shadow-sm" style="background-color: #ffffff; color: var(--text-main); border: 1.5px solid var(--wood-border);" id="brightness-badge">Tone: 100%</span>
                            </div>

                            <div class="mb-3 px-1">
                                <label class="form-label text-muted small fw-bold mb-1">Sesuaikan Tingkat Kecerahan Tone</label>
                                <input type="range" class="form-range" id="brightness-slider" min="40" max="160" value="100" oninput="adjustBrightness(this.value)" style="accent-color: var(--primary-color);">
                            </div>

                            <input type="hidden" name="color_hex" id="final_color_hex_input" value="#d97706">
                            <input type="hidden" name="color_name" id="color_name_input" value="Amber Gold">
                            <input type="hidden" name="tone_percent" id="tone_percent_input" value="100">

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="swatch-group-title m-0">🎨 Custom Color Picker</span>
                                <div class="custom-color-picker-wrapper" title="Klik untuk pilih warna kustom bebas">
                                    <input type="color" class="custom-color-input" id="customColorPicker" value="#d97706" onchange="selectCustomColor(this.value)">
                                </div>
                            </div>

                            <div class="swatch-group-title">🪵 Tone Kayu Natural & Klasik</div>
                            <div class="color-swatch-container">
                                <button type="button" class="color-swatch-btn" style="background-color: #fde68a;" onclick="selectBaseColor('#fde68a', 'Light Pine', this)" title="Light Pine"></button>
                                <button type="button" class="color-swatch-btn active" style="background-color: #d97706;" onclick="selectBaseColor('#d97706', 'Amber Gold', this)" title="Amber Gold"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #c85a32;" onclick="selectBaseColor('#c85a32', 'Terracotta Warm', this)" title="Terracotta Warm"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #8d6e63;" onclick="selectBaseColor('#8d6e63', 'Cokelat Sedang', this)" title="Cokelat Sedang"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #4a2c2a;" onclick="selectBaseColor('#4a2c2a', 'Deep Mahogany', this)" title="Deep Mahogany"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #2c221e;" onclick="selectBaseColor('#2c221e', 'Espresso Dark', this)" title="Espresso Dark"></button>
                            </div>

                            <div class="swatch-group-title">🎨 Tone Modern Solid</div>
                            <div class="color-swatch-container">
                                <button type="button" class="color-swatch-btn" style="background-color: #ffffff;" onclick="selectBaseColor('#ffffff', 'Duco Pure White', this)" title="Duco White"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #cbd5e1;" onclick="selectBaseColor('#cbd5e1', 'Light Grey', this)" title="Light Grey"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1e293b;" onclick="selectBaseColor('#1e293b', 'Charcoal Matte', this)" title="Charcoal Matte"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #15803d;" onclick="selectBaseColor('#15803d', 'Emerald Wood', this)" title="Emerald Wood"></button>
                                <button type="button" class="color-swatch-btn" style="background-color: #1e3a8a;" onclick="selectBaseColor('#1e3a8a', 'Navy Blue', this)" title="Navy Blue"></button>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Upload Sketsa & Catatan -->
                    <div class="mb-4">
                        <label class="form-label-custom">Upload Referensi Gambar/Sketsa (Opsional)</label>
                        <input type="file" name="sketch_image" class="form-control form-control-custom" accept="image/*">
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">Format didukung: JPG, PNG, WEBP (Maks. 5MB)</small>
                    </div>

                    <div class="mb-2">
                        <label class="form-label-custom">Catatan Khusus Pengrajin</label>
                        <textarea name="notes" id="inputCatatan" class="form-control form-control-custom" rows="3" placeholder="Contoh: Tolong model kaki meja dibubut ukir klasik, sandaran sofa dibuat busa empuk..."></textarea>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN: ESTIMASI BIAYA & AKSI PESAN -->
            <div class="col-lg-5">
                <div class="studio-card p-4 p-md-5 position-sticky" style="top: 20px;">
                    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" style="border-color: var(--light-border) !important;">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="fa-solid fa-receipt me-2" style="color: var(--accent-gold);"></i>Ringkasan & Estimasi
                        </h5>
                        <span class="badge bg-success bg-opacity-10 text-success fw-bold px-2.5 py-1 rounded-pill small">Valid Real-Time</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted fw-medium">Estimasi Harga Mebel</span>
                        <span class="fw-bold text-dark fs-6" id="displayHargaMebel">Rp 3.500.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted fw-medium">Ongkos Kirim Standar</span>
                        <span class="fw-bold text-dark fs-6">Rp 50.000</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-2 border-bottom">
                        <span class="text-muted fw-medium">Total Nilai Pesanan</span>
                        <span class="fw-bold text-dark fs-6" id="displayTotalPesanan">Rp 3.550.000</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4 p-3 rounded-4" style="background-color: var(--wood-bg); border: 1.5px solid var(--wood-border);">
                        <div>
                            <span class="text-muted d-block small fw-bold text-uppercase">Uang Muka (DP 50%)</span>
                            <span class="fw-bold text-dark small">Mulai Produksi</span>
                        </div>
                        <span class="price-display" id="displayDP">Rp 1.775.000</span>
                    </div>

                    <!-- PILIH METODE BAYAR DP -->
                    <div class="mb-4">
                        <label class="form-label-custom">Pilih Metode Pembayaran DP</label>
                        <select name="payment_method" class="form-select form-select-custom">
                            <option value="qris">QRIS (Semua E-Wallet & M-Banking)</option>
                            <option value="transfer">Transfer Bank (BCA 8830-1289-44)</option>
                            <option value="dana">E-Wallet DANA</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button class="btn btn-orange py-3 fs-6" type="button" onclick="bukaModalReview()">
                            <i class="fa-solid fa-paper-plane me-2"></i> Ajukan Desain & Pesan Sekarang
                        </button>
                        <div class="text-center mt-2">
                            <small class="text-muted"><i class="fa-solid fa-shield-halved me-1 text-success"></i> Transaksi Terverifikasi & Bergaransi Mebel Assalam</small>
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
                    <p class="text-muted small mb-4">Pastikan data spesifikasi berikut sudah sesuai sebelum dikirim ke pengrajin.</p>

                    <div class="text-start mb-4 mx-auto p-4 rounded-4 w-100" style="max-width: 550px; background-color: var(--wood-bg); border: 1.5px solid var(--wood-border);">
                        <p class="mb-1 small text-muted">Kategori : <span class="text-dark fw-bold" id="rev_kategori">-</span></p>
                        <p class="mb-1 small text-muted">Dimensi : <span class="text-dark fw-bold" id="rev_dimensi">-</span></p>
                        <p class="mb-1 small text-muted">Material Kayu : <span class="text-dark fw-bold" id="rev_material">-</span></p>
                        <p class="mb-1 small text-muted">Finishing Warna : <span class="text-dark fw-bold" id="rev_warna">-</span></p>
                        <hr class="my-2" style="border-color: var(--wood-border);">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Total Tagihan :</span>
                            <strong class="text-dark" id="rev_total">-</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Wajib DP (50%) :</span>
                            <strong class="text-primary" id="rev_dp">-</strong>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-3 fw-bold" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-orange px-5 py-2">Konfirmasi & Simpan Pesanan 🚀</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

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

    function hitungHargaReal() {
        let p = parseFloat(document.getElementById('inputPanjang').value) || 180;
        let l = parseFloat(document.getElementById('inputLebar').value) || 80;
        let t = parseFloat(document.getElementById('inputTinggi').value) || 75;
        let material = document.getElementById('inputMaterial').value;

        let volume = (p * l * t) / 1000000; // m3
        let baseRate = 3500000;
        if (material.includes('Jati')) baseRate = 4800000;
        else if (material.includes('Mahoni')) baseRate = 3800000;

        let hargaMebel = Math.round(Math.max(2500000, baseRate * Math.max(0.8, volume * 1.8)) / 50000) * 50000;
        let ongkir = 50000;
        let total = hargaMebel + ongkir;
        let dp = Math.round(total * 0.5);

        document.getElementById('displayHargaMebel').innerText = 'Rp ' + hargaMebel.toLocaleString('id-ID');
        document.getElementById('displayTotalPesanan').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('displayDP').innerText = 'Rp ' + dp.toLocaleString('id-ID');
    }

    function bukaModalReview() {
        document.getElementById('rev_kategori').innerText = document.getElementById('inputKategori').value;
        let p = document.getElementById('inputPanjang').value;
        let l = document.getElementById('inputLebar').value;
        let t = document.getElementById('inputTinggi').value;
        document.getElementById('rev_dimensi').innerText = `${p} x ${l} x ${t} cm`;
        document.getElementById('rev_material').innerText = document.getElementById('inputMaterial').value;
        document.getElementById('rev_warna').innerText = document.getElementById('color_name_input').value + " (" + document.getElementById('final_color_hex_input').value + ")";
        document.getElementById('rev_total').innerText = document.getElementById('displayTotalPesanan').innerText;
        document.getElementById('rev_dp').innerText = document.getElementById('displayDP').innerText;

        let modal = new bootstrap.Modal(document.getElementById('modalReviewDesain'));
        modal.show();
    }

    document.addEventListener("DOMContentLoaded", function() {
        hitungHargaReal();
    });
</script>
@endsection