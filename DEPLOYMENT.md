# Deployment Guide - FTP Dual Storage Update

## Overview

Panduan ini adalah **supplement** untuk [`docs/deployment-guide.md`](file:///c:/laragon/www/dapennew/docs/deployment-guide.md) yang fokus pada deployment **FTP Dual Storage** feature.

Untuk deployment server dari awal, ikuti panduan utama di `docs/deployment-guide.md` terlebih dahulu.

---

## Files yang Berubah (FTP Implementation)

### 1. Configuration Files

- `.env` - Tambah konfigurasi FTP (8 baris baru)
- `config/filesystems.php` - Tambah FTP disk configuration

### 2. New Service

- `app/Services/FtpStorageService.php` - **NEW FILE** (257 lines)

### 3. Modified Controllers

- `app/Http/Controllers/BaseDocumentController.php` - Integrasi FTP
- `app/Http/Controllers/UserProfileController.php` - Integrasi FTP

### 4. Test Script (Optional)

- `test_ftp_native.php` - Script test FTP connection

---

## Deployment ke Server Production

### Step 1: Pull Latest Changes

```bash
# SSH ke server production
ssh user@your-server.com
cd /var/www/dapennew

# Pull changes dari git
git pull origin main
```

### Step 2: Install PHP FTP Extension

```bash
# Ubuntu/Debian
sudo apt-get install php8.1-ftp -y

# Verify installation
php -m | grep ftp

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

### Step 3: Update Environment Configuration

```bash
# Edit .env
nano .env
```

Tambahkan di akhir file `.env`:

```ini
# FTP Configuration
FTP_HOST=10.15.2.56
FTP_USERNAME=Administrator
FTP_PASSWORD=D@pentel@151
FTP_PORT=21
FTP_ROOT=/
FTP_PASSIVE=true
FTP_SSL=false
FTP_TIMEOUT=30
```

### Step 4: Clear Cache

```bash
# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Re-cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Test FTP Connection

```bash
# Upload test script (jika belum ada)
# Atau sudah ada dari git pull

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

5. Testing file upload to FTP...
   ✓ File uploaded successfully

... (all tests pass)

=== All Tests Completed Successfully! ===
```

### Step 6: Set Permissions (Jika Perlu)

```bash
# Pastikan storage writable
sudo chown -R www-data:www-data /var/www/dapennew/storage
sudo chmod -R 775 /var/www/dapennew/storage
```

### Step 7: Monitor Logs

```bash
# Monitor FTP activity
tail -f storage/logs/laravel.log | grep FTP
```

### Step 8: Test Upload via Browser

1. Login ke aplikasi DMS
2. Upload dokumen baru
3. Verify:
    - File ada di `storage/app/documents/[module]/`
    - File ada di FTP server `/documents/[module]/`
    - Log menunjukkan "File uploaded to FTP successfully"

---

## Struktur Folder di FTP Server

```
/ (FTP Root)
├── documents/
│   ├── akuntansi/
│   │   ├── aturan-kebijakan/
│   │   ├── jurnal-umum/
│   │   └── laporan-bulanan/
│   ├── anggaran/
│   │   ├── aturan-kebijakan/
│   │   ├── dokumen-rra/
│   │   └── rencana-kerja-tahunan/
│   ├── hukum-kepatuhan/
│   ├── investasi/
│   ├── keuangan/
│   ├── logistik/
│   ├── sdm/
│   └── sekretariat/
├── versions/
│   └── (all document versions)
└── public/
    ├── profiles/
    │   └── (user profile photos)
    └── signatures/
        └── (user signatures)
```

---

## Troubleshooting

### 1. PHP FTP Extension Not Found

```bash
# Install extension
sudo apt-get install php8.1-ftp -y

# Verify
php -m | grep ftp

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

### 2. FTP Connection Timeout

```bash
# Check firewall
sudo ufw status

# Allow FTP port if needed
sudo ufw allow 21/tcp

# Test connection from server
telnet 10.15.2.56 21
```

### 3. FTP Upload Failed (Permission Denied)

- Cek permission folder di FTP server
- Pastikan user `Administrator` punya write access
- Cek log: `tail -f storage/logs/laravel.log | grep FTP`

### 4. Files Not Uploading to FTP

```bash
# Check logs
tail -f storage/logs/laravel.log | grep -i ftp

# Common issues:
# - FTP_HOST not set in .env
# - PHP FTP extension not installed
# - FTP server unreachable
# - Wrong credentials
```

### 5. Config Not Updating

```bash
# Clear config cache
php artisan config:clear

# Verify FTP config loaded
php artisan tinker
>>> env('FTP_HOST')
=> "10.15.2.56"
```

---

## Rollback Plan

### Disable FTP Temporarily

Jika FTP bermasalah, aplikasi **tetap berjalan normal** (files tersimpan di local storage).

Untuk disable FTP sementara:

```bash
# Edit .env
nano .env

# Comment atau hapus FTP_HOST
# FTP_HOST=10.15.2.56

# Clear cache
php artisan config:clear
```

### Rollback Complete

```bash
# Rollback ke commit sebelumnya
git log --oneline -5
git reset --hard <commit-hash-sebelum-ftp>

# Clear cache
php artisan config:clear
php artisan cache:clear

# Restart services
sudo systemctl restart php8.1-fpm nginx
```

---

## Monitoring & Maintenance

### Check FTP Upload Success Rate

```bash
# Count successful uploads
grep "File uploaded to FTP successfully" storage/logs/laravel.log | wc -l

# Count failed uploads
grep "FTP upload failed" storage/logs/laravel.log | wc -l
```

### Verify Files on FTP Server

Gunakan FTP client (FileZilla/WinSCP):

- Host: `10.15.2.56`
- User: `Administrator`
- Password: `D@pentel@151`
- Port: `21`

### Cleanup Test Files

```bash
# Remove test script dari production (optional)
rm test_ftp_native.php
```

---

## Deployment Checklist

- [ ] Pull latest changes (`git pull origin main`)
- [ ] Install PHP FTP extension (`sudo apt-get install php8.1-ftp`)
- [ ] Update `.env` dengan FTP credentials
- [ ] Clear all cache (`php artisan config:clear`)
- [ ] Test FTP connection (`php test_ftp_native.php`)
- [ ] Set proper permissions untuk storage
- [ ] Test upload via browser
- [ ] Monitor logs untuk konfirmasi FTP upload
- [ ] Verify files di FTP server
- [ ] Remove test script (optional)

---

## Performance Notes

- FTP upload bersifat **synchronous** (blocking)
- Waktu upload tergantung ukuran file dan network speed
- Untuk file besar (>10MB), pertimbangkan queue/background job di masa depan
- FTP failure **tidak akan crash aplikasi** - file tetap tersimpan di local

---

## Security Notes

- FTP credentials disimpan di `.env` (tidak di-commit ke git)
- Pastikan `.env` permission: `chmod 600 .env`
- Gunakan FTP_SSL=true jika FTP server support FTPS
- Pertimbangkan SFTP untuk keamanan lebih baik (requires different implementation)

---

## Next Steps (Optional Improvements)

### 1. Background Job untuk FTP Upload

Untuk performa lebih baik:

```php
// Di FtpStorageService.php
dispatch(new UploadToFtpJob($localPath, $ftpPath));
```

### 2. Retry Mechanism

Tambahkan retry untuk FTP upload yang gagal:

```php
for ($i = 0; $i < 3; $i++) {
    if ($this->uploadToFtpNative($path)) {
        break;
    }
    sleep(2);
}
```

### 3. FTP Sync Command

Buat artisan command untuk sync file lama ke FTP:

```bash
php artisan ftp:sync
```

---

## Support

Untuk issue atau pertanyaan, cek:

- Laravel logs: `storage/logs/laravel.log`
- Nginx logs: `/var/log/nginx/error.log`
- PHP-FPM logs: `/var/log/php8.1-fpm.log`
