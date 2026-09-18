# Studi Kasus: Konfirmasi Pendaftaran via Email (PHPMailer + SMTP Gmail)

## Struktur Proyek
```
konfirmasi-pendaftaran-email/
├── composer.json
├── config/
│   ├── database.php        <- koneksi ke MySQL
│   └── email_config.php    <- kredensial SMTP Gmail
├── database.sql             <- script buat database & tabel
├── index.html                <- form pendaftaran
├── proses_daftar.php         <- simpan data + kirim email
├── kirim_email.php           <- fungsi kirim email (PHPMailer)
└── vendor/                   <- akan muncul otomatis setelah composer install
```

---

## LANGKAH 1 — Siapkan XAMPP
1. Buka **XAMPP Control Panel**.
2. Start service **Apache** dan **MySQL**.

## LANGKAH 2 — Copy proyek ke folder htdocs
1. Copy folder `konfirmasi-pendaftaran-email` ke:
   - Windows: `C:\xampp\htdocs\`
   - Mac: `/Applications/XAMPP/htdocs/`
2. Pastikan hasil akhirnya: `htdocs/konfirmasi-pendaftaran-email/index.html`

## LANGKAH 3 — Install PHPMailer via Composer
PHPMailer TIDAK disertakan langsung di file ini (karena harus di-download), jadi kamu perlu install lewat Composer.

1. Kalau belum punya Composer, download dulu di https://getcomposer.org/download/
2. Buka terminal / CMD, masuk ke folder proyek:
   ```
   cd C:\xampp\htdocs\konfirmasi-pendaftaran-email
   ```
3. Jalankan:
   ```
   composer install
   ```
   Ini akan otomatis membuat folder `vendor/` berisi PHPMailer, sesuai `composer.json` yang sudah disediakan.

   > Alternatif tanpa Composer: download PHPMailer manual dari GitHub (github.com/PHPMailer/PHPMailer), lalu `require` file `src/PHPMailer.php`, `src/SMTP.php`, `src/Exception.php` secara manual di `kirim_email.php` (ganti baris `require vendor/autoload.php`). Cara Composer jauh lebih mudah.

## LANGKAH 4 — Buat Database
1. Buka `http://localhost/phpmyadmin`
2. Klik tab **SQL**, lalu copy-paste isi file `database.sql`, klik **Go**.
3. Pastikan database `db_pendaftaran` dan tabel `pendaftar` sudah muncul.

Jika koneksi MySQL kamu punya user/password berbeda dari default XAMPP (`root` tanpa password), sesuaikan di `config/database.php`.

## LANGKAH 5 — Membuat App Password Gmail
Gmail tidak mengizinkan login SMTP pakai password akun biasa, jadi kamu wajib pakai **App Password**:

1. Aktifkan dulu **2-Step Verification** di akun Google:
   `https://myaccount.google.com/security` → aktifkan "Verifikasi 2 Langkah".
2. Setelah aktif, buka:
   `https://myaccount.google.com/apppasswords`
3. Pilih app "Mail" / ketik nama bebas misal "PHPMailer", klik **Create/Buat**.
4. Google akan menampilkan password 16 digit (format: `xxxx xxxx xxxx xxxx`). Copy password ini.

## LANGKAH 6 — Isi Konfigurasi Email
Buka `config/email_config.php`, ganti:
```php
'username'   => 'emailkamu@gmail.com',   // email Gmail asli kamu
'password'   => 'xxxx xxxx xxxx xxxx',    // App Password 16 digit dari langkah 5
'from_email' => 'emailkamu@gmail.com',
```

## LANGKAH 7 — Jalankan Proyek
1. Buka browser, akses:
   ```
   http://localhost/konfirmasi-pendaftaran-email/index.html
   ```
2. Isi form (nama, email, no HP) lalu klik **Daftar**.
3. Jika berhasil:
   - Data masuk ke tabel `pendaftar` di database.
   - Email konfirmasi otomatis terkirim ke email yang didaftarkan.
4. Cek juga tabel `pendaftar` di phpMyAdmin, kolom `status_email` akan berubah jadi `terkirim` atau `gagal`.

---

## Troubleshooting Umum
| Masalah | Penyebab | Solusi |
|---|---|---|
| `Koneksi database gagal` | Service MySQL belum jalan / kredensial salah | Cek XAMPP Control Panel, cek `config/database.php` |
| `SMTP connect() failed` | Port 587 diblokir firewall/antivirus, atau salah App Password | Cek koneksi internet, pastikan App Password benar (bukan password akun biasa) |
| Email masuk folder Spam | Wajar untuk pengiriman testing | Cek folder spam/junk penerima |
| `Class "PHPMailer\PHPMailer\PHPMailer" not found` | Belum menjalankan `composer install` | Jalankan ulang langkah 3 |
| Error `Could not authenticate` | 2-Step Verification belum aktif atau App Password salah copy | Ulangi langkah 5, pastikan tanpa spasi ekstra saat paste |
