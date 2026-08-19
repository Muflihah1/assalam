# ASSALAM MEBEL - Sistem Informasi Penjualan & Custom Furniture Kayu Solid

Website e-commerce dan studio kustomisasi mebel berbasis **Laravel**, menghadirkan pengalaman belanja mebel kayu jati & mahoni solid kualitas Jepara, dilengkapi dengan Studio Custom Desain interaktif, pelacakan progres produksi, dan notifikasi WhatsApp Gateway otomatis.

---

## 🌟 Fitur Utama

### 🛒 1. Katalog Produk Nyata (Real Furniture Photography)
- Koleksi furnitur kayu solid lengkap dengan foto asli beresolusi tinggi (bukan mockup).
- Pilihan kategori: Sofa Luxury, Meja Makan Scandinavian, Lemari Pakaian Duco, Tempat Tidur King Size, Pintu Ukir Klasik Jepara, Credenza TV Japandi, Meja Kerja Direktur, dll.
- Modal detail produk interaktif dengan opsi penentuan kuantitas belanja dan pembelian langsung ke keranjang.

### 🎨 2. Studio Custom Desain 3D
- Kustomisasi ukuran dimensi presisi (panjang, lebar, tinggi dalam cm).
- Pilihan jenis kayu solid: **Kayu Jati Perhutani Grade A**, **Kayu Mahoni Solid**, **Kayu Mindi**, **Kayu Sungkai**.
- Pilihan warna finishing: Natural Teak, Walnut Glossy, Dark Mahogani, White Duco, Salak Brown.
- Fitur rotasi 360°, penyesuaian pencahayaan real-time, dan kalkulasi estimasi harga otomatis secara transparan.

### 📦 3. Manajemen Pesanan & Keranjang Belanja
- Sistem checkout dengan opsi pembayaran uang muka (DP 50%) atau bayar lunas.
- Integrasi tarif ongkos kirim dinamis berdasarkan wilayah tujuan.
- Upload bukti transfer pembayaran dengan konfirmasi status otomatis.

### 🔄 4. Pelacakan Progres Produksi Real-Time
- Pelanggan dapat memantau setiap tahapan pengerjaan (Pemilihan Kayu, Pemotongan & Perakitan, Pengukiran, Pengamplasan, Finishing & Pengecatan, Kontrol Kualitas / QC, Pengemasan & Pengiriman).
- Dokumentasi foto & catatan pengerjaan di setiap timeline.

### 📲 5. WhatsApp Gateway & Notifikasi Otomatis
- Kirim pesan pembaruan otomatis langsung ke nomor WhatsApp pelanggan setiap kali status produksi diperbarui oleh admin.
- Pengaturan template pesan dinamis (*placeholder*: `{nama}`, `{produk}`, `{no_pesanan}`, `{tahap}`).

### ⚙️ 6. Dashboard Admin Lengkap
- Manajemen Pesanan Masuk (Verifikasi DP / Pelunasan).
- Pembaruan status produksi beserta upload dokumentasi foto progres.
- Manajemen Katalog Produk (Tambah, Edit, Hapus, Upload Foto).
- Pengaturan Profil Bengkel & Tarif Ongkir per Kota.

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

1. **Clone Repository:**
   ```bash
   git clone https://github.com/Muflihah1/assalam.git
   cd assalam
   ```

2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (`.env`):**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi Database & Seeder Data Awal (termasuk unduh otomatis foto mebel asli):**
   ```bash
   php artisan migrate:fresh --seed
   php artisan storage:link
   ```

5. **Jalankan Aplikasi:**
   ```bash
   # Di terminal 1 (Laravel Dev Server):
   php artisan serve

   # Di terminal 2 (Vite Asset Bundler):
   npm run dev
   ```

---

## 🛠️ Teknologi yang Digunakan
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Blade Templating, Bootstrap 5, Vanilla CSS, FontAwesome 6, Three.js
- **Database**: SQLite / MySQL
- **Integrasi**: WhatsApp API Gateway

---

*Dikembangkan untuk ASSALAM MEBEL - Jepara & Pasuruan Furniture Craftsmanship.*
