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
        $stmt = $conn->prepare('UPDATE account SET description = ? WHERE username = ?');
        if (!$stmt) {
            $message = 'Failed to update profile.';
            $isError = true;
        } else {
            $stmt->bind_param('ss', $description, $username);
            if ($stmt->execute()) {
                $message = 'Saved.';
                $isError = false;
            } else {
                $message = 'Failed to update profile.';
                $isError = true;
            }
            $stmt->close();
        }
    }
}

$me = sn_get_account_by_username($username);
$currentDescription = is_array($me) ? (string)($me['description'] ?? '') : '';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SocialNet - Setting</title>
</head>
<body>

<?php sn_render_menubar(); ?>

<h2>Setting</h2>

<p>Edit your Profile Page content (stored in the <code>description</code> field).</p>

<?php if ($message !== ''): ?>
    <p style="color: <?php echo $isError ? 'crimson' : 'green'; ?>;">
        <?php echo sn_e($message); ?>
    </p>
<?php endif; ?>

<form method="post" action="/socialnet/setting.php">
    <input type="hidden" name="csrf_token" value="<?php echo sn_e(sn_csrf_token()); ?>">

    <div>
        <textarea name="description" rows="10" cols="80"><?php echo sn_e($currentDescription); ?></textarea>
    </div>
    <br>

    <button type="submit">Save</button>
</form>

</body>
</html>

