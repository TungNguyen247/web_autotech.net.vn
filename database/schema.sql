-- =============================================================
-- Autotech Website Database Schema
-- Target: MySQL 5.7+ / MariaDB 10.3+
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- Table: admin_users
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(80)     NOT NULL,
  `password_hash` VARCHAR(255)    NOT NULL,
  `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admin_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: categories
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name_vi`    VARCHAR(150)  NOT NULL,
  `name_en`    VARCHAR(150)  NOT NULL,
  `slug`       VARCHAR(160)  NOT NULL,
  `created_at` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_category_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: products
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id`              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `category_id`     INT UNSIGNED    DEFAULT NULL,
  `name_vi`         VARCHAR(200)    NOT NULL,
  `name_en`         VARCHAR(200)    NOT NULL,
  `short_desc_vi`   VARCHAR(500)    DEFAULT NULL,
  `short_desc_en`   VARCHAR(500)    DEFAULT NULL,
  `description_vi`  TEXT            DEFAULT NULL,
  `description_en`  TEXT            DEFAULT NULL,
  `price`           DECIMAL(15,2)   DEFAULT NULL,
  `image_path`      VARCHAR(300)    DEFAULT NULL,
  `is_active`       TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product_category` (`category_id`),
  KEY `idx_product_active`   (`is_active`),
  CONSTRAINT `fk_product_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- Table: contacts
-- -------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contacts` (
  `id`         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(150)  NOT NULL,
  `email`      VARCHAR(254)  NOT NULL,
  `phone`      VARCHAR(30)   NOT NULL,
  `subject`    VARCHAR(100)  DEFAULT NULL,
  `message`    TEXT          NOT NULL,
  `is_read`    TINYINT(1)    NOT NULL DEFAULT 0,
  `created_at` DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_contact_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================
-- Seed: default categories
-- =============================================================
INSERT IGNORE INTO `categories` (`name_vi`, `name_en`, `slug`) VALUES
  ('Biến tần',            'Inverter',              'bien-tan'),
  ('PLC & HMI',           'PLC & HMI',             'plc-hmi'),
  ('Tự động hóa',         'Automation',            'tu-dong-hoa'),
  ('Tư vấn & Lắp đặt',   'Consulting & Install',  'tu-van-lap-dat');

-- =============================================================
-- Seed: default admin user
--   username : admin
--   password : Admin@2026  (change after first login!)
--   hash     : password_hash('Admin@2026', PASSWORD_BCRYPT)
-- =============================================================
INSERT IGNORE INTO `admin_users` (`username`, `password_hash`) VALUES
  ('admin', '$2y$12$tXDqmklJaIlFJ4fGIflkuuamJlPJjqOc6qNlMFgULGECRGe0eexJW');
