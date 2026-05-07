<?php

declare(strict_types=1);

// Basic hardening defaults. (Disable display_errors in production.)
error_reporting(E_ALL);
ini_set('display_errors', '0');

function sn_env(string $key, ?string $default = null): ?string
{
    $val = getenv($key);
    if ($val === false) {
        return $default;
    }
    return $val;
}

function sn_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443');

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function sn_db(): mysqli
{
    static $conn = null;
    if ($conn instanceof mysqli) {
        return $conn;
    }

    $host = sn_env('SOCIALNET_DB_HOST', 'localhost');
    $user = sn_env('SOCIALNET_DB_USER', 'webuser');
    $pass = sn_env('SOCIALNET_DB_PASS', '123456');
    $name = sn_env('SOCIALNET_DB_NAME', 'socialnet');

    $conn = new mysqli((string)$host, (string)$user, (string)$pass, (string)$name);
    if ($conn->connect_error) {
        http_response_code(500);
        echo "Database connection failed.";
        exit();
    }

    $conn->set_charset('utf8mb4');
    return $conn;
}

function sn_e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function sn_csrf_token(): string
{
    sn_session_start();
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token']) || $_SESSION['csrf_token'] === '') {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function sn_csrf_validate(): void
{
    sn_session_start();
    $token = $_POST['csrf_token'] ?? '';
    $expected = $_SESSION['csrf_token'] ?? '';
    if (!is_string($token) || !is_string($expected) || $token === '' || !hash_equals($expected, $token)) {
        http_response_code(400);
        echo "Invalid CSRF token.";
        exit();
    }
}

function sn_require_login(): void
{
    sn_session_start();
    if (empty($_SESSION['username']) || !is_string($_SESSION['username'])) {
        header('Location: /socialnet/signin.php');
        exit();
    }
}

function sn_current_username(): ?string
{
    sn_session_start();
    $u = $_SESSION['username'] ?? null;
    return is_string($u) && $u !== '' ? $u : null;
}

function sn_get_account_by_username(string $username): ?array
{
    $conn = sn_db();
    $stmt = $conn->prepare('SELECT id, username, fullname, description FROM account WHERE username = ? LIMIT 1');
    if (!$stmt) {
        return null;
    }
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res ? $res->fetch_assoc() : null;
    $stmt->close();
    return is_array($row) ? $row : null;
}

function sn_render_menubar(): void
{
    echo '<hr>';
    echo '<a href="/socialnet/index.php">Home</a> | ';
    echo '<a href="/socialnet/setting.php">Setting</a> | ';
    echo '<a href="/socialnet/profile.php">Profile</a> | ';
    echo '<a href="/socialnet/about.php">About</a> | ';
    echo '<a href="/socialnet/signout.php">SignOut</a>';
    echo '<hr>';
}

