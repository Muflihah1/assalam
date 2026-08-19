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
            'shop_name' => 'Assalam Mebel Indonesia',
            'shop_address' => 'Jl. Raya Trunojoyo No. 45, Sumenep, Madura',
            'bank_name' => 'BCA',
            'bank_account_number' => '8830-1289-44',
            'bank_account_holder' => 'CV. Assalam Mebel Indonesia',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
