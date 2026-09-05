# Daftar Masalah, Penjelasan, dan Solusi
## Sistem Penjualan Produk Mebel — Project SHOP

Dokumen ini berisi daftar permasalahan yang ditemukan pada sistem penjualan produk mebel pada Project SHOP, beserta penjelasan dan solusi yang perlu diterapkan.

---

## 1. Validasi Input pada Pendaftaran Akun

### Masalah
Pada form pendaftaran akun, field yang seharusnya hanya menerima angka masih dapat diisi menggunakan huruf.

### Penjelasan
Kondisi ini dapat menyebabkan data yang tersimpan tidak sesuai dengan format yang seharusnya. Contohnya adalah nomor telepon yang seharusnya hanya berisi angka, tetapi sistem masih menerima karakter alfabet.

Validasi hanya pada tampilan (frontend) juga belum cukup karena data dapat dimanipulasi melalui request secara langsung.

### Solusi
- Tambahkan validasi input pada frontend.
- Field yang hanya diperbolehkan berisi angka harus menggunakan aturan input numerik.
- Tambahkan validasi kembali pada backend.
- Pastikan data yang masuk ke database telah sesuai dengan format yang ditentukan.
- Berikan pesan error yang jelas apabila input tidak sesuai.

---

## 2. Alur Setelah Registrasi Akun Tidak Sesuai

### Masalah
Setelah pelanggan berhasil melakukan pendaftaran, sistem langsung masuk ke akun tanpa melalui halaman login terlebih dahulu. Selain itu, username pelanggan tidak tersedia atau tidak ditampilkan dengan benar.

### Penjelasan
Alur autentikasi seharusnya memisahkan proses registrasi dan login. Setelah registrasi berhasil, data akun disimpan dan pelanggan diarahkan ke halaman login untuk melakukan autentikasi menggunakan akun yang baru dibuat.

Username juga perlu disimpan dan ditampilkan dengan benar karena dapat digunakan sebagai identitas akun pelanggan.

### Solusi
- Setelah registrasi berhasil, arahkan pelanggan ke halaman login.
- Jangan otomatis membuat sesi login apabila alur sistem memang mengharuskan pelanggan login setelah registrasi.
- Pastikan username tersimpan di database.
- Pastikan username ditampilkan pada halaman profil/dashboard pelanggan.
- Validasi username agar tidak terjadi duplikasi jika username harus bersifat unik.

---

## 3. Produk Katalog Tidak Terhubung dengan Fitur Kostumisasi

### Masalah
Ketika pelanggan memilih produk dari katalog dan masuk ke fitur kostumisasi, halaman desain dimulai dari awal dan tidak menggunakan produk yang sebelumnya dipilih.

### Penjelasan
Produk yang dipilih dari katalog seharusnya menjadi produk dasar yang akan dikostumisasi. Jika hubungan tersebut tidak ada, pelanggan harus memilih atau mengisi ulang informasi produk dari awal.

Hal ini juga dapat menyebabkan data produk, harga dasar, kategori, dan gambar tidak sesuai dengan produk yang dipilih pelanggan.

### Solusi
- Kirim `product_id` atau ID produk ketika pelanggan memilih fitur kostumisasi.
- Halaman kostumisasi mengambil data produk berdasarkan ID tersebut.
- Tampilkan informasi produk yang dipilih sebagai produk dasar.
- Data kostumisasi disimpan dengan referensi ke produk utama.
- Pastikan harga dasar produk tetap terhubung dengan produk katalog.
- Hindari membuat produk baru dari nol apabila pelanggan sebenarnya sedang mengkostumisasi produk yang sudah ada di katalog.

---

## 4. Validasi Form Desain/Kostumisasi

### Masalah
Pelanggan harus mengisi seluruh bagian desain sebelum dapat melakukan pemesanan atau memasukkan produk ke keranjang, tetapi validasi pada form belum berjalan dengan baik.

### Penjelasan
Produk mebel yang dikostumisasi membutuhkan informasi desain yang lengkap agar pesanan dapat diproses oleh admin dan bagian produksi.

Jika terdapat data desain yang kosong, pesanan dapat menjadi tidak lengkap dan berpotensi menimbulkan kesalahan dalam proses produksi.

### Solusi
- Tentukan field mana saja yang wajib diisi.
- Tambahkan validasi pada setiap field wajib.
- Tombol **Pesan** atau **Tambah ke Keranjang** hanya dapat diproses apabila seluruh data wajib telah lengkap.
- Tampilkan pesan yang menjelaskan bagian desain yang belum diisi.
- Lakukan validasi kembali di backend.
- Simpan seluruh detail kostumisasi bersama ID produk dan ID pelanggan.

---

## 5. Belum Ada Konfirmasi Pesanan oleh Admin

### Masalah
Ketika pelanggan melakukan pemesanan, belum terdapat mekanisme yang memungkinkan admin menerima atau menolak pesanan.

### Penjelasan
Dalam sistem penjualan mebel, terutama untuk produk custom, admin perlu memeriksa pesanan terlebih dahulu. Admin perlu memastikan detail produk, ukuran, desain, jumlah, harga, dan informasi lainnya sesuai sebelum pesanan diterima.

Pesanan tidak seharusnya langsung dianggap diterima tanpa proses pemeriksaan.

### Solusi
Tambahkan sistem status pesanan, misalnya:

- `Menunggu Konfirmasi`
- `Diterima`
- `Ditolak`
- `Menunggu Pembayaran`
- `Pembayaran Diverifikasi`
- `Diproses`
- `Selesai`
- `Dibatalkan`

Admin dapat:
- Melihat detail pesanan.
- Menerima pesanan.
- Menolak pesanan.
- Memberikan alasan jika pesanan ditolak.
- Mengubah status pesanan sesuai proses transaksi.

Pelanggan harus dapat melihat status terbaru dari pesanannya.

---

## 6. Keamanan Sistem Pembayaran Belum Memadai

### Masalah
Sistem pembayaran belum memiliki mekanisme keamanan dan verifikasi transaksi yang memadai.

### Penjelasan
Status pembayaran tidak boleh hanya ditentukan dari sisi frontend. Jika sistem hanya mempercayai data yang dikirim oleh browser pelanggan, status pembayaran dapat dimanipulasi.

Untuk sistem penjualan, pembayaran harus dapat diverifikasi secara aman dan memiliki identitas transaksi yang jelas.

### Solusi
- Gunakan payment gateway yang memiliki mekanisme verifikasi transaksi apabila memungkinkan.
- Lakukan verifikasi pembayaran pada backend.
- Jangan mempercayai status pembayaran yang dikirim langsung dari frontend.
- Gunakan ID transaksi yang unik.
- Simpan riwayat transaksi dan status pembayaran di database.
- Gunakan HTTPS.
- Terapkan autentikasi dan otorisasi pada endpoint pembayaran.
- Pastikan pelanggan hanya dapat melihat transaksi miliknya sendiri.
- Gunakan webhook/payment notification dari payment gateway jika tersedia.
- Pastikan perubahan status pembayaran dilakukan melalui proses yang tervalidasi.

---

## 7. Total Pelanggan pada Dashboard Admin Mengalami Error

### Masalah
Bagian dashboard admin yang menampilkan total pelanggan mengalami error atau menampilkan jumlah yang tidak sesuai.

### Penjelasan
Dashboard admin seharusnya mengambil jumlah pelanggan berdasarkan data yang terdapat di database. Kesalahan dapat terjadi pada query database, filter role pengguna, relasi tabel, atau proses pengambilan data dari backend.

### Solusi
- Periksa query untuk menghitung jumlah pelanggan.
- Pastikan sistem hanya menghitung akun dengan role/status pelanggan.
- Periksa koneksi antara backend dan database.
- Periksa endpoint/API yang digunakan dashboard.
- Pastikan data yang ditampilkan merupakan data terbaru.
- Tangani kondisi ketika database tidak memiliki data pelanggan agar dashboard tidak menghasilkan error.

---

## 8. Halaman Akun Pelanggan pada Admin Mengalami Error

### Masalah
Admin mengalami error ketika mengakses atau mengelola data akun pelanggan.

### Penjelasan
Fitur pengelolaan pelanggan merupakan bagian penting dari dashboard admin. Admin perlu dapat melihat informasi pelanggan dengan benar untuk mendukung proses pengelolaan transaksi dan pesanan.

Error dapat disebabkan oleh query database, struktur data yang tidak sesuai, endpoint yang bermasalah, atau permission/role yang tidak benar.

### Solusi
- Periksa query pengambilan data pelanggan.
- Periksa struktur tabel pengguna/pelanggan.
- Pastikan relasi database berjalan dengan benar.
- Periksa endpoint/API untuk data pelanggan.
- Pastikan middleware/authorization memberikan akses kepada admin.
- Tambahkan error handling ketika data pelanggan tidak ditemukan.
- Pastikan admin tidak dapat mengakses data atau fungsi yang berada di luar hak aksesnya.

---

## 9. Perubahan Data Akun Tidak Dapat Disimpan

### Masalah
Perubahan data pada akun admin maupun pelanggan tidak dapat disimpan.

### Penjelasan
Ketika pengguna mengubah informasi akun, data yang baru seharusnya dikirim ke backend dan diperbarui di database. Jika perubahan tidak tersimpan, kemungkinan terdapat masalah pada form, endpoint update, validasi backend, query `UPDATE`, atau koneksi database.

### Solusi
- Pastikan form mengirim data yang telah diubah ke backend.
- Periksa endpoint/API untuk proses update akun.
- Periksa validasi data pada backend.
- Periksa query `UPDATE` pada database.
- Pastikan ID pengguna yang akan diperbarui benar.
- Pastikan backend mengembalikan status keberhasilan atau error yang jelas.
- Setelah berhasil, tampilkan notifikasi bahwa data berhasil diperbarui.
- Ambil kembali data terbaru dari database agar perubahan langsung terlihat.
- Terapkan proses yang sama untuk akun admin dan akun pelanggan sesuai hak akses masing-masing.

---

# Alur Sistem yang Disarankan

## Alur Pelanggan

```text
Registrasi
    ↓
Berhasil Registrasi
    ↓
Halaman Login
    ↓
Login
    ↓
Dashboard / Katalog
    ↓
Pilih Produk
    ↓
Pilih Kostumisasi
    ↓
Kostumisasi Produk
    ↓
Validasi Seluruh Desain
    ↓
Tambah ke Keranjang
    ↓
Checkout
    ↓
Menunggu Konfirmasi Admin
    ↓
Pesanan Diterima
    ↓
Pembayaran
    ↓
Verifikasi Pembayaran
    ↓
Pesanan Diproses
    ↓
Pesanan Selesai
```

## Alur Admin

```text
Login Admin
    ↓
Dashboard
    ↓
Melihat Pesanan Baru
    ↓
Memeriksa Detail Produk & Kostumisasi
    ↓
    ┌───────────────┐
    ↓               ↓
  Terima           Tolak
    ↓               ↓
Pembayaran      Alasan Penolakan
    ↓
Verifikasi Pembayaran
    ↓
Proses Pesanan
    ↓
Pesanan Selesai
```

# Prioritas Perbaikan

## Prioritas Tinggi
1. Keamanan dan verifikasi pembayaran.
2. Perbaikan alur registrasi dan login.
3. Integrasi produk katalog dengan fitur kostumisasi.
4. Validasi desain sebelum produk masuk keranjang.
5. Sistem konfirmasi penerimaan/penolakan pesanan.
6. Perbaikan fungsi penyimpanan perubahan akun.

## Prioritas Sedang
7. Perbaikan data akun pelanggan pada dashboard admin.
8. Perbaikan perhitungan total pelanggan pada dashboard admin.

## Prioritas Pendukung
9. Penyempurnaan validasi input pada form pendaftaran dan form lainnya.

---

# Kesimpulan

Permasalahan pada Project SHOP mencakup beberapa bagian utama, yaitu:

- **Validasi input**
- **Autentikasi dan alur registrasi/login**
- **Integrasi katalog dengan kostumisasi produk**
- **Validasi desain produk custom**
- **Manajemen dan konfirmasi pesanan**
- **Keamanan pembayaran**
- **Dashboard admin**
- **Pengelolaan akun pelanggan**
- **Penyimpanan perubahan data**

Perbaikan sebaiknya tidak hanya dilakukan pada tampilan frontend. Setiap proses penting seperti validasi, autentikasi, perubahan data, pemesanan, dan pembayaran juga harus divalidasi pada **backend dan database** agar sistem lebih aman, konsisten, dan dapat diandalkan.