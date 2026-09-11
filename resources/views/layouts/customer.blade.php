<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Assalam Mebel') }} - Toko & Custom Mebel Kayu Solid Karduluk Sumenep (Madura)</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Swiper.js CSS (Touch Slider) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Leaflet.js CSS & JS for Interactive Map & Coverage Boundary (Se-Madura) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Batas Geografis Resmi Pulau Madura (GeoJSON) -->
    <script src="{{ asset('js/madura-boundary.js') }}"></script>

    <!-- Assalam E-Commerce Design System (Anti-AI Slop) -->
    <link rel="stylesheet" href="{{ asset('css/ecommerce.css') }}">

    @stack('styles')
</head>
<body>

    <!-- 1. TOP ANNOUNCEMENT BAR -->
    <div class="top-announcement-bar d-none d-md-block">
        <div class="container-xl">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <span><i class="fa-solid fa-certificate text-warning me-1"></i> 100% Kayu Solid Legalitas Perhutani (Jati & Mahoni)</span>
                    <span class="opacity-50">|</span>
                    <span><i class="fa-solid fa-shield-halved text-warning me-1"></i> Garansi Konstruksi & Finishing Presisi</span>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <a href="{{ route('customer.design') }}">
                        <i class="fa-solid fa-pen-ruler text-warning me-1"></i> Studio Custom Furniture
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp text-success me-1"></i> CS WhatsApp: 0812-3456-7890
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MAIN STORE HEADER -->
    <header class="main-header py-2 py-lg-3">
        <div class="container-xl">
            <div class="d-flex align-items-center justify-content-between gap-2 gap-md-4">
                
                <!-- Left: Hamburger (Mobile) & Brand Logo -->
                <div class="d-flex align-items-center gap-2">
                    <button class="btn d-lg-none p-2 border-0 text-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenuOffcanvas" aria-controls="mobileMenuOffcanvas" aria-label="Buka Menu">
                        <i class="fa-solid fa-bars-staggered fs-5"></i>
                    </button>
                    
                    <a href="{{ route('customer.beranda') }}" class="header-brand text-decoration-none d-flex align-items-center gap-2">
                        <img src="{{ asset('logo.png') }}" alt="Assalam Mebel">
                    </a>
                </div>

                <!-- Center: Big Live Search Bar (Desktop) -->
                <div class="store-search-box d-none d-md-block flex-grow-1 mx-lg-4">
                    <form action="{{ route('customer.katalog') }}" method="GET" class="position-relative">
                        <input type="text" name="keyword" value="{{ request('keyword', request('q', '')) }}" class="form-control" placeholder="Cari mebel jati, kursi ukir, mimbar podium, Meja, lemari..." autocomplete="off">
                        <button type="submit" class="btn-search" title="Cari Produk">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>

                <!-- Right: Header Action Buttons -->
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    
                    <!-- Search Icon Toggle for Small Mobile -->
                    <button class="btn d-md-none p-2 text-dark border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearchCollapse" aria-expanded="false" aria-label="Cari Produk">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </button>

                    <!-- Cart Button with Dynamic Badge -->
                    @php $cartCount = count(session('cart', [])); @endphp
                    <a href="{{ route('customer.cart') }}" class="header-cart-btn text-decoration-none d-inline-flex align-items-center" title="Keranjang Belanja">
                        <i class="fa-solid fa-basket-shopping fs-5"></i>
                        <span class="d-none d-sm-inline ms-2">Keranjang</span>
                        <span class="cart-counter-badge" id="headerCartBadge" style="{{ $cartCount > 0 ? '' : 'display:none;' }}">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <!-- User Account / Auth Buttons -->
                    @auth
                        <div class="dropdown">
                            <button class="header-action-btn dropdown-toggle border-0" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 32px; height: 32px; background-color: var(--primary-color);">
                                    <i class="fa-solid fa-user small"></i>
                                </div>
                                <span class="d-none d-xl-inline text-truncate" style="max-width: 120px;">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2 mt-2" aria-labelledby="userMenuDropdown" style="min-width: 220px;">
                                <li class="px-3 py-2 border-bottom mb-1">
                                    <span class="d-block fw-bold text-dark text-truncate">{{ Auth::user()->name }}</span>
                                    <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;">{{ Auth::user()->email }}</small>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2" href="{{ route('customer.account') }}">
                                        <i class="fa-solid fa-user-gear text-muted"></i> Akun Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2" href="{{ route('customer.progress') }}">
                                        <i class="fa-solid fa-truck-fast text-muted"></i> Lacak Pesanan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 rounded-3 d-flex align-items-center gap-2" href="{{ route('customer.riwayat') }}">
                                        <i class="fa-solid fa-file-invoice text-muted"></i> Riwayat Transaksi
                                    </a>
                                </li>
                                @if(Auth::user()->role === 'admin')
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2 rounded-3 text-primary d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                                            <i class="fa-solid fa-shield-halved"></i> Panel Administrator
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 rounded-3 text-danger d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-power-off"></i> Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('login') }}" class="btn btn-sm btn-store-secondary d-none d-sm-inline-flex">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-sm btn-store-primary">
                                Daftar
                            </a>
                        </div>
                    @endauth

                </div>

            </div>

            <!-- Mobile Search Collapse Bar -->
            <div class="collapse d-md-none mt-2 pt-2 border-top" id="mobileSearchCollapse">
                <form action="{{ route('customer.katalog') }}" method="GET" class="position-relative">
                    <input type="text" name="keyword" value="{{ request('keyword', request('q', '')) }}" class="form-control rounded-pill pe-5 ps-3 py-2" placeholder="Cari mebel jati..." autocomplete="off">
                    <button type="submit" class="btn btn-sm position-absolute end-0 top-50 translate-middle-y me-2 rounded-circle text-muted">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

        </div>
    </header>

    <!-- 3. MAIN NAVIGATION BAR (DESKTOP) -->
    <nav class="category-nav-strip d-none d-lg-block">
        <div class="container-xl">
            <div class="d-flex align-items-center gap-1">
                <a href="{{ route('customer.beranda') }}" class="nav-link-ecommerce {{ request()->routeIs('customer.beranda') || request()->is('/') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
                <a href="{{ route('customer.katalog') }}" class="nav-link-ecommerce {{ request()->routeIs('customer.katalog') ? 'active' : '' }}">
                    <i class="fa-solid fa-couch"></i> Katalog Produk
                </a>
                <a href="{{ route('customer.design') }}" class="nav-link-ecommerce {{ request()->routeIs('customer.design') ? 'active' : '' }}">
                    <i class="fa-solid fa-pen-ruler"></i> Custom Mebel
                    <span class="badge px-2 py-0.5 rounded-pill bg-warning text-dark fw-bold ms-1" style="font-size: 0.68rem;">Bisa Custom</span>
                </a>
                <a href="{{ route('customer.progress') }}" class="nav-link-ecommerce {{ request()->routeIs('customer.progress') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-fast"></i> Lacak Pesanan
                </a>
                @auth
                    <a href="{{ route('customer.riwayat') }}" class="nav-link-ecommerce {{ request()->routeIs('customer.riwayat') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice"></i> Riwayat Transaksi
                    </a>
                @endauth
                <a href="{{ route('customer.workshop') }}" class="nav-link-ecommerce {{ request()->routeIs('customer.workshop') ? 'active' : '' }}">
                    <i class="fa-solid fa-store"></i> Workshop Karduluk
                </a>
                <a href="https://wa.me/6285234567890" target="_blank" rel="noopener" class="nav-link-ecommerce ms-auto text-success fw-bold">
                    <i class="fa-brands fa-whatsapp fs-6"></i> Bantuan CS WhatsApp
                </a>
            </div>
        </div>
    </nav>

    <!-- 4. FLASH ALERTS CONTAINER -->
    <div class="container-xl pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                <i class="fa-solid fa-circle-check fs-5 text-success"></i>
                <div class="flex-grow-1 fw-semibold small">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                <i class="fa-solid fa-triangle-exclamation fs-5 text-danger"></i>
                <div class="flex-grow-1 fw-semibold small">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm border-0" role="alert">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa-solid fa-circle-exclamation fs-5 text-danger"></i>
                    <strong class="small">Periksa kembali formulir yang diisi:</strong>
                </div>
                <ul class="mb-0 ps-3 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- 5. MAIN CONTENT WRAPPER -->
    <main class="main-content-area py-2 py-lg-4">
        @yield('content')
    </main>

    <!-- 6. COMPREHENSIVE E-COMMERCE FOOTER -->
    <footer class="ecommerce-footer" id="tentangToko">
        <div class="container-xl pb-5">
            <div class="row g-4">
                
                <!-- Col 1: Store Bio & Identity -->
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <img src="{{ asset('logo-white.png') }}" alt="Assalam Mebel" style="max-height: 48px; width: auto;" onerror="this.src='{{ asset('logo.png') }}'">
                    </div>
                    <p class="small text-muted-light mb-4 pe-lg-3">
                        Spesialis produsen dan supplier mebel kayu jati & mahoni solid asli sentra ukir Karduluk, Sumenep. Kami melayani pembelian mebel siap pakai maupun pemesanan custom ukuran presisi dengan layanan pengiriman eksklusif se-Pulau Madura.
                    </p>
                    <div class="d-flex gap-2">
                        <a href="#" class="footer-social-btn" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="footer-social-btn" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://wa.me/6285234567890" target="_blank" class="footer-social-btn" title="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="#" class="footer-social-btn" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>

                <!-- Col 2: Navigasi Belanja -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Navigasi</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('customer.beranda') }}">Beranda Toko</a></li>
                        <li><a href="{{ route('customer.katalog') }}">Semua Katalog</a></li>
                        <li><a href="{{ route('customer.design') }}">Custom Mebel</a></li>
                        <li><a href="{{ route('customer.cart') }}">Keranjang Belanja</a></li>
                        <li><a href="{{ route('customer.progress') }}">Lacak Pesanan</a></li>
                        <li><a href="{{ route('customer.workshop') }}">Workshop Karduluk</a></li>
                        <li><a href="{{ route('customer.riwayat') }}">Riwayat Transaksi</a></li>
                    </ul>
                </div>

                <!-- Col 3: Kategori Populer -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="footer-heading">Kategori Mebel</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                        <li><a href="{{ route('customer.katalog', ['keyword' => 'Kursi']) }}">Kursi & Sofa Tamu</a></li>
                        <li><a href="{{ route('customer.katalog', ['keyword' => 'Meja']) }}">Meja Kayu</a></li>
                        <li><a href="{{ route('customer.katalog', ['keyword' => 'Lemari']) }}">Lemari Pakaian</a></li>
                        <li><a href="{{ route('customer.katalog', ['keyword' => 'Pintu']) }}">Pintu Ukir Gebyok</a></li>
                        <li><a href="{{ route('customer.katalog', ['keyword' => 'Podium']) }}">Podium & Mimbar</a></li>
                        <li><a href="{{ route('customer.katalog', ['keyword' => 'Pendopo']) }}">Gazebo & Pendopo</a></li>
                    </ul>
                </div>

                <!-- Col 4: Keunggulan & Garansi Kayu Solid -->
                <div class="col-lg-4 col-md-6">
                    <h6 class="footer-heading">Garansi Kayu Solid</h6>
                    <div class="p-3 rounded-4 mb-3" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <i class="fa-solid fa-tree fa-2x text-warning"></i>
                            <div>
                                <strong class="d-block text-white small">Kayu Solid Legal Perhutani</strong>
                                <small class="text-muted-light" style="font-size: 0.76rem;">Diproses oven kering (kiln dry) standar ekspor anti-rayap.</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-handshake-angle fa-2x text-warning"></i>
                            <div>
                                <strong class="d-block text-white small">Skema DP 50% Aman</strong>
                                <small class="text-muted-light" style="font-size: 0.76rem;">Uang muka 50% untuk produksi, pelunasan saat barang siap kirim.</small>
                            </div>
                        </div>
                    </div>
                    <div class="small text-muted-light">
                        <i class="fa-solid fa-location-dot text-danger me-1"></i> Workshop: VPR6+PH7, Somangkaan, Karduluk, Pragaan, Sumenep, Madura
                    </div>
                </div>

            </div>

            <!-- Footer Bottom Strip -->
            <div class="footer-bottom-strip d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    &copy; {{ date('Y') }} <strong>Assalam Mebel</strong>. Seluruh hak cipta dilindungi. Pengrajin Mebel Kayu Solid Asli.
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="small text-muted-light">Dibuat dengan dedikasi pengrajin kayu Indonesia</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- 7. MOBILE OFFCANVAS MENU DRAWER -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenuOffcanvas" aria-labelledby="mobileMenuOffcanvasLabel">
        <div class="offcanvas-header border-bottom py-3">
            <a href="{{ route('customer.beranda') }}" class="text-decoration-none">
                <img src="{{ asset('logo.png') }}" alt="Assalam Mebel" style="max-height: 38px;">
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            
            <!-- User Status in Offcanvas -->
            <div class="p-3 bg-white border-bottom">
                @auth
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 38px; height: 38px; background-color: var(--primary-color);">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="overflow-hidden">
                            <span class="d-block fw-bold small text-dark text-truncate">{{ Auth::user()->name }}</span>
                            <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;">{{ Auth::user()->email }}</small>
                        </div>
                    </div>
                @else
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-sm btn-store-secondary w-50 fw-bold">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-store-primary w-50 fw-bold">Daftar</a>
                    </div>
                @endauth
            </div>

            <!-- Navigation Links -->
            <div class="py-2">
                <a href="{{ route('customer.beranda') }}" class="nav-item-mobile {{ request()->routeIs('customer.beranda') || request()->is('/') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
                <a href="{{ route('customer.katalog') }}" class="nav-item-mobile {{ request()->routeIs('customer.katalog') ? 'active' : '' }}">
                    <i class="fa-solid fa-couch"></i> Katalog Produk
                </a>
                <a href="{{ route('customer.design') }}" class="nav-item-mobile {{ request()->routeIs('customer.design') ? 'active' : '' }}">
                    <i class="fa-solid fa-pen-ruler"></i> Custom Mebel
                </a>
                <a href="{{ route('customer.cart') }}" class="nav-item-mobile {{ request()->routeIs('customer.cart') ? 'active' : '' }}">
                    <i class="fa-solid fa-basket-shopping"></i> Keranjang Belanja
                    @if($cartCount > 0)
                        <span class="badge rounded-pill bg-danger ms-auto">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('customer.progress') }}" class="nav-item-mobile {{ request()->routeIs('customer.progress') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-fast"></i> Lacak Pesanan
                </a>
                <a href="{{ route('customer.workshop') }}" class="nav-item-mobile {{ request()->routeIs('customer.workshop') ? 'active' : '' }}">
                    <i class="fa-solid fa-store"></i> Workshop Karduluk
                </a>
                @auth
                    <a href="{{ route('customer.riwayat') }}" class="nav-item-mobile {{ request()->routeIs('customer.riwayat') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice"></i> Riwayat Transaksi
                    </a>
                    <a href="{{ route('customer.account') }}" class="nav-item-mobile {{ request()->routeIs('customer.account') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-gear"></i> Pengaturan Akun
                    </a>
                    <div class="p-3 mt-2">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 py-2">
                                <i class="fa-solid fa-power-off"></i> Keluar
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

        </div>
    </div>

    <!-- 8. MOBILE BOTTOM NAVIGATION BAR (App-Like Sticky Navigation) -->
    <nav class="mobile-bottom-nav d-lg-none">
        <a href="{{ route('customer.beranda') }}" class="mobile-nav-item {{ request()->routeIs('customer.beranda') || request()->is('/') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('customer.katalog') }}" class="mobile-nav-item {{ request()->routeIs('customer.katalog') ? 'active' : '' }}">
            <i class="fa-solid fa-couch"></i>
            <span>Katalog</span>
        </a>
        <a href="{{ route('customer.design') }}" class="mobile-nav-item {{ request()->routeIs('customer.design') ? 'active' : '' }}">
            <i class="fa-solid fa-pen-ruler"></i>
            <span>Custom</span>
        </a>
        <a href="{{ route('customer.progress') }}" class="mobile-nav-item {{ request()->routeIs('customer.progress') || request()->routeIs('customer.riwayat') ? 'active' : '' }}">
            <i class="fa-solid fa-truck-fast"></i>
            <span>Lacak</span>
        </a>
        <a href="{{ Auth::check() ? route('customer.account') : route('login') }}" class="mobile-nav-item {{ request()->routeIs('customer.account') || request()->routeIs('login') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>{{ Auth::check() ? 'Akun' : 'Masuk' }}</span>
        </a>
    </nav>

    <!-- Bootstrap 5.3 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 (Modern Notification Toast) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Swiper.js Slider Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Global E-Commerce Scripts (Instant AJAX Add to Cart & Counter) -->
    <script>
        // Global Add-to-Cart Function via JSON
        window.addToCart = function(productId, quantity = 1, buttonElement = null) {
            let originalHtml = '';
            if (buttonElement) {
                originalHtml = buttonElement.innerHTML;
                buttonElement.disabled = true;
                buttonElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const targetUrl = "{{ route('customer.cart.add', ':id') }}".replace(':id', productId);

            const formData = new FormData();
            formData.append('quantity', quantity);

            fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            })
            .then(response => {
                if (response.status === 401 || response.redirected) {
                    window.location.href = "{{ route('login') }}";
                    return null;
                }
                return response.json();
            })
            .then(data => {
                if (!data) return;

                if (data.success) {
                    // Update header cart badge
                    const headerBadges = document.querySelectorAll('#headerCartBadge');
                    headerBadges.forEach(badge => {
                        badge.textContent = data.cartCount;
                        badge.style.display = 'inline-block';
                    });

                    // Trigger modern SweetAlert2 toast
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Produk dimasukkan ke keranjang!',
                        showConfirmButton: true,
                        confirmButtonText: '<i class="fa-solid fa-basket-shopping me-1"></i> Keranjang',
                        confirmButtonColor: '#3B2314',
                        timer: 4000,
                        timerProgressBar: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('customer.cart') }}";
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: data.message || 'Gagal menambahkan ke keranjang.',
                        confirmButtonColor: '#3B2314'
                    });
                }
            })
            .catch(error => {
                console.error('Add to cart error:', error);
                // Fallback to standard form submit if network/json issues
                if (buttonElement && buttonElement.closest('form')) {
                    buttonElement.closest('form').submit();
                }
            })
            .finally(() => {
                if (buttonElement) {
                    buttonElement.disabled = false;
                    buttonElement.innerHTML = originalHtml;
                }
            });
        };
    </script>

    @stack('scripts')

</body>
</html>