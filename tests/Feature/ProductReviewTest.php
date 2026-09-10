<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Produk;
use App\Models\ProductReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductReviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
    }

    private function customer()
    {
        return User::factory()->create([
            'role' => 'customer',
            'username' => 'cust_' . uniqid(),
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
            'nama' => 'Kursi Ukir (1 Set)',
            'deskripsi' => 'Set kursi tamu ukir kayu jati premium.',
            'harga' => 32000000,
            'foto' => 'produk/test.jpg',
        ]);
    }

    public function test_guest_can_view_product_detail_page_with_reviews()
    {
        $produk = $this->produk();

        $reviewer = $this->customer();
        ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $reviewer->id,
            'rating' => 5,
            'title' => 'Bagus sekali',
            'comment' => 'Ukiran rapi dan kayu berkualitas.',
            'status' => 'Disetujui',
        ]);

        // Ulasan pending tidak boleh tampil
        ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $this->customer()->id,
            'rating' => 1,
            'comment' => 'Ulasan masih menunggu persetujuan admin.',
            'status' => 'Menunggu Persetujuan',
        ]);

        $response = $this->get(route('customer.produk.detail', $produk->id));

        $response->assertOk();
        $response->assertSee('Kursi Ukir (1 Set)');
        $response->assertSee('Bagus sekali');
        $response->assertSee('Ukiran rapi dan kayu berkualitas.');
        $response->assertDontSee('Ulasan masih menunggu persetujuan admin.');
    }

    public function test_customer_can_submit_review_and_it_starts_pending()
    {
        $produk = $this->produk();
        $customer = $this->customer();

        $response = $this->actingAs($customer)->post(route('customer.produk.review.store', $produk->id), [
            'rating' => 4,
            'title' => 'Kualitas mantap',
            'comment' => 'Mebel kokoh dan ukirannya indah, recommended!',
        ]);

        $response->assertRedirect(route('customer.produk.detail', $produk->id));
        $response->assertSessionHas('success');

        $review = ProductReview::where('produk_id', $produk->id)->where('user_id', $customer->id)->first();
        $this->assertNotNull($review);
        $this->assertEquals(4, $review->rating);
        $this->assertEquals('Menunggu Persetujuan', $review->status);
        $this->assertFalse($review->is_verified_buyer);
    }

    public function test_review_validation_requires_rating_and_comment()
    {
        $produk = $this->produk();
        $customer = $this->customer();

        $response = $this->actingAs($customer)
            ->from(route('customer.produk.detail', $produk->id))
            ->post(route('customer.produk.review.store', $produk->id), [
                'comment' => 'Tanpa rating seharusnya gagal.',
            ]);

        $response->assertSessionHasErrors(['rating']);
        $this->assertEquals(0, ProductReview::where('produk_id', $produk->id)->count());

        $response2 = $this->actingAs($customer)
            ->post(route('customer.produk.review.store', $produk->id), [
                'rating' => 5,
            ]);

        $response2->assertSessionHasErrors(['comment']);
    }

    public function test_admin_cannot_submit_review()
    {
        $produk = $this->produk();
        $admin = $this->admin();

        $response = $this->actingAs($admin)
            ->post(route('customer.produk.review.store', $produk->id), [
                'rating' => 5,
                'comment' => 'Admin mencoba menulis ulasan.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(0, ProductReview::count());
    }

    public function test_verified_buyer_flag_detected_from_order_history()
    {
        $produk = $this->produk();
        $customer = $this->customer();

        $order = \App\Models\Order::create([
            'order_number' => 'ORD-REVIEW-' . uniqid(),
            'user_id' => $customer->id,
            'total_price' => 32000000,
            'dp_amount' => 16000000,
            'remaining_payment' => 16000000,
            'order_status' => 'Selesai',
            'payment_status' => 'Lunas',
            'production_status' => 'Selesai',
            'current_stage' => 'Pesanan Selesai',
            'recipient_name' => 'Budi',
            'recipient_phone' => '081234567890',
            'shipping_address' => 'Jl. Test No. 1',
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'produk_id' => $produk->id,
            'product_name' => $produk->nama,
            'price' => $produk->harga,
            'quantity' => 1,
            'subtotal' => $produk->harga,
        ]);

        $this->actingAs($customer)
            ->post(route('customer.produk.review.store', $produk->id), [
                'rating' => 5,
                'comment' => 'Barang sampai dengan selamat, kualitas terbaik!',
            ]);

        $review = ProductReview::where('produk_id', $produk->id)->where('user_id', $customer->id)->first();
        $this->assertTrue($review->is_verified_buyer);
        $this->assertEquals($order->id, $review->order_id);
    }

    public function test_customer_can_delete_own_review()
    {
        $produk = $this->produk();
        $customer = $this->customer();

        $review = ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $customer->id,
            'rating' => 3,
            'comment' => 'Ulasan milik sendiri yang mau dihapus.',
            'status' => 'Disetujui',
        ]);

        $response = $this->actingAs($customer)
            ->delete(route('customer.produk.review.destroy', $review->id));

        $response->assertRedirect(route('customer.produk.detail', $produk->id));
        $this->assertDatabaseMissing('product_reviews', ['id' => $review->id]);
    }

    public function test_admin_can_approve_reject_and_delete_reviews()
    {
        $admin = $this->admin();
        $produk = $this->produk();
        $customer = $this->customer();

        $review = ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $customer->id,
            'rating' => 2,
            'comment' => 'Ulasan yang akan dimoderasi admin.',
            'status' => 'Menunggu Persetujuan',
        ]);

        // Approve
        $this->actingAs($admin)
            ->post(route('admin.ulasan.approve', $review->id))
            ->assertSessionHas('success');
        $this->assertEquals('Disetujui', $review->fresh()->status);

        // Reject dengan alasan
        $this->actingAs($admin)
            ->post(route('admin.ulasan.reject', $review->id), ['admin_note' => 'Mengandung konten tidak pantas'])
            ->assertSessionHas('warning');
        $this->assertEquals('Ditolak', $review->fresh()->status);
        $this->assertEquals('Mengandung konten tidak pantas', $review->fresh()->admin_note);

        // Hapus permanen
        $this->actingAs($admin)
            ->delete(route('admin.ulasan.destroy', $review->id))
            ->assertSessionHas('success');
        $this->assertDatabaseMissing('product_reviews', ['id' => $review->id]);
    }

    public function test_admin_moderation_page_accessible_only_to_admin()
    {
        $admin = $this->admin();
        $produk = $this->produk();

        ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $this->customer()->id,
            'rating' => 5,
            'comment' => 'Ulasan untuk halaman moderasi.',
            'status' => 'Menunggu Persetujuan',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.ulasan'))
            ->assertOk()
            ->assertSee('Ulasan untuk halaman moderasi.');

        // Pelanggan tidak boleh akses moderasi
        $customer = $this->customer();
        $this->actingAs($customer)
            ->get(route('admin.ulasan'))
            ->assertRedirect(route('customer.beranda'));
    }

    public function test_rating_average_only_counts_approved_reviews()
    {
        $produk = $this->produk();

        $this->assertEquals(0, $produk->rating_count);
        $this->assertEquals(0.0, $produk->rating_average);

        ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $this->customer()->id,
            'rating' => 5,
            'comment' => 'Lima bintang, produk sesuai ekspektasi.',
            'status' => 'Disetujui',
        ]);
        ProductReview::create([
            'produk_id' => $produk->id,
            'user_id' => $this->customer()->id,
            'rating' => 1,
            'comment' => 'Satu bintang tapi masih pending moderasi.',
            'status' => 'Menunggu Persetujuan',
        ]);

        $produk->refresh();
        $this->assertEquals(1, $produk->rating_count);
        $this->assertEquals(5.0, $produk->rating_average);
    }
}
