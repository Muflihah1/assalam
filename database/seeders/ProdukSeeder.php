<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $produks = [
            [
                'nama' => 'Sofa Modern Luxury 3 Seater',
                'deskripsi' => 'Sofa sudut ruang tamu dengan rangka kayu jati solid, busa royal foam berlapis kain beludru impor super lembut dan tahan lama.',
                'harga' => 4800000,
                'foto' => 'katalog/sofa_luxury.jpg',
            ],
            [
                'nama' => 'Set Meja Makan Jati Scandinavian',
                'deskripsi' => 'Paket 1 meja makan minimalis kayu jati grade A + 6 kursi dudukan empuk ergonomis dengan finishing melamine natural glossy.',
                'harga' => 5200000,
                'foto' => 'katalog/meja_makan.jpg',
            ],
            [
                'nama' => 'Lemari Pakaian 3 Pintu Duco Minimalis',
                'deskripsi' => 'Lemari pakaian kayu mahoni solid dengan full cermin tengah, gantungan luas, rak bertingkat, dan cat duco putih premium anti-jamur.',
                'harga' => 3800000,
                'foto' => 'katalog/lemari_duco.jpg',
            ],
            [
                'nama' => 'Tempat Tidur King Size Headboard Mewah',
                'deskripsi' => 'Dipan tempat tidur ukuran 180x200 cm berbahan kayu jati perhutani dengan sandaran busa berbalut kulit sintetis eksklusif.',
                'harga' => 4500000,
                'foto' => 'katalog/tempat_tidur.jpg',
            ],
            [
                'nama' => 'Pintu Utama Ukir Klasik Jepara',
                'deskripsi' => 'Sepasang daun pintu kupu tarung kayu jati tua tebal 4cm dengan ukiran motif khas Jepara bernilai seni tinggi.',
                'harga' => 6200000,
                'foto' => 'katalog/pintu_ukir.jpg',
            ],
            [
                'nama' => 'Credenza TV Minimalis Modern Teak',
                'deskripsi' => 'Meja buffet TV panjang 180cm multifungsi dengan laci soft-close dan rak penyimpanan rapi bergaya Japandi.',
                'harga' => 2750000,
                'foto' => 'katalog/credenza_tv.jpg',
            ],
        ];

        foreach ($produks as $p) {
            Produk::updateOrCreate(['nama' => $p['nama']], $p);
        }
    }
}
