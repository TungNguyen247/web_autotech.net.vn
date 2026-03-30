<?php
/**
 * admin/categories/delete.php — Delete a category (POST only).
 */

require_once __DIR__ . '/../../includes/helpers.php';
bootstrap();
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

csrf_verify();

$id = (int)($_POST['id'] ?? 0);
if (!$id) {
    flash('error', 'ID không hợp lệ.');
    redirect('index.php');
}

try {
    $stmt = db()->prepare('DELETE FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    flash('success', 'Đã xóa danh mục.');
} catch (Exception $e) {
    flash('error', 'Không thể xóa danh mục này.');
}

redirect('index.php');
