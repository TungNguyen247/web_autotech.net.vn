<?php
/**
 * helpers.php — Shared utility functions.
 */

/**
 * HTML-escape a value (alias for htmlspecialchars).
 */
function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Redirect to a URL and exit.
 */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/**
 * Store a flash message in the session.
 *
 * @param string $type  'success' | 'error' | 'warning' | 'info'
 */
function flash(string $type, string $message): void
{
    session_start_secure();
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

/**
 * Retrieve and clear all flash messages.
 *
 * @return array<int, array{type: string, message: string}>
 */
function get_flashes(): array
{
    session_start_secure();
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

/**
 * Render flash messages as HTML.
 */
function render_flashes(): string
{
    $html = '';
    foreach (get_flashes() as $f) {
        $type = h($f['type']);
        $msg  = h($f['message']);
        $html .= "<div class=\"alert alert--{$type}\">{$msg}</div>\n";
    }
    return $html;
}

/**
 * Generate a URL-friendly slug from a UTF-8 string.
 */
function slugify(string $text): string
{
    // transliterate Vietnamese to ASCII if intl is available
    if (function_exists('transliterator_transliterate')) {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    } else {
        $text = mb_strtolower($text, 'UTF-8');
        // Basic Vietnamese map
        $map = [
            'à'=>'a','á'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a',
            'ẩ'=>'a','ẫ'=>'a','ậ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ẳ'=>'a',
            'ẵ'=>'a','ặ'=>'a','è'=>'e','é'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e',
            'ề'=>'e','ế'=>'e','ể'=>'e','ễ'=>'e','ệ'=>'e','ì'=>'i','í'=>'i',
            'ỉ'=>'i','ĩ'=>'i','ị'=>'i','ò'=>'o','ó'=>'o','ỏ'=>'o','õ'=>'o',
            'ô'=>'o','ồ'=>'o','ố'=>'o','ổ'=>'o','ỗ'=>'o','ộ'=>'o','ơ'=>'o',
            'ờ'=>'o','ớ'=>'o','ở'=>'o','ỡ'=>'o','ợ'=>'o','ù'=>'u','ú'=>'u',
            'ủ'=>'u','ũ'=>'u','ụ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ử'=>'u',
            'ữ'=>'u','ự'=>'u','ỳ'=>'y','ý'=>'y','ỷ'=>'y','ỹ'=>'y','ỵ'=>'y',
            'đ'=>'d',
        ];
        $text = strtr($text, $map);
    }

    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return $text;
}

/**
 * Handle image upload for products.
 * Returns relative path (e.g. "assets/uploads/abc123.jpg") or throws.
 *
 * @param  array  $file  Element from $_FILES
 * @return string        Relative path to uploaded file
 * @throws RuntimeException on invalid file
 */
function upload_product_image(array $file): string
{
    $maxBytes   = 5 * 1024 * 1024; // 5 MB
    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload failed (error code ' . $file['error'] . ').');
    }

    if ($file['size'] > $maxBytes) {
        throw new RuntimeException('Image too large (max 5 MB).');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('Invalid file type. Allowed: jpg, png, webp.');
    }

    // Validate MIME with finfo
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!in_array($mime, $allowedMime, true)) {
        throw new RuntimeException('Invalid image content type.');
    }

    $uploadDir = __DIR__ . '/../assets/uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $destPath = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Could not save uploaded file.');
    }

    return 'assets/uploads/' . $filename;
}

/**
 * Delete a product image file if it exists.
 */
function delete_product_image(string $relativePath): void
{
    if (empty($relativePath)) {
        return;
    }
    $full = __DIR__ . '/../' . ltrim($relativePath, '/');
    if (is_file($full)) {
        unlink($full);
    }
}

/**
 * Bootstrap: load config and all includes.
 * Call this at the top of every entry point.
 */
function bootstrap(): void
{
    $config = __DIR__ . '/../config/config.php';
    if (!file_exists($config)) {
        // Fallback to example so site doesn't crash without config (dev only)
        $config = __DIR__ . '/../config/config.example.php';
    }
    require_once $config;
    require_once __DIR__ . '/db.php';
    require_once __DIR__ . '/auth.php';
    require_once __DIR__ . '/csrf.php';
}
