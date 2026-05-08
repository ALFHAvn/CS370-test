<?php

require_once __DIR__ . '/../includes/bootstrap.php';

sn_session_start();

// Clear session data
$_SESSION = [];

// Best-effort session cookie cleanup
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
}

session_destroy();

header('Location: /socialnet/signin.php');
exit();

