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

sn_render_shell_start('Profile', ['nav' => true]);

?>
        <h1 class="sn-page-title">Profile</h1>
        <p class="sn-page-lead">Bio text comes from the description saved in Settings.</p>

        <div class="sn-card">
            <?php if ($account === null): ?>
                <div class="sn-alert sn-alert--error">User not found.</div>
            <?php else: ?>
                <div class="sn-profile-header">
                    <p class="sn-profile-label">Profile owner</p>
                    <p class="sn-profile-owner"><?php echo sn_e((string)$account['username']); ?></p>
                    <p class="sn-user-full" style="margin-top: 0.35rem;"><?php echo sn_e((string)$account['fullname']); ?></p>
                </div>

                <h2 class="sn-profile-label" style="margin: 1.25rem 0 0.5rem;">Description</h2>
                <?php
                    $desc = (string)($account['description'] ?? '');
                    if (trim($desc) === '') {
                        echo '<p class="sn-muted">No description yet.</p>';
                    } else {
                        echo '<p class="sn-prose">' . $desc . '</p>';
                    }
                ?>
            <?php endif; ?>
        </div>
<?php
sn_render_shell_end();
