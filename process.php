<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get form data
$username = $_POST["username"];
$fullname = $_POST["fullname"];
$password = $_POST["password"];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Connect DB
$conn = new mysqli("localhost", "webuser", "123456", "userdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert safely
$stmt = $conn->prepare("INSERT INTO users (username, fullname, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $fullname, $hashed_password);

if ($stmt->execute()) {
    echo " Data saved successfully!";
} else {
    echo " Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
