# Cara Menggunakan Aplikasi WA Blast

Dokumentasi singkat untuk pengguna area **Client**: login, menu, dan koneksi WhatsApp.

---

## 1. Login

1. Buka aplikasi di browser (URL yang diberikan admin).
2. Masukkan **email** dan **password** yang sudah didaftarkan.
3. Klik **Log in**.

Setelah login, Anda masuk ke **Dashboard** dengan menu di **sidebar kiri**.

---

## 2. Menu (Sidebar)

Menu utama berada di **sidebar kiri**:

| Menu       | Fungsi |
|-----------|--------|
| **Dashboard** | Beranda: ringkasan campaign, API key, dan pengingat scheduler. |
| **Kontak**    | Kelola daftar kontak untuk broadcast. |
| **Segment**   | Kelompok kontak (segment) untuk target campaign. |
| **Template**  | Template pesan yang bisa dipakai di campaign. |
| **Campaign**  | Buat dan lihat campaign broadcast. |
| **WhatsApp**  | Halaman koneksi/scan QR WhatsApp (lihat bawah). |

Di bawah menu ada **Profil** dan **Keluar**.

---

## 3. Koneksi WhatsApp (Scan QR Code)

Agar bisa mengirim pesan, akun WhatsApp harus terhubung dulu lewat **scan QR code**.

### Langkah 1: Buka halaman WhatsApp

- Klik **WhatsApp** di sidebar.

### Langkah 2: Cek status

- **Terhubung** (hijau): WhatsApp siap dipakai. Pesan dari API akan menampilkan teks seperti *"Terhubung. WhatsApp siap dipakai."*
- **Belum terhubung** (kuning): Perlu scan QR code (kotak QR akan tampil di halaman).

### Langkah 3: Scan QR code (jika belum terhubung)

1. Pastikan **server WhatsApp API (Node)** sudah berjalan (admin yang mengatur).
2. Di halaman WhatsApp, tunggu sampai **QR code** muncul (ada loading “Memuat QR Code...”).
3. Di **HP**:
   - Buka aplikasi **WhatsApp**.
   - Masuk ke **Menu** (⋮) → **Perangkat tertaut** → **Sambungkan perangkat**.
4. Arahkan kamera ke **QR code di layar** dan scan.
5. Setelah berhasil, status berubah menjadi **Terhubung** dan halaman akan **memuat ulang** otomatis.

### Tips

- QR code **diperbarui otomatis** setiap 15 detik. Jika gagal, klik **Muat ulang QR**.
- Jika QR tidak muncul: cek bahwa server Node jalan dan API key client benar; lalu coba **Reconnect** atau **Refresh**.
- **Reconnect**: meminta server Node untuk generate ulang sesi/koneksi.
- **Refresh**: memuat ulang halaman dan cek status lagi.
- **Logout WhatsApp**: putuskan perangkat dari WhatsApp (untuk ganti nomor atau reset).

---

## 4. Ringkasan

- Login dengan email/password → Dashboard.
- Pakai **sidebar** untuk pindah: Dashboard, Kontak, Segment, Template, Campaign, WhatsApp.
- **WhatsApp** → cek status; jika belum terhubung, scan QR dari HP (Menu → Perangkat tertaut → Sambungkan perangkat).
- Setelah status “Terhubung”, aplikasi siap dipakai untuk campaign/broadcast.
