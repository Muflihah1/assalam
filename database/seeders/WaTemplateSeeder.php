<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WaTemplate;

class WaTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'code' => 'order_created',
                'name' => 'Konfirmasi Pesanan Masuk',
                'event_trigger' => 'Saat Pelanggan Checkout / Mengajukan Pesanan Custom',
                'content' => "Halo *{nama}*,\n\nTerima kasih telah memesan mebel premium di *Assalam Mebel*! 🪵\n\n📌 *Detail Pesanan:*\n• No. Pesanan: *#{no_pesanan}*\n• Produk: *{produk}*\n• Estimasi Total: *Rp {total_harga}*\n• Tagihan DP (50%): *Rp {dp_amount}*\n\nSilakan transfer pembayaran DP agar pesanan Anda dapat segera dijadwalkan ke dapur produksi pengrajin kami.\n\n🔗 Pantau progres pesanan Anda di:\n{link_tracking}\n\nSalam hangat,\n*Tim Assalam Mebel Jepara*",
                'is_active' => true,
            ],
            [
                'code' => 'dp_verified',
                'name' => 'Verifikasi Pembayaran DP Berhasil',
                'event_trigger' => 'Saat Admin Memverifikasi Pembayaran DP',
                'content' => "Halo *{nama}*,\n\nPembayaran Uang Muka (DP) untuk pesanan *#{no_pesanan}* (*{produk}*) sebesar *Rp {dp_amount}* telah *TERVERIFIKASI* oleh tim Assalam Mebel. ✅\n\nPengrajin kami kini mulai menyiapkan material kayu solid pilihan untuk memproduksi furniture impian Anda.\n\n🔗 Cek pembaruan status pengerjaan:\n{link_tracking}\n\nTerima kasih atas kepercayaan Anda!",
                'is_active' => true,
            ],
            [
                'code' => 'progress_updated',
                'name' => 'Pembaruan Tahap Produksi Mebel',
                'event_trigger' => 'Saat Admin Mengubah Tahap Pengerjaan di Menu Progres Produksi',
                'content' => "Halo *{nama}*,\n\nAda kabar terbaru untuk pesanan furniture Anda (*#{no_pesanan}* - *{produk}*)! 🪚✨\n\n🎯 *Tahap Pengerjaan Saat Ini:*\n👉 *{tahap}*\n\n📝 *Catatan Pengrajin:*\n_{catatan}_\n\n📸 Foto dokumentasi pengerjaan telah kami unggah ke sistem. Anda dapat melihat foto & detail tahapan secara langsung di tautan berikut:\n{link_tracking}\n\nSalam,\n*Assalam Mebel Jepara*",
                'is_active' => true,
            ],
            [
                'code' => 'payment_completed',
                'name' => 'Konfirmasi Pelunasan Pembayaran',
                'event_trigger' => 'Saat Pembayaran Sisa Tagihan Dilunasi',
                'content' => "Halo *{nama}*,\n\nPembayaran pelunasan untuk pesanan *#{no_pesanan}* telah kami terima dengan sukses. Status tagihan Anda saat ini: *LUNAS* 🎉\n\nFurniture Anda sedang melalui tahap pengecekan kualitas akhir (Quality Control) dan siap dikemas rapi untuk pengiriman aman.\n\n🔗 Pantau pengiriman di:\n{link_tracking}",
                'is_active' => true,
            ],
            [
                'code' => 'order_finished',
                'name' => 'Pesanan Selesai & Dikirim',
                'event_trigger' => 'Saat Pesanan Diserahkan ke Ekspedisi / Telah Tiba',
                'content' => "Halo *{nama}*,\n\nPesanan furniture *#{no_pesanan}* (*{produk}*) telah selesai diproduksi dan kini dalam perjalanan menuju alamat Anda! 🚚📦\n\nSemoga furniture kayu solid dari Assalam Mebel mempercantik ruangan Anda dan awet berpuluh-puluh tahun. Jangan ragu menghubungi kami jika memerlukan panduan perawatan mebel kayu solid.\n\nTerima kasih telah berbelanja di *Assalam Mebel*! ❤️",
                'is_active' => true,
            ],
        ];

        foreach ($templates as $tmpl) {
            WaTemplate::updateOrCreate(['code' => $tmpl['code']], $tmpl);
        }
    }
}
