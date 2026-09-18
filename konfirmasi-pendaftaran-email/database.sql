-- =========================================================
-- database.sql
-- Studi Kasus: Konfirmasi Pendaftaran via Email (PHPMailer + SMTP Gmail)
-- =========================================================

CREATE DATABASE IF NOT EXISTS db_pendaftaran;
USE db_pendaftaran;

CREATE TABLE IF NOT EXISTS pendaftar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_hp VARCHAR(20) DEFAULT NULL,
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_email ENUM('terkirim', 'gagal') NOT NULL DEFAULT 'gagal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
