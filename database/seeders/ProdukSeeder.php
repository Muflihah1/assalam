<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan folder penyimpanan storage/app/public/produk tersedia
        $storageProdukDir = storage_path('app/public/produk');
        if (!File::exists($storageProdukDir)) {
            File::makeDirectory($storageProdukDir, 0755, true);
        }

        // Folder sumber di public/produk
        $publicProdukDir = public_path('produk');
        if (!File::exists($publicProdukDir)) {
            File::makeDirectory($publicProdukDir, 0755, true);
        }

        // Sinkronisasi file gambar antara public/produk dan storage/app/public/produk
        if (File::exists($publicProdukDir)) {
            $files = File::files($publicProdukDir);
            foreach ($files as $file) {
                $target = $storageProdukDir . DIRECTORY_SEPARATOR . $file->getFilename();
                if (!File::exists($target)) {
                    File::copy($file->getRealPath(), $target);
                }
            }
        }

        // 2. Daftar 13 Produk sesuai katalog-produk-mebel-ukir.md
        $produks = [
            [
                'id' => 1,
                'nama' => 'Kursi Ukir (1 Set)',
                'deskripsi' => 'Set kursi tamu ukir kayu jati, terdiri dari sofa besar, sepasang kursi kecil, dan meja tengah, ukiran motif bunga dan sulur khas Madura. Ukuran: Sofa: P150×L60×T95 cm; kursi kecil: P70×60 cm; meja: P120×L55×T45 cm.',
                'harga' => 32000000,
                'foto' => 'produk/01_kursi-sofa-ukir-set.jpg',
            ],
            [
                'id' => 2,
                'nama' => 'Podium/Mimbar Kayu Ukir Logo Garuda',
                'deskripsi' => 'Podium kayu jati dengan lambang Garuda Pancasila berwarna emas dan papan nama "DESA PAKAMBAN LAOK", motif ukir batik pada badan podium.',
                'harga' => 4500000,
                'foto' => 'produk/02_podium-desa-pakamban-laok.jpg',
            ],
            [
                'id' => 3,
                'nama' => 'Pintu Tarung Full Ukir',
                'deskripsi' => 'Sepasang pintu kayu jati ukir penuh motif bunga dan sulur daun, bagian atas melengkung (arch top). Ukuran: 250×130 cm, tebal 4 cm.',
                'harga' => 8000000,
                'foto' => 'produk/03_pintu-tarung-full-ukir.jpg',
            ],
            [
                'id' => 4,
                'nama' => 'Logo NU Ukir',
                'deskripsi' => 'Panel kayu ukir logo Nahdlatul Ulama (NU) dengan finishing prada emas, dilengkapi kaligrafi Arab dan bintang sembilan. Ukuran: 150×100 cm, tebal 3 cm.',
                'harga' => 2000000,
                'foto' => 'produk/04_logo-NU.jpg',
            ],
            [
                'id' => 5,
                'nama' => 'Lemari 2 Pintu Sliding',
                'deskripsi' => 'Lemari pakaian pintu geser (sliding) 2 pintu dengan 2 laci bawah, motif garis minimalis, finishing coklat tua.',
                'harga' => 2200000,
                'foto' => 'produk/05_lemari-2-pintu-sliding.jpg',
            ],
            [
                'id' => 6,
                'nama' => 'Kursi Sidang 5 Set + Meja',
                'deskripsi' => 'Meja dan 5 kursi sidang ukir kayu jati dengan jok bludru biru, ukiran prada warna-warni dan logo lambang di tengah meja.',
                'harga' => 16500000,
                'foto' => 'produk/06_kursi-sidang-5-set-meja.jpg',
            ],
            [
                'id' => 7,
                'nama' => 'Pendopo/Gazebo Kayu Jati',
                'deskripsi' => 'Bangunan pendopo terbuka kayu jati dengan atap joglo genteng tanah liat, tiang-tiang penyangga berukir.',
                'harga' => 45000000,
                'foto' => 'produk/07_pendopo-gazebo-jati.jpg',
            ],
            [
                'id' => 8,
                'nama' => 'Kursi Sofa Motif 1 (Set 3)',
                'deskripsi' => 'Set sofa 3 buah (1 sofa panjang + 2 kursi single) dengan meja tengah, ukiran prada warna-warni motif bunga.',
                'harga' => 10000000,
                'foto' => 'produk/08_kursi-sofa-motif-1-set-3.jpg',
            ],
            [
                'id' => 9,
                'nama' => 'Blawong',
                'deskripsi' => 'Panel ukir gantung (blawong) sepasang burung phoenix/merak dengan motif sulur, kayu jati. Ukuran: 65×45 cm, tebal 2 cm.',
                'harga' => 250000,
                'foto' => 'produk/09_blawong-65x45.jpg',
            ],
            [
                'id' => 10,
                'nama' => 'Lemari Minimalis 10 Pintu',
                'deskripsi' => 'Lemari serbaguna minimalis 10 pintu dengan rak kaca dan ruang terbuka tengah, finishing natural kayu. Ukuran: P200×L65 cm, tinggi 190 cm.',
                'harga' => 2500000,
                'foto' => 'produk/10_lemari-minimalis-10-pintu.jpg',
            ],
            [
                'id' => 11,
                'nama' => 'Blawong (Set 2 Motif)',
                'deskripsi' => 'Sepasang panel ukir gantung motif burung dan bunga, warna prada emas dan ungu.',
                'harga' => 500000,
                'foto' => 'produk/11_blawong-set-2-motif.jpg',
            ],
            [
                'id' => 12,
                'nama' => 'Ukiran 30×30 (Set 2)',
                'deskripsi' => 'Panel ukir persegi motif bunga dan sulur daun, kayu jati, dijual berpasangan. Ukuran: 30×30 cm, tebal 2 cm.',
                'harga' => 150000,
                'foto' => 'produk/12_ukiran-30x30-set-2.jpg',
            ],
            [
                'id' => 13,
                'nama' => 'Lemari Rias Kaca',
                'deskripsi' => 'Lemari rias dengan cermin besar berlampu, 2 lemari kaca samping dan 4 laci tengah. Ukuran: P150×L55 cm, tinggi 160 cm.',
                'harga' => 2200000,
                'foto' => 'produk/13_lemari-rias-kaca.jpg',
            ],
        ];

        // 3. Reset dan isi ulang tabel produks
        Schema::disableForeignKeyConstraints();
        Produk::truncate();
        Schema::enableForeignKeyConstraints();

        foreach ($produks as $p) {
            Produk::create($p);
        }
    }
}
