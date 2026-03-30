<?php
/**
 * admin/products/delete.php — Delete a product (POST only).
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
    $pdo  = db();
    $stmt = $pdo->prepare('SELECT image_path FROM products WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row  = $stmt->fetch();

    if ($row) {
        delete_product_image($row['image_path'] ?? '');
        $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);
        flash('success', 'Đã xóa sản phẩm.');
    } else {
        flash('error', 'Sản phẩm không tồn tại.');
    }
} catch (Exception $e) {
    flash('error', 'Không thể xóa sản phẩm này.');
}

redirect('index.php');
