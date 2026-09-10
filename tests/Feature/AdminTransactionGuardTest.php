<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produk;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTransactionGuardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
    }

    private function admin()
    {
        return User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_' . uniqid(),
        ]);
    }

    private function produk()
    {
        return Produk::create([
            'nama' => 'Meja Ukir Jati',
            'deskripsi' => 'Meja kayu jati ukir motif Madura.',
            'harga' => 5000000,
            'foto' => 'produk/test.jpg',
        ]);
    }

    public function test_admin_cannot_add_product_to_cart()
    {
        $admin = $this->admin();
        $produk = $this->produk();

        $response = $this->actingAs($admin)
            ->post(route('customer.cart.add', $produk->id));

        // Admin dialihkan dengan pesan error, keranjang tetap kosong
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals([], session('cart', []));

        // Untuk permintaan AJAX/JSON, admin menerima 403
        $this->actingAs($admin)
            ->postJson(route('customer.cart.add', $produk->id))
            ->assertStatus(403);
    }

    public function test_admin_cannot_update_or_remove_cart_items()
    {
        $cartSession = [
            'cart' => [
                'prod_1' => ['type' => 'katalog', 'product_id' => 1, 'name' => 'X', 'price' => 1000, 'quantity' => 1],
            ],
        ];

        $response = $this->actingAs($this->admin())
            ->withSession($cartSession)
            ->post(route('customer.cart.update', 'prod_1'), ['action' => 'increase']);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $response2 = $this->actingAs($this->admin())
            ->withSession($cartSession)
            ->delete(route('customer.cart.remove', 'prod_1'));

        $response2->assertRedirect();
        $response2->assertSessionHas('error');
    }

    public function test_admin_cannot_checkout_cart()
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->post(route('customer.cart.checkout'), [
                'recipient_name' => 'Admin Test',
                'recipient_phone' => '081234567890',
                'shipping_address' => 'Jl. Kantor Admin No. 1',
                'shipping_cost' => 50000,
                'payment_method' => 'dana',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('orders', ['recipient_name' => 'Admin Test']);
    }

    public function test_admin_cannot_submit_custom_design_order()
    {
        $admin = $this->admin();
        $produk = $this->produk();

        $response = $this->actingAs($admin)
            ->post(route('customer.design.order'), [
                'product_id' => $produk->id,
                'category' => 'Meja',
                'length_cm' => 120,
                'width_cm' => 60,
                'height_cm' => 75,
                'color_name' => 'Natural',
                'color_hex' => '#C8A165',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('custom_designs', 0);
    }

    public function test_customer_can_still_add_to_cart()
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'username' => 'cust_' . uniqid(),
        ]);
        $produk = $this->produk();

        $response = $this->actingAs($customer)
            ->post(route('customer.cart.add', $produk->id));

        $response->assertRedirect();
        $cart = session('cart', []);
        $this->assertArrayHasKey('prod_' . $produk->id, $cart);
    }

    public function test_guest_can_view_catalog_and_product_detail_without_login()
    {
        $produk = $this->produk();

        $this->get(route('customer.katalog'))->assertOk();
        $this->get(route('customer.produk.detail', $produk->id))->assertOk();
    }
}
