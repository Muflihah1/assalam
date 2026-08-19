<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assalam Mebel - Toko & Custom Mebel Kayu Solid Premium</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --sidebar-width: 260px;
        --transition-smooth: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        
        /* Tema Latte / Warm Wood Premium */
        --light-bg: #faf6f0;          /* Warm Alabaster Background */
        --light-card: #ffffff;        /* Pure White Card */
        --light-border: #dcd4cc;      /* Natural Wood Border */
        --primary-color: #5d4037;     /* Cokelat Mebel Kayu */
        --primary-dark: #3e2723;      /* Deep Mahogany */
        --secondary-color: #8d6e63;   /* Cokelat Sedang */
        --accent-orange: #d77a61;     /* Terracotta Warm */
        --accent-gold: #d97706;       /* Amber Gold */
        --text-main: #2c221e;         /* Espresso Dark */
        --text-muted: #796d66;        /* Muted Taupe */
        --wood-bg: #edd6bd;           /* Krem Kayu */
        --wood-border: #bfa084;
    }

    body {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        background-color: var(--light-bg) !important;
        color: var(--text-main);
        overflow-x: hidden;
    }

    /* --- SIDEBAR BASE --- */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        background-color: var(--light-card) !important;
        border-right: 1.5px solid var(--light-border);
        z-index: 1050;
        transition: var(--transition-smooth);
        box-shadow: 5px 0 20px rgba(93, 64, 55, 0.03);
        display: flex;
        flex-direction: column;
    }

    /* HEADER SIDEBAR */
    .sidebar-header {
        padding: 15px 20px;
        border-bottom: 1.5px solid var(--light-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 72px;
        background-color: var(--light-card) !important;
    }

    .sidebar-brand {
        font-weight: 800;
        font-size: 1.05rem;
        color: var(--primary-color);
        letter-spacing: 0.5px;
        text-transform: uppercase;
        line-height: 1.2;
    }

    /* Tombol Toggle Sidebar */
    .btn-toggle-sidebar {
        background: var(--light-bg);
        border: 1px solid var(--light-border);
        border-radius: 8px;
        padding: 4px 10px;
        cursor: pointer;
        font-size: 1rem;
        color: var(--text-main);
        transition: var(--transition-smooth);
    }

    .btn-toggle-sidebar:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    /* MENU SIDEBAR */
    .sidebar-menu {
        list-style: none;
        padding: 15px 0;
        margin: 0;
    }

    .sidebar-menu li a {
        display: flex;
        align-items: center;
        padding: 13px 22px;
        color: var(--text-main);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .sidebar-menu li a i {
        font-size: 1.2rem;
        width: 36px;
        color: var(--text-muted);
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .sidebar-menu li a:hover {
        background-color: rgba(93, 64, 55, 0.08);
        color: var(--primary-color);
        padding-left: 26px;
    }

    .sidebar-menu li a:hover i {
        transform: scale(1.15);
        color: var(--primary-color);
    }

    .sidebar-menu li a.active {
        background-color: var(--primary-color);
        color: #ffffff;
        font-weight: 700;
    }

    .sidebar-menu li a.active i {
        color: #ffffff;
    }

    /* --- MAIN WRAPPER --- */
    .main-wrapper {
        margin-left: var(--sidebar-width);
        transition: var(--transition-smooth);
        min-height: 100vh;
        background-color: var(--light-bg) !important;
    }

    /* --- EFEK SLIDE TUTUP SIDEBAR --- */
    .sidebar.hide-sidebar {
        transform: translateX(-100%);
        box-shadow: none;
    }

    .main-wrapper.full-width {
        margin-left: 0;
    }

    /* --- TOP NAVBAR --- */
    .top-navbar {
        padding: 12px 24px;
        display: flex;
        align-items: center;
        gap: 15px;
        background: var(--light-card) !important;
        height: 72px;
        border-bottom: 1.5px solid var(--light-border);
    }

    .btn-open-sidebar {
        display: none;
        background: var(--light-bg);
        border: 1px solid var(--light-border);
        border-radius: 8px;
        padding: 5px 12px;
        cursor: pointer;
        font-size: 1.1rem;
        color: var(--text-main);
        transition: var(--transition-smooth);
    }

    .btn-open-sidebar:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: #ffffff;
    }

    .main-wrapper.full-width .btn-open-sidebar {
        display: block;
    }

    .search-bar {
        position: relative;
        flex-grow: 1;
        max-width: 400px;
    }

    .search-bar input {
        background-color: var(--light-bg) !important;
        border: 1.5px solid var(--light-border);
        color: var(--text-main);
        border-radius: 20px;
        padding-left: 40px;
        height: 40px;
        font-weight: 500;
        transition: var(--transition-smooth);
    }

    .search-bar input::placeholder {
        color: var(--text-muted);
    }

    .search-bar input:focus {
        box-shadow: 0 0 0 3px rgba(93, 64, 55, 0.15);
        border-color: var(--primary-color);
        background-color: #ffffff !important;
    }

    .search-bar i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .nav-actions {
        margin-left: auto;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .cart-badge-icon {
        position: relative;
        font-size: 1.35rem;
        color: var(--text-main);
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 10px;
        background: var(--light-bg);
        border: 1px solid var(--light-border);
        transition: all 0.2s ease;
    }

    .cart-badge-icon:hover {
        background: var(--wood-bg);
        color: var(--primary-color);
        transform: scale(1.05);
    }

    .cart-badge-count {
        position: absolute;
        top: -6px;
        right: -6px;
        background-color: var(--accent-orange);
        color: #ffffff;
        font-size: 0.72rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 20px;
        border: 2px solid #ffffff;
    }

    .btn-auth-nav {
        font-size: 0.85rem;
        font-weight: 700;
        padding: 7px 16px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s;
    }

    /* Mobile Backdrop */
    .sidebar-backdrop {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 1040;
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        .sidebar.show-mobile {
            transform: translateX(0);
        }
        .main-wrapper {
            margin-left: 0 !important;
        }
        .btn-open-sidebar {
            display: block !important;
        }
        .sidebar-backdrop.active {
            display: block;
        }
    }
</style>
</head>
<body>

    <!-- BACKDROP MOBILE -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- SIDEBAR -->
    <div class="sidebar" id="mySidebar">
        <div class="d-flex flex-column h-100 justify-content-between">
            <div>
                <!-- Header Sidebar (Judul + Garis 3) -->
                <div class="sidebar-header">
                    <a href="{{ route('customer.beranda') }}" class="sidebar-brand text-decoration-none d-flex align-items-center gap-2">
                        <img src="{{ asset('logo.png') }}" alt="Assalam Mebel" style="max-height: 42px; width: auto; object-fit: contain;">
                    </a>
                    <!-- Tombol Garis 3 untuk Menutup -->
                    <button class="btn-toggle-sidebar" id="closeBtn" title="Tutup Sidebar">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
                </div>

                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('customer.beranda') }}" class="{{ request()->routeIs('customer.beranda') || request()->is('/') ? 'active' : '' }}">
                            <i class="fa-solid fa-house"></i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customer.katalog') }}" class="{{ request()->routeIs('customer.katalog') ? 'active' : '' }}">
                            <i class="fa-solid fa-basket-shopping"></i>
                            <span>Katalog Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customer.design') }}" class="{{ request()->routeIs('customer.design') ? 'active' : '' }}">
                            <i class="fa-solid fa-pen-ruler"></i>
                            <span>Your Design 🎨</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customer.cart') }}" class="{{ request()->routeIs('customer.cart') ? 'active' : '' }}">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span>Keranjang Belanja</span>
                            @php $cartCount = count(session('cart', [])); @endphp
                            @if($cartCount > 0)
                                <span class="badge rounded-pill bg-danger ms-auto">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="px-3 py-2">
                        <hr class="my-1" style="border-color: var(--light-border);">
                    </li>

                    @auth
                        <li>
                            <a href="{{ route('customer.progress') }}" class="{{ request()->routeIs('customer.progress') ? 'active' : '' }}">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Progres Pesanan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.riwayat') }}" class="{{ request()->routeIs('customer.riwayat') ? 'active' : '' }}">
                                <i class="fa-solid fa-file-invoice"></i>
                                <span>Riwayat Pesanan</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('customer.account') }}" class="{{ request()->routeIs('customer.account') ? 'active' : '' }}">
                                <i class="fa-solid fa-user"></i>
                                <span>Akun Saya</span>
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">
                                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                <span>Masuk / Login</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>Daftar Akun Baru</span>
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>

            <!-- Footer User Sidebar -->
            <div class="p-3 border-top" style="border-color: var(--light-border) !important; background-color: var(--light-bg);">
                @auth
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 36px; height: 36px; background-color: var(--primary-color);">
                                <i class="fa-solid fa-user small"></i>
                            </div>
                            <div class="text-truncate">
                                <span class="d-block fw-bold small text-dark text-truncate">{{ Auth::user()->name }}</span>
                                <small class="text-muted d-block text-truncate" style="font-size: 0.72rem;">{{ Auth::user()->email }}</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <span class="small text-muted d-block mb-2">Belum memiliki akun?</span>
                        <a href="{{ route('register') }}" class="btn btn-sm btn-dark w-100 rounded-3 fw-bold" style="background-color: var(--primary-color); border: none;">
                            Daftar Sekarang
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-wrapper" id="mainWrapper">
        <div class="top-navbar">
            <!-- Tombol Garis 3 Buka Sidebar -->
            <button class="btn-open-sidebar" id="openBtn" title="Buka Sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- KOTAK PENCARIAN -->
            <div class="search-bar d-none d-sm-block">
                <form action="{{ route('customer.katalog') }}" method="GET">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="keyword" class="form-control" placeholder="Cari model mebel impian..." value="{{ request('keyword') }}">
                </form>
            </div>

            <!-- Icon Kanan Topbar (Keranjang + Auth / Profile) -->
            <div class="nav-actions">
                @php $cartCount = count(session('cart', [])); @endphp
                <a href="{{ route('customer.cart') }}" class="cart-badge-icon" title="Keranjang Belanja">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if($cartCount > 0)
                        <span class="cart-badge-count">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <a href="{{ route('customer.account') }}" class="d-flex align-items-center gap-2 text-decoration-none" title="Akun Saya">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 38px; height: 38px; background-color: var(--primary-color);">
                            <i class="fa-solid fa-user small"></i>
                        </div>
                        <span class="fw-bold small d-none d-md-inline text-dark">{{ Auth::user()->name }}</span>
                    </a>
                @else
                    <div class="d-flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-dark btn-auth-nav">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-dark btn-auth-nav d-none d-sm-inline" style="background-color: var(--primary-color); border: none;">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="px-4 pt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="p-3 p-md-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Slide Sidebar & Mobile Drawer -->
    <script>
        const closeBtn = document.getElementById('closeBtn');
        const openBtn = document.getElementById('openBtn');
        const sidebar = document.getElementById('mySidebar');
        const mainWrapper = document.getElementById('mainWrapper');
        const backdrop = document.getElementById('sidebarBackdrop');

        closeBtn.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show-mobile');
                backdrop.classList.remove('active');
            } else {
                sidebar.classList.add('hide-sidebar');
                mainWrapper.classList.add('full-width');
            }
        });

        openBtn.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('show-mobile');
                backdrop.classList.add('active');
            } else {
                sidebar.classList.remove('hide-sidebar');
                mainWrapper.classList.remove('full-width');
            }
        });

        if (backdrop) {
            backdrop.addEventListener('click', function() {
                sidebar.classList.remove('show-mobile');
                backdrop.classList.remove('active');
            });
        }
    </script>
    
</body>
</html>