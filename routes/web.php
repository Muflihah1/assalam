<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudioSettingController;
use App\Models\StudioSetting;
use App\Models\Produk;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Halaman Utama (Langsung ke login)
Route::get('/', function () {
    return view('auth.login');
});

// 2. Autentikasi Pelanggan (Publik / Tamu)
// Ini artinya: Kalau admin buka link /admin/pelanggan, panggil si CustomerController
Route::get('/admin/pelanggan', [CustomerController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Login Admin
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');


// 3. Rute Pelanggan (Memerlukan Login)
Route::middleware(['auth'])->group(function () {
    
   Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    // Rute ini akan menjadi: /admin/dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});

    // Group khusus Customer
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/beranda', function () { return view('customer.beranda'); })->name('beranda');
        Route::get('/katalog', function () { return view('customer.katalog'); })->name('katalog');
        
        // Halaman Design Studio (Sudah terhubung database untuk ambil data settings)
        Route::get('/design', function () {
            $settings = StudioSetting::pluck('value', 'key');
            return view('customer.design', compact('settings'));
        })->name('design');

        Route::get('/progress', function () { return view('customer.progress'); })->name('progress');
        Route::get('/riwayat', function () { return view('customer.riwayat'); })->name('riwayat');
        Route::get('/account', function () { return view('customer.account'); })->name('account');
        Route::get('/cart', function () { return view('customer.cart'); })->name('cart');
    });

});



Route::middleware(['auth'])->group(function () {
    Route::prefix('customer')->name('customer.')->group(function () {
        // Ubah rute katalog menjadi memanggil data database
        Route::get('/katalog', function () {
            $katalogs = Produk::latest()->get(); // Ambil semua data produk, urutkan dari yang terbaru
            return view('customer.katalog', compact('katalogs'));
        })->name('katalog');
    });
});


// 4. Rute Admin (Memerlukan Login & Hak Akses Admin)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard & Manajemen Studio Settings
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::get('/studio-settings', [StudioSettingController::class, 'index'])->name('studio.index');
    Route::post('/studio-settings', [StudioSettingController::class, 'update'])->name('studio.update');

    // Rute Katalog (CRUD Lengkap)
    Route::get('/katalog', [AdminController::class, 'katalog'])->name('katalog');
    Route::post('/katalog', [AdminController::class, 'storeKatalog'])->name('katalog.store');
    Route::put('/katalog/{id}', [AdminController::class, 'updateKatalog'])->name('katalog.update');
    Route::delete('/katalog/hapus/{id}', [AdminController::class, 'destroyKatalog'])->name('katalog.destroy');

    Route::get('/pesanan-masuk', [AdminController::class, 'pesananMasuk'])->name('pesanan.masuk');
    
    // Progres Produksi
    Route::get('/progres-produksi/{id?}', [AdminController::class, 'progresProduksi'])->name('progres.produksi');
    Route::put('/progres-produksi/{id}', [AdminController::class, 'updateProgres'])->name('progres.update');

    Route::get('/data-pelanggan', [AdminController::class, 'dataPelanggan'])->name('data.pelanggan');
    Route::get('/riwayat', [AdminController::class, 'riwayat'])->name('riwayat');

    // Pengaturan Admin
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/profil', [SettingController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::post('/pengaturan/whatsapp', [SettingController::class, 'updateWhatsapp'])->name('pengaturan.whatsapp');
    Route::post('/pengaturan/shipping', [SettingController::class, 'storeShipping'])->name('pengaturan.shipping.store');
    Route::put('/pengaturan/shipping/{id}', [SettingController::class, 'updateShipping'])->name('pengaturan.shipping.update');
});