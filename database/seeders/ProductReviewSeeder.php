<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder ulasan produk:
 * Setiap produk katalog mendapat ulasan lengkap dengan komentar & rating
 * sehingga halaman detail produk langsung menampilkan ulasan yang informatif.
 *
 * Memakai akun pelanggan demo yang dibuat AdminSeeder (budi, rina, ahmad).
 * Jika belum ada pelanggan sama sekali (mis. seeder dijalankan sendirian),
 * dibuat beberapa akun pelanggan contoh.
 */
class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->orderBy('id')->get();

        // Fallback: buat akun pelanggan contoh hanya jika database benar-benar kosong
        if ($customers->isEmpty()) {
            $dummyCustomers = [
                ['name' => 'Budi Santoso', 'username' => 'budisantoso', 'email' => 'budi.santoso@assalam.test', 'whatsapp_number' => '628123456789', 'alamat' => 'Jl. Raya Pamekasan No. 12, Madura', 'password' => bcrypt('password123')],
                ['name' => 'Siti Aminah', 'username' => 'siti.aminah', 'email' => 'siti.aminah@assalam.test', 'whatsapp_number' => '628123456790', 'alamat' => 'Jl. Diponegoro No. 45, Surabaya', 'password' => bcrypt('password123')],
                ['name' => 'Ahmad Fauzi', 'username' => 'ahmadfauzi', 'email' => 'ahmad.fauzi@assalam.test', 'whatsapp_number' => '628123456791', 'alamat' => 'Perum Griya Asri Blok C-8, Jakarta', 'password' => bcrypt('password123')],
                ['name' => 'Dewi Lestari', 'username' => 'dewilestari', 'email' => 'dewi.lestari@assalam.test', 'whatsapp_number' => '628123456792', 'alamat' => 'Jl. Kartini No. 7, Semarang', 'password' => bcrypt('password123')],
            ];

            foreach ($dummyCustomers as $data) {
                $existing = User::where('email', $data['email'])->first();
                if (!$existing) {
                    User::create(array_merge($data, ['role' => 'customer']));
                }
            }

            $customers = User::where('role', 'customer')->orderBy('id')->get();
        }

        // Pool komentar ulasan realistis untuk mebel ukir kayu jati
        $reviewPool = [
            ['rating' => 5, 'title' => 'Ukiran sangat rapi dan indah', 'comment' => 'Kualitas ukirannya luar biasa, detail motif bunga dan sulurnya sangat halus. Kayu jati terasa padat dan berkualitas. Packing juga aman saat pengiriman. Sangat direkomendasikan!'],
            ['rating' => 5, 'title' => 'Puas sekali, mebel impian tercapai', 'comment' => 'Awalnya ragu beli online karena harga lumayan, ternyata tidak mengecewakan. Finishing warnanya persis seperti foto katalog. Pelayanan admin juga responsif saat ditanya-tanya dulu.'],
            ['rating' => 4, 'title' => 'Kualitas bagus, pengiriman agak lama', 'comment' => 'Produk sesuai deskripsi, kayu jati asli dan ukirannya bagus. Hanya proses pembuatannya agak lama karena antrean produksi, tapi hasilnya sepadan dengan waktu tunggu.'],
            ['rating' => 5, 'title' => 'Kualitas premium, langganan tetap', 'comment' => 'Sudah order kedua kali di sini. Konsisten kualitasnya. Kayu solid, tidak mengecor, dan ukiran dibuat manual oleh pengrajin berpengalaman. Terima kasih Assalam Mebel!'],
            ['rating' => 4, 'title' => 'Bagus dan kokoh', 'comment' => 'Mebelnya kokoh dan stabil, tidak goyang sama sekali. Warna tonenya elegan. Sedikit catatan: sebaiknya dilapisi kain pelindung di bagian kaki. Overall puas.'],
            ['rating' => 5, 'title' => 'Desain sesuai permintaan', 'comment' => 'Saya request penyesuaian ukuran lewat Studio Custom dan hasilnya persis seperti desain yang saya ajukan. Komunikasinya lancar dari awal sampai barang jadi.'],
            ['rating' => 3, 'title' => 'Cukup baik, ada perbaikan kecil', 'comment' => 'Secara umum produk bagus. Ada sedikit perbedaan warna dengan foto katalog, mungkin efek pencahayaan. Admin menanggapi dengan baik dan memberi solusi.'],
            ['rating' => 5, 'title' => 'Cocok untuk hadiah pernikahan', 'comment' => 'Dibeli sebagai hadiah untuk keluarga, sampainya dengan selamat dan penerimanya sangat senang. Ukirannya mewah dan classy. Harga sepadan dengan kualitas.'],
            ['rating' => 4, 'title' => 'Recommended seller mebel jati', 'comment' => 'Proses DP dan pelunasannya jelas, ada progres produksi yang bisa dipantau step by step. Jadi tenang karena tahu pesanan kita dikerjakan sungguhan.'],
            ['rating' => 5, 'title' => 'Kayu jati asli, bukan abu-abu', 'comment' => 'Sebagai orang yang paham kayu, saya memastikan ini jati asli perhutani. Serat kayunya indah dan beratnya sesuai. Ukiran tangan terlihat dari detailnya. Mantap!'],
        ];

        foreach (Produk::all() as $index => $produk) {
            // Lewati produk yang sudah punya ulasan agar seeder aman dijalankan ulang
            if ($produk->reviews()->exists()) {
                continue;
            }

            // Setiap produk mendapat 2-4 ulasan
            $reviewCount = 2 + ($index % 3);
            $usedTitles = [];

            for ($i = 0; $i < $reviewCount; $i++) {
                $template = $reviewPool[($index * 2 + $i) % count($reviewPool)];

                // Hindari duplikasi ulasan identik pada produk yang sama
                if (in_array($template['title'], $usedTitles)) {
                    $template = $reviewPool[($index * 2 + $i + 5) % count($reviewPool)];
                }
                $usedTitles[] = $template['title'];

                $author = $customers[($index + $i) % max(1, $customers->count())] ?? null;
                if (!$author) {
                    continue;
                }

                ProductReview::create([
                    'produk_id' => $produk->id,
                    'user_id' => $author->id,
                    'rating' => $template['rating'],
                    'title' => $template['title'],
                    'comment' => $template['comment'],
                    'is_verified_buyer' => ($i % 2 === 0), // sebagian berstatus pembeli terverifikasi
                    'status' => 'Disetujui',
                    'created_at' => now()->subDays(rand(3, 90)),
                ]);
            }
        }
    }
}
