<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_session_start();

// If already signed in, go home.
if (sn_current_username() !== null) {
    header('Location: /socialnet/index.php');
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sn_csrf_validate();

    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = 'Invalid username or password.';
    } else {
        $conn = sn_db();
        $stmt = $conn->prepare('SELECT password FROM account WHERE username = ? LIMIT 1');
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res ? $res->fetch_assoc() : null;
            $stmt->close();

            $hash = is_array($row) ? (string)($row['password'] ?? '') : '';
            if ($hash !== '' && password_verify($password, $hash)) {
                // Prevent session fixation
                session_regenerate_id(true);
                $_SESSION['username'] = $username;
                header('Location: /socialnet/index.php');
                exit();
            }
        }

        $message = 'Invalid username or password.';
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SocialNet - Sign In</title>
</head>
<body>

<h2>Sign In</h2>

<?php if ($message !== ''): ?>
    <p style="color: crimson;"><?php echo sn_e($message); ?></p>
<?php endif; ?>

<form method="post" action="/socialnet/signin.php">
    <input type="hidden" name="csrf_token" value="<?php echo sn_e(sn_csrf_token()); ?>">

    <div>
        <label>
            Username
            <input type="text" name="username" required>
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

    <button type="submit">Sign In</button>
</form>

<hr>
<p>Accounts are created from the admin page: <a href="/admin/newuser.php">/admin/newuser.php</a></p>

</body>
</html>

