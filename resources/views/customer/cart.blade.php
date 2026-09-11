@extends('layouts.customer')

@section('content')
<div class="container-xl">

    <!-- BREADCRUMBS & TITLE -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small mb-0">
            <li class="breadcrumb-item"><a href="{{ route('customer.beranda') }}" class="text-decoration-none text-muted">Beranda</a></li>
            <li class="breadcrumb-item active fw-bold text-dark" aria-current="page">Keranjang Belanja</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h2 class="section-title mb-1">Keranjang Belanja</h2>
            <p class="section-subtitle">Periksa item mebel pilihan Anda dan lanjutkan ke pembayaran uang muka (DP 50%)</p>
        </div>
        <a href="{{ route('customer.katalog') }}" class="btn btn-sm btn-store-secondary rounded-3">
            <i class="fa-solid fa-arrow-left me-1"></i> Lanjut Belanja Mebel
        </a>
    </div>

    @if(empty($cart) || count($cart) === 0)
        <!-- EMPTY CART STATE -->
        <div class="card border-0 shadow-sm rounded-4 text-center p-5 mb-5 bg-white" style="border: 1px solid var(--border-color) !important;">
            <div class="py-5">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-muted mb-3" style="width: 84px; height: 84px; background-color: var(--primary-subtle);">
                    <i class="fa-solid fa-basket-shopping fa-3x" style="color: var(--primary-color);"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Keranjang Belanja Anda Masih Kosong</h4>
                <p class="text-muted small mb-4" style="max-width: 480px; margin: 0 auto;">
                    Jelajahi berbagai koleksi furniture kayu jati solid perhutani grade A kami, atau buat rancangan mebel impian Anda di Studio Desain.
                </p>
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('customer.katalog') }}" class="btn btn-store-primary px-4 py-2.5 rounded-3">
                        <i class="fa-solid fa-cubes me-1"></i> Jelajahi Katalog
                    </a>
                    <a href="{{ route('customer.design') }}" class="btn btn-store-secondary px-4 py-2.5 rounded-3">
                        <i class="fa-solid fa-pen-ruler me-1"></i> Custom Mebel
                    </a>
                </div>
            </div>
        </div>
    @else
        @php
            if (!isset($subtotal)) {
                $subtotal = 0;
                foreach (($cart ?? []) as $it) {
                    $subtotal += (($it['price'] ?? 0) * ($it['quantity'] ?? 1));
                }
            }
            $defaultShipping = $defaultShipping ?? ($shippingCosts->first()->biaya ?? 50000);
            $total = $total ?? ($subtotal > 0 ? ($subtotal + $defaultShipping) : 0);
            $dpAmount = $dpAmount ?? round($total * 0.5);
        @endphp
        <!-- CART WITH ITEMS -->
        <div class="row g-4 mb-5">
            
            <!-- Left: Cart Items List -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="border: 1px solid var(--border-color) !important;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h6 class="fw-bold text-dark mb-0">Daftar Produk ({{ count($cart) }} Item)</h6>
                        <span class="small text-muted">Rincian Belanja</span>
                    </div>

                    @foreach($cart as $key => $item)
                        <div class="p-3 rounded-3 mb-3 border bg-light" style="border-color: var(--border-color) !important;">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                
                                <!-- Product Info & Thumbnail -->
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 overflow-hidden bg-white border d-flex align-items-center justify-content-center flex-shrink-0" style="width: 80px; height: 80px;">
                                        @if(!empty($item['image']))
                                            @php
                                                if (str_starts_with($item['image'], 'http://') || str_starts_with($item['image'], 'https://')) {
                                                    $imgSrc = $item['image'];
                                                } elseif (file_exists(public_path($item['image']))) {
                                                    $imgSrc = '/' . ltrim($item['image'], '/');
                                                } else {
                                                    $imgSrc = '/storage/' . ltrim($item['image'], '/');
                                                }
                                            @endphp
                                            <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fa-solid fa-couch fa-2x" style="color: var(--primary-light);"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $item['name'] }}</h6>
                                        <div class="fw-bold small mb-1" style="color: var(--primary-color);">
                                            Rp {{ number_format($item['price'], 0, ',', '.') }} / unit
                                        </div>
                                        <small class="text-muted d-block">{{ Str::limit($item['description'] ?? '', 45) }}</small>
                                    </div>
                                </div>

                                <!-- Quantity Controls & Subtotal -->
                                <div class="d-flex align-items-center justify-content-between justify-content-sm-end gap-3 pt-2 pt-sm-0 border-top border-sm-top-0">
                                    <div class="d-flex align-items-center gap-1">
                                        
                                        <!-- Decrease Form -->
                                        <form action="{{ route('customer.cart.update', $key) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="btn btn-sm btn-white border px-2 py-1 rounded-2" title="Kurangi">
                                                <i class="fa-solid fa-minus small"></i>
                                            </button>
                                        </form>

                                        <span class="fw-bold px-2 text-dark small">{{ $item['quantity'] }}</span>

                                        <!-- Increase Form -->
                                        <form action="{{ route('customer.cart.update', $key) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="btn btn-sm btn-white border px-2 py-1 rounded-2" title="Tambah">
                                                <i class="fa-solid fa-plus small"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="text-end" style="min-width: 110px;">
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Subtotal:</small>
                                        <strong class="text-dark small">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</strong>
                                    </div>

                                    <!-- Remove Button -->
                                    <form action="{{ route('customer.cart.remove', $key) }}" method="POST" onsubmit="return confirm('Hapus mebel ini dari keranjang?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-danger p-1 border-0" title="Hapus Item">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach

                    <!-- Trust Strip -->
                    <div class="p-3 rounded-3 mt-3 d-flex align-items-center gap-3" style="background-color: var(--primary-subtle); border: 1px solid var(--border-color);">
                        <i class="fa-solid fa-shield-halved fa-2x" style="color: var(--primary-color);"></i>
                        <div>
                            <strong class="text-dark small d-block">Garansi Konstruksi Kayu Solid 100%</strong>
                            <small class="text-secondary">Produksi menggunakan sambungan tradisional pasak & purus kayu solid pengrajin Karduluk Madura, kokoh bertahun-tahun.</small>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right: Order Summary & Checkout Form -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white position-sticky" style="top: 90px; border: 1px solid var(--border-color) !important;">
                    
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="fa-solid fa-receipt me-2" style="color: var(--accent-gold);"></i> Ringkasan Belanja
                    </h5>

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Subtotal Produk</span>
                        <strong class="text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>

                    <!-- Shipping Area Selector (Khusus Se-Madura) -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label small text-dark fw-bold mb-0">Wilayah Pengiriman Truk:</label>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.68rem;">
                                <i class="fa-solid fa-truck-fast me-1"></i>Eksklusif Se-Madura
                            </span>
                        </div>
                        <select class="form-select form-select-sm rounded-3" id="shippingSelect" onchange="updateTotalWithShipping()">
                            @foreach($shippingCosts as $loc)
                                <option value="{{ $loc->biaya }}" {{ $loop->first ? 'selected' : '' }}>
                                    {{ $loc->kecamatan }} - Rp {{ number_format($loc->biaya, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-circle-info me-1 text-primary"></i>Pengiriman mebel aman langsung dari Workshop Karduluk, Sumenep ke seluruh penjuru Pulau Madura.
                        </small>
                    </div>

                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-muted small">Ongkos Kirim Truk Mebel</span>
                        <strong class="text-dark" id="displayShippingCost">Rp {{ number_format($defaultShipping, 0, ',', '.') }}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold text-dark">Total Biaya Pesanan</span>
                        <h4 class="fw-extrabold mb-0" style="color: var(--primary-color);" id="displayTotal">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </h4>
                    </div>

                    <!-- DP 50% Callout -->
                    <div class="p-3 rounded-3 mb-4" style="background-color: rgba(217, 119, 6, 0.1); border: 1.5px solid var(--accent-gold);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.72rem;">Uang Muka (DP 50%)</small>
                                <small class="text-dark fw-semibold" style="font-size: 0.75rem;">Pelunasan saat barang siap kirim</small>
                            </div>
                            <strong class="fs-5" style="color: var(--accent-gold);" id="displayDP">
                                Rp {{ number_format($dpAmount, 0, ',', '.') }}
                            </strong>
                        </div>
                    </div>

                    <!-- Checkout Form (If Logged In) -->
                    @auth
                        <form action="{{ route('customer.cart.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shipping_cost" id="inputShippingCost" value="{{ $defaultShipping }}">

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-1">Nama Penerima:</label>
                                <input type="text" name="recipient_name" class="form-control form-control-sm rounded-3" value="{{ Auth::user()->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-1">Nomor WhatsApp Aktif:</label>
                                <input type="text" name="recipient_phone" class="form-control form-control-sm rounded-3" value="{{ Auth::user()->whatsapp_number }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-1">Alamat Lengkap Pengiriman:</label>
                                <textarea name="shipping_address" class="form-control form-control-sm rounded-3" rows="2" placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan..." required>{{ Auth::user()->alamat }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark mb-1">Metode Pembayaran DP & Pelunasan:</label>
                                <input type="hidden" name="payment_method" value="dana">
                                <div class="p-3 rounded-3 border d-flex align-items-center justify-content-between" style="background-color: #f0f9ff; border-color: #bae6fd !important;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 36px; height: 36px; background-color: #118eea;">
                                            <i class="fa-solid fa-wallet"></i>
                                        </div>
                                        <div>
                                            <strong class="text-dark small d-block">E-Wallet DANA Resmi</strong>
                                            <span class="text-muted" style="font-size: 0.72rem;">{{ \App\Models\Setting::get('payment_dana_number', '0852-3456-7890') }}</span>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill bg-primary px-2.5 py-1 text-white fw-bold" style="font-size: 0.68rem;">DANA Resmi</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-dark mb-1">Catatan Tambahan (Opsional):</label>
                                <textarea name="customer_notes" class="form-control form-control-sm rounded-3" rows="2" placeholder="Catatan warna atau petunjuk khusus..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-store-primary w-100 py-3 rounded-3 shadow">
                                <i class="fa-solid fa-shield-halved me-1"></i> Lanjut Pembayaran DP 50%
                            </button>
                        </form>
                    @else
                        <!-- Guest Auth Callout -->
                        <div class="p-3 rounded-4 text-center bg-light border">
                            <i class="fa-solid fa-user-lock fa-2x mb-2" style="color: var(--primary-color);"></i>
                            <h6 class="fw-bold text-dark mb-1">Masuk untuk Checkout</h6>
                            <p class="text-muted small mb-3">Masuk atau daftar akun agar Anda dapat memantau progres pengerjaan mebel dan upload bukti transfer DP.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('login') }}" class="btn btn-store-primary py-2.5 rounded-3">
                                    <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Masuk Akun
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-store-secondary py-2 rounded-3">
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

@push('scripts')
<script>
    const subtotal = {{ $subtotal ?? 0 }};

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
@endpush
@endsection
