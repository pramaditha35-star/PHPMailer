<?php
// =========================================================
// kirim_email.php
// Fungsi untuk mengirim email konfirmasi pendaftaran
// menggunakan PHPMailer via SMTP Gmail
// =========================================================

require __DIR__ . '/src/Exception.php';
require __DIR__ . '/src/PHPMailer.php';
require __DIR__ . '/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Mengirim email konfirmasi pendaftaran.
 *
 * @param string $namaPenerima Nama pendaftar
 * @param string $emailPenerima Email tujuan
 * @return array ['status' => bool, 'pesan' => string]
 */
function kirimEmailKonfirmasi(string $namaPenerima, string $emailPenerima): array
{
    $config = require __DIR__ . '/config/email_config.php';

    $mail = new PHPMailer(true); // true = aktifkan exception

    try {
        // ----- Konfigurasi Server SMTP -----
        $mail->isSMTP();
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER; // tampilkan detail proses SMTP
        $mail->Timeout = 15; // batas waktu koneksi 15 detik  
        $mail->Host       = $config['host'];
        $mail->SMTPAuth   = $config['smtp_auth'];
        $mail->Username   = $config['username'];
        $mail->Password   = $config['password'];
        $mail->SMTPSecure = $config['secure']; // 'tls'
        $mail->Port       = $config['port'];   // 587

        // ----- Pengirim & Penerima -----
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($emailPenerima, $namaPenerima);

        // ----- Isi Email -----
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Konfirmasi Pendaftaran Berhasil';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; font-size: 14px;'>
                <h2>Pendaftaran Berhasil!</h2>
                <p>Halo <b>{$namaPenerima}</b>,</p>
                <p>Terima kasih telah mendaftar. Data kamu sudah kami terima dan simpan.</p>
                <p>Tanggal daftar: " . date('d-m-Y H:i:s') . "</p>
                <hr>
                <p style='color: #888; font-size: 12px;'>Email ini dikirim otomatis, mohon tidak membalas.</p>
            </div>
        ";
        $mail->AltBody = "Halo $namaPenerima, pendaftaran kamu berhasil kami terima pada " . date('d-m-Y H:i:s');

        $mail->send();
        return ['status' => true, 'pesan' => 'Email berhasil dikirim.'];

    } catch (Exception $e) {
        return ['status' => false, 'pesan' => "Email gagal dikirim. Error: {$mail->ErrorInfo}"];
    }
}
