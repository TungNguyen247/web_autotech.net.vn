<?php
/**
 * contact_submit.php — Handle public contact form submission.
 * Only accepts POST. Stores message in `contacts` table, then redirects.
 */

require_once __DIR__ . '/includes/helpers.php';
bootstrap();
session_start_secure();

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php#contact');
}

csrf_verify();

// Sanitize inputs
$name    = trim(strip_tags($_POST['name']    ?? ''));
$email   = trim(strip_tags($_POST['email']   ?? ''));
$phone   = trim(strip_tags($_POST['phone']   ?? ''));
$subject = trim(strip_tags($_POST['subject'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

// Validate
$errors = [];

if (mb_strlen($name) < 2) {
    $errors[] = 'Vui lòng nhập họ tên hợp lệ (ít nhất 2 ký tự).';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Địa chỉ email không hợp lệ.';
}

if (!preg_match('/^[0-9+\s\-().]{7,20}$/', $phone)) {
    $errors[] = 'Số điện thoại không hợp lệ.';
}

if (mb_strlen($message) < 10) {
    $errors[] = 'Nội dung phải có ít nhất 10 ký tự.';
}

if ($errors) {
    flash('error', implode(' | ', $errors));
    redirect('index.php#contact');
}

try {
    $pdo  = db();
    $stmt = $pdo->prepare(
        'INSERT INTO contacts (name, email, phone, subject, message)
         VALUES (:name, :email, :phone, :subject, :message)'
    );
    $stmt->execute([
        ':name'    => $name,
        ':email'   => $email,
        ':phone'   => $phone,
        ':subject' => $subject,
        ':message' => $message,
    ]);

    flash('success', 'Cảm ơn! Chúng tôi sẽ liên hệ lại sớm nhất.');
} catch (Exception $e) {
    flash('error', 'Đã xảy ra lỗi. Vui lòng thử lại sau.');
}

redirect('index.php#contact');
