<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_absen";

session_start();
date_default_timezone_set('Asia/Jakarta');

$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Buat database jika belum ada
$sql = "CREATE DATABASE IF NOT EXISTS $db";
mysqli_query($conn, $sql);

// Pilih database
mysqli_select_db($conn, $db);

// Buat tabel jika belum ada
$table_anggota = "CREATE TABLE IF NOT EXISTS anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
)";
mysqli_query($conn, $table_anggota);

$table_absensi = "CREATE TABLE IF NOT EXISTS absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    waktu TIME NOT NULL,
    keterangan ENUM('Hadir', 'Izin', 'Sakit', 'Alfa') DEFAULT 'Hadir'
)";
mysqli_query($conn, $table_absensi);

// Hapus foreign key lama jika masih ada (mencegah error constraint)
mysqli_query($conn, "ALTER TABLE absensi DROP FOREIGN KEY IF EXISTS absensi_ibfk_1");

// Update skema jika tabel sudah ada tapi masih pakai format lama
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM absensi LIKE 'nama'");
if (mysqli_num_rows($check_column) == 0) {
    // Hapus foreign key jika ada
    mysqli_query($conn, "ALTER TABLE absensi DROP FOREIGN KEY IF EXISTS absensi_ibfk_1");
    mysqli_query($conn, "ALTER TABLE absensi ADD COLUMN nama VARCHAR(100) NOT NULL AFTER id");
    mysqli_query($conn, "ALTER TABLE absensi DROP COLUMN anggota_id");
}

// Cek jika tabel anggota kosong, isi data awal
$check_anggota = mysqli_query($conn, "SELECT id FROM anggota LIMIT 1");
if (mysqli_num_rows($check_anggota) == 0) {
    mysqli_query($conn, "INSERT INTO anggota (nama) VALUES ('Personel 1'), ('Personel 2'), ('Personel 3')");
}

// Buat tabel admin
$table_admin = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
mysqli_query($conn, $table_admin);

// Cek jika admin kosong, tambahkan admin default
$check_admin = mysqli_query($conn, "SELECT id FROM admin LIMIT 1");
if (mysqli_num_rows($check_admin) == 0) {
    $pass_default = password_hash("admin123", PASSWORD_DEFAULT);
    mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('admin', '$pass_default')");
}
?>