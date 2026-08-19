<?php

namespace Database\Seeders;

use App\Models\StudioSetting;
use Illuminate\Database\Seeder;

class StudioSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'base_rate_jati' => '4500000',
            'base_rate_mahoni' => '3500000',
            'base_rate_sungkai' => '3800000',
            'dp_percentage' => '50',
            'min_length' => '50',
            'max_length' => '300',
            'min_width' => '30',
            'max_width' => '200',
            'min_height' => '30',
            'max_height' => '250',
            'estimated_work_days' => '14',
        ];

        foreach ($settings as $key => $value) {
            StudioSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
