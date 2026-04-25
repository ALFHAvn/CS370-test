<?php
$conn = new mysqli("localhost", "webuser", "123456", "userdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // 🔐 VERIFY password
        if (password_verify($password, $row['password'])) {
            $message = "Login successful!";
        } else {
            $message = "Wrong password!";
        }

    } else {
        $message = "User not found!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<form method="POST">
    Username: <input type="text" name="username"><br><br>
    Password: <input type="password" name="password"><br><br>

    <input type="submit" value="Login">
</form>

<p><?php echo $message; ?></p>

</body>
</html>
