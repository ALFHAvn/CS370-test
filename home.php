<?php
session_start();

// Check login
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>

<h2>Hello, <?php echo $_SESSION['username']; ?> </h2>

<hr>

<!-- Simple menu -->
<a href="home.php">Home</a> |
<a href="#">Setting</a> |
<a href="logout.php">Sign out</a>

<hr>

<p>Welcome to your social app homepage.</p>

</body>
</html>
