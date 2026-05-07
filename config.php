<?php
// ============================================================
// SocialNet - Database Configuration
// ============================================================
//
// This file handles all database connections for the application.
// Include this file at the top of any page that needs database access.
//
// Usage:
// <?php include 'config.php'; ?>
// // Now $conn is available for database queries
//
// ============================================================

// ============================================================
// Database Credentials
// ============================================================
// Change these values if your database setup is different

$db_host = "localhost";      // Database server address
$db_user = "webuser";        // Database username
$db_pass = "123456";         // Database password
$db_name = "socialnet";      // Database name

// ============================================================
// Create Database Connection
// ============================================================
// Using mysqli (improved MySQL extension)
// $conn will be used throughout the application

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// ============================================================
// Check Connection
// ============================================================
// If connection fails, display error and stop execution

if ($conn->connect_error) {
    // Connection failed - show error message
    die("❌ Database Connection Error: " . $conn->connect_error);
}

// ============================================================
// Set Character Set to UTF-8
// ============================================================
// Ensures proper handling of special characters (accents, emojis, etc.)

$conn->set_charset("utf8mb4");

// ============================================================
// Configuration Complete
// ============================================================
// $conn is now ready to use for:
// - Prepared statements: $conn->prepare()
// - Queries: $conn->query()
// - Escaping: $conn->real_escape_string()
//
// Example:
// $stmt = $conn->prepare("SELECT * FROM account WHERE username = ?");
// $stmt->bind_param("s", $username);
// $stmt->execute();
// $result = $stmt->get_result();
//
// ============================================================
?>
