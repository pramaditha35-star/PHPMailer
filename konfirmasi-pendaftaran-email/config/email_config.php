<?php
// =========================================================
// config/email_config.php
// Konfigurasi SMTP Google (Gmail) untuk PHPMailer
//
// PENTING:
// - Gmail sudah TIDAK mengizinkan login SMTP pakai password akun biasa.
// - Kamu WAJIB membuat "App Password" (Sandi Aplikasi) di akun Google.
// - Caranya ada di README.md bagian "Membuat App Password Gmail".
// =========================================================

return [
    'host'       => 'smtp.gmail.com',
    'smtp_auth'  => true,
    'username'   => 'pramaditha011@gmail.com',
    'password'   => 'benb ugqr uyyd qdzv',
    'secure'     => 'tls',
    'port'       => 587,
    'from_email' => 'pramaditha011@gmail.com',
    'from_name'  => 'Panitia Pendaftaran',
];