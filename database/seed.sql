-- ============================================================
--  SEED DATA AWAL
--  Jalankan SETELAH schema.sql.
--  CLI:  mysql -u root -p digital_printing < database/seed.sql
-- ============================================================

USE `digital_printing`;

-- ------------------------------------------------------------
--  Akun admin default
--  Email    : admin@printing.test
--  Password : admin123   (SEGERA GANTI setelah login pertama!)
--  Hash di bawah = bcrypt dari "admin123".
-- ------------------------------------------------------------
INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
('Administrator', 'admin@printing.test',
 '$2b$10$KJU7xDoBJ3a/.bDkdqc0.OlgfQCPY1AzhgJK9q8CgST9AujA6T/a.',
 'admin')
ON DUPLICATE KEY UPDATE `email` = `email`;

-- ------------------------------------------------------------
--  Setting company default (key-value)
-- ------------------------------------------------------------
INSERT INTO `settings` (`key_name`, `value`) VALUES
('company_name', 'CETAK JAYA DIGITAL PRINTING'),
('tagline',      'Cetak Cepat, Hasil Nampol.'),
('address',      'Jl. Contoh No. 123, Bandung'),
('phone',        '022-1234567'),
('whatsapp',     '6281234567890'),
('email',        'halo@cetakjaya.test'),
('instagram',    'https://instagram.com/cetakjaya'),
('open_hours',   'Senin–Sabtu, 08.00–20.00'),
('maps_embed',   '')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

-- ------------------------------------------------------------
--  Contoh layanan (biar landing page nggak kosong saat demo)
-- ------------------------------------------------------------
INSERT INTO `services` (`name`, `slug`, `description`, `price_from`, `sort_order`) VALUES
('Spanduk & Banner', 'spanduk-banner', 'Cetak spanduk flexi outdoor/indoor, tahan cuaca.', 25000, 1),
('Kartu Nama',       'kartu-nama',     'Kartu nama art carton, laminasi doff/glossy.',      35000, 2),
('Stiker & Label',   'stiker-label',   'Stiker vinyl, chromo, hologram, cutting.',          15000, 3),
('Undangan',         'undangan',       'Cetak undangan custom berbagai model.',             2000,  4)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
