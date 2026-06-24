CREATE DATABASE IF NOT EXISTS `kos_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `kos_db`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('Administrator','Petugas') NOT NULL DEFAULT 'Petugas',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `rooms` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `room_code` VARCHAR(20) NOT NULL UNIQUE,
  `type` ENUM('Standar','VIP') NOT NULL DEFAULT 'Standar',
  `price` DECIMAL(12,0) NOT NULL,
  `status` ENUM('Terisi','Kosong') NOT NULL DEFAULT 'Kosong',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `tenants` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `room_id` INT NOT NULL,
  `phone` VARCHAR(20),
  `start_date` DATE NOT NULL,
  `status` ENUM('Aktif','Keluar') NOT NULL DEFAULT 'Aktif',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tenant_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `month` VARCHAR(20) NOT NULL,
  `amount` DECIMAL(12,0) NOT NULL,
  `method` ENUM('Transfer','Cash') NOT NULL DEFAULT 'Cash',
  `pay_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `bills` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tenant_id` INT NOT NULL,
  `room_id` INT NOT NULL,
  `month` VARCHAR(20) NOT NULL,
  `rent` DECIMAL(12,0) NOT NULL DEFAULT 0,
  `electric` DECIMAL(12,0) NOT NULL DEFAULT 0,
  `water` DECIMAL(12,0) NOT NULL DEFAULT 0,
  `total` DECIMAL(12,0) NOT NULL DEFAULT 0,
  `status` ENUM('Lunas','Belum Lunas') NOT NULL DEFAULT 'Belum Lunas',
  `bill_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- password = "password" (bcrypt)
INSERT INTO `users` (`name`,`email`,`password`,`role`) VALUES
('Admin','admin@kost.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Administrator'),
('Petugas 1','petugas1@kost.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Petugas'),
('Petugas 2','petugas2@kost.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Petugas');

INSERT INTO `rooms` (`room_code`,`type`,`price`,`status`) VALUES
('A-01','Standar',700000,'Terisi'),('A-02','Standar',700000,'Terisi'),
('A-03','VIP',900000,'Kosong'),('A-04','VIP',900000,'Terisi'),
('A-05','Standar',700000,'Terisi'),('B-01','Standar',650000,'Terisi'),
('B-02','Standar',650000,'Terisi'),('B-03','Standar',650000,'Terisi');

INSERT INTO `tenants` (`name`,`room_id`,`phone`,`start_date`) VALUES
('Budi Santoso',2,'081234567890','2026-05-01'),
('Siti Nurhaliza',5,'082345678901','2026-04-10'),
('Andi Saputra',6,'083456789012','2026-03-15'),
('Rina Marlina',7,'085678901234','2026-04-20'),
('Dwi Kurniawan',8,'089012345678','2026-05-05');

INSERT INTO `payments` (`tenant_id`,`room_id`,`month`,`amount`,`method`,`pay_date`) VALUES
(1,2,'Mei 2026',700000,'Transfer','2026-05-01'),(2,5,'Mei 2026',700000,'Cash','2026-05-01'),
(3,6,'Mei 2026',650000,'Transfer','2026-05-02'),(4,7,'Mei 2026',650000,'Transfer','2026-05-02'),
(5,8,'Mei 2026',650000,'Transfer','2026-05-03');

INSERT INTO `bills` (`tenant_id`,`room_id`,`month`,`rent`,`electric`,`water`,`total`,`status`,`bill_date`,`due_date`) VALUES
(1,2,'Juni 2026',700000,30000,20000,750000,'Belum Lunas','2026-05-25','2026-06-05'),
(2,5,'Juni 2026',700000,30000,20000,750000,'Lunas','2026-05-25','2026-06-05'),
(3,6,'Juni 2026',650000,30000,20000,700000,'Belum Lunas','2026-05-25','2026-06-05'),
(4,7,'Juni 2026',650000,30000,20000,700000,'Lunas','2026-05-25','2026-06-05'),
(5,8,'Juni 2026',650000,30000,20000,700000,'Belum Lunas','2026-05-25','2026-06-05');

-- ===== TABEL TRANSFER BUKTI (fitur konfirmasi transfer) =====
ALTER TABLE `bills` 
  MODIFY COLUMN `status` ENUM('Lunas','Belum Lunas','Menunggu Verifikasi') NOT NULL DEFAULT 'Belum Lunas';

CREATE TABLE `transfer_bukti` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `bill_id` INT NOT NULL,
  `tenant_id` INT NOT NULL,
  `bank_name` VARCHAR(50) NOT NULL COMMENT 'BCA, BRI, Mandiri, BNI, dll',
  `account_number` VARCHAR(30) NOT NULL COMMENT 'Nomor rekening penyewa pengirim',
  `transfer_date` DATE NOT NULL,
  `amount` DECIMAL(12,0) NOT NULL,
  `bukti_file` VARCHAR(255) NOT NULL COMMENT 'Nama file bukti transfer',
  `catatan_penyewa` TEXT NULL,
  `status` ENUM('Menunggu','Dikonfirmasi','Ditolak') NOT NULL DEFAULT 'Menunggu',
  `catatan_admin` TEXT NULL COMMENT 'Alasan tolak jika ditolak',
  `verified_by` INT NULL COMMENT 'user id admin yang verifikasi',
  `verified_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`bill_id`) REFERENCES `bills`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`tenant_id`) REFERENCES `tenants`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;
