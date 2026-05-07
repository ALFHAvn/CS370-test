-- ============================================================
-- SocialNet Database Setup Script
-- ============================================================
-- This script creates the socialnet database and account table
-- Run this on your MySQL server to set up the application database
--
-- Usage: mysql -u webuser -p < db.sql
-- Or in MySQL console: source db.sql;
-- ============================================================

-- Drop existing database if it exists (for clean setup)
DROP DATABASE IF EXISTS socialnet;

-- Create the socialnet database
CREATE DATABASE socialnet;

-- Select the database for use
USE socialnet;

-- ============================================================
-- Create the account table
-- ============================================================
-- This table stores all user information for the SocialNet app
-- 
-- Columns:
--   id          : Unique identifier for each user (auto-incremented)
--   username    : Unique username for login (must be unique)
--   fullname    : User's full name for display
--   password    : Hashed password (stored securely with password_hash())
--   description : User's profile description/bio (can be empty)
-- ============================================================

CREATE TABLE account (
    -- Primary Key: Auto-incrementing unique ID
    -- INT: Integer number
    -- AUTO_INCREMENT: Automatically generates next number
    -- PRIMARY KEY: Uniquely identifies each row
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Username for login
    -- VARCHAR(50): String up to 50 characters
    -- UNIQUE: No two users can have same username
    -- NOT NULL: Must always have a value
    username VARCHAR(50) UNIQUE NOT NULL,
    
    -- User's full name for display
    -- VARCHAR(100): String up to 100 characters
    -- NOT NULL: Must always have a value
    fullname VARCHAR(100) NOT NULL,
    
    -- Hashed password (DO NOT store plain text passwords!)
    -- VARCHAR(255): Hash functions create long strings
    -- NOT NULL: Must always have a value
    password VARCHAR(255) NOT NULL,
    
    -- User's profile description/bio
    -- TEXT: Longer text field for descriptions
    -- DEFAULT '': Empty string if not provided
    description TEXT DEFAULT ''
);

-- ============================================================
-- Insert test data (OPTIONAL - for development/testing)
-- Uncomment below to add test users
-- ============================================================
-- Password hashes created with PHP password_hash('testpass123', PASSWORD_DEFAULT)
-- All test users have password: testpass123

/*
INSERT INTO account (username, fullname, password, description) VALUES
('toan_thang', 'Nguyen Dinh Toan Thang', '$2y$10$K1DjrKfF8Z1Ar/RYgO8/uOYjkJvNH5s5X4pQ9mK2lZ3uB6cE5pK5m', 'Welcome to my profile!'),
('khoa_dang', 'Do Dang Khoa', '$2y$10$K1DjrKfF8Z1Ar/RYgO8/uOYjkJvNH5s5X4pQ9mK2lZ3uB6cE5pK5m', 'Hello everyone!'),
('hai_an', 'Dao Hai An', '$2y$10$K1DjrKfF8Z1Ar/RYgO8/uOYjkJvNH5s5X4pQ9mK2lZ3uB6cE5pK5m', 'Nice to meet you!');
*/

-- ============================================================
-- Verify table creation
-- ============================================================
-- Run these commands to verify the setup:
-- SHOW TABLES;
-- DESCRIBE account;
-- SELECT * FROM account;
-- ============================================================
