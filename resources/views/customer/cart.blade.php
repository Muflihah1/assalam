@extends('layouts.customer')

@section('content')
<style>
    .cart-card {
        background-color: var(--light-card);
        border: 1.5px solid var(--light-border);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 6px 18px rgba(93, 64, 55, 0.05);
    }

    .cart-item-row {
        background-color: #ffffff;
        border: 1.5px solid var(--light-border);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    .cart-item-row:hover {
        border-color: var(--wood-border);
        box-shadow: 0 4px 12px rgba(93, 64, 55, 0.06);
    }

    .cart-img-box {
        width: 85px;
        height: 85px;
        border-radius: 12px;
        overflow: hidden;
        background-color: #fdfaf6;
        border: 1px solid var(--light-border);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1.5px solid var(--wood-border);
        background-color: var(--light-bg);
        color: var(--text-dark);
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .qty-btn:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    .btn-orange {
        background: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        padding: 14px 24px;
        transition: all 0.2s;
    }

    .btn-orange:hover {
        background: var(--secondary-color);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(93, 64, 55, 0.2);
    }

    .form-control-custom, .form-select-custom {
        background-color: #fdfcfb !important;
        border: 1.5px solid var(--wood-border) !important;
        color: var(--text-dark) !important;
        border-radius: 12px;
        padding: 10px 14px;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(93, 64, 55, 0.15) !important;
    }
</style>

<div class="container-fluid px-2 px-md-4 py-2">
    
    <!-- HEADER -->
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h3 class="fw-bold mb-1" style="color: var(--primary-color);">Keranjang Belanja Anda</h3>
            <p class="text-muted small mb-0">Periksa daftar item belanja, atur jumlah barang, dan selesaikan pesanan Anda.</p>
        </div>
        <a href="{{ route('customer.katalog') }}" class="btn btn-outline-dark px-3 py-2 rounded-3 fw-bold small">
            <i class="fa-solid fa-arrow-left me-1"></i> Lanjut Belanja
        </a>
    </div>

    @if(empty($cart) || count($cart) === 0)
        <!-- EMPTY CART STATE -->
        <div class="cart-card text-center py-5">
            <div class="mb-3">
                <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center" style="width: 85px; height: 85px; background-color: rgba(93, 64, 55, 0.1);">
                    <i class="fa-solid fa-cart-shopping fa-3x" style="color: var(--primary-color);"></i>
                </div>
            </div>
            <h4 class="fw-bold text-dark mb-2">Keranjang Belanja Anda Masih Kosong</h4>
            <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
                Temukan berbagai koleksi furniture berkualitas dari kayu jati perhutani & mahoni solid pilihan kami, atau rancang desain mebel impian Anda sekarang.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('customer.katalog') }}" class="btn btn-outline-dark px-4 py-2.5 rounded-3 fw-bold">
                    <i class="fa-solid fa-basket-shopping me-1"></i> Buka Katalog Mebel
                </a>
                <a href="{{ route('customer.design') }}" class="btn btn-orange px-4 py-2.5 rounded-3">
                    <i class="fa-solid fa-pen-ruler me-1"></i> Studio Custom Desain 🎨
                </a>
            </div>
        </div>
    @else
        <!-- CART WITH ITEMS -->
        <div class="row g-4">
            
            <!-- KOLOM KIRI: DAFTAR ITEM KERANJANG -->
            <div class="col-lg-8">
                <div class="cart-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom" style="border-color: var(--light-border) !important;">
                        <h5 class="fw-bold text-dark mb-0">Daftar Produk ({{ count($cart) }} Item)</h5>
                        <span class="text-muted small">Harga Satuan</span>
                    </div>

                    @foreach($cart as $key => $item)
                        <div class="cart-item-row">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                <!-- Info Produk & Thumbnail -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="cart-img-box">
                                        @if(!empty($item['image']))
                                            @php
                                                $imgSrc = (str_starts_with($item['image'], 'http://') || str_starts_with($item['image'], 'https://'))
                                                    ? $item['image']
                                                    : \Illuminate\Support\Facades\Storage::url($item['image']);
                                            @endphp
                                            <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}">
                                        @else
                                            <i class="fa-solid fa-couch fa-2x" style="color: var(--primary-color);"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $item['name'] }}</h6>
                                        <span class="fw-bold fs-6 d-block mb-1" style="color: var(--primary-color);">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }}
                                        </span>
                                        <small class="text-muted">{{ Str::limit($item['description'] ?? '', 45) }}</small>
                                    </div>
                                </div>

                                <!-- Kontrol Qty & Subtotal -->
                                <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 pt-2 pt-sm-0 border-top border-sm-top-0" style="border-color: var(--light-border) !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <!-- Kurang Qty -->
                                        <form action="{{ route('customer.cart.update', $key) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="qty-btn" title="Kurangi">
                                                <i class="fa-solid fa-minus small"></i>
                                            </button>
                                        </form>

                                        <span class="fw-bold px-2 text-dark">{{ $item['quantity'] }}</span>

                                        <!-- Tambah Qty -->
                                        <form action="{{ route('customer.cart.update', $key) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="qty-btn" title="Tambah">
                                                <i class="fa-solid fa-plus small"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="text-end" style="min-width: 110px;">
                                        <span class="small text-muted d-block">Subtotal:</span>
                                        <strong class="text-dark">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</strong>
                                    </div>

                                    <!-- Hapus Item -->
                                    <form action="{{ route('customer.cart.remove', $key) }}" method="POST" onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Hapus dari keranjang">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="p-3 rounded-4 mt-3 d-flex align-items-center gap-3" style="background-color: var(--wood-bg); border: 1.5px solid var(--wood-border);">
                        <i class="fa-solid fa-shield-halved fa-2x" style="color: var(--primary-color);"></i>
                        <div>
                            <strong class="text-dark small d-block">Garansi Mebel 100% Kayu Solid</strong>
                            <span class="text-muted small">Setiap pesanan diproduksi dengan bahan kayu jati/mahoni berkualitas tinggi dan dikerjakan oleh pengrajin berpengalaman.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KOLOM KANAN: RINGKASAN & FORM CHECKOUT -->
            <div class="col-lg-4">
                <div class="cart-card position-sticky" style="top: 20px;">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom" style="border-color: var(--light-border) !important;">
                        <i class="fa-solid fa-receipt me-2" style="color: var(--accent-gold);"></i>Ringkasan Belanja
                    </h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Subtotal Produk</span>
                        <strong class="text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>

                    <!-- Pilihan Wilayah Pengiriman -->
                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold mb-1">Pilih Wilayah Ongkir:</label>
                        <select class="form-select form-select-custom form-select-sm" id="shippingSelect" onchange="updateTotalWithShipping()">
                            @foreach($shippingCosts as $loc)
                                <option value="{{ $loc->biaya }}" {{ $loop->first ? 'selected' : '' }}>
                                    {{ $loc->kecamatan }} - Rp {{ number_format($loc->biaya, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom" style="border-color: var(--light-border) !important;">
                        <span class="text-muted small">Ongkos Kirim</span>
                        <strong class="text-dark" id="displayShippingCost">Rp {{ number_format($defaultShipping, 0, ',', '.') }}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark">Total Pembayaran</span>
                        <h4 class="fw-extrabold mb-0" style="color: var(--primary-color);" id="displayTotal">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </h4>
                    </div>

                    <div class="p-3 rounded-3 mb-4" style="background-color: rgba(217, 119, 6, 0.1); border: 1px solid var(--accent-gold);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block fw-bold">Uang Muka (DP 50%)</small>
                                <small class="text-dark">Sisa dibayar saat siap kirim</small>
                            </div>
                            <strong class="fs-5" style="color: var(--accent-gold);" id="displayDP">
                                Rp {{ number_format($dpAmount, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>

                    <!-- FORM CHECKOUT ATAU CTA LOGIN -->
                    @auth
                        <form action="{{ route('customer.cart.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shipping_cost" id="inputShippingCost" value="{{ $defaultShipping }}">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Nama Penerima:</label>
                                <input type="text" name="recipient_name" class="form-control form-control-custom form-control-sm" value="{{ Auth::user()->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Nomor WhatsApp:</label>
                                <input type="text" name="recipient_phone" class="form-control form-control-custom form-control-sm" value="{{ Auth::user()->whatsapp_number }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Alamat Pengiriman:</label>
                                <textarea name="shipping_address" class="form-control form-control-custom form-control-sm" rows="2" placeholder="Alamat lengkap tujuan..." required>{{ Auth::user()->alamat }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">Pilih Metode Pembayaran DP:</label>
                                <select name="payment_method" class="form-select form-select-custom form-select-sm" required>
                                    <option value="qris">QRIS (Semua E-Wallet & M-Banking)</option>
                                    <option value="transfer">Transfer Bank (BCA 8830-1289-44)</option>
                                    <option value="dana">E-Wallet DANA</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-dark">Catatan Pesanan (Opsional):</label>
                                <textarea name="customer_notes" class="form-control form-control-custom form-control-sm" rows="2" placeholder="Catatan tambahan..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-orange w-100 py-3 shadow-sm">
                                <i class="fa-solid fa-lock me-1"></i> Checkout & Bayar DP Sekarang 🚀
                            </button>
                        </form>
                    @else
                        <div class="p-3 rounded-4 text-center" style="background-color: var(--light-bg); border: 1.5px solid var(--wood-border);">
                            <i class="fa-solid fa-user-lock fa-2x mb-2" style="color: var(--primary-color);"></i>
                            <h6 class="fw-bold text-dark mb-1">Masuk untuk Melanjutkan Checkout</h6>
                            <p class="text-muted small mb-3">Login atau buat akun baru agar Anda dapat memantau progres pengerjaan dan riwayat pesanan.</p>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-orange py-2.5">
                                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Masuk / Login
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-outline-dark py-2 rounded-3 fw-bold small">
                                    <i class="fa-solid fa-user-plus me-1"></i> Daftar Akun Baru
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

        </div>
    @endif

</div>

<script>
    const subtotal = {{ $subtotal }};

    function updateTotalWithShipping() {
        const select = document.getElementById('shippingSelect');
        const shippingCost = parseFloat(select.value) || 0;
        const total = subtotal + shippingCost;
        const dp = Math.round(total * 0.5);

        document.getElementById('displayShippingCost').innerText = 'Rp ' + shippingCost.toLocaleString('id-ID');
        document.getElementById('displayTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('displayDP').innerText = 'Rp ' + dp.toLocaleString('id-ID');

        const inputShipping = document.getElementById('inputShippingCost');
        if (inputShipping) {
            inputShipping.value = shippingCost;
        }
    }
</script>
@endsection
