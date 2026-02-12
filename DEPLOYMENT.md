# Deployment Guide - FTP Dual Storage

## Files yang Berubah

Berikut adalah file-file yang dimodifikasi untuk implementasi FTP dual storage:

### 1. Configuration Files

- `.env` - Tambah konfigurasi FTP
- `config/filesystems.php` - Tambah FTP disk configuration

### 2. New Service

- `app/Services/FtpStorageService.php` - Service untuk handle dual storage (NEW)

### 3. Modified Controllers

- `app/Http/Controllers/BaseDocumentController.php` - Integrasi FTP untuk dokumen
- `app/Http/Controllers/UserProfileController.php` - Integrasi FTP untuk profil

### 4. Test Files (Optional - tidak perlu di-deploy)

- `test_ftp_native.php` - Script untuk test FTP connection

---

## Langkah Deployment ke Server Production

### Step 1: Backup Server Production

```bash
# SSH ke server production
ssh user@your-server.com

# Backup database
mysqldump -u root -p dapennew > backup_dapennew_$(date +%Y%m%d).sql

# Backup files
tar -czf backup_files_$(date +%Y%m%d).tar.gz /path/to/dapennew
```

### Step 2: Push Changes ke Git Repository

```bash
# Di local development (Windows)
git add .
git commit -m "Implement FTP dual storage for file uploads"
git push origin main
```

### Step 3: Pull Changes di Server Production

```bash
# SSH ke server production
cd /path/to/dapennew

# Pull latest changes
git pull origin main

# Atau jika ada conflict, stash dulu
git stash
git pull origin main
git stash pop
```

### Step 4: Update Configuration di Server

```bash
# Edit .env di server production
nano .env

# Tambahkan konfigurasi FTP (di akhir file):
FTP_HOST=10.15.2.56
FTP_USERNAME=Administrator
FTP_PASSWORD=D@pentel@151
FTP_PORT=21
FTP_ROOT=/
FTP_PASSIVE=true
FTP_SSL=false
FTP_TIMEOUT=30
```

### Step 5: Enable PHP FTP Extension (Jika Belum)

```bash
# Ubuntu/Debian
sudo apt-get install php-ftp

# CentOS/RHEL
sudo yum install php-ftp

# Atau edit php.ini
sudo nano /etc/php/8.x/fpm/php.ini
# Uncomment: extension=ftp

# Restart PHP-FPM
sudo systemctl restart php8.x-fpm

# Restart web server
sudo systemctl restart nginx
# atau
sudo systemctl restart apache2
```

### Step 6: Clear Cache

```bash
cd /path/to/dapennew

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan config:cache
php artisan route:cache
```

### Step 7: Set Permissions

```bash
# Set proper permissions untuk storage
sudo chown -R www-data:www-data storage
sudo chmod -R 775 storage

# Set permissions untuk bootstrap/cache
sudo chown -R www-data:www-data bootstrap/cache
sudo chmod -R 775 bootstrap/cache
```

### Step 8: Test FTP Connection di Server

```bash
# Upload test script ke server
scp test_ftp_native.php user@server:/path/to/dapennew/

# SSH ke server dan test
cd /path/to/dapennew
php test_ftp_native.php
```

**Expected Output:**

```
=== FTP Connection Test (Native PHP) ===

1. Checking PHP FTP extension...
   ✓ PHP FTP extension is available

2. Loading configuration...
   ✓ FTP Host: 10.15.2.56
   ✓ FTP User: Administrator
   ✓ FTP Port: 21

3. Testing FTP connection...
   ✓ FTP connection successful!

4. Testing FTP login...
   ✓ FTP login successful!

... (all tests pass)

=== All Tests Completed Successfully! ===
```

### Step 9: Monitor Logs

```bash
# Monitor Laravel logs untuk FTP activity
tail -f storage/logs/laravel.log | grep FTP

# Test upload dokumen dari browser
# Cek log untuk konfirmasi upload FTP
```

### Step 10: Verify FTP Server

Gunakan FTP client untuk verify:

- Host: `10.15.2.56`
- User: `Administrator`
- Password: `D@pentel@151`

Cek struktur folder:

```
/
├── documents/
│   ├── sekretariat/
│   ├── sdm/
│   ├── akuntansi/
│   └── ... (modul lainnya)
├── versions/
└── public/
    ├── profiles/
    └── signatures/
```

---

## Rollback Plan (Jika Ada Masalah)

### Jika FTP Bermasalah

FTP failure **TIDAK akan crash aplikasi**. File tetap tersimpan di local storage. Untuk disable FTP sementara:

```bash
# Edit .env
nano .env

# Hapus atau comment FTP_HOST
# FTP_HOST=10.15.2.56

# Clear config cache
php artisan config:clear
```

### Jika Perlu Rollback Complete

```bash
# Restore dari backup
git reset --hard HEAD~1
php artisan config:clear
php artisan cache:clear

# Restore database jika perlu
mysql -u root -p dapennew < backup_dapennew_YYYYMMDD.sql
```

---

## Checklist Deployment

- [ ] Backup database production
- [ ] Backup files production
- [ ] Push changes ke git repository
- [ ] Pull changes di server production
- [ ] Update `.env` dengan FTP credentials
- [ ] Enable PHP FTP extension
- [ ] Clear all cache
- [ ] Set proper permissions
- [ ] Test FTP connection (`php test_ftp_native.php`)
- [ ] Test upload dokumen via browser
- [ ] Monitor logs untuk konfirmasi FTP upload
- [ ] Verify files di FTP server
- [ ] Remove `test_ftp_native.php` dari production (optional)

---

## Troubleshooting

| Issue                         | Solution                                            |
| ----------------------------- | --------------------------------------------------- |
| `PHP FTP extension not found` | Install php-ftp: `sudo apt-get install php-ftp`     |
| `FTP connection timeout`      | Cek firewall, pastikan port 21 terbuka              |
| `Permission denied on FTP`    | Cek credentials dan permission folder di FTP server |
| `Files not uploading to FTP`  | Cek `storage/logs/laravel.log` untuk error detail   |
| `Config not updating`         | Run `php artisan config:clear`                      |

---

## Notes

- **Zero Downtime**: Deployment ini tidak memerlukan downtime
- **Backward Compatible**: File lama tetap bisa diakses
- **Safe Deployment**: FTP failure tidak akan crash aplikasi
- **No Database Changes**: Tidak ada migrasi database yang diperlukan
