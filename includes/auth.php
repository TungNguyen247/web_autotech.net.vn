<?php
/**
 * auth.php — Session-based admin authentication helpers.
 */

/**
 * Start (or resume) the application session with secure settings.
 */
function session_start_secure(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $name     = defined('SESSION_NAME')     ? SESSION_NAME     : 'autotech_sess';
    $lifetime = defined('SESSION_LIFETIME') ? SESSION_LIFETIME : 7200;
    $secure   = defined('SESSION_SECURE')   ? SESSION_SECURE   : false;
    $httponly = defined('SESSION_HTTPONLY')  ? SESSION_HTTPONLY : true;

    session_name($name);
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $secure,
        'httponly' => $httponly,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * Return true if an admin is currently logged in.
 */
function is_logged_in(): bool
{
    session_start_secure();
    return !empty($_SESSION['admin_id']);
}

/**
 * Redirect to the login page if not authenticated.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        redirect(admin_url('login.php'));
    }
}

/**
 * Log an admin user in (call after verifying credentials).
 */
function login_admin(int $id, string $username): void
{
    session_start_secure();
    session_regenerate_id(true);
    $_SESSION['admin_id']       = $id;
    $_SESSION['admin_username'] = $username;
    $_SESSION['login_at']       = time();
}

/**
 * Destroy the admin session.
 */
function logout_admin(): void
{
    session_start_secure();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $p['path'], $p['domain'],
            $p['secure'], $p['httponly']
        );
    }
    session_destroy();
}

/**
 * Return the username of the logged-in admin, or empty string.
 */
function current_admin(): string
{
    return $_SESSION['admin_username'] ?? '';
}

/**
 * Build an absolute URL under /admin/.
 */
function admin_url(string $path = ''): string
{
    $base = defined('APP_URL') ? rtrim(APP_URL, '/') : '';
    return $base . '/admin/' . ltrim($path, '/');
}
