<?php

namespace Database\Seeders;

use App\Models\ShippingCost;
use Illuminate\Database\Seeder;

class ShippingCostSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['kecamatan' => 'Kota Sumenep (Sumenep)', 'biaya' => 25000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kalianget (Sumenep)', 'biaya' => 35000, 'status' => 'Aktif'],
            ['kecamatan' => 'Bluto (Sumenep)', 'biaya' => 40000, 'status' => 'Aktif'],
            ['kecamatan' => 'Saronggi (Sumenep)', 'biaya' => 45000, 'status' => 'Aktif'],
            ['kecamatan' => 'Ambunten (Sumenep)', 'biaya' => 50000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kota Pamekasan (Pamekasan)', 'biaya' => 75000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kota Surabaya (Jawa Timur)', 'biaya' => 150000, 'status' => 'Aktif'],
            ['kecamatan' => 'Kota Malang (Jawa Timur)', 'biaya' => 200000, 'status' => 'Aktif'],
        ];

        foreach ($locations as $loc) {
            ShippingCost::updateOrCreate(
                ['kecamatan' => $loc['kecamatan']],
                ['biaya' => $loc['biaya'], 'status' => $loc['status']]
            );
        }
    }
}
