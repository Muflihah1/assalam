<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Administrator Utama
        User::updateOrCreate(
            ['email' => 'admin@assalammebel.com'],
            [
                'name' => 'Administrator Assalam',
                'password' => Hash::make('password123'),
                'whatsapp_number' => '085234567890',
                'alamat' => 'Jl. Raya Mebel Assalam No. 12, Sumenep, Madura',
                'role' => 'admin',
            ]
        );

        // 2. Akun Pelanggan Demo 1
        User::updateOrCreate(
            ['email' => 'budi@gmail.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'whatsapp_number' => '081234567890',
                'alamat' => 'Jl. Pemuda No. 45, Kecamatan Genteng, Kota Surabaya, Jawa Timur',
                'role' => 'customer',
            ]
        );

        // 3. Akun Pelanggan Demo 2
        User::updateOrCreate(
            ['email' => 'rina.wijaya@gmail.com'],
            [
                'name' => 'Rina Wijaya',
                'password' => Hash::make('password123'),
                'whatsapp_number' => '085987654321',
                'alamat' => 'Jl. Diponegoro No. 88, Kota Malang, Jawa Timur',
                'role' => 'customer',
            ]
        );

        // 4. Akun Pelanggan Demo 3
        User::updateOrCreate(
            ['email' => 'ahmad.fauzi@gmail.com'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('password123'),
                'whatsapp_number' => '087811223344',
                'alamat' => 'Jl. Panglima Sudirman No. 10, Kabupaten Sumenep, Jawa Timur',
                'role' => 'customer',
            ]
        );
    }
}
