<?php
// =========================================================
// proses_daftar.php
// 1. Validasi input dari form
// 2. Simpan data pendaftar ke database
// 3. Kirim email konfirmasi via PHPMailer (SMTP Gmail)
// 4. Update status_email di database sesuai hasil pengiriman
// =========================================================

require __DIR__ . '/config/database.php';
require __DIR__ . '/kirim_email.php';

// Ambil data dari form
$nama  = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');

// Validasi sederhana
if ($nama === '' || $email === '' || $no_hp === '') {
    die("Semua field wajib diisi. <a href='index.html'>Kembali</a>");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Format email tidak valid. <a href='index.html'>Kembali</a>");
}

try {
    // 1. Simpan ke database dulu (status_email default 'gagal')
    $stmt = $pdo->prepare("INSERT INTO pendaftar (nama, email, no_hp) VALUES (:nama, :email, :no_hp)");
    $stmt->execute([
        ':nama'  => $nama,
        ':email' => $email,
        ':no_hp' => $no_hp,
    ]);
    $idPendaftar = $pdo->lastInsertId();

    // 2. Kirim email konfirmasi
    $hasilEmail = kirimEmailKonfirmasi($nama, $email);

    // 3. Update status_email berdasarkan hasil pengiriman
    $statusEmail = $hasilEmail['status'] ? 'terkirim' : 'gagal';
    $update = $pdo->prepare("UPDATE pendaftar SET status_email = :status WHERE id = :id");
    $update->execute([':status' => $statusEmail, ':id' => $idPendaftar]);

    // 4. Tampilkan hasil ke user
    if ($hasilEmail['status']) {
        echo "<h3>Pendaftaran berhasil!</h3>";
        echo "<p>Halo <b>$nama</b>, email konfirmasi telah dikirim ke <b>$email</b>. Silakan cek inbox atau folder spam.</p>";
    } else {
        echo "<h3>Pendaftaran tersimpan, tapi email gagal terkirim.</h3>";
        echo "<p>Detail error: " . htmlspecialchars($hasilEmail['pesan']) . "</p>";
    }
    echo "<a href='index.html'>Kembali ke form</a>";

} catch (PDOException $e) {
    // Jika email duplikat (UNIQUE constraint)
    if ($e->getCode() == 23000) {
        die("Email sudah terdaftar sebelumnya. <a href='index.html'>Kembali</a>");
    }
    die("Terjadi kesalahan database: " . $e->getMessage());
}
