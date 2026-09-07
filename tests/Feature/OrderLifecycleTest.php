<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderProgress;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class OrderLifecycleTest extends TestCase
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

    private function createCustomerWithOrder($orderStatus = 'Menunggu Konfirmasi', $paymentStatus = 'Menunggu Pembayaran DP', $stage = 'Konfirmasi Pesanan')
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer_' . uniqid() . '@assalam.test',
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-' . uniqid() . '-' . rand(100, 999),
            'user_id' => $customer->id,
            'total_price' => 2000000,
            'dp_amount' => 1000000,
            'remaining_payment' => 1000000,
            'order_status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'production_status' => $orderStatus === 'Menunggu Konfirmasi' ? 'Menunggu Konfirmasi' : 'Antrean Produksi',
            'current_stage' => $stage,
            'recipient_name' => 'Budi Santoso',
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Mawar No. 10',
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
                'status' => $stepNum === 1 ? ($orderStatus === 'Menunggu Konfirmasi' ? 'Sedang Berjalan' : 'Selesai') : 'Belum Dimulai',
            ]);
        }

        return [$customer, $order];
    }

    private function getAdmin()
    {
        return User::firstOrCreate(
            ['email' => 'admin@assalam.test'],
            ['name' => 'Admin Teak', 'username' => 'admin_test', 'password' => bcrypt('password'), 'role' => 'admin']
        );
    }

    public function test_customer_can_cancel_order_when_waiting_confirmation()
    {
        [$customer, $order] = $this->createCustomerWithOrder('Menunggu Konfirmasi');

        $response = $this->actingAs($customer)
            ->post("/customer/progress/{$order->id}/cancel", [
                'reason' => 'Ingin mengganti model mebel',
            ]);

        $response->assertRedirect(route('customer.riwayat'));
        $order->refresh();
        $this->assertEquals('Dibatalkan', $order->order_status);
        $this->assertEquals('Dibatalkan', $order->production_status);
        $this->assertStringContainsString('Ingin mengganti model mebel', $order->rejection_reason);

        $step1 = OrderProgress::where('order_id', $order->id)->where('step_number', 1)->first();
        $this->assertEquals('Dibatalkan', $step1->status);
    }

    public function test_customer_cannot_cancel_order_when_already_processed()
    {
        [$customer, $order] = $this->createCustomerWithOrder('Diproses', 'DP Terverifikasi', 'Menyiapkan Bahan');

        $response = $this->actingAs($customer)
            ->from("/customer/progress")
            ->post("/customer/progress/{$order->id}/cancel", [
                'reason' => 'Mau batal',
            ]);

        $order->refresh();
        $this->assertEquals('Diproses', $order->order_status);
    }

    public function test_admin_confirm_order_activates_stage_two()
    {
        $admin = $this->getAdmin();
        [$customer, $order] = $this->createCustomerWithOrder('Menunggu Konfirmasi');

        $response = $this->actingAs($admin)
            ->from('/admin/pesanan-masuk')
            ->post("/admin/pesanan-masuk/{$order->id}/confirm", [
                'admin_notes' => 'Pesanan diterima, silakan DP',
            ]);

        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('Pesanan Diterima', $order->order_status);
        $this->assertEquals('Validasi Pembayaran', $order->current_stage);
        $this->assertEquals('Menunggu Pembayaran DP', $order->payment_status);

        $step1 = OrderProgress::where('order_id', $order->id)->where('step_number', 1)->first();
        $this->assertEquals('Selesai', $step1->status);

        $step2 = OrderProgress::where('order_id', $order->id)->where('step_number', 2)->first();
        $this->assertEquals('Sedang Berjalan', $step2->status);
    }

    public function test_admin_reject_order_with_reason()
    {
        $admin = $this->getAdmin();
        [$customer, $order] = $this->createCustomerWithOrder('Menunggu Konfirmasi');

        $response = $this->actingAs($admin)
            ->from('/admin/pesanan-masuk')
            ->post("/admin/pesanan-masuk/{$order->id}/reject", [
                'rejection_reason' => 'Bahan kayu sedang kosong',
            ]);

        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('Ditolak', $order->order_status);
        $this->assertEquals('Bahan kayu sedang kosong', $order->rejection_reason);
    }

    public function test_payment_dp_rejection_and_reupload_flow()
    {
        $admin = $this->getAdmin();
        [$customer, $order] = $this->createCustomerWithOrder('Pesanan Diterima', 'Menunggu Verifikasi DP', 'Validasi Pembayaran');

        // 1. Admin tolak DP
        $response = $this->actingAs($admin)
            ->from('/admin/pesanan-masuk')
            ->post("/admin/pesanan-masuk/{$order->id}/reject-dp", [
                'rejection_reason' => 'Struk transfer tidak terbaca/buram',
            ]);

        $response->assertSessionHas('warning');
        $order->refresh();
        $this->assertEquals('Bukti DP Ditolak', $order->payment_status);
        $this->assertEquals('Struk transfer tidak terbaca/buram', $order->rejection_reason);

        // 2. Customer upload bukti baru
        $file = UploadedFile::fake()->image('bukti_dp_baru.jpg');
        $uploadResp = $this->actingAs($customer)
            ->from('/customer/progress')
            ->post("/customer/progress/{$order->id}/upload-dp", [
                'dp_receipt_proof' => $file,
            ]);

        $uploadResp->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('Menunggu Verifikasi DP', $order->payment_status);
        $this->assertNull($order->rejection_reason); // Harus di-reset!
    }

    public function test_sequential_locking_in_progres_produksi()
    {
        $admin = $this->getAdmin();
        [$customer, $order] = $this->createCustomerWithOrder('Pesanan Diterima', 'Menunggu Pembayaran DP', 'Validasi Pembayaran');

        // Coba loncat langsung ke Tahap 4 (Menyiapkan Bahan) saat DP belum diverifikasi -> Harus Ditolak (Kunci Fisik)
        $response = $this->actingAs($admin)
            ->from("/admin/progres-produksi/{$order->id}")
            ->put("/admin/progres-produksi/{$order->id}", [
                'tahap' => 'Menyiapkan Bahan',
            ]);

        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('Validasi Pembayaran', $order->current_stage);

        // Verifikasi DP terlebih dahulu
        $this->actingAs($admin)
            ->from('/admin/pesanan-masuk')
            ->post("/admin/pesanan-masuk/{$order->id}/verify-dp");
        $order->refresh();
        $this->assertEquals('DP Terverifikasi', $order->payment_status);
        $this->assertEquals('Pesanan Diterima', $order->current_stage); // Tahap 3

        // Coba loncat dari Tahap 3 langsung ke Tahap 6 (Penyelesaian) -> Harus Ditolak (Sequential rule)
        $skipResp = $this->actingAs($admin)
            ->from("/admin/progres-produksi/{$order->id}")
            ->put("/admin/progres-produksi/{$order->id}", [
                'tahap' => 'Penyelesaian',
            ]);

        $skipResp->assertSessionHas('error');
        $order->refresh();
        $this->assertEquals('Pesanan Diterima', $order->current_stage);

        // Maju secara berurutan ke Tahap 4 (Menyiapkan Bahan) -> Berhasil!
        $validResp = $this->actingAs($admin)
            ->from("/admin/progres-produksi/{$order->id}")
            ->put("/admin/progres-produksi/{$order->id}", [
                'tahap' => 'Menyiapkan Bahan',
                'catatan' => 'Kayu jati solid telah dipilih dan siap potong.',
            ]);

        $validResp->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('Menyiapkan Bahan', $order->current_stage);
        $this->assertEquals('Dalam Pengerjaan', $order->production_status);
    }

    public function test_pelunasan_rejection_and_verification_flow()
    {
        $admin = $this->getAdmin();
        [$customer, $order] = $this->createCustomerWithOrder('Diproses', 'DP Terverifikasi', 'Penyelesaian');

        // Customer upload bukti pelunasan
        $file = UploadedFile::fake()->image('bukti_pelunasan.jpg');
        $this->actingAs($customer)
            ->from('/customer/progress')
            ->post("/customer/progress/{$order->id}/pay-remaining", [
                'final_receipt_proof' => $file,
            ]);

        $order->refresh();
        $this->assertEquals('Menunggu Verifikasi Pelunasan', $order->payment_status);

        // Admin tolak bukti pelunasan
        $this->actingAs($admin)
            ->from('/admin/pesanan-masuk')
            ->post("/admin/pesanan-masuk/{$order->id}/reject-pelunasan", [
                'rejection_reason' => 'Nominal transfer pelunasan kurang Rp 150.000',
            ]);

        $order->refresh();
        $this->assertEquals('Bukti Pelunasan Ditolak', $order->payment_status);
        $this->assertEquals('Nominal transfer pelunasan kurang Rp 150.000', $order->rejection_reason);

        // Admin verifikasi pelunasan yang valid
        $this->actingAs($admin)
            ->from('/admin/pesanan-masuk')
            ->post("/admin/pesanan-masuk/{$order->id}/verify-pelunasan");

        $order->refresh();
        $this->assertEquals('Lunas', $order->payment_status);
        $this->assertEquals(0, $order->remaining_payment);
        $this->assertNull($order->rejection_reason);
    }

    public function test_customer_can_confirm_completed_when_shipped_and_paid()
    {
        [$customer, $order] = $this->createCustomerWithOrder('Dikirim', 'Lunas', 'Pengiriman');
        $order->update([
            'remaining_payment' => 0,
            'production_status' => 'Pengiriman',
        ]);

        $response = $this->actingAs($customer)
            ->post("/customer/progress/{$order->id}/confirm-completed");

        $response->assertRedirect(route('customer.riwayat'));
        $order->refresh();
        $this->assertEquals('Selesai', $order->order_status);
        $this->assertEquals('Selesai', $order->production_status);
        $this->assertEquals('Pesanan Selesai', $order->current_stage);

        $step8 = OrderProgress::where('order_id', $order->id)->where('step_number', 8)->first();
        $this->assertEquals('Selesai', $step8->status);
    }
}
