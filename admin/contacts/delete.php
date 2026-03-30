<?php
/**
 * admin/contacts/delete.php — Delete a contact message (POST only).
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
    db()->prepare('DELETE FROM contacts WHERE id = ?')->execute([$id]);
    flash('success', 'Đã xóa tin nhắn.');
} catch (Exception $e) {
    flash('error', 'Không thể xóa tin nhắn.');
}

redirect('index.php');
