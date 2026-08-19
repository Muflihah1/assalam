# ASSALAM MEBEL - Sistem Informasi Penjualan & Custom Furniture Kayu Solid

Website e-commerce dan studio kustomisasi mebel berbasis **Laravel**, menghadirkan pengalaman belanja mebel kayu jati & mahoni solid kualitas Jepara, dilengkapi dengan Studio Custom Desain 3D interaktif, pelacakan progres produksi real-time, dan notifikasi WhatsApp Gateway otomatis menggunakan *Node.js WhatsApp Sidecar*.

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Persiapan Awal & Dependensi
Pastikan **PHP >= 8.2**, **Composer**, dan **Node.js** (beserta npm) sudah terpasang di sistem Anda.

```bash
# Clone repository
git clone https://github.com/Muflihah1/assalam.git
cd assalam

# Install dependensi PHP & Node.js
composer install
npm install

# Setup file environment & app key
cp .env.example .env
php artisan key:generate

# Migrasi Database & Seeder (otomatis download foto katalog mebel asli)
php artisan migrate:fresh --seed
php artisan storage:link
```

---

### 2. Instalasi & Menjalankan WhatsApp Gateway (Sidecar)

Aplikasi ini menggunakan modul **WhatsApp Web Sidecar** (`kstmostofa/laravel-whatsapp`) berbasis Node.js untuk mengirimkan pesan notifikasi progres secara real-time tanpa perlu API berbayar.

```bash
# 1. Install dependensi Node untuk WhatsApp Sidecar
php artisan whatsapp:sidecar:install

# 2. Jalankan WhatsApp Sidecar di background (port 3000)
php artisan whatsapp:sidecar:start

# 3. Periksa status koneksi Sidecar
php artisan whatsapp:sidecar:status
# atau
php artisan whatsapp:health
```

#### Menghubungkan Nomor WhatsApp:
1. Buka Admin Panel di browser: `http://127.0.0.1:8000/admin/whatsapp`
2. Pindai **QR Code** langsung menggunakan aplikasi WhatsApp di HP Anda (*Perangkat Tertaut > Tautkan Perangkat*) atau gunakan fitur **Pairing Code 8 Digit**.
3. Setelah terhubung, status gateway otomatis berubah menjadi **Ready** / **Terhubung**.

> **Tips:** Untuk menghentikan proses background Sidecar, gunakan perintah:
> ```bash
> php artisan whatsapp:sidecar:stop
> ```

---

### 3. Menjalankan Aplikasi

Cukup jalankan satu perintah berikut di terminal:

```bash
composer run dev
```

Perintah `composer run dev` ini secara otomatis menjalankan seluruh layanan yang dibutuhkan aplikasi secara bersamaan (*concurrently*):
- 🌐 **Laravel Web Server** (`php artisan serve`) pada `http://127.0.0.1:8000`
- ⚡ **Vite Asset Bundler** (`npm run dev`)
- 📨 **Queue Worker / Listener** (`php artisan queue:listen`)

---

## 🌟 Fitur Unggulan

### 🛒 1. Katalog Produk Nyata (Real Furniture Photography)
- Koleksi furnitur kayu solid lengkap dengan foto asli beresolusi tinggi (bukan mockup).
- Kategori: Sofa Luxury Velvet, Meja Makan Scandinavian Jati, Lemari Pakaian Duco 3 Pintu, King Size Bed Mewah, Pintu Ukir Klasik Jepara, Credenza TV Japandi, Meja Kerja Direktur, Kursi Lounge Nordic, Kitchen Set Minimalis Jati, Rak Buku Partisi, Meja Rias LED, Bale-Bale Daybed.
- Modal detail interaktif dengan pemilihan kuantitas barang dan pembelian langsung ke keranjang.

### 🎨 2. Studio Custom Desain 3D
- Kustomisasi ukuran dimensi presisi (panjang, lebar, tinggi dalam cm).
- Pilihan jenis kayu solid: **Kayu Jati Perhutani Grade A**, **Kayu Mahoni Solid**, **Kayu Mindi**, **Kayu Sungkai**.
- Pilihan warna finishing: Natural Teak, Walnut Glossy, Dark Mahogani, White Duco, Salak Brown.
- Rotasi 360°, penyesuaian pencahayaan real-time, dan kalkulasi estimasi harga otomatis yang transparan.

### 📦 3. Manajemen Pesanan & Keranjang Belanja
- Sistem checkout dengan opsi pembayaran uang muka (DP 50%) atau bayar lunas.
- Integrasi tarif ongkos kirim dinamis berdasarkan wilayah tujuan.
- Upload bukti transfer pembayaran dan verifikasi admin.

### 🔄 4. Pelacakan Progres Produksi Real-Time
- Pelanggan dapat memantau setiap tahapan pengerjaan (Pemilihan Kayu, Pemotongan & Perakitan, Pengukiran, Pengamplasan, Finishing & Pengecatan, Kontrol Kualitas / QC, Pengemasan & Pengiriman).
- Dokumentasi foto & catatan pengerjaan di setiap timeline.

### 📲 5. WhatsApp Gateway & Notifikasi Otomatis
- Kirim pesan pembaruan otomatis langsung ke nomor WhatsApp pelanggan setiap kali status produksi diperbarui oleh admin.
- Pengaturan template pesan dinamis (*placeholder*: `{nama}`, `{produk}`, `{no_pesanan}`, `{tahap}`).
- Riwayat log pengiriman pesan beserta fitur *retry* jika gagal.

### ⚙️ 6. Dashboard Admin Lengkap
- Manajemen Pesanan Masuk (Verifikasi DP / Pelunasan).
- Pembaruan status produksi beserta upload dokumentasi foto progres.
- Manajemen Katalog Produk (Tambah, Edit, Hapus, Upload Foto).
- Pengaturan Profil Bengkel, Template WhatsApp, & Tarif Ongkir per Kota.

---

## 🛠️ Teknologi yang Digunakan
- **Backend**: Laravel 12.x (PHP 8.2+)
- **WhatsApp Engine**: WhatsApp Web Sidecar (`kstmostofa/laravel-whatsapp` & `whatsapp-web.js`)
- **Frontend**: Blade Templating, Bootstrap 5, Vanilla CSS, FontAwesome 6, Three.js
- **Database**: SQLite / MySQL

---

*Dikembangkan untuk ASSALAM MEBEL - Toko & Custom Furniture Kayu Solid.*
