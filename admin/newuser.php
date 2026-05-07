<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_session_start();

$message = '';
$isError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sn_csrf_validate();

    $username = trim((string)($_POST['username'] ?? ''));
    $fullname = trim((string)($_POST['fullname'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $description = trim((string)($_POST['description'] ?? ''));

    if ($username === '' || $fullname === '' || $password === '') {
        $message = 'Username, fullname, and password are required.';
        $isError = true;
    } elseif (mb_strlen($username) > 50 || mb_strlen($fullname) > 120) {
        $message = 'Username/fullname too long.';
        $isError = true;
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $conn = sn_db();

        $stmt = $conn->prepare('INSERT INTO account (username, fullname, password, description) VALUES (?, ?, ?, ?)');
        if (!$stmt) {
            $message = 'Failed to prepare statement.';
            $isError = true;
        } else {
            $stmt->bind_param('ssss', $username, $fullname, $hash, $description);
            if ($stmt->execute()) {
                $message = 'User created successfully. You can now sign in.';
                $isError = false;
            } else {
                // Common: duplicate username
                $message = 'Failed to create user (username may already exist).';
                $isError = true;
            }
            $stmt->close();
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - New User</title>
</head>
<body>

<h2>Admin: Create New User</h2>

<?php if ($message !== ''): ?>
    <p style="color: <?php echo $isError ? 'crimson' : 'green'; ?>;">
        <?php echo sn_e($message); ?>
    </p>
<?php endif; ?>

<form method="post" action="/admin/newuser.php" autocomplete="off">
    <input type="hidden" name="csrf_token" value="<?php echo sn_e(sn_csrf_token()); ?>">

    <div>
        <label>
            Username
            <input type="text" name="username" maxlength="50" required>
        </label>
    </div>
    <br>

    <div>
        <label>
            Full name
            <input type="text" name="fullname" maxlength="120" required>
        </label>
    </div>
    <br>

    <div>
        <label>
            Password
            <input type="password" name="password" required>
        </label>
    </div>
    <br>

    <div>
        <label>
            Description (optional)
            <br>
            <textarea name="description" rows="5" cols="60"></textarea>
        </label>
    </div>
    <br>

    <button type="submit">Create user</button>
</form>

<hr>
<a href="/socialnet/signin.php">Go to Sign In</a>

</body>
</html>

