#!/bin/bash
echo "Starting Laravel Scheduler..."
echo "Campaign terjadwal akan terkirim otomatis setiap menit."
echo ""
echo "Tekan Ctrl+C untuk stop scheduler."
echo ""
php artisan schedule:work
