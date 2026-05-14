<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_require_login();

$username = sn_current_username();
if ($username === null) {
    header('Location: /socialnet/signin.php');
    exit();
}

$message = '';
$isError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    sn_csrf_validate();

    $description = (string)($_POST['description'] ?? '');
    if (mb_strlen($description) > 5000) {
        $message = 'Description is too long.';
        $isError = true;
    } else {
        $conn = sn_db();
        $query = "UPDATE account SET description = '$description' WHERE username = '$username'";
        if ($conn->query($query)) {
            $message = 'Saved.';
            $isError = false;
        } else {
            $message = 'Failed to update profile.';
            $isError = true;
        }
    }
}

$me = sn_get_account_by_username($username);
$currentDescription = is_array($me) ? (string)($me['description'] ?? '') : '';

sn_render_shell_start('Settings', ['nav' => true]);

?>
        <h1 class="sn-page-title">Settings</h1>
        <p class="sn-page-lead">This text is stored in the <code>description</code> column and shown on your profile.</p>

        <div class="sn-card">
            <?php if ($message !== ''): ?>
                <div class="sn-alert <?php echo $isError ? 'sn-alert--error' : 'sn-alert--success'; ?>">
                    <?php echo sn_e($message); ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/socialnet/setting.php">
                <input type="hidden" name="csrf_token" value="<?php echo sn_e(sn_csrf_token()); ?>">

                <div class="sn-field">
                    <label for="description">Profile description</label>
                    <textarea class="sn-textarea" id="description" name="description" rows="10"><?php echo sn_e($currentDescription); ?></textarea>
                </div>

                <div class="sn-actions">
                    <button class="sn-btn sn-btn--primary" type="submit">Save changes</button>
                </div>
            </form>
        </div>
<?php
sn_render_shell_end();
