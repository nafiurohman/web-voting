-- Database Initialization for Pemilihan Ketua ICP Periode 2026-2027
CREATE DATABASE IF NOT EXISTS `icp_voting` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `icp_voting`;

-- Drop existing tables if re-initializing
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `voters`;
DROP TABLE IF EXISTS `candidates`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `settings`;
SET FOREIGN_KEY_CHECKS = 1;

-- 1. Table Users (Admin)
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `name` VARCHAR(100) NOT NULL DEFAULT 'Administrator ICP',
    `role` VARCHAR(20) NOT NULL DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Table Candidates
CREATE TABLE `candidates` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `candidate_number` INT NOT NULL UNIQUE,
    `name` VARCHAR(150) NOT NULL,
    `tagline` VARCHAR(255) DEFAULT NULL,
    `photo` VARCHAR(255) DEFAULT '/assets/uploads/default.png',
    `vision` TEXT NOT NULL,
    `mission` TEXT NOT NULL,
    `programs` TEXT DEFAULT NULL,
    `votes_count` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Table Voters
CREATE TABLE `voters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nim` VARCHAR(50) NOT NULL UNIQUE,
    `voter_name` VARCHAR(150) NOT NULL,
    `candidate_id` INT NOT NULL,
    `voted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    INDEX `idx_candidate` (`candidate_id`),
    CONSTRAINT `fk_voters_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Table Settings
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(50) NOT NULL UNIQUE,
    `setting_value` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('election_title', 'Pemilihan Ketua ICP Periode 2026-2027'),
('organization', 'International Class Program (ICP)'),
('election_status', 'open'),
('allow_public_dashboard', '1');

-- Seed Admin (Password: icp123 - hashed using bcrypt and stored)
-- Password hash for 'icp123'
INSERT INTO `users` (`username`, `password`, `name`, `role`) VALUES
('icp', '$2y$10$f6bS4yZzU93.uGgKrkJjleWf4dwh4Qz/qW.xUq77nNqP8Zk9q6mty', 'Panitia Pemilihan ICP', 'admin');

-- Seed Initial 3 Candidates
INSERT INTO `candidates` (`candidate_number`, `name`, `tagline`, `photo`, `vision`, `mission`, `programs`, `votes_count`) VALUES
(1, 'Adrian Wijaya & Nabila Putri', 'ICP Inklusif: Mengakselerasi Prestasi Internasional & Kebersamaan', '/assets/uploads/calon1.svg', 
'Mewujudkan ICP sebagai wadah pengembangan kepemimpinan global yang adaptif, kolaboratif, dan berdaya saing internasional dengan menjunjung tinggi nilai kekeluargaan dan integritas.',
'1. Mengoptimalkan program mentoring akademik dan persiapan karir global untuk seluruh mahasiswa ICP.\n2. Meningkatkan sinergi lintas angkatan melalui forum berkala dan ruang kolaborasi terbuka.\n3. Memfasilitasi partisipasi aktif mahasiswa dalam kompetisi internasional dan konferensi ilmiah.\n4. Menerapkan tata kelola organisasi yang transparan, akuntabel, dan berbasis teknologi informasi.',
'• ICP Global Mentorship & Career Accelerator\n• International Case & Debate Training Camp\n• ICP Synergy Day & Cross-Batch Bonding\n• Portal Digital Aspirasi Mahasiswa', 0),

(2, 'Dimas Arya Pratama & Keisha Zahra', 'ICP Berdampak: Sinergi Riset, Kepemimpinan, dan Pengabdian Dunia', '/assets/uploads/calon2.svg',
'Transformasi ICP sebagai episentrum keunggulan mahasiswa berpikiran global dengan kontribusi nyata dalam riset inovatif, kepemimpinan etis, dan jaringan alumni dunia.',
'1. Membangun platform ICP Global Connect untuk menghubungkan mahasiswa aktif dengan jejaring alumni dunia.\n2. Menginisiasi pendanaan riset inovasi mahasiswa ICP (ICP Research & Innovation Fund).\n3. Memperluas kerja sama strategis dengan organisasi mahasiswa internasional dan industri global.\n4. Membangun lingkungan organisasi yang inklusif, suportif terhadap kesehatan mental, dan responsif.',
'• ICP Global Alumni Networking Hub\n• Research & Innovation Seed Funding\n• Ambassador of Culture & SDGs Community Outreach\n• Wellness & Mental Health Circle', 0),

(3, 'Farhan Maulana & Cindy Aurelia', 'ICP Action: Tanggap, Dinamis, dan Siap Memimpin Masa Depan', '/assets/uploads/calon3.svg',
'Menciptakan ICP yang berkarakter mandiri, solutif terhadap revolusi digital dan kecerdasan buatan, serta terdepan dalam pembentukan pemimpin abad 21.',
'1. Menyelenggarakan pelatihan keterampilan masa depan (AI literacy, Data Analytics, Public Speaking).\n2. Meluncurkan aksi pengabdian masyarakat internasional yang terintegrasi (ICP Community Action).\n3. Digitalisasi menyeluruh sistem aspirasi dan keterbukaan kas himpunan secara real-time.\n4. Menghidupkan unit kegiatan mahasiswa bidang seni, olahraga, dan inovasi teknologi.',
'• AI & Future Skills Bootcamp\n• ICP Impact for Society (Desa Binaan Digital)\n• Real-Time Open Treasury & Dashboard\n• ICP Arts, Sports, and Tech Tournament', 0);
