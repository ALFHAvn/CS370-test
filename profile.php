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
    <title>Profile</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<h2>Profile Page</h2>

<p>Username: <?php echo $_SESSION['username']; ?></p>

</body>
</html>
