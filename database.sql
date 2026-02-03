CREATE DATABASE IF NOT EXISTS db_absen;
USE db_absen;

CREATE TABLE IF NOT EXISTS anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    anggota_id INT NOT NULL,
    tanggal DATE NOT NULL,
    waktu TIME NOT NULL,
    keterangan ENUM('Hadir', 'Izin', 'Sakit', 'Alfa') DEFAULT 'Hadir',
    FOREIGN KEY (anggota_id) REFERENCES anggota(id)
);

-- Data awal anggota Bintang Salsa Grup
INSERT INTO anggota (nama) VALUES 
('Personel 1'), 
('Personel 2'), 
('Personel 3');