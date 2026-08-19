<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\CustomDesign;
use App\Models\OrderProgress;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $budi = User::where('email', 'budi@gmail.com')->first();
        $rina = User::where('email', 'rina.wijaya@gmail.com')->first();
        $ahmad = User::where('email', 'ahmad.fauzi@gmail.com')->first();

        if (!$budi || !$rina) return;

        // 1. Pesanan Budi - Sedang Perakitan (Dalam Pengerjaan)
        $orderBudi = Order::updateOrCreate(
            ['order_number' => 'ORD-8821'],
            [
                'user_id' => $budi->id,
                'total_price' => 4500000,
                'dp_amount' => 2250000,
                'shipping_cost' => 50000,
                'remaining_payment' => 2300000,
                'payment_method' => 'qris',
                'payment_status' => 'DP Terverifikasi',
                'production_status' => 'Dalam Pengerjaan',
                'current_stage' => 'Perakitan',
                'recipient_name' => $budi->name,
                'recipient_phone' => $budi->whatsapp_number,
                'shipping_address' => $budi->alamat,
                'customer_notes' => 'Tolong sandaran dibuat empuk dobel busa dan kaki meja dibubut rapi.',
                'admin_notes' => 'Rangka kayu jati sudah selesai dipotong, saat ini masuk tahap perakitan sambungan purus.',
            ]
        );

        CustomDesign::updateOrCreate(
            ['order_id' => $orderBudi->id],
            [
                'category' => 'Sofa & Kursi Tamu Mewah',
                'length_cm' => 180,
                'width_cm' => 80,
                'height_cm' => 75,
                'wood_material' => 'Kayu Jati Perhutani (Grade A)',
                'color_name' => 'Amber Gold',
                'color_hex' => '#d97706',
                'tone_percent' => 100,
                'notes' => 'Model sofa minimalis 3 seater dengan kain pelapis fabric hangat.',
            ]
        );

        $stagesBudi = [
            ['step' => 1, 'name' => 'Konfirmasi Pesanan', 'status' => 'Selesai', 'completed_at' => now()->subDays(5)],
            ['step' => 2, 'name' => 'Validasi Pembayaran', 'status' => 'Selesai', 'completed_at' => now()->subDays(4)],
            ['step' => 3, 'name' => 'Pesanan Diterima', 'status' => 'Selesai', 'completed_at' => now()->subDays(3)],
            ['step' => 4, 'name' => 'Menyiapkan Bahan', 'status' => 'Selesai', 'completed_at' => now()->subDays(2)],
            ['step' => 5, 'name' => 'Perakitan', 'status' => 'Sedang Berjalan', 'completed_at' => null],
            ['step' => 6, 'name' => 'Penyelesaian', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 7, 'name' => 'Pengiriman', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 8, 'name' => 'Pesanan Selesai', 'status' => 'Pending', 'completed_at' => null],
        ];

        foreach ($stagesBudi as $st) {
            OrderProgress::updateOrCreate(
                ['order_id' => $orderBudi->id, 'step_number' => $st['step']],
                [
                    'stage_name' => $st['name'],
                    'status' => $st['status'],
                    'completed_at' => $st['completed_at'],
                    'notes' => $st['step'] === 5 ? 'Proses perakitan kerangka utama sofa jati 70%' : null,
                ]
            );
        }

        // 2. Pesanan Rina - Menunggu Konfirmasi Admin (Pesanan Baru)
        $orderRina = Order::updateOrCreate(
            ['order_number' => 'ORD-10025'],
            [
                'user_id' => $rina->id,
                'total_price' => 3800000,
                'dp_amount' => 1900000,
                'shipping_cost' => 50000,
                'remaining_payment' => 1950000,
                'payment_method' => 'transfer',
                'payment_status' => 'Menunggu Pembayaran DP',
                'production_status' => 'Menunggu Konfirmasi',
                'current_stage' => 'Konfirmasi Pesanan',
                'recipient_name' => $rina->name,
                'recipient_phone' => $rina->whatsapp_number,
                'shipping_address' => $rina->alamat,
                'customer_notes' => 'Finishing tolong warna natural oak doff.',
            ]
        );

        CustomDesign::updateOrCreate(
            ['order_id' => $orderRina->id],
            [
                'category' => 'Meja Makan Minimalis Modern',
                'length_cm' => 160,
                'width_cm' => 90,
                'height_cm' => 78,
                'wood_material' => 'Kayu Mahoni Oven Premium',
                'color_name' => 'Natural Oak',
                'color_hex' => '#c85a32',
                'tone_percent' => 90,
                'notes' => 'Include 4 kursi makan dudukan busa.',
            ]
        );

        $stagesRina = [
            ['step' => 1, 'name' => 'Konfirmasi Pesanan', 'status' => 'Sedang Berjalan', 'completed_at' => null],
            ['step' => 2, 'name' => 'Validasi Pembayaran', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 3, 'name' => 'Pesanan Diterima', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 4, 'name' => 'Menyiapkan Bahan', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 5, 'name' => 'Perakitan', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 6, 'name' => 'Penyelesaian', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 7, 'name' => 'Pengiriman', 'status' => 'Pending', 'completed_at' => null],
            ['step' => 8, 'name' => 'Pesanan Selesai', 'status' => 'Pending', 'completed_at' => null],
        ];

        foreach ($stagesRina as $st) {
            OrderProgress::updateOrCreate(
                ['order_id' => $orderRina->id, 'step_number' => $st['step']],
                [
                    'stage_name' => $st['name'],
                    'status' => $st['status'],
                    'completed_at' => $st['completed_at'],
                ]
            );
        }

        // 3. Pesanan Ahmad - Sudah Selesai (Riwayat)
        if ($ahmad) {
            $orderAhmad = Order::updateOrCreate(
                ['order_number' => 'ORD-10018'],
                [
                    'user_id' => $ahmad->id,
                    'total_price' => 6000000,
                    'dp_amount' => 3000000,
                    'shipping_cost' => 50000,
                    'remaining_payment' => 0,
                    'payment_method' => 'qris',
                    'payment_status' => 'Lunas',
                    'production_status' => 'Selesai',
                    'current_stage' => 'Pesanan Selesai',
                    'recipient_name' => $ahmad->name,
                    'recipient_phone' => $ahmad->whatsapp_number,
                    'shipping_address' => $ahmad->alamat,
                    'customer_notes' => 'Pintu utama ukir Jepara bunga melati.',
                ]
            );

            CustomDesign::updateOrCreate(
                ['order_id' => $orderAhmad->id],
                [
                    'category' => 'Pintu Rumah & Gebyok',
                    'length_cm' => 210,
                    'width_cm' => 90,
                    'height_cm' => 4,
                    'wood_material' => 'Kayu Jati Perhutani (Grade A)',
                    'color_name' => 'Deep Mahogany',
                    'color_hex' => '#4a2c2a',
                    'tone_percent' => 100,
                    'notes' => 'Pintu kupu tarung 2 daun full ukir klasik.',
                ]
            );

            for ($i = 1; $i <= 8; $i++) {
                OrderProgress::updateOrCreate(
                    ['order_id' => $orderAhmad->id, 'step_number' => $i],
                    [
                        'stage_name' => ['Konfirmasi Pesanan', 'Validasi Pembayaran', 'Pesanan Diterima', 'Menyiapkan Bahan', 'Perakitan', 'Penyelesaian', 'Pengiriman', 'Pesanan Selesai'][$i - 1],
                        'status' => 'Selesai',
                        'completed_at' => now()->subDays(10 - $i),
                    ]
                );
            }
        }
    }
}
