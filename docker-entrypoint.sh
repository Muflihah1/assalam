#!/usr/bin/env bash
set -e

echo "=== [ASSALAM MEBEL] Memulai Entrypoint Docker ==="

# 1. Setup file .env jika belum ada
if [ ! -f .env ]; then
    echo ">> File .env tidak ditemukan. Menyalin dari .env.example..."
    cp .env.example .env
fi

# 2. Sinkronisasi konfigurasi database di .env jika masih sqlite / default
if grep -q "DB_CONNECTION=sqlite" .env; then
    echo ">> Memperbarui konfigurasi .env untuk MySQL..."
    sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/g' .env
    
    # Tambahkan variabel DB jika belum ada di .env
    grep -q "^DB_HOST=" .env || echo "DB_HOST=mysql" >> .env
    grep -q "^DB_PORT=" .env || echo "DB_PORT=3306" >> .env
    grep -q "^DB_DATABASE=" .env || echo "DB_DATABASE=assalam" >> .env
    grep -q "^DB_USERNAME=" .env || echo "DB_USERNAME=assalam_user" >> .env
    grep -q "^DB_PASSWORD=" .env || echo "DB_PASSWORD=password" >> .env
    grep -q "^SERVER_HOST=" .env || echo "SERVER_HOST=0.0.0.0" >> .env
fi

# 3. Install composer dependencies jika vendor belum ada
if [ ! -f vendor/autoload.php ]; then
    echo ">> Memasang dependensi Composer (vendor)..."
    composer install --no-interaction --prefer-dist
fi

# 4. Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=base64:" .env; then
    echo ">> Membuat Application Key..."
    php artisan key:generate --force
fi

# 5. Menunggu MySQL siap menerima koneksi
echo ">> Menghubungkan ke database MySQL..."
MAX_TRIES=30
TRIES=0
until php -r "
try {
    \$host = getenv('DB_HOST') ?: 'mysql';
    \$port = getenv('DB_PORT') ?: '3306';
    \$db   = getenv('DB_DATABASE') ?: 'assalam';
    \$user = getenv('DB_USERNAME') ?: 'assalam_user';
    \$pass = getenv('DB_PASSWORD') ?: 'password';
    new PDO(\"mysql:host={\$host};port={\$port};dbname={\$db}\", \$user, \$pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    exit(0);
} catch (Exception \$e) {
    exit(1);
}
"; do
    TRIES=$((TRIES+1))
    if [ $TRIES -gt $MAX_TRIES ]; then
        echo "!! Gagal terhubung ke MySQL setelah $MAX_TRIES kali percobaan."
        exit 1
    fi
    echo "   Menunggu MySQL siap... ($TRIES/$MAX_TRIES)"
    sleep 2
done
echo ">> Berhasil terhubung ke MySQL!"

# 6. Migrasi database dan seeding data katalog jika tabel masih kosong
echo ">> Menjalankan database migration..."
php artisan migrate --force

PRODUK_COUNT=$(php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();
try {
    echo \App\Models\Produk::count();
} catch (Exception \$e) {
    echo '0';
}
" 2>/dev/null || echo "0")

if [ "$PRODUK_COUNT" = "0" ]; then
    echo ">> Database kosong. Menjalankan DatabaseSeeder..."
    php artisan db:seed --force
fi

# 7. Symlink storage
if [ ! -L public/storage ]; then
    echo ">> Membuat storage symlink..."
    php artisan storage:link || true
fi

# 8. Install dependensi NPM jika node_modules belum ada
if [ ! -d node_modules ]; then
    echo ">> Memasang dependensi Node.js (node_modules)..."
    npm install
fi

# 9. Build aset frontend jika belum ada dist build
if [ ! -d public/build ]; then
    echo ">> Mengompilasi aset Vite frontend..."
    npm run build || true
fi

echo "=== Setup Selesai! Menjalankan perintah utama: $@ ==="
exec "$@"
