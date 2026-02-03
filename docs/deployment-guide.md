# Panduan Deployment DAPEN DMS (Laravel) ke Ubuntu Server

Panduan ini mencakup langkah-langkah instalasi dan konfigurasi untuk men-deploy aplikasi DAPEN DMS ke server Ubuntu (20.04/22.04/24.04).

## 1. Persiapan Server (Prerequisites)

Pastikan server _fresh install_ dan update paket sistem.

```bash
sudo apt update && sudo apt upgrade -y
```

### Install PHP 8.1, Nginx, & MySQL/MariaDB

Laravel 10 membutuhkan minimal PHP 8.1.

```bash
# Add PHP repository
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.1 & Extensions
sudo apt install -y php8.1 php8.1-cli php8.1-fpm php8.1-common \
php8.1-mysql php8.1-zip php8.1-gd php8.1-mbstring \
php8.1-curl php8.1-xml php8.1-bcmath php8.1-intl

# Install Nginx & MySQL Server
sudo apt install -y nginx mysql-server curl git unzip

# Start Services
sudo systemctl enable --now nginx mysql php8.1-fpm
```

### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Install Node.js & NPM (untuk Vite asset bundling)

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 2. Setup Database

Amankan instalasi MySQL dan buat database.

```bash
sudo mysql_secure_installation
```

> **Catatan:** Pada MySQL 8.0 ke atas, `mysql_secure_installation` mungkin meminta Anda mengaktifkan VALIDATE PASSWORD COMPONENT. Sesuaikan dengan kebijakan keamanan Anda.

Login ke MySQL dan buat user & database:

```sql
sudo mysql -u root -p

-- Didalam shell MySQL:
-- Pastikan menggunakan 'mysql_native_password' jika ingin kompatibilitas maksimal dengan PHP lama, meski PHP 8.1 support caching_sha2_password
CREATE DATABASE dapen_dms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'dapen_user'@'localhost' IDENTIFIED WITH mysql_native_password BY 'PasswordRahasiaAnda123';
GRANT ALL PRIVILEGES ON dapen_dms.* TO 'dapen_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 3. Clone & Setup Project

Pindah ke direktori web root (biasanya `/var/www`):

```bash
cd /var/www

# Clone project (gunakan HTTPS atau SSH key Anda)
# Ganti URL_REPO_ANDA dengan URL repository git project ini
sudo git clone https://github.com/username/dapen-dms.git dapennew

# Ubah kepemilikan folder ke user saat ini (untuk mempermudah editing)
# Nanti kita akan ubah ke www-data di akhir
sudo chown -R $USER:$USER /var/www/dapennew

cd dapennew
```

### Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install JS dependencies & Build assets
npm install
npm run build
```

**Catatan:** Karena project ini menggunakan `laravel-vite-plugin`, perintah `npm run build` akan menghasilkan file assets (CSS/JS) di folder `public/build`. Pastikan proses ini sukses.

---

## 4. Konfigurasi Environment & Database

Copy file `.env.example` dan sesuaikan.

```bash
cp .env.example .env
nano .env
```

Sesuaikan baris berikut:

```ini
APP_NAME="DAPEN DMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-server-ip
# Contoh: APP_URL=http://103.11.22.33

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dapen_dms
DB_USERNAME=dapen_user
DB_PASSWORD=PasswordRahasiaAnda123

# Driver Storage (Penting untuk upload dokumen)
FILESYSTEM_DISK=public
```

### Generate Key & Migrate

```bash
# Import Database dari SQL Dump
# (Gunakan ini jika Anda sudah punya file backup full database, misal: dump-dapennew-202601292146.sql)
# Sesuaikan nama file .sql dengan file yang Anda miliki
sudo mysql -u dapen_user -p dapen_dms < database/migrations/dump-dapennew-202601292146.sql

# JIKA Import SQL sukses, Anda TIDAK PERLU jalankan migrate & seed di bawah ini.
# Lewati langkah migrate & seed jika sudah import SQL.

# --- OPSI ALTERNATIF (Fresh Install) ---
# Generate App Key
php artisan key:generate

# Migrasi Database (Hanya jika TIDAK pakai dump SQL)
# php artisan migrate --force

# Seed Data Awal (Hanya jika TIDAK pakai dump SQL)
# php artisan db:seed --class=InitialDataSeeder
# php artisan db:seed --class=MenuSeeder
# php artisan db:seed --class=DashboardMenusSeeder
```

### Storage Link

Penting agar file dokumen bisa diakses publik (jika perlu) atau di-link dengan benar.

```bash
php artisan storage:link
```

---

## 5. Konfigurasi Nginx

Buat file konfigurasi server block baru.

```bash
sudo nano /etc/nginx/sites-available/dapennew
```

isi dengan konfigurasi berikut:

```nginx
server {
    listen 80;
    server_name your-server-ip;
    # Atau gunakan server_name _; jika hanya ada satu website di server ini

    # Root mengarah ke folder public Laravel
    root /var/www/dapennew/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    # Handle PHP Scripts
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan konfigurasi dan restart Nginx:

```bash
sudo ln -s /etc/nginx/sites-available/dapennew /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 6. Permissions (Hak Akses Folder)

Laravel butuh hak tulis ke folder `storage` dan `bootstrap/cache`.

```bash
# Ubah group owner ke www-data (user Nginx)
sudo chown -R www-data:www-data /var/www/dapennew/storage
sudo chown -R www-data:www-data /var/www/dapennew/bootstrap/cache

# Set permission (775: User & Group bisa write)
sudo chmod -R 775 /var/www/dapennew/storage
sudo chmod -R 775 /var/www/dapennew/bootstrap/cache
```

---

## 7. Optimasi (Optional tapi Recommended)

Untuk performa production yang lebih baik:

```bash
# Cache Config, Routes, & Views
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Jika ada perubahan di `.env` atau `routes`, jalankan perintah-perintah di atas lagi.

## 8. SSL (HTTPS) dengan Certbot

Jika menggunakan domain publik, aktifkan HTTPS gratis via Let's Encrypt:

```bash
sudo apt install snapd
sudo snap install core; sudo snap refresh core
sudo snap install --classic certbot
sudo ln -s /snap/bin/certbot /usr/bin/certbot

# Request sertifikat & otomatis update Nginx
sudo certbot --nginx -d domain-anda.com -d www.domain-anda.com
```

---

## Troubleshooting Umum

1.  **Error 500 (Server Error):**
    - Cek log permission: `sudo tail -f /var/log/nginx/error.log`
    - Cek log Laravel: `tail -f storage/logs/laravel.log`
    - Pastikan folder `storage` permission-nya benar (Langkah 6).

2.  **Asset tidak muncul/berantakan:**
    - Pastikan `npm run build` sukses.
    - Pastikan `APP_URL` di `.env` sudah benar (`https://...`).

3.  **Upload Gagal (File terlalu besar):**
    - Edit `php.ini` (`sudo nano /etc/php/8.1/fpm/php.ini`) dan `nginx.conf`.
    - PHP: `upload_max_filesize = 100M`, `post_max_size = 100M`.
    - Nginx: tambahkan `client_max_body_size 100M;` di dalam blok `server` atau `http`.
    - Restart service (`sudo systemctl restart php8.1-fpm nginx`).
