<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\OrderManagementController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudioSettingController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\AccountController as CustomerAccountController;
use App\Http\Controllers\Admin\WhatsAppGatewayController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Customer\ReviewController;
use App\Models\Produk;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. HALAMAN UTAMA PUBLIK (Bebas Akses Tanpa Login)
Route::get('/', function () {
    $produks = Produk::orderBy('id', 'asc')->get();
    $reviews = \App\Models\ProductReview::with(['user', 'produk'])->where('status', 'Disetujui')->latest()->take(6)->get();
    return view('customer.beranda', compact('produks', 'reviews'));
})->name('beranda');

Route::get('/beranda', function () {
    $produks = Produk::orderBy('id', 'asc')->get();
    $reviews = \App\Models\ProductReview::with(['user', 'produk'])->where('status', 'Disetujui')->latest()->take(6)->get();
    return view('customer.beranda', compact('produks', 'reviews'));
})->name('customer.beranda');

Route::get('/katalog', function (\Illuminate\Http\Request $request) {
    $query = Produk::query();
    $rawKeyword = $request->input('keyword', $request->input('q', $request->input('kategori', $request->input('category', ''))));
    $keyword = trim($rawKeyword);

    if ($keyword !== '') {
        $terms = array_filter(preg_split('/\s+/', $keyword));
        $query->where(function ($sub) use ($terms, $keyword) {
            // Cocokkan frasa penuh langsung pada nama atau deskripsi
            $sub->where('nama', 'like', "%{$keyword}%")
                ->orWhere('deskripsi', 'like', "%{$keyword}%");

            // Atau cocokkan semua token kata kunci (misal: "meja jati", "kursi santai", "lemari minimalis")
            $sub->orWhere(function ($allTermsQuery) use ($terms) {
                foreach ($terms as $term) {
                    $allTermsQuery->where(function ($termQ) use ($term) {
                        $termQ->where('nama', 'like', "%{$term}%")
                              ->orWhere('deskripsi', 'like', "%{$term}%");
                        if (is_numeric($term)) {
                            $termQ->orWhere('harga', 'like', "%{$term}%");
                        }
                    });
                }
            });
        });
    }

    $sort = $request->input('sort', 'latest');
    if ($sort === 'price_asc') {
        $query->orderBy('harga', 'asc');
    } elseif ($sort === 'price_desc') {
        $query->orderBy('harga', 'desc');
    } elseif ($sort === 'name_asc') {
        $query->orderBy('nama', 'asc');
    } else {
        $query->latest();
    }

    $katalogs = $query->get();
    return view('customer.katalog', compact('katalogs', 'keyword', 'sort'));
})->name('customer.katalog');

// Studio Desain Interaktif (Bebas Dieksplorasi Tamu/Publik)
Route::get('/design', [CustomerOrderController::class, 'design'])->name('customer.design');

// Halaman Workshop & Batas Wilayah Pengiriman Se-Madura (Publik)
Route::get('/workshop', function () {
    return view('customer.workshop');
})->name('customer.workshop');

// Keranjang Belanja Publik (Tersimpan di Session)
// Aksi transaksi dilindungi middleware 'customer' agar AKUN ADMIN tidak bisa bertransaksi
Route::get('/cart', [CartController::class, 'index'])->name('customer.cart');
Route::middleware(['customer'])->group(function () {
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('customer.cart.add');
    Route::post('/cart/update/{key}', [CartController::class, 'update'])->name('customer.cart.update');
    Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('customer.cart.remove');
});


// DETAIL PRODUK + ULASAN (Publik: tamu dapat membaca ulasan)
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('customer.produk.detail');

// 2. AUTENTIKASI & REGISTRASI
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Lupa Password via OTP WhatsApp
Route::get('/forgot-password', [ForgotPasswordController::class, 'showRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.send_otp');

Route::get('/verify-otp', [ForgotPasswordController::class, 'showVerifyOtpForm'])->name('password.verify_otp_view');
Route::post('/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.verify_otp');
Route::post('/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend_otp');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset_view');
Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');

// Rute Login Tunggal Terpadu (Mengalihkan /admin/login ke /login)
Route::redirect('/admin/login', '/login')->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'authenticate'])->name('admin.login.submit');


// 3. TRANSAKSI & AREA KHUSUS PELANGGAN (Wajib Login & Bukan Akun Admin)
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    
    // Checkout Keranjang Belanja
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Pengajuan Pesanan Custom
    Route::post('/design/order', [CustomerOrderController::class, 'store'])->name('design.order');

    // Pelacakan Progres & Riwayat Pesanan
    Route::get('/progress', [CustomerOrderController::class, 'progress'])->name('progress');
    Route::post('/progress/{id}/upload-dp', [CustomerOrderController::class, 'uploadDP'])->name('progress.upload_dp');
    Route::post('/progress/{id}/pay-remaining', [CustomerOrderController::class, 'payRemaining'])->name('progress.pay_remaining');
    Route::post('/progress/{id}/confirm-completed', [CustomerOrderController::class, 'confirmCompleted'])->name('progress.confirm_completed');
    Route::post('/progress/{id}/cancel', [CustomerOrderController::class, 'cancelOrder'])->name('progress.cancel');

    Route::get('/riwayat', [CustomerOrderController::class, 'riwayat'])->name('riwayat');

    // Akun Saya
    Route::get('/account', [CustomerAccountController::class, 'index'])->name('account');
    Route::post('/account/profile', [CustomerAccountController::class, 'updateProfile'])->name('account.profile');
    Route::post('/account/password', [CustomerAccountController::class, 'updatePassword'])->name('account.password');

    // Ulasan Produk (Komentar & Rating — khusus akun pelanggan)
    Route::post('/produk/{id}/review', [ReviewController::class, 'store'])->name('produk.review.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('produk.review.destroy');
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

    // Manajemen Pesanan Masuk & Verifikasi DP / Konfirmasi
    Route::get('/pesanan-masuk', [OrderManagementController::class, 'pesananMasuk'])->name('pesanan.masuk');
    Route::post('/pesanan-masuk/{id}/confirm', [OrderManagementController::class, 'confirmOrder'])->name('pesanan.confirm');
    Route::post('/pesanan-masuk/{id}/reject', [OrderManagementController::class, 'rejectOrder'])->name('pesanan.reject');
    Route::post('/pesanan-masuk/{id}/verify-dp', [OrderManagementController::class, 'verifyDP'])->name('pesanan.verify_dp');
    Route::post('/pesanan-masuk/{id}/reject-dp', [OrderManagementController::class, 'rejectDP'])->name('pesanan.reject_dp');
    Route::post('/pesanan-masuk/{id}/verify-pelunasan', [OrderManagementController::class, 'verifyPelunasan'])->name('pesanan.verify_pelunasan');
    Route::post('/pesanan-masuk/{id}/reject-pelunasan', [OrderManagementController::class, 'rejectPelunasan'])->name('pesanan.reject_pelunasan');
    
    // Manajemen Progres Produksi
    Route::get('/progres-produksi/{id?}', [OrderManagementController::class, 'progresProduksi'])->name('progres.produksi');
    Route::put('/progres-produksi/{id}', [OrderManagementController::class, 'updateProgres'])->name('progres.update');

    // Moderasi Ulasan Produk
    Route::get('/ulasan', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('ulasan');
    Route::post('/ulasan/{id}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('ulasan.approve');
    Route::post('/ulasan/{id}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('ulasan.reject');
    Route::delete('/ulasan/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('ulasan.destroy');

    // Data Akun Pelanggan & Riwayat
    Route::get('/data-pelanggan', [AdminCustomerController::class, 'index'])->name('data.pelanggan');
    Route::get('/riwayat', [OrderManagementController::class, 'riwayat'])->name('riwayat');
    Route::delete('/riwayat/{id}', [OrderManagementController::class, 'destroyRiwayat'])->name('riwayat.destroy');

    // Pengaturan Admin (Profil, Gateway WhatsApp, Ongkir)
    Route::get('/pengaturan', [SettingController::class, 'index'])->name('pengaturan');
    Route::post('/pengaturan/profil', [SettingController::class, 'updateProfile'])->name('pengaturan.profile');
    Route::post('/pengaturan/password', [SettingController::class, 'updatePassword'])->name('pengaturan.password');
    Route::post('/pengaturan/whatsapp', [SettingController::class, 'updateWhatsapp'])->name('pengaturan.whatsapp');
    Route::post('/pengaturan/payment', [SettingController::class, 'updatePayment'])->name('pengaturan.payment');
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
    Route::post('/whatsapp/disconnect', [WhatsAppGatewayController::class, 'disconnect'])->name('whatsapp.disconnect');
    Route::post('/whatsapp/restart', [WhatsAppGatewayController::class, 'restartSidecar'])->name('whatsapp.restart');
    Route::post('/whatsapp/send-test', [WhatsAppGatewayController::class, 'sendTestMessage'])->name('whatsapp.send_test');
});