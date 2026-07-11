-- ============================================================
--  SCHEMA DATABASE: digital_printing
--  Jalankan file ini di phpMyAdmin / MySQL CLI untuk membuat
--  seluruh struktur tabel.
--
--  CLI:  mysql -u root -p < database/schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS `digital_printing`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `digital_printing`;

-- ------------------------------------------------------------
--  users : akun admin panel
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100)  NOT NULL,
    `email`      VARCHAR(150)  NOT NULL UNIQUE,
    `password`   VARCHAR(255)  NOT NULL,          -- hash bcrypt
    `role`       VARCHAR(20)   NOT NULL DEFAULT 'admin',
    `created_at` TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  ads : iklan / banner hero di landing page
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ads` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title`      VARCHAR(150)  NOT NULL,
    `subtitle`   VARCHAR(255)  DEFAULT NULL,
    `image`      VARCHAR(255)  NOT NULL,          -- nama file di uploads/ads
    `link_url`   VARCHAR(255)  DEFAULT NULL,      -- link tombol (opsional)
    `is_active`  TINYINT(1)    NOT NULL DEFAULT 1,
    `sort_order` INT           NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  services : layanan cetak (spanduk, banner, kartu nama, dll)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `services` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`        VARCHAR(150)  NOT NULL,
    `slug`        VARCHAR(170)  NOT NULL UNIQUE,
    `description` TEXT          DEFAULT NULL,
    `icon`        VARCHAR(255)  DEFAULT NULL,     -- nama file/ikon
    `price_from`  DECIMAL(12,2) DEFAULT NULL,     -- harga mulai dari
    `is_active`   TINYINT(1)    NOT NULL DEFAULT 1,
    `sort_order`  INT           NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  products : produk cetak yang dijual per item
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `service_id`  INT UNSIGNED  DEFAULT NULL,     -- relasi ke services (opsional)
    `name`        VARCHAR(150)  NOT NULL,
    `description` TEXT          DEFAULT NULL,
    `image`       VARCHAR(255)  DEFAULT NULL,     -- uploads/products
    `price`       DECIMAL(12,2) DEFAULT NULL,
    `is_active`   TINYINT(1)    NOT NULL DEFAULT 1,
    `created_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_service`
        FOREIGN KEY (`service_id`) REFERENCES `services`(`id`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  portfolio : hasil kerjaan / galeri
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `portfolio` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title`       VARCHAR(150)  NOT NULL,
    `client`      VARCHAR(150)  DEFAULT NULL,
    `description` TEXT          DEFAULT NULL,
    `image`       VARCHAR(255)  NOT NULL,         -- uploads/portfolio
    `category`    VARCHAR(80)   DEFAULT NULL,
    `is_active`   TINYINT(1)    NOT NULL DEFAULT 1,
    `sort_order`  INT           NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  messages : pesan masuk dari form kontak
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name`       VARCHAR(100)  NOT NULL,
    `email`      VARCHAR(150)  DEFAULT NULL,
    `phone`      VARCHAR(30)   DEFAULT NULL,
    `subject`    VARCHAR(180)  DEFAULT NULL,
    `body`       TEXT          NOT NULL,
    `is_read`    TINYINT(1)    NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
--  settings : info company (key-value)
--  contoh key: company_name, address, phone, whatsapp,
--              email, instagram, open_hours, maps_embed
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
    `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key_name`   VARCHAR(80)   NOT NULL UNIQUE,
    `value`      TEXT          DEFAULT NULL,
    `updated_at` TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
