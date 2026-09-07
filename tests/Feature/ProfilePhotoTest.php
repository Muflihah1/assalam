<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProfilePhotoTest extends TestCase
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

    public function test_customer_can_upload_profile_photo()
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'username' => 'testuser_' . uniqid(),
            'whatsapp_number' => '081234567890',
        ]);

        $file = UploadedFile::fake()->image('my_avatar.jpg');

        $response = $this->actingAs($customer)
            ->from(route('customer.account'))
            ->post(route('customer.account.profile'), [
                'name' => 'Budi Santoso Baru',
                'username' => $customer->username,
                'whatsapp_number' => '081234567890',
                'email' => $customer->email,
                'alamat' => 'Jl. Kenanga 12',
                'profile_photo' => $file,
            ]);

        $response->assertSessionHas('success');
        $customer->refresh();

        $this->assertNotNull($customer->profile_photo);
        Storage::disk('public')->assertExists($customer->profile_photo);
        $this->assertEquals(Storage::url($customer->profile_photo), $customer->profile_photo_url);
    }

    public function test_customer_can_remove_profile_photo()
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'username' => 'testuser_' . uniqid(),
            'whatsapp_number' => '081234567890',
        ]);

        // 1. Upload photo
        $file = UploadedFile::fake()->image('my_avatar.jpg');
        $this->actingAs($customer)->post(route('customer.account.profile'), [
            'name' => $customer->name,
            'username' => $customer->username,
            'whatsapp_number' => '081234567890',
            'email' => $customer->email,
            'profile_photo' => $file,
        ]);

        $customer->refresh();
        $photoPath = $customer->profile_photo;
        Storage::disk('public')->assertExists($photoPath);

        // 2. Remove photo
        $response = $this->actingAs($customer)->post(route('customer.account.profile'), [
            'name' => $customer->name,
            'username' => $customer->username,
            'whatsapp_number' => '081234567890',
            'email' => $customer->email,
            'remove_photo' => '1',
        ]);

        $response->assertSessionHas('success');
        $customer->refresh();

        $this->assertNull($customer->profile_photo);
        Storage::disk('public')->assertMissing($photoPath);
        $this->assertStringContainsString('dicebear.com', $customer->profile_photo_url);
    }

    public function test_admin_can_upload_and_remove_profile_photo()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@assalammebel.com'],
            ['name' => 'Admin Assalam', 'username' => 'admin_assalam', 'password' => bcrypt('password123'), 'role' => 'admin']
        );

        // 1. Admin upload photo
        $file = UploadedFile::fake()->image('admin_avatar.png');
        $response = $this->actingAs($admin)
            ->from('/admin/pengaturan')
            ->post('/admin/pengaturan/profil', [
                'name' => 'Admin Assalam Updated',
                'username' => $admin->username,
                'email' => $admin->email,
                'profile_photo' => $file,
            ]);

        $response->assertSessionHas('success');
        $admin->refresh();
        $this->assertNotNull($admin->profile_photo);
        Storage::disk('public')->assertExists($admin->profile_photo);

        // 2. Admin remove photo
        $delResponse = $this->actingAs($admin)
            ->from('/admin/pengaturan')
            ->post('/admin/pengaturan/profil', [
                'name' => $admin->name,
                'username' => $admin->username,
                'email' => $admin->email,
                'remove_photo' => '1',
            ]);

        $delResponse->assertSessionHas('success');
        $admin->refresh();
        $this->assertNull($admin->profile_photo);
        $this->assertStringContainsString('dicebear.com', $admin->profile_photo_url);
    }
}
