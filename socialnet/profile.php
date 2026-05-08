<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_require_login();

$current = sn_current_username();
if ($current === null) {
    header('Location: /socialnet/signin.php');
    exit();
}

$owner = trim((string)($_GET['owner'] ?? ''));
if ($owner === '') {
    $owner = $current;
}
if (mb_strlen($owner) > 50) {
    $owner = $current;
}

$account = sn_get_account_by_username($owner);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SocialNet - Profile</title>
</head>
<body>

<?php sn_render_menubar(); ?>

<h2>Profile</h2>

<?php if ($account === null): ?>
    <p style="color: crimson;">User not found.</p>
<?php else: ?>
    <p>Owner: <strong><?php echo sn_e((string)$account['username']); ?></strong></p>
    <p>Full name: <?php echo sn_e((string)$account['fullname']); ?></p>

    <h3>Description</h3>
    <?php
        $desc = (string)($account['description'] ?? '');
        if (trim($desc) === '') {
            echo '<p><em>No description yet.</em></p>';
        } else {
            echo '<div style="white-space: pre-wrap;">' . sn_e($desc) . '</div>';
        }
    ?>
<?php endif; ?>

</body>
</html>

