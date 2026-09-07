<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\Order;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PaymentAndCustomRedesignTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
    }

    public function test_admin_can_update_dana_payment_settings_and_qr()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_test_' . uniqid() . '@assalam.test',
        ]);

        $fakeQr = UploadedFile::fake()->image('my_custom_dana_qr.png', 400, 400);

        $response = $this->actingAs($admin)
            ->from(route('admin.pengaturan', ['tab' => 'payment']))
            ->post(route('admin.pengaturan.payment'), [
                'payment_dana_status' => 'Aktif',
                'payment_dana_number' => '081299887766',
                'payment_dana_name' => 'Assalam Official Store',
                'payment_dana_instructions' => 'Instruksi pembayaran via DANA official',
                'payment_dana_qr' => $fakeQr,
            ]);

        $response->assertRedirect(route('admin.pengaturan', ['tab' => 'payment']));
        $response->assertSessionHas('success');

        $this->assertEquals('081299887766', Setting::get('payment_dana_number'));
        $this->assertEquals('Assalam Official Store', Setting::get('payment_dana_name'));
        $this->assertNotNull(Setting::get('payment_dana_qr'));
        $this->assertStringContainsString('storage/payment_qr/', Setting::getDanaQrUrl());
    }

    public function test_customer_can_submit_custom_order_locked_to_kayu_jati()
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer_custom_' . uniqid() . '@assalam.test',
        ]);

        $product = Produk::first() ?? Produk::create([
            'nama' => 'Kursi Bale Bale Jati',
            'harga' => 3000000,
            'stok' => 5,
            'foto' => 'kursi.jpg',
            'deskripsi' => 'Kursi bale kayu jati',
        ]);

        $response = $this->actingAs($customer)->post(route('customer.design.order'), [
            'product_id' => $product->id,
            'category' => 'Kursi & Sofa',
            'length_cm' => 200,
            'width_cm' => 100,
            'height_cm' => 75,
            'wood_material' => 'Kayu Jati Solid Grade A',
            'color_name' => 'Natural Teak Doff',
            'color_hex' => '#c29b38',
            'tone_percent' => 100,
            'notes' => 'Mohon pengerjaan halus dan presisi',
        ]);

        $response->assertRedirect(route('customer.progress'));
        $response->assertSessionHas('success');

        $order = Order::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('dana', $order->payment_method);
        $this->assertEquals(round($order->total_price * 0.5), $order->dp_amount);
        $this->assertEquals($order->total_price - $order->dp_amount, $order->remaining_payment);
        $this->assertNotNull($order->customDesign);
        $this->assertEquals('Kayu Jati Solid Grade A (Perhutani)', $order->customDesign->wood_material);
        $this->assertEquals(200, $order->customDesign->length_cm);
        $this->assertEquals(100, $order->customDesign->width_cm);
        $this->assertEquals(75, $order->customDesign->height_cm);
    }

    public function test_customer_account_and_riwayat_pages_render_cleanly()
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer_pages_' . uniqid() . '@assalam.test',
        ]);

        $respAccount = $this->actingAs($customer)->get(route('customer.account'));
        $respAccount->assertStatus(200);
        $respAccount->assertSee('Kelola identitas akun');

        $respRiwayat = $this->actingAs($customer)->get(route('customer.riwayat'));
        $respRiwayat->assertStatus(200);
        $respRiwayat->assertSee('Lacak status verifikasi DP');
    }

    public function test_admin_katalog_and_riwayat_pages_render_with_tables_and_actions()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_pages_' . uniqid() . '@assalam.test',
        ]);

        $product = Produk::create([
            'nama' => 'Pintu Tarung Jati Jepara',
            'harga' => 8500000,
            'stok' => 2,
            'foto' => 'katalog/test_pintu.jpg',
            'deskripsi' => 'Pintu ukir jati mewah',
        ]);

        $respKatalog = $this->actingAs($admin)->get(route('admin.katalog'));
        $respKatalog->assertStatus(200);
        $respKatalog->assertSee('MANAJEMEN KATALOG PRODUK');
        $respKatalog->assertSee('Pintu Tarung Jati Jepara');
        $respKatalog->assertSee('action-dots-btn');

        $respPesanan = $this->actingAs($admin)->get(route('admin.pesanan.masuk'));
        $respPesanan->assertStatus(200);
        $respPesanan->assertSee('PESANAN MASUK');
        $respPesanan->assertSee('action-dots-btn');

        $respRiwayat = $this->actingAs($admin)->get(route('admin.riwayat'));
        $respRiwayat->assertStatus(200);
        $respRiwayat->assertSee('RIWAYAT SEMUA TRANSAKSI');
        $respRiwayat->assertSee('action-dots-btn');
    }

    public function test_admin_navigation_and_logout_and_custom_notes_prominence()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_nav_' . uniqid() . '@assalam.test',
            'name' => 'Super Admin Test',
        ]);

        $customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer_note_' . uniqid() . '@assalam.test',
            'name' => 'Budi Setiawan',
        ]);

        // 1. Verify admin layout has navigation & logout
        $respAdminLayout = $this->actingAs($admin)->get(route('admin.dashboard'));
        $respAdminLayout->assertStatus(200);
        $respAdminLayout->assertSee('Pesanan Masuk');
        $respAdminLayout->assertSee('Pengaturan');
        $respAdminLayout->assertSee('Keluar / Logout');
        $respAdminLayout->assertSee('Keluar');

        // 2. Verify customer layout shows Panel Admin button & logout when admin visits customer store
        $respCustomerLayoutAsAdmin = $this->actingAs($admin)->get(route('customer.beranda'));
        $respCustomerLayoutAsAdmin->assertStatus(200);
        $respCustomerLayoutAsAdmin->assertSee('Panel Admin');
        $respCustomerLayoutAsAdmin->assertSee('Pengaturan Toko');
        $respCustomerLayoutAsAdmin->assertSee('Keluar / Logout');

        // 3. Test product default_dimensions parsing
        $prodDoor = Produk::create([
            'nama' => 'Pintu Gebyok Jati Ukir',
            'harga' => 12000000,
            'stok' => 2,
            'foto' => 'katalog/test_gebyok.jpg',
            'deskripsi' => 'Ukuran P250xL130xT4 cm ukiran antik',
        ]);
        $this->assertEquals(250, $prodDoor->default_dimensions['length']);
        $this->assertEquals(130, $prodDoor->default_dimensions['width']);
        $this->assertEquals(4, $prodDoor->default_dimensions['height']);

        // 4. Test studio custom page loads product dimensions
        $respStudio = $this->actingAs($customer)->get(route('customer.design', ['product_id' => $prodDoor->id]));
        $respStudio->assertStatus(200);
        $respStudio->assertSee('value="250"', false);
        $respStudio->assertSee('value="130"', false);
        $respStudio->assertSee('value="4"', false);
        $respStudio->assertSee('Catatan');

        // 5. Submit custom order with special notes
        $specialNoteText = 'Tolong ukiran kaligrafi pada bagian atas pintu dihaluskan ekstra presisi!';
        $respOrder = $this->actingAs($customer)->post(route('customer.design.order'), [
            'product_id' => $prodDoor->id,
            'category' => 'Pintu & Kusen',
            'length_cm' => 250,
            'width_cm' => 130,
            'height_cm' => 4,
            'wood_material' => 'Kayu Jati Solid Grade A',
            'color_name' => 'Natural Teak Doff',
            'color_hex' => '#c29b38',
            'tone_percent' => 100,
            'notes' => $specialNoteText,
        ]);
        $respOrder->assertRedirect(route('customer.progress'));

        $order = Order::where('user_id', $customer->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals($specialNoteText, $order->customer_notes);

        // 6. Verify admin pesanan masuk page shows special note prominently
        $respPesananMasuk = $this->actingAs($admin)->get(route('admin.pesanan.masuk'));
        $respPesananMasuk->assertStatus(200);
        $respPesananMasuk->assertSee($specialNoteText);
        $respPesananMasuk->assertSee('CATATAN KHUSUS PELANGGAN');
    }
}

