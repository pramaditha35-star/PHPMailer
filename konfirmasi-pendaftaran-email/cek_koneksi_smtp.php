<?php
// =========================================================
// cek_koneksi_smtp.php
// Script diagnostik: tes koneksi socket MENTAH ke server SMTP Gmail,
// tanpa lewat PHPMailer, supaya penyebab error lebih jelas.
// =========================================================

header('Content-Type: text/plain; charset=utf-8');

echo "=== INFO DASAR PHP ===\n";
echo "PHP Version     : " . phpversion() . "\n";
echo "Loaded php.ini  : " . php_ini_loaded_file() . "\n";
echo "OpenSSL aktif   : " . (extension_loaded('openssl') ? 'YA' : 'TIDAK') . "\n";
echo "disable_functions: " . (ini_get('disable_functions') ?: '(kosong)') . "\n";
echo "allow_url_fopen : " . (ini_get('allow_url_fopen') ? 'YA' : 'TIDAK') . "\n\n";

function tesKoneksi($host, $port, $label) {
    echo "=== TES: $label ($host:$port) ===\n";

    if (!function_exists('fsockopen')) {
        echo "GAGAL: fungsi fsockopen() tidak tersedia (dinonaktifkan di php.ini).\n\n";
        return;
    }

    $errno = 0;
    $errstr = '';
    $start = microtime(true);

    $conn = @fsockopen($host, $port, $errno, $errstr, 10); // timeout 10 detik

    $waktu = round(microtime(true) - $start, 2);

    if ($conn) {
        echo "BERHASIL connect dalam {$waktu} detik.\n";
        $banner = fgets($conn, 512);
        echo "Balasan server: " . trim($banner) . "\n";
        fclose($conn);
    } else {
        echo "GAGAL connect setelah {$waktu} detik.\n";
        echo "Error code   : $errno\n";
        echo "Error message: " . ($errstr ?: '(kosong - kemungkinan diblokir firewall/antivirus sebelum sempat balas)') . "\n";
    }
    echo "\n";
}

// Tes ke beberapa kombinasi host:port yang umum dipakai Gmail SMTP
tesKoneksi('smtp.gmail.com', 587, 'Plain socket ke port 587 (TLS/STARTTLS)');
tesKoneksi('ssl://smtp.gmail.com', 465, 'SSL socket ke port 465');
tesKoneksi('smtp.gmail.com', 465, 'Plain socket ke port 465');
tesKoneksi('google.com', 443, 'Kontrol: koneksi umum ke internet (google.com:443)');

echo "=== KESIMPULAN CEPAT ===\n";
echo "- Kalau SEMUA tes di atas gagal (termasuk yang ke google.com), berarti PHP di komputer\n";
echo "  ini memang tidak bisa akses internet sama sekali (firewall Apache/httpd.exe diblokir total,\n";
echo "  atau fsockopen dinonaktifkan).\n";
echo "- Kalau tes ke google.com BERHASIL tapi ke smtp.gmail.com GAGAL, berarti spesifik\n";
echo "  port SMTP (587/465) yang diblokir oleh firewall/antivirus/jaringan.\n";
echo "- Kalau salah satu dari port 587 atau 465 BERHASIL, pakai kombinasi host:port itu\n";
echo "  di config/email_config.php.\n";
