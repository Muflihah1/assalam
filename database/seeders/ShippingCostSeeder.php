<?php

namespace Database\Seeders;

use App\Models\ShippingCost;
use Illuminate\Database\Seeder;

class ShippingCostSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama non-Madura
        ShippingCost::where('kecamatan', 'like', '%Surabaya%')
            ->orWhere('kecamatan', 'like', '%Malang%')
            ->orWhere('kecamatan', 'like', '%Jawa%')
            ->delete();

        $locations = [
            // KABUPATEN SUMENEP (Area Pusat Workshop & Galeri Karduluk)
            ['kecamatan' => 'Pragaan & Karduluk (Sumenep - Area Workshop)', 'biaya' => 20000, 'status' => 'Aktif'],
            ['kecamatan' => 'Bluto (Sumenep)', 'biaya' => 30000, 'status' => 'Aktif'],
            ['kecamatan' => 'Saronggi (Sumenep)', 'biaya' => 35000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kota Sumenep (Sumenep)', 'biaya' => 45000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kalianget (Sumenep)', 'biaya' => 45000, 'status' => 'Aktif'],
            ['kecamatan' => 'Ganding & Guluk-Guluk (Sumenep)', 'biaya' => 40000, 'status' => 'Aktif'],
            ['kecamatan' => 'Lenteng & Manding (Sumenep)', 'biaya' => 45000, 'status' => 'Aktif'],
            ['kecamatan' => 'Ambunten & Pasongsongan (Sumenep)', 'biaya' => 55000, 'status' => 'Aktif'],
            ['kecamatan' => 'Batang-Batang & Gapura (Sumenep)', 'biaya' => 60000, 'status' => 'Aktif'],
            ['kecamatan' => 'Dungkek (Sumenep)', 'biaya' => 65000, 'status' => 'Aktif'],

            // KABUPATEN PAMEKASAN
            ['kecamatan' => 'Larangan & Galis (Pamekasan)', 'biaya' => 40000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kota Pamekasan (Pamekasan)', 'biaya' => 50000, 'status' => 'Aktif'],
            ['kecamatan' => 'Tlanakan & Pademawu (Pamekasan)', 'biaya' => 50000, 'status' => 'Aktif'],
            ['kecamatan' => 'Proppo & Palengaan (Pamekasan)', 'biaya' => 60000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kadur & Pegantenan (Pamekasan)', 'biaya' => 65000, 'status' => 'Aktif'],
            ['kecamatan' => 'Waru, Batumarmar & Pasean (Pamekasan)', 'biaya' => 75000, 'status' => 'Aktif'],

            // KABUPATEN SAMPANG
            ['kecamatan' => 'Camplong (Sampang)', 'biaya' => 65000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kota Sampang (Sampang)', 'biaya' => 80000, 'status' => 'Aktif'],
            ['kecamatan' => 'Torjun & Jrengik (Sampang)', 'biaya' => 90000, 'status' => 'Aktif'],
            ['kecamatan' => 'Omben, Kedungdung & Robatal (Sampang)', 'biaya' => 95000, 'status' => 'Aktif'],
            ['kecamatan' => 'Sreseh & Tambelangan (Sampang)', 'biaya' => 100000, 'status' => 'Aktif'],
            ['kecamatan' => 'Ketapang, Banyuates & Sokobanah (Sampang)', 'biaya' => 110000, 'status' => 'Aktif'],

            // KABUPATEN BANGKALAN
            ['kecamatan' => 'Blega & Galis (Bangkalan)', 'biaya' => 100000, 'status' => 'Aktif'],
            ['kecamatan' => 'Tanah Merah & Konang (Bangkalan)', 'biaya' => 110000, 'status' => 'Aktif'],
            ['kecamatan' => 'Modung & Kwanyar (Bangkalan)', 'biaya' => 120000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kota Bangkalan & Burneh (Bangkalan)', 'biaya' => 125000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kamal & Labang / Akses Suramadu (Bangkalan)', 'biaya' => 130000, 'status' => 'Aktif'],
            ['kecamatan' => 'Arosbaya, Klampis & Sepulu (Bangkalan)', 'biaya' => 140000, 'status' => 'Aktif'],
            ['kecamatan' => 'Tanjungbumi & Geger (Bangkalan)', 'biaya' => 150000, 'status' => 'Aktif'],
        ];

        foreach ($locations as $loc) {
            ShippingCost::updateOrCreate(
                ['kecamatan' => $loc['kecamatan']],
                ['biaya' => $loc['biaya'], 'status' => $loc['status']]
            );
        }
    }
}
