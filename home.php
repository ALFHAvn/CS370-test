<?php
session_start();

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

<?php include 'navbar.php'; ?>

<h2>Home Page</h2>

<p>Hello, <?php echo $_SESSION['username']; ?>!</p>

</body>
</html>
