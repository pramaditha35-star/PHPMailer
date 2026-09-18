<?php
// =========================================================
// config/database.php
// Konfigurasi koneksi database menggunakan PDO
// =========================================================

$host   = "localhost";
$dbname = "pendaftaran_email";
$user   = "root";   // default XAMPP
$pass   = "";        // default XAMPP (kosong)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    // Supaya error ditampilkan sebagai exception, bukan warning
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
