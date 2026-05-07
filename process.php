<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect DB
$conn = new mysqli("localhost", "webuser", "123456", "userdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $password = $_POST['password'] ?? '';

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert
    $stmt = $conn->prepare(
        "INSERT INTO users (username, fullname, password)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param(
        "sss",
        $username,
        $fullname,
        $hashed_password
    );

    if ($stmt->execute()) {
        echo "Register successful!";
        echo "<br><a href='signin.php'>Login now</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>

