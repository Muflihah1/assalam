<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ASSALAM MEBEL</title>
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
            --primary-color: #5d4037;     /* Cokelat Mebel Kayu */
            --primary-dark: #3e2723;      /* Deep Mahogany */
            --secondary-color: #8d6e63;   /* Cokelat Sedang */
            --accent-orange: #d77a61;     /* Terracotta */
            --accent-gold: #d97706;       /* Amber Gold */
            --light-bg: #faf6f0;          /* Warm Alabaster */
            --light-card: #ffffff;        /* Pure White */
            --light-border: #dcd4cc;      /* Soft Natural Border */
            --text-dark: #2c221e;         /* Espresso Dark */
            --text-muted: #796d66;        /* Taupe Muted */
            --sidebar-width: 260px;
        }

        body { 
            background-color: var(--light-bg); 
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .admin-sidebar { 
            width: var(--sidebar-width); 
            min-height: 100vh; 
            background: #ffffff; 
            border-right: 1.5px solid var(--light-border); 
            position: fixed; 
            top: 0; left: 0;
            z-index: 1050; 
            box-shadow: 4px 0 20px rgba(93, 64, 55, 0.03);
            transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .main-content { 
            margin-left: var(--sidebar-width); 
            padding: 20px 30px; 
            transition: all 0.35s ease;
        }

        .brand-box { 
            border-bottom: 1.5px solid var(--light-border); 
            padding: 20px; 
            text-align: center; 
            background-color: #ffffff;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--primary-color);
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .nav-link { 
            color: var(--text-dark); 
            font-weight: 600; 
            padding: 13px 20px; 
            border-radius: 12px;
            margin: 4px 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            color: var(--text-muted);
            transition: color 0.2s;
        }

        .nav-link:hover { 
            background: rgba(93, 64, 55, 0.08); 
            color: var(--primary-color);
            transform: translateX(4px);
        }

        .nav-link:hover i {
            color: var(--primary-color);
        }

        .nav-link.active { 
            background: var(--primary-color); 
            color: #ffffff !important;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(93, 64, 55, 0.25);
        }

        .nav-link.active i {
            color: #ffffff;
        }

        .interactive-icon {
            cursor: pointer;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .interactive-icon:hover {
            transform: scale(1.08);
            color: var(--primary-color) !important;
        }

        .top-navbar-admin {
            background-color: var(--light-card);
            border: 1.5px solid var(--light-border);
            border-radius: 16px;
            padding: 12px 24px;
            box-shadow: 0 4px 15px rgba(93, 64, 55, 0.04);
            margin-bottom: 24px;
        }

        /* Mobile Backdrop */
        .admin-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1040;
        }

        .btn-mobile-toggle {
            display: none;
            background: #ffffff;
            border: 1.5px solid var(--light-border);
            border-radius: 8px;
            padding: 6px 12px;
            color: var(--primary-color);
        }

        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show-mobile {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .btn-mobile-toggle {
                display: block;
            }
            .admin-backdrop.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- BACKDROP MOBILE -->
    <div class="admin-backdrop" id="adminBackdrop"></div>

    <!-- SIDEBAR ADMIN -->
    <div class="admin-sidebar" id="adminSidebar">
        <div class="brand-box d-flex align-items-center justify-content-between">
            <div class="brand-title text-start">
                ASSALAM<br>MEBEL ADMIN
            </div>
            <button class="btn btn-sm btn-light border d-lg-none" id="closeAdminSidebar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.katalog') }}" class="nav-link {{ request()->routeIs('admin.katalog*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-open"></i> Katalog Produk
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.pesanan.masuk') }}" class="nav-link {{ request()->routeIs('admin.pesanan.masuk*') ? 'active' : '' }}">
                    <i class="fa-solid fa-cart-arrow-down"></i> Pesanan Masuk
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.progres.produksi') }}" class="nav-link {{ request()->routeIs('admin.progres.produksi*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gears"></i> Progres Produksi
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.data.pelanggan') }}" class="nav-link {{ request()->routeIs('admin.data.pelanggan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Akun Pelanggan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.riwayat') }}" class="nav-link {{ request()->routeIs('admin.riwayat*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.pengaturan') }}" class="nav-link {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i> Pengaturan
                </a>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="d-flex justify-content-between align-items-center top-navbar-admin">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-mobile-toggle" id="openAdminSidebar" title="Buka Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="d-none d-sm-block">
                    <span class="badge px-3 py-1.5 rounded-pill fw-bold" style="background-color: rgba(93, 64, 55, 0.1); color: var(--primary-color);">
                        <i class="fa-solid fa-shield-halved me-1"></i> Control Panel Assalam Mebel
                    </span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Ikon Profil Admin -->
                <div class="d-flex align-items-center gap-2 interactive-icon" data-bs-toggle="modal" data-bs-target="#modalProfilAdmin" title="Lihat Profil Admin">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 38px; height: 38px; background-color: var(--primary-color);">
                        <i class="fa-solid fa-user-shield fs-6"></i>
                    </div>
                    <span class="fw-bold small d-none d-md-inline" style="color: var(--text-dark);">{{ Auth::user()->name ?? 'Administrator' }}</span>
                </div>

                <!-- Ikon Logout -->
                <div class="interactive-icon text-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiLogout" title="Keluar Sistem">
                    <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fa-solid fa-power-off fs-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
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

        <!-- Konten Halaman Dinamis -->
        @yield('content')
    </div>

    <!-- MODAL : PROFIL ADMIN -->
    <div class="modal fade" id="modalProfilAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow border-0" style="background-color: var(--light-card);">
                <div class="modal-header rounded-top-4 text-white" style="background-color: var(--primary-color);">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-user-shield me-1"></i> Informasi Akun Administrator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="border rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 75px; height: 75px; background-color: var(--light-bg); border-color: var(--light-border) !important;">
                        <i class="fa-solid fa-user-tie fa-2x" style="color: var(--primary-color);"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ Auth::user()->name ?? 'Administrator' }}</h5>
                    <p class="small text-muted mb-3">{{ Auth::user()->email ?? 'admin@assalammebel.com' }}</p>
                    
                    <div class="text-start p-3 rounded-3 small" style="background-color: var(--light-bg); border: 1px solid var(--light-border);">
                        <div class="mb-2 d-flex justify-content-between border-bottom pb-1">
                            <span class="text-muted">Hak Akses :</span>
                            <strong class="text-dark">Super Administrator</strong>
                        </div>
                        <div class="mb-2 d-flex justify-content-between border-bottom pb-1">
                            <span class="text-muted">Nomor WhatsApp :</span>
                            <span class="text-dark fw-semibold">{{ Auth::user()->whatsapp_number ?? '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status Akun :</span>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 justify-content-between">
                    <a href="{{ route('admin.pengaturan') }}" class="btn btn-outline-dark btn-sm rounded-3 px-3">
                        <i class="fa fa-pen-to-square me-1"></i> Edit Profil
                    </a>
                    <button type="button" class="btn btn-dark btn-sm rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL : KONFIRMASI LOGOUT -->
    <div class="modal fade" id="modalKonfirmasiLogout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-4 shadow text-center p-3 border-0">
                <div class="modal-body">
                    <div class="text-danger mb-3">
                        <i class="fa-solid fa-power-off fa-3x"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Keluar dari Panel Admin?</h6>
                    <p class="small text-muted mb-4">Anda harus masuk kembali menggunakan kredensial admin untuk mengakses halaman ini.</p>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger btn-sm rounded-3 px-3 fw-bold">Ya, Keluar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT BOOTSTRAP JS & SIDEBAR TOGGLE -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const openBtn = document.getElementById('openAdminSidebar');
        const closeBtn = document.getElementById('closeAdminSidebar');
        const sidebar = document.getElementById('adminSidebar');
        const backdrop = document.getElementById('adminBackdrop');

        if (openBtn) {
            openBtn.addEventListener('click', () => {
                sidebar.classList.add('show-mobile');
                backdrop.classList.add('active');
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                sidebar.classList.remove('show-mobile');
                backdrop.classList.remove('active');
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', () => {
                sidebar.classList.remove('show-mobile');
                backdrop.classList.remove('active');
            });
        }
    </script>
</body>
</html>