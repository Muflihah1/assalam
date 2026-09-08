<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WaTemplate;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppGatewayTest extends TestCase
{
    use DatabaseTransactions;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin_' . uniqid() . '@assalam.test',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer_' . uniqid() . '@assalam.test',
        ]);

        WaTemplate::updateOrCreate(
            ['code' => 'order_created'],
            [
                'name' => 'Pesanan Baru Dibuat',
                'event_trigger' => 'Saat Pesanan Dibuat',
                'content' => 'Halo {nama}, pesanan #{no_pesanan} ({produk}) berhasil dibuat.',
                'is_active' => true,
            ]
        );
    }

    public function test_guest_cannot_access_whatsapp_gateway(): void
    {
        $response = $this->get(route('admin.whatsapp.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_whatsapp_gateway(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.whatsapp.index'));
        $response->assertRedirect(route('customer.beranda'));
    }

    public function test_admin_can_access_whatsapp_gateway_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.whatsapp.index'));
        $response->assertOk();
        $response->assertSee('WhatsApp Gateway');
        $response->assertSee('Sidecar:');
        $response->assertSee('Hubungkan WhatsApp');
    }

    public function test_whatsapp_status_endpoint_returns_json(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.whatsapp.status'));
        $response->assertOk();
        $response->assertJsonStructure([
            'online',
            'status',
            'status_label',
            'phone_number',
            'session_id',
            'sidecar_running',
        ]);
    }

    public function test_whatsapp_qr_endpoint_returns_json(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.whatsapp.qr'));
        $response->assertOk();
        $response->assertJsonStructure([
            'success',
            'status',
        ]);
    }

    public function test_whatsapp_pairing_code_generation(): void
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->postJson(route('admin.whatsapp.pairing'), [
                'phone_number' => '081234567890',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertNotEmpty($response->json('code'));
    }

    public function test_admin_can_disconnect_whatsapp_session(): void
    {
        $response = $this->actingAs($this->admin)
            ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class)
            ->post(route('admin.whatsapp.disconnect'));
        $response->assertSessionHas('success');
    }
}
