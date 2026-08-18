<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - ASSALAM MEBEL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .sidebar { width: 260px; min-height: 100vh; background: #ffffff; border-right: 2px solid #000; position: fixed; z-index: 100; }
        .main-content { margin-left: 260px; padding: 20px; }
        .brand-box { border-bottom: 2px solid #000; padding: 20px; text-align: center; }
        .nav-link { color: #000; font-weight: 500; padding: 12px 20px; }
        .nav-link:hover, .nav-link.active { background: #f1f1f1; font-weight: bold; }
        .interactive-icon {
            cursor: pointer;
            transition: transform 0.15s ease, color 0.15s ease;
        }
        .interactive-icon:hover {
            transform: scale(1.1);
            color: #3e2723 !important;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="brand-box">
            <h4 class="fw-bold mb-0" style="color: #3e2723;">ASSALAM<br>MEBEL</h4>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fa-solid fa-chevron-right me-2"></i> Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('admin.katalog') }}" class="nav-link"><i class="fa-solid fa-chevron-right me-2"></i> Katalog</a></li>
            <li class="nav-item"><a href="{{ route('admin.pesanan.masuk') }}" class="nav-link"><i class="fa-solid fa-chevron-right me-2"></i> Pesanan Masuk</a></li>
            <li class="nav-item"><a href="{{ route('admin.progres.produksi') }}" class="nav-link"><i class="fa-solid fa-chevron-right me-2"></i> Progres Produksi</a></li>
            <li class="nav-item"><a href="{{ route('admin.data.pelanggan') }}" class="nav-link"><i class="fa-solid fa-chevron-right me-2"></i> Data Akun Pelanggan</a></li>
            <li class="nav-item"><a href="{{ route('admin.riwayat') }}" class="nav-link"><i class="fa-solid fa-chevron-right me-2"></i> Riwayat</a></li>
            <li class="nav-item"><a href="{{ route('admin.pengaturan') }}" class="nav-link"><i class="fa-solid fa-chevron-right me-2"></i> Pengaturan</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Top Navbar (Pencarian Dihapus, Ikon Profil & Logout Dibuat Interaktif) -->
        <div class="d-flex justify-content-end align-items-center bg-white p-3 border border-dark rounded-3 shadow-sm mb-4">
            <div class="d-flex align-items-center gap-4">
                <!-- Ikon Profil (Bisa diklik membuka modal detail profil admin) -->
                <div class="d-flex align-items-center gap-2 interactive-icon" data-bs-toggle="modal" data-bs-target="#modalProfilAdmin" title="Lihat Profil Admin">
                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fa-solid fa-user fs-6"></i>
                    </div>
                    <span class="fw-semibold text-dark small d-none d-md-inline">Administrator</span>
                </div>

                <!-- Ikon Logout (Bisa diklik membuka modal konfirmasi keluar) -->
                <div class="interactive-icon text-danger" data-bs-toggle="modal" data-bs-target="#modalKonfirmasiLogout" title="Keluar Sistem">
                    <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                        <i class="fa-solid fa-right-from-bracket fs-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Konten Halaman Dinamis -->
        @yield('content')
    </div>

  <!-- ========================================== -->
    <!-- MODAL : PROFIL ADMIN -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalProfilAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-dark rounded-4 shadow">
                <div class="modal-header bg-dark text-white rounded-top-4">
                    <h5 class="modal-title fs-6 fw-bold"><i class="fa fa-user-shield me-1"></i> Informasi Akun Administrator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="bg-light border border-dark rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-user-tie fa-2x text-dark"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Admin Assalam Mebel</h5>
                    <p class="small text-muted mb-3">admin@assalammebel.com</p>
                    
                    <div class="text-start bg-light p-3 border border-dark rounded-3 small">
                        <div class="mb-2 d-flex justify-content-between border-bottom pb-1">
                            <span class="text-muted">Hak Akses :</span>
                            <strong class="text-dark">Super Administrator</strong>
                        </div>
                        <div class="mb-2 d-flex justify-content-between border-bottom pb-1">
                            <span class="text-muted">Status Akun :</span>
                            <span class="badge bg-success">Aktif</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Login Terakhir :</span>
                            <span class="text-dark fw-semibold">Hari ini, 16:28 WIB</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4 justify-content-between">
                    <!-- Tombol Edit Profil dialihkan langsung ke Halaman Pengaturan (Menu Profil) -->
                    <a href="{{ route('admin.pengaturan') }}" class="btn btn-outline-dark btn-sm rounded-0 px-3 text-decoration-none">
                        <i class="fa fa-pen-to-square me-1"></i> Edit Profil
                    </a>
                    <button type="button" class="btn btn-dark btn-sm rounded-0 px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL : KONFIRMASI LOGOUT -->
    <!-- ========================================== -->
    <div class="modal fade" id="modalKonfirmasiLogout" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-dark rounded-4 shadow text-center p-3">
                <div class="modal-body">
                    <div class="text-danger mb-3">
                        <i class="fa-solid fa-power-off fa-3x"></i>
                    </div>
                    <h6 class="fw-bold mb-2">Keluar dari Sistem?</h6>
                    <p class="small text-muted mb-4">Anda harus masuk kembali menggunakan kredensial admin untuk mengakses halaman ini.</p>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-outline-dark btn-sm rounded-0 px-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger btn-sm rounded-0 px-3">Ya, Keluar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>