<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $katalogPath = storage_path('app/public/katalog');
        if (!File::exists($katalogPath)) {
            File::makeDirectory($katalogPath, 0755, true);
        }

        $produks = [
            [
                'nama' => 'Sofa Modern Luxury 3-Seater Emerald',
                'deskripsi' => 'Sofa ruang tamu elegan 3 dudukan dengan rangka kayu jati solid oven, busa high-density royal foam berlapis velvet emerald premium dengan aksen kaki stainless gold.',
                'harga' => 4850000,
                'filename' => 'sofa_luxury_emerald.jpg',
                'url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Set Meja Makan Jati Scandinavian 6 Kursi',
                'deskripsi' => 'Paket meja makan kayu jati perhutani grade A berkonsep Nordic Scandinavian. Dilengkapi 6 kursi ergonomis dengan sandaran lengkung dan dudukan empuk nyaman.',
                'harga' => 5400000,
                'filename' => 'meja_makan_scandinavian.jpg',
                'url' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Lemari Pakaian 3 Pintu Duco Minimalis Modern',
                'deskripsi' => 'Lemari pakaian kayu mahoni solid finishing cat duco putih matte anti rayap dan jamur. Dilengkapi cermin full body, gantungan baju luas, dan laci pakaian dalam bersekat.',
                'harga' => 3950000,
                'filename' => 'lemari_duco_minimalis.jpg',
                'url' => 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Tempat Tidur King Size Headboard Mewah Teak',
                'deskripsi' => 'Dipan tempat tidur ukuran King 180x200 cm berbahan kayu jati tua pilihan dengan sandaran kepala empuk berbalut kain woven premium, kokoh dan tanpa bunyi.',
                'harga' => 4750000,
                'filename' => 'tempat_tidur_king_size.jpg',
                'url' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Pintu Utama Kayu Jati Ukir Klasik Jepara',
                'deskripsi' => 'Sepasang daun pintu kupu tarung kayu jati solid tebal 4 cm dengan ukiran relief tradisional khas Jepara. Finishing natural dark walnut melamine doff tahan cuaca.',
                'harga' => 6500000,
                'filename' => 'pintu_ukir_jepara.jpg',
                'url' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Credenza TV Minimalis Japandi Teak Wood',
                'deskripsi' => 'Buffet TV minimalis panjang 180 cm kombinasi kayu jati natural dan aksen pintu rotan alami. Dilengkapi laci sistem push-to-open dan lubang manajemen kabel rapi.',
                'harga' => 2850000,
                'filename' => 'credenza_tv_japandi.jpg',
                'url' => 'https://images.unsplash.com/photo-1538688525198-9b88f6f53126?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Meja Kerja Direktur Kayu Jati Solid Natural',
                'deskripsi' => 'Meja kantor dan kerja eksekutif berukuran 160x80 cm dari kayu jati perhutani utuh dengan serat kayu eksotis, laci berkunci, dan finishing natural coating ramah lingkungan.',
                'harga' => 3600000,
                'filename' => 'meja_kerja_direktur.jpg',
                'url' => 'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Kursi Santai Lounge Armchair Retro Nordic',
                'deskripsi' => 'Kursi santai single seat dengan rangka kayu jati lengkung ergonomis, bantalan busa tebal berlapis kain tweed lembut, sangat cocok untuk ruang baca atau santai keluarga.',
                'harga' => 1950000,
                'filename' => 'kursi_santai_lounge.jpg',
                'url' => 'https://images.unsplash.com/photo-1567538096630-e0c55bd6374c?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Kitchen Set Minimalis Kayu Jati & Granit Top',
                'deskripsi' => 'Set kabinet dapur atas dan bawah custom dengan rangka jati solid anti-lembab, engsel hidrolik slow-motion, rak piring stainless, dan table top mewah.',
                'harga' => 8900000,
                'filename' => 'kitchen_set_minimalis.jpg',
                'url' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Rak Buku Partisi Ruangan Teak Industrial',
                'deskripsi' => 'Rak display buku dan partisi sekat ruangan multifungsi berukuran 120x200 cm berbahan kayu jati solid modular dengan konstruksi kokoh tanpa goyang.',
                'harga' => 3200000,
                'filename' => 'rak_buku_partisi.jpg',
                'url' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Meja Rias Vanity Duco Cermin LED Touchscreen',
                'deskripsi' => 'Meja rias modern warna broken white dengan cermin bulat berlampu LED 3 mode warna cahaya, laci aksesoris bersekat bludru, dan kursi puff senada.',
                'harga' => 2650000,
                'filename' => 'meja_rias_vanity.jpg',
                'url' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=1200&q=85',
            ],
            [
                'nama' => 'Bale-Bale Daybed Santai Jati Jepara Minimalis',
                'deskripsi' => 'Daybed santai bale-bale kayu jati berukuran 200x80 cm lengkap dengan kasur busa jok tebal dan 3 bantal peluk, cocok untuk teras depan atau ruang santai keluarga.',
                'harga' => 3450000,
                'filename' => 'bale_bale_daybed.jpg',
                'url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1200&q=85',
            ],
        ];

        // Hapus data dummy lama jika ada
        Produk::query()->delete();

        foreach ($produks as $p) {
            $destFile = $katalogPath . DIRECTORY_SEPARATOR . $p['filename'];
            $relPath = 'katalog/' . $p['filename'];

            // Unduh file gambar jika belum ada secara lokal
            if (!File::exists($destFile)) {
                try {
                    $response = Http::timeout(15)->get($p['url']);
                    if ($response->successful()) {
                        File::put($destFile, $response->body());
                    }
                } catch (\Throwable $e) {
                    Log::warning('Gagal download gambar produk: ' . $p['filename'] . ' - ' . $e->getMessage());
                }
            }

            // Jika file berhasil tersimpan di lokal, gunakan path lokal, jika tidak gunakan URL langsung
            $fotoValue = File::exists($destFile) ? $relPath : $p['url'];

            Produk::create([
                'nama' => $p['nama'],
                'deskripsi' => $p['deskripsi'],
                'harga' => $p['harga'],
                'foto' => $fotoValue,
            ]);
        }
    }
}

