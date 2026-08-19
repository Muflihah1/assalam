# Testing Checklist

## Scenario 1: Guest Browsing & Cart Flow
- [x] Navigate to http://127.0.0.1:8000/ and verify homepage loads.
- [x] Go to "Katalog Produk" (/katalog) and verify product list.
- [x] Add a product to the cart using "+ Keranjang".
- [x] Navigate to /cart and test increasing quantity (+ button) and check subtotal.
- [x] Change shipping region in cart and verify total updates.
- [x] Go to Interactive Studio (/design) and test changing dimensions, material, color swatches, and brightness.

## Scenario 2: Customer Auth & Order Flow
- [x] Navigate to /login and login as customer (`budi@gmail.com` / `password123`).
- [x] Go to /cart and checkout.
- [x] Verify redirect to /customer/progress and 8-stage timeline.
- [x] Inspect timeline detail modal.
- [x] Verify order history (/customer/riwayat).
- [x] Inspect profile page (/customer/account).

## Scenario 3: Admin Control Panel Flow
- [x] Logout and login as admin (`admin@assalammebel.com` / `password123`).
- [x] Inspect dashboard statistics.
- [x] Go to "Pesanan Masuk" (/admin/pesanan-masuk) and inspect #ORD-PSDN2650 details.
- [x] Go to "Progres Produksi" (/admin/progres-produksi), select #ORD-PSDN2650, update stage "Menyiapkan Bahan" with notes and photo.
- [x] Verify "Akun Pelanggan" (/admin/data-pelanggan).
- [x] Verify "Pengaturan" (/admin/pengaturan) tabs (Profile, WhatsApp Gateway, Ongkir).
