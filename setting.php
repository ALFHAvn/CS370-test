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
    <title>Setting</title>
</head>
<body>

<?php include 'navbar.php'; ?>

<h2>Settings</h2>

<p>Setting page here.</p>

</body>
</html>
