<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Produk;
use App\Models\OrderProgress;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductReadyVsPreOrderTest extends TestCase
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

    private function createCustomer()
    {
        return User::factory()->create([
            'role' => 'customer',
            'email' => 'cust_' . uniqid() . '@assalam.test',
        ]);
    }

    private function createAdmin()
    {
        return User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_' . uniqid() . '@assalam.test',
        ]);
    }

    public function test_katalog_displays_ready_stock_and_preorder_products_and_filters_correctly()
    {
        $readyProduct = Produk::create([
            'nama' => 'Kursi Ready Stock ' . uniqid(),
            'deskripsi' => 'Kursi kayu jati siap kirim langsung.',
            'harga' => 1500000,
            'foto' => 'produk/test_ready.jpg',
            'tipe_produk' => 'ready',
            'estimasi_po' => null,
        ]);

        $poProduct = Produk::create([
            'nama' => 'Lemari Pre Order ' . uniqid(),
            'deskripsi' => 'Lemari ukir kayu jati diproduksi di bengkel.',
            'harga' => 5000000,
            'foto' => 'produk/test_po.jpg',
            'tipe_produk' => 'pre_order',
            'estimasi_po' => 14,
        ]);

        // 1. Visit catalog all
        $response = $this->get(route('customer.katalog'));
        $response->assertStatus(200);
        $response->assertSee($readyProduct->nama);
        $response->assertSee($poProduct->nama);
        $response->assertSee('Ready Stock');
        $response->assertSee('Pre-Order');

        // 2. Filter ready stock only
        $responseReady = $this->get(route('customer.katalog', ['tipe' => 'ready']));
        $responseReady->assertStatus(200);
        $responseReady->assertSee($readyProduct->nama);
        $responseReady->assertDontSee($poProduct->nama);

        // 3. Filter pre-order only
        $responsePo = $this->get(route('customer.katalog', ['tipe' => 'pre_order']));
        $responsePo->assertStatus(200);
        $responsePo->assertSee($poProduct->nama);
        $responsePo->assertDontSee($readyProduct->nama);
    }

    public function test_admin_katalog_can_create_and_update_ready_stock_and_preorder_product()
    {
        $admin = $this->createAdmin();

        // 1. Admin creates ready stock product
        $image = UploadedFile::fake()->image('mebel_ready.jpg', 600, 600);
        $createResponse = $this->actingAs($admin)->post(route('admin.katalog.store'), [
            'nama' => 'Meja Jati Ready ' . uniqid(),
            'harga' => 2750000,
            'deskripsi' => 'Meja kopi kayu jati finishing natural siap pajang dan kirim.',
            'tipe_produk' => 'ready',
            'foto' => $image,
        ]);
        $createResponse->assertRedirect(route('admin.katalog'));

        $this->assertDatabaseHas('produks', [
            'harga' => 2750000,
            'tipe_produk' => 'ready',
        ]);

        $createdProduct = Produk::where('harga', 2750000)->latest()->first();

        // 2. Admin updates product to pre-order with estimasi_po
        $updateResponse = $this->actingAs($admin)->put(route('admin.katalog.update', $createdProduct->id), [
            'nama' => $createdProduct->nama . ' (PO)',
            'harga' => 3000000,
            'deskripsi' => 'Diubah menjadi pesanan pre-order dengan ukiran custom.',
            'tipe_produk' => 'pre_order',
            'estimasi_po' => 20,
        ]);
        $updateResponse->assertRedirect(route('admin.katalog'));

        $this->assertDatabaseHas('produks', [
            'id' => $createdProduct->id,
            'tipe_produk' => 'pre_order',
            'estimasi_po' => 20,
        ]);
    }

    public function test_checkout_ready_stock_creates_order_with_5_stages_omitting_manufacturing()
    {
        $customer = $this->createCustomer();

        $readyProduct = Produk::create([
            'nama' => 'Cermin Jati Ready ' . uniqid(),
            'deskripsi' => 'Cermin hias kayu jati ready stock.',
            'harga' => 750000,
            'foto' => 'produk/test_cermin.jpg',
            'tipe_produk' => 'ready',
            'estimasi_po' => null,
        ]);

        // Customer adds ready stock item to session cart
        $cart = [
            $readyProduct->id => [
                'id' => $readyProduct->id,
                'nama' => $readyProduct->nama,
                'harga' => 750000,
                'jumlah' => 1,
                'foto' => $readyProduct->foto_url,
                'tipe_produk' => 'ready',
                'estimasi_po' => null,
            ]
        ];

        $checkoutResponse = $this->actingAs($customer)
            ->withSession(['cart' => $cart])
            ->post(route('customer.cart.checkout'), [
                'recipient_name' => 'Budi Siap Kirim',
                'recipient_phone' => '081298765432',
                'shipping_address' => 'Jl. Pahlawan No. 45, Pasuruan',
                'shipping_cost' => 50000,
                'customer_notes' => 'Tolong dicek kaca cerminnya jangan sampai retak.',
                'payment_method' => 'transfer',
            ]);

        $checkoutResponse->assertRedirect();

        // Verify order created has tipe_pesanan = 'ready'
        $order = Order::where('recipient_name', 'Budi Siap Kirim')->latest()->first();
        $this->assertNotNull($order);
        $this->assertEquals('ready', $order->tipe_pesanan);
        $this->assertTrue($order->isReadyStock());

        // Verify Order Items
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'produk_id' => $readyProduct->id,
            'tipe_produk' => 'ready',
        ]);

        // Verify exactly 5 fulfillment stages created
        $stages = $order->progresses()->orderBy('step_number')->get();
        $this->assertCount(5, $stages);

        $stageNames = $stages->pluck('stage_name')->toArray();
        $expectedStages = [
            'Konfirmasi Pesanan',
            'Validasi Pembayaran',
            'Pengemasan Barang',
            'Pengiriman',
            'Pesanan Selesai',
        ];
        $this->assertEquals($expectedStages, $stageNames);

        // Assert workshop manufacturing stages are absent
        $this->assertNotContains('Menyiapkan Bahan', $stageNames);
        $this->assertNotContains('Perakitan', $stageNames);
        $this->assertNotContains('Penyelesaian', $stageNames);
    }

    public function test_customer_progress_page_renders_ready_stock_without_manufacturing_steps()
    {
        $customer = $this->createCustomer();

        $order = Order::create([
            'order_number' => 'ORD-RDY-' . uniqid(),
            'user_id' => $customer->id,
            'tipe_pesanan' => 'ready',
            'total_price' => 1500000,
            'dp_amount' => 1500000,
            'remaining_payment' => 0,
            'order_status' => 'Sedang Diproses',
            'payment_status' => 'Lunas',
            'production_status' => 'Siap Dikemas',
            'current_stage' => 'Pengemasan Barang',
            'recipient_name' => $customer->name,
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 1',
        ]);

        $stages = [
            1 => 'Konfirmasi Pesanan',
            2 => 'Validasi Pembayaran',
            3 => 'Pengemasan Barang',
            4 => 'Pengiriman',
            5 => 'Pesanan Selesai',
        ];

        foreach ($stages as $stepNum => $stageName) {
            OrderProgress::create([
                'order_id' => $order->id,
                'step_number' => $stepNum,
                'stage_name' => $stageName,
                'status' => $stepNum < 3 ? 'Selesai' : ($stepNum === 3 ? 'Sedang Berjalan' : 'Pending'),
                'completed_at' => $stepNum < 3 ? now() : null,
            ]);
        }

        $response = $this->actingAs($customer)->get(route('customer.progress', $order->id));
        $response->assertStatus(200);

        // Must show Ready Stock badge and packaging stage
        $response->assertSee('Ready Stock');
        $response->assertSee('Pengemasan Barang');
        $response->assertSee('ALUR PENGIRIMAN PRODUK READY STOCK');

        // Must NOT show raw workshop manufacturing steps in progress timeline
        $response->assertDontSee('Tahap 4: Menyiapkan Bahan');
        $response->assertDontSee('Tahap 5: Perakitan');
        $response->assertDontSee('Tahap 6: Penyelesaian');
    }

    public function test_preorder_custom_order_retains_all_8_workshop_manufacturing_stages()
    {
        $customer = $this->createCustomer();

        // Custom order created
        $order = Order::create([
            'order_number' => 'ORD-PO-' . uniqid(),
            'user_id' => $customer->id,
            'tipe_pesanan' => 'pre_order',
            'total_price' => 5000000,
            'dp_amount' => 2500000,
            'remaining_payment' => 2500000,
            'order_status' => 'Sedang Diproses',
            'payment_status' => 'DP Terverifikasi',
            'production_status' => 'Dalam Pengerjaan',
            'current_stage' => 'Perakitan',
            'recipient_name' => $customer->name,
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Ukir Jepara No. 12',
        ]);

        $stages = [
            1 => 'Konfirmasi Pesanan',
            2 => 'Validasi Pembayaran',
            3 => 'Pesanan Diterima',
            4 => 'Menyiapkan Bahan',
            5 => 'Perakitan',
            6 => 'Penyelesaian',
            7 => 'Pengiriman',
            8 => 'Pesanan Selesai',
        ];

        foreach ($stages as $stepNum => $stageName) {
            OrderProgress::create([
                'order_id' => $order->id,
                'step_number' => $stepNum,
                'stage_name' => $stageName,
                'status' => $stepNum < 5 ? 'Selesai' : ($stepNum === 5 ? 'Sedang Berjalan' : 'Pending'),
                'completed_at' => $stepNum < 5 ? now() : null,
            ]);
        }

        $this->assertEquals(8, $order->progresses()->count());
        $this->assertTrue($order->isPreOrder());

        $response = $this->actingAs($customer)->get(route('customer.progress', $order->id));
        $response->assertStatus(200);

        // Pre-order badges and workshop stages
        $response->assertSee('Pre-Order');
        $response->assertSee('Menyiapkan Bahan');
        $response->assertSee('Perakitan');
        $response->assertSee('Penyelesaian');
    }

    public function test_admin_confirm_order_routes_properly_for_ready_stock()
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();

        $readyOrder = Order::create([
            'order_number' => 'ORD-RDY-' . uniqid(),
            'user_id' => $customer->id,
            'tipe_pesanan' => 'ready',
            'total_price' => 2000000,
            'dp_amount' => 2000000,
            'remaining_payment' => 0,
            'order_status' => 'Menunggu Konfirmasi',
            'payment_status' => 'DP Terverifikasi', // Already paid
            'production_status' => 'Menunggu Konfirmasi',
            'current_stage' => 'Konfirmasi Pesanan',
            'recipient_name' => 'Pak Rudi',
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Siap No. 2',
        ]);

        $stages = [
            1 => 'Konfirmasi Pesanan',
            2 => 'Validasi Pembayaran',
            3 => 'Pengemasan Barang',
            4 => 'Pengiriman',
            5 => 'Pesanan Selesai',
        ];

        foreach ($stages as $stepNum => $stageName) {
            OrderProgress::create([
                'order_id' => $readyOrder->id,
                'step_number' => $stepNum,
                'stage_name' => $stageName,
                'status' => $stepNum === 1 ? 'Sedang Berjalan' : 'Pending',
            ]);
        }

        // 1. Admin confirms order -> advances to Validasi Pembayaran & Menunggu Pembayaran
        $response = $this->actingAs($admin)->post(route('admin.pesanan.confirm', $readyOrder->id));
        $response->assertRedirect();

        $readyOrder->refresh();
        $this->assertEquals('Menunggu Pembayaran', $readyOrder->production_status);
        $this->assertEquals('Validasi Pembayaran', $readyOrder->current_stage);

        // 2. Admin verifies payment -> advances to Siap Dikemas & Pengemasan Barang (skipping antrean workshop)
        $verifyResponse = $this->actingAs($admin)->post(route('admin.pesanan.verify_dp', $readyOrder->id));
        $verifyResponse->assertRedirect();

        $readyOrder->refresh();
        $this->assertEquals('Siap Dikemas', $readyOrder->production_status);
        $this->assertEquals('Pengemasan Barang', $readyOrder->current_stage);
    }
}
