<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'wa_number' => '085234567890',
            'wa_status' => 'Terhubung / Aktif',
            'wa_template' => 'Halo *{nama}*, pembaruan untuk pesanan mebel custom Anda (*{produk}* - #{no_pesanan}) saat ini telah memasuki tahap: *{tahap}*. Silakan cek foto progres di aplikasi Assalam Mebel. Terima kasih!',
            'shop_name' => 'Assalam Mebel',
            'shop_address' => 'VPR6+PH7, Somangkaan, Karduluk, Kec. Pragaan, Kabupaten Sumenep, Jawa Timur 69465',
            'workshop_address' => 'VPR6+PH7, Somangkaan, Karduluk, Kec. Pragaan, Kabupaten Sumenep, Jawa Timur 69465',
            'workshop_plus_code' => 'VPR6+PH7',
            'workshop_lat' => '-7.1082125',
            'workshop_lng' => '113.7114219',
            'coverage_area' => 'Se-Pulau Madura (Sumenep, Pamekasan, Sampang, Bangkalan)',
            'payment_dana_status' => 'Aktif',
            'payment_dana_number' => '085234567890',
            'payment_dana_name' => 'Assalam Mebel Official',
            'payment_dana_qr' => null,
            'payment_dana_instructions' => 'Buka aplikasi DANA > Tekan Pindai / Pay > Scan QR Code atau transfer manual ke nomor DANA di atas. Masukkan nominal sesuai tagihan.',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
