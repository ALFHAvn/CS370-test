<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_session_start();

$message = '';
$isError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
                $message = 'Failed to create user (username may already exist).';
                $isError = true;
            }
            $stmt->close();
        }
    }
}

sn_render_shell_start('Admin — New user', [
    'nav' => false,
    'brand_href' => '/admin/newuser.php',
    'brand_label' => 'SocialNet Admin',
]);

?>
        <h1 class="sn-page-title">Create account</h1>
        <p class="sn-page-lead">Add a user to the database. They can sign in from the main app.</p>

        <div class="sn-card">
            <?php if ($message !== ''): ?>
                <div class="sn-alert <?php echo $isError ? 'sn-alert--error' : 'sn-alert--success'; ?>">
                    <?php echo sn_e($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/admin/newuser.php" autocomplete="off">

                <div class="sn-field">
                    <label for="username">Username</label>
                    <input class="sn-input" id="username" type="text" name="username" maxlength="50" required>
                </div>

                <div class="sn-field">
                    <label for="fullname">Full name</label>
                    <input class="sn-input" id="fullname" type="text" name="fullname" maxlength="120" required>
                </div>

                <div class="sn-field">
                    <label for="password">Password</label>
                    <input class="sn-input" id="password" type="password" name="password" required>
                </div>

                <div class="sn-field">
                    <label for="description">Description <span class="sn-muted">(optional)</span></label>
                    <textarea class="sn-textarea" id="description" name="description" rows="4"></textarea>
                </div>

                <div class="sn-actions">
                    <button class="sn-btn sn-btn--primary" type="submit">Create user</button>
                    <a class="sn-link" href="/socialnet/signin.php">Go to sign in →</a>
                </div>
            </form>
        </div>
<?php
sn_render_shell_end();
