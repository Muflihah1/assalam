<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudioSettingController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\AccountController as CustomerAccountController;
use App\Http\Controllers\Admin\WhatsAppGatewayController;
use App\Models\Produk;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HALAMAN UTAMA PUBLIK (Bebas Akses Tanpa Login)
Route::get('/', function () {
    $produks = Produk::latest()->take(6)->get();
    return view('customer.beranda', compact('produks'));
})->name('beranda');

Route::get('/beranda', function () {
    $produks = Produk::latest()->take(6)->get();
    return view('customer.beranda', compact('produks'));
})->name('customer.beranda');

Route::get('/katalog', function () {
    $katalogs = Produk::latest()->get();
    return view('customer.katalog', compact('katalogs'));
})->name('customer.katalog');

// Studio Desain Interaktif (Bebas Dieksplorasi Tamu/Publik)
Route::get('/design', [CustomerOrderController::class, 'design'])->name('customer.design');

// Keranjang Belanja Publik (Tersimpan di Session)
Route::get('/cart', [CartController::class, 'index'])->name('customer.cart');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('customer.cart.add');
Route::post('/cart/update/{key}', [CartController::class, 'update'])->name('customer.cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('customer.cart.remove');


// 2. AUTENTIKASI & REGISTRASI
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Khusus Login Admin
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');


// 3. TRANSAKSI & AREA KHUSUS PELANGGAN (Wajib Login)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    
    // Checkout Keranjang Belanja
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Pengajuan Pesanan Custom
    Route::post('/design/order', [CustomerOrderController::class, 'store'])->name('design.order');

    // Pelacakan Progres & Riwayat Pesanan
    Route::get('/progress', [CustomerOrderController::class, 'progress'])->name('progress');
    Route::post('/progress/{id}/pay-remaining', [CustomerOrderController::class, 'payRemaining'])->name('progress.pay_remaining');
    Route::post('/progress/{id}/confirm-completed', [CustomerOrderController::class, 'confirmCompleted'])->name('progress.confirm_completed');

    Route::get('/riwayat', [CustomerOrderController::class, 'riwayat'])->name('riwayat');

    // Akun Saya
    Route::get('/account', [CustomerAccountController::class, 'index'])->name('account');
    Route::post('/account/profile', [CustomerAccountController::class, 'updateProfile'])->name('account.profile');
    Route::post('/account/password', [CustomerAccountController::class, 'updatePassword'])->name('account.password');
});


// 4. AREA ADMINISTRATOR (Wajib Login & Hak Akses Admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin
    Route::get('/dashboard', [OrderManagementController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Studio Settings
    Route::get('/studio-settings', [StudioSettingController::class, 'index'])->name('studio.index');
    Route::post('/studio-settings', [StudioSettingController::class, 'update'])->name('studio.update');

    // Rute Katalog Produk (CRUD Lengkap)
    Route::get('/katalog', [AdminController::class, 'katalog'])->name('katalog');
    Route::post('/katalog', [AdminController::class, 'storeKatalog'])->name('katalog.store');
    Route::put('/katalog/{id}', [AdminController::class, 'updateKatalog'])->name('katalog.update');
    Route::delete('/katalog/{id}', [AdminController::class, 'destroyKatalog'])->name('katalog.destroy');

    // Manajemen Pesanan Masuk & Verifikasi DP
    Route::get('/pesanan-masuk', [OrderManagementController::class, 'pesananMasuk'])->name('pesanan.masuk');
    Route::post('/pesanan-masuk/{id}/verify-dp', [OrderManagementController::class, 'verifyDP'])->name('pesanan.verify_dp');
    
    // Manajemen Progres Produksi
    Route::get('/progres-produksi/{id?}', [OrderManagementController::class, 'progresProduksi'])->name('progres.produksi');
    Route::put('/progres-produksi/{id}', [OrderManagementController::class, 'updateProgres'])->name('progres.update');

    // Data Akun Pelanggan & Riwayat
    Route::get('/data-pelanggan', [AdminCustomerController::class, 'index'])->name('data.pelanggan');
    Route::get('/riwayat', [OrderManagementController::class, 'riwayat'])->name('riwayat');
    Route::delete('/riwayat/{id}', [OrderManagementController::class, 'destroyRiwayat'])->name('riwayat.destroy');

    // Pengaturan Admin (Profil, Gateway WhatsApp, Ongkir)
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/profil', [SettingController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::post('/pengaturan/whatsapp', [SettingController::class, 'updateWhatsapp'])->name('pengaturan.whatsapp');
    Route::post('/pengaturan/shipping', [SettingController::class, 'storeShipping'])->name('pengaturan.shipping.store');
    Route::put('/pengaturan/shipping/{id}', [SettingController::class, 'updateShipping'])->name('pengaturan.shipping.update');

    // Manajemen WhatsApp Gateway Terpadu (laravel-whatsapp sidecar, QR/Pairing, Templates, Message Logs & Retry)
    Route::get('/whatsapp', [WhatsAppGatewayController::class, 'index'])->name('whatsapp.index');
    Route::get('/whatsapp/status', [WhatsAppGatewayController::class, 'getStatus'])->name('whatsapp.status');
    Route::get('/whatsapp/qr', [WhatsAppGatewayController::class, 'getQrCode'])->name('whatsapp.qr');
    Route::post('/whatsapp/pairing-code', [WhatsAppGatewayController::class, 'requestPairingCode'])->name('whatsapp.pairing');
    Route::put('/whatsapp/templates/{id}', [WhatsAppGatewayController::class, 'updateTemplate'])->name('whatsapp.templates.update');
    Route::post('/whatsapp/logs/{id}/retry', [WhatsAppGatewayController::class, 'retryLog'])->name('whatsapp.logs.retry');
    Route::post('/whatsapp/logs/retry-all', [WhatsAppGatewayController::class, 'retryAllFailed'])->name('whatsapp.logs.retry_all');
    Route::post('/whatsapp/send-test', [WhatsAppGatewayController::class, 'sendTestMessage'])->name('whatsapp.send_test');
});