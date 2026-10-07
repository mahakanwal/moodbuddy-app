-- =========================================================
-- MoodBuddy - database schema (MySQL 5.7+ / 8.x, MariaDB, TiDB)
-- Import this ONCE into your empty database.
-- If you have your own export from XAMPP/phpMyAdmin, you can import that instead.
-- =========================================================

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  first_name    VARCHAR(100) NOT NULL,
  last_name     VARCHAR(100) NOT NULL,
  username      VARCHAR(100) NOT NULL UNIQUE,
  email         VARCHAR(190) NOT NULL UNIQUE,
  profile_photo VARCHAR(255) DEFAULT 'default.jpg',
  password      VARCHAR(255) NOT NULL,
  role          VARCHAR(20)  NOT NULL DEFAULT 'user',
  bio           TEXT NULL,
  verify_token  VARCHAR(100) NULL,
  is_verified   TINYINT(1)   NOT NULL DEFAULT 0,
  reset_token   VARCHAR(100) NULL,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  description TEXT NULL,
  mood        VARCHAR(30)  NOT NULL,
  category    VARCHAR(100) NULL,
  image       VARCHAR(255) NULL,
  price       DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_products_mood (mood)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS songs (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(255) NOT NULL,
  singer      VARCHAR(255) NULL,
  mood        VARCHAR(30)  NOT NULL,
  file_path   VARCHAR(255) NOT NULL,
  description TEXT NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_songs_mood (mood)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS quotes (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  text       TEXT NOT NULL,
  author     VARCHAR(255) NULL,
  mood       VARCHAR(30)  NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_quotes_mood (mood)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS memes (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  title      VARCHAR(255) NULL,
  mood       VARCHAR(30)  NOT NULL,
  image      VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_memes_mood (mood)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS games (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(255) NOT NULL,
  description TEXT NULL,
  mood        VARCHAR(30)  NOT NULL,
  url         VARCHAR(500) NULL,
  image       VARCHAR(255) NULL,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_games_mood (mood)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
