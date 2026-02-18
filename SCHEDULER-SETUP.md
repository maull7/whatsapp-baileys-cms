# Setup Scheduler untuk Campaign Terjadwal

Campaign terjadwal akan terkirim **otomatis sesuai jam** tanpa perlu reload halaman, asalkan scheduler Laravel berjalan.

## Development (Lokal)

Jalankan scheduler di terminal terpisah:

**Windows:**
```bash
php artisan schedule:work
```

Atau gunakan file helper:
```bash
start-scheduler.bat
```

**Linux/Mac:**
```bash
php artisan schedule:work
```

Atau:
```bash
chmod +x start-scheduler.sh
./start-scheduler.sh
```

Scheduler akan jalan terus dan cek campaign terjadwal setiap menit.

## Production (Server)

Setup cron job di server. Edit crontab:

```bash
crontab -e
```

Tambahkan baris ini (sesuaikan path project):

```bash
* * * * * cd /path/to/whatsapp-baileys-blast && php artisan schedule:run >> /dev/null 2>&1
```

Ini akan menjalankan scheduler setiap menit.

## Verifikasi

1. Buat campaign dengan jadwal beberapa menit ke depan
2. Pastikan scheduler berjalan (`schedule:work` atau cron)
3. Tunggu sampai waktu jadwal tiba
4. Campaign akan terkirim otomatis (cek di halaman Campaigns)

## Log

Log scheduler ada di `storage/logs/laravel.log`. Cari "Broadcast terjadwal" untuk melihat aktivitas.
