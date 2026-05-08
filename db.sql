-- SocialNet database schema
-- This file is used to create a fresh DB in a new environment.

CREATE DATABASE IF NOT EXISTS socialnet
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE socialnet;

CREATE TABLE IF NOT EXISTS account (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL,
  fullname VARCHAR(120) NOT NULL,
  password VARCHAR(255) NOT NULL,
  description TEXT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uniq_account_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

