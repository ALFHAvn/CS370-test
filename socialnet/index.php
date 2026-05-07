<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_require_login();

$username = sn_current_username();
if ($username === null) {
    header('Location: /socialnet/signin.php');
    exit();
}

$me = sn_get_account_by_username($username);
if ($me === null) {
    // Session refers to a non-existent user; reset.
    $_SESSION = [];
    header('Location: /socialnet/signin.php');
    exit();
}

$conn = sn_db();
$stmt = $conn->prepare('SELECT username, fullname FROM account WHERE username <> ? ORDER BY username ASC');
$others = [];
if ($stmt) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($res && ($row = $res->fetch_assoc())) {
        $others[] = $row;
    }
    $stmt->close();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SocialNet - Home</title>
</head>
<body>

<?php sn_render_menubar(); ?>

<h2>Home</h2>

<h3>Your info</h3>
<ul>
    <li>Username: <?php echo sn_e((string)$me['username']); ?></li>
    <li>Full name: <?php echo sn_e((string)$me['fullname']); ?></li>
</ul>

<h3>Other users</h3>

<?php if (count($others) === 0): ?>
    <p>No other users found.</p>
<?php else: ?>
    <ul>
        <?php foreach ($others as $u): ?>
            <?php
                $ou = (string)($u['username'] ?? '');
                $of = (string)($u['fullname'] ?? '');
                $href = '/socialnet/profile.php?owner=' . rawurlencode($ou);
            ?>
            <li>
                <a href="<?php echo sn_e($href); ?>">
                    <?php echo sn_e($ou); ?>
                </a>
                (<?php echo sn_e($of); ?>)
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

</body>
</html>

