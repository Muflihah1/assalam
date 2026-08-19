<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaMessageLog;
use App\Models\Order;

class WaMessageLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $budiOrder = $orders->first();
        $rinaOrder = $orders->skip(1)->first();
        $ahmadOrder = $orders->skip(2)->first();

        $logs = [
            [
                'order_id' => $budiOrder?->id,
                'recipient_name' => 'Budi Santoso',
                'recipient_phone' => '6281234567890',
                'template_code' => 'order_created',
                'message_body' => "Halo *Budi Santoso*,\n\nTerima kasih telah memesan mebel premium di *Assalam Mebel*! 🪵\n\n📌 *Detail Pesanan:*\n• No. Pesanan: *#" . ($budiOrder?->order_number ?? 'ORD-8821') . "*\n• Produk: *Sofa Tamu Minimalis Jati*\n• Estimasi Total: *Rp 4.500.000*\n• Tagihan DP (50%): *Rp 2.250.000*\n\nSilakan transfer pembayaran DP agar pesanan Anda dapat segera dijadwalkan ke dapur produksi pengrajin kami.\n\n🔗 Pantau progres pesanan Anda di:\n" . url('/customer/progress'),
                'status' => 'Delivered',
                'response_payload' => json_encode(['id' => 'true_6281234567890@c.us_3EB0123456789', 'ack' => 2, 'timestamp' => now()->subHours(6)->timestamp]),
                'retry_count' => 0,
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'order_id' => $budiOrder?->id,
                'recipient_name' => 'Budi Santoso',
                'recipient_phone' => '6281234567890',
                'template_code' => 'dp_verified',
                'message_body' => "Halo *Budi Santoso*,\n\nPembayaran Uang Muka (DP) untuk pesanan *#" . ($budiOrder?->order_number ?? 'ORD-8821') . "* (Sofa Tamu Minimalis Jati) sebesar *Rp 2.250.000* telah *TERVERIFIKASI* oleh tim Assalam Mebel. ✅\n\nPengrajin kami kini mulai menyiapkan material kayu solid pilihan untuk memproduksi furniture impian Anda.\n\n🔗 Cek pembaruan status pengerjaan:\n" . url('/customer/progress'),
                'status' => 'Sent',
                'response_payload' => json_encode(['id' => 'true_6281234567890@c.us_3EB0987654321', 'ack' => 1, 'timestamp' => now()->subHours(4)->timestamp]),
                'retry_count' => 0,
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'order_id' => $budiOrder?->id,
                'recipient_name' => 'Budi Santoso',
                'recipient_phone' => '6281234567890',
                'template_code' => 'progress_updated',
                'message_body' => "Halo *Budi Santoso*,\n\nAda kabar terbaru untuk pesanan furniture Anda (*#" . ($budiOrder?->order_number ?? 'ORD-8821') . "* - Sofa Tamu Minimalis Jati)! 🪚✨\n\n🎯 *Tahap Pengerjaan Saat Ini:*\n👉 *Menyiapkan Bahan*\n\n📝 *Catatan Pengrajin:*\n_Kayu jati perhutani TPK grade A telah dipotong dan diserut presisi sesuai ukuran._\n\n📸 Foto dokumentasi pengerjaan telah kami unggah ke sistem. Anda dapat melihat foto & detail tahapan secara langsung di tautan berikut:\n" . url('/customer/progress'),
                'status' => 'Sent',
                'response_payload' => json_encode(['id' => 'true_6281234567890@c.us_3EB0112233445', 'ack' => 1, 'timestamp' => now()->subHours(2)->timestamp]),
                'retry_count' => 0,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'order_id' => $rinaOrder?->id,
                'recipient_name' => 'Rina Kartika',
                'recipient_phone' => '6285987654321',
                'template_code' => 'order_created',
                'message_body' => "Halo *Rina Kartika*,\n\nTerima kasih telah memesan mebel premium di *Assalam Mebel*! 🪵\n\n📌 *Detail Pesanan:*\n• No. Pesanan: *#" . ($rinaOrder?->order_number ?? 'ORD-8822') . "*\n• Produk: *Meja Makan Trembesi Solid*\n• Estimasi Total: *Rp 8.200.000*\n• Tagihan DP (50%): *Rp 4.100.000*\n\nSilakan transfer pembayaran DP agar pesanan Anda dapat segera dijadwalkan ke dapur produksi pengrajin kami.\n\n🔗 Pantau progres pesanan Anda di:\n" . url('/customer/progress'),
                'status' => 'Failed',
                'response_payload' => json_encode(['error' => 'Connection to WhatsApp Web Sidecar timeout. Retry requested.']),
                'retry_count' => 1,
                'last_retry_at' => now()->subMinutes(30),
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subMinutes(30),
            ],
        ];

        foreach ($logs as $log) {
            WaMessageLog::create($log);
        }
    }
}
