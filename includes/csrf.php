<?php
/**
 * csrf.php — CSRF token generation and validation.
 */

/**
 * Generate (or retrieve) a CSRF token stored in the session.
 */
function csrf_token(): string
{
    session_start_secure();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Render a hidden CSRF input field.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

/**
 * Validate the CSRF token from a POST request.
 * Terminates with HTTP 403 on failure.
 */
function csrf_verify(): void
{
    session_start_secure();
    $token = $_POST['csrf_token'] ?? '';
    if (
        empty($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(403);
        exit('403 Forbidden – CSRF token mismatch.');
    }
}
