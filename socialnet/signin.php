<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_session_start();

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
        $query = "SELECT username, password FROM account WHERE username = '$username' LIMIT 1";
        $res = $conn->query($query);
        $row = $res ? $res->fetch_assoc() : null;

        $hash = is_array($row) ? (string)($row['password'] ?? '') : '';
        if ($hash !== '' && password_verify($password, $hash)) {
            session_regenerate_id(true);
            $_SESSION['username'] = $row['username']; // Use database value for session
            header('Location: /socialnet/index.php');
            exit();
        }

        $message = 'Invalid username or password.';
    }
}

sn_render_shell_start('Sign in', ['nav' => false]);

?>
        <div class="sn-card sn-card--narrow">
            <h1 class="sn-page-title" style="margin-bottom: 0.25rem;">Welcome back</h1>
            <p class="sn-page-lead" style="margin-bottom: 1.25rem;">Sign in with an account created by the admin.</p>

            <?php if ($message !== ''): ?>
                <div class="sn-alert sn-alert--error"><?php echo sn_e($message); ?></div>
            <?php endif; ?>

            <form method="post" action="/socialnet/signin.php">
                <input type="hidden" name="csrf_token" value="<?php echo sn_e(sn_csrf_token()); ?>">

                <div class="sn-field">
                    <label for="username">Username</label>
                    <input class="sn-input" id="username" type="text" name="username" required autocomplete="username">
                </div>

                <div class="sn-field">
                    <label for="password">Password</label>
                    <input class="sn-input" id="password" type="password" name="password" required autocomplete="current-password">
                </div>

                <div class="sn-actions">
                    <button class="sn-btn sn-btn--primary" type="submit">Sign in</button>
                </div>
            </form>
        </div>

        <p class="sn-page-lead" style="text-align: center; max-width: 420px; margin: 1.25rem auto 0;">
            Need an account? An admin can create one at
            <a class="sn-link" href="/admin/newuser.php">/admin/newuser.php</a>
        </p>
<?php
sn_render_shell_end();
