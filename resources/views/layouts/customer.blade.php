<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assalam Mebel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --sidebar-width: 260px;
        --transition-smooth: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
        
        /* Tema Latte / Warm Wood (Sangat Adem di Mata, Tanpa Putih Silau) */
        --light-bg: #f2eee9;          /* Background abu-abu cream hangat */
        --light-card: #fdfcfb;        /* Kartu off-white sangat lembut (bukan putih murni) */
        --light-border: #dcd4cc;      /* Garis batas lebih natural */
        --primary-color: #5d4037;     /* Cokelat Mebel */
        --accent-orange: #d77a61;     /* Terracotta / Oranye Hangat */
        --text-main: #2c221e;         /* Teks cokelat tua pekat yang nyaman dibaca */
        --text-muted: #796d66;        /* Teks redup senada */
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        border-right: 1px solid var(--light-border);
        z-index: 1050;
        transition: var(--transition-smooth);
        box-shadow: 5px 0 20px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
    }

    /* HEADER SIDEBAR */
    .sidebar-header {
        padding: 15px 20px;
        border-bottom: 1px solid var(--light-border);
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

    /* Tombol Garis 3 di dalam Sidebar */
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
        padding: 20px 0;
        margin: 0;
    }

    .sidebar-menu li a {
        display: flex;
        align-items: center;
        padding: 14px 24px;
        color: var(--text-main);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        transition: var(--transition-smooth);
    }

    .sidebar-menu li a i {
        font-size: 1.25rem;
        width: 38px;
        color: var(--text-muted);
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .sidebar-menu li a:hover {
        background-color: rgba(93, 64, 55, 0.08);
        color: var(--primary-color);
        padding-left: 28px;
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
        padding: 15px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        background: var(--light-card) !important;
        height: 72px;
        border-bottom: 1px solid var(--light-border);
    }

    /* Tombol Buka jika Sidebar Tersembunyi sepenuhnya */
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
        border: 1px solid var(--light-border);
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
        background-color: var(--light-card) !important;
        color: var(--text-main);
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
        gap: 20px;
    }

    .nav-action-icon {
        font-size: 1.6rem;
        color: var(--text-main);
        text-decoration: none;
        transition: transform 0.3s ease, color 0.3s ease;
        position: relative;
    }

    .nav-action-icon:hover {
        transform: scale(1.15);
        color: var(--primary-color);
    }
</style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar" id="mySidebar">
        <div>
            <!-- Header Sidebar (Judul + Garis 3) -->
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    ASSALAM<br>MEBEL
                </div>
                <!-- Tombol Garis 3 untuk Menutup -->
                <button class="btn-toggle-sidebar" id="closeBtn" title="Tutup Sidebar">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('customer.beranda') }}" class="{{ request()->routeIs('customer.beranda') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i>
                        <span>Beranda</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.katalog') }}" class="{{ request()->routeIs('customer.katalog') ? 'active' : '' }}">
                        <i class="fa-solid fa-basket-shopping"></i>
                        <span>Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.design') }}" class="{{ request()->routeIs('customer.design') ? 'active' : '' }}">
                        <i class="fa-solid fa-pen-ruler"></i>
                        <span>your Design</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.progress') }}" class="{{ request()->routeIs('customer.progress') ? 'active' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span>Progress</span>
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
                        <span>My Account</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-wrapper" id="mainWrapper">
        <div class="top-navbar">
            <!-- Tombol Garis 3 Pengganti (Hanya muncul saat sidebar tertutup rapat) -->
            <button class="btn-open-sidebar" id="openBtn" title="Buka Sidebar">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- KOTAK PENCARIAN (Hanya tampil di Beranda & Katalog) -->
            @if(request()->routeIs('customer.beranda') || request()->routeIs('customer.katalog'))
                <div class="search-bar">
                    <form action="{{ route('customer.katalog') }}" method="GET">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="keyword" class="form-control" placeholder="Cari mebel..." value="{{ request('keyword') }}">
                    </form>
                </div>
            @endif

            <!-- Icon Kanan Topbar (Keranjang + Account) -->
            <div class="nav-actions">
                <a href="{{ Route::has('customer.cart') ? route('customer.cart') : '#' }}" class="nav-action-icon" title="Keranjang">
                    <i class="fa-solid fa-cart-shopping"></i>
                </a>
                <a href="{{ route('customer.account') }}" class="nav-action-icon" title="Akun Saya">
                    <i class="fa-solid fa-circle-user"></i>
                </a>
            </div>
        </div>

        <hr class="m-0" style="border-top: 1px solid var(--light-border);">

        <div class="p-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Slide Sidebar -->
    <script>
        const closeBtn = document.getElementById('closeBtn');
        const openBtn = document.getElementById('openBtn');
        const sidebar = document.getElementById('mySidebar');
        const mainWrapper = document.getElementById('mainWrapper');

        // Fungsi Tutup
        closeBtn.addEventListener('click', function() {
            sidebar.classList.add('hide-sidebar');
            mainWrapper.classList.add('full-width');
        });

        // Fungsi Buka Kembali
        openBtn.addEventListener('click', function() {
            sidebar.classList.remove('hide-sidebar');
            mainWrapper.classList.remove('full-width');
        });
    </script>
    
</body>
</html>