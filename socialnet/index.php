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

sn_render_shell_start('Home', ['nav' => true]);

?>
        <h1 class="sn-page-title">Home</h1>
        <p class="sn-page-lead">Your profile summary and everyone else on the network.</p>

        <div class="sn-card">
            <h2 class="sn-profile-label" style="margin-bottom: 0.75rem;">Your info</h2>
            <dl class="sn-dl">
                <div>
                    <dt>Username</dt>
                    <dd><?php echo sn_e((string)$me['username']); ?></dd>
                </div>
                <div>
                    <dt>Full name</dt>
                    <dd><?php echo sn_e((string)$me['fullname']); ?></dd>
                </div>
            </dl>
        </div>

        <div class="sn-card">
            <h2 class="sn-profile-label" style="margin-bottom: 0.75rem;">Other users</h2>

            <?php if (count($others) === 0): ?>
                <p class="sn-empty">No other users yet. Create another account from the admin page.</p>
            <?php else: ?>
                <ul class="sn-user-list">
                    <?php foreach ($others as $u): ?>
                        <?php
                            $ou = (string)($u['username'] ?? '');
                            $of = (string)($u['fullname'] ?? '');
                            $href = '/socialnet/profile.php?owner=' . rawurlencode($ou);
                            $letter = sn_avatar_letter($ou);
                        ?>
                        <li class="sn-user-item">
                            <span class="sn-avatar" aria-hidden="true"><?php echo sn_e($letter); ?></span>
                            <div class="sn-user-meta">
                                <a class="sn-user-name" href="<?php echo sn_e($href); ?>"><?php echo sn_e($ou); ?></a>
                                <div class="sn-user-full"><?php echo sn_e($of); ?></div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
<?php
sn_render_shell_end();
