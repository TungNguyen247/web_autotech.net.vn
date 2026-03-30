<?php
/**
 * admin/login.php — Admin login page.
 */

require_once __DIR__ . '/../includes/helpers.php';
bootstrap();
session_start_secure();

// Already logged in → go to dashboard
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    } else {
        try {
            $stmt = db()->prepare('SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1');
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                login_admin((int)$user['id'], $user['username']);
                flash('success', 'Đăng nhập thành công. Xin chào, ' . $user['username'] . '!');
                redirect('index.php');
            } else {
                // Generic error to prevent user enumeration
                $error = 'Tên đăng nhập hoặc mật khẩu không đúng.';
            }
        } catch (Exception $e) {
            $error = 'Đã xảy ra lỗi. Vui lòng thử lại.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Đăng nhập — Autotech Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="login-wrap">
  <div class="login-card">
    <div class="login-logo">⚙️ Autotech Admin</div>

    <?php if ($error): ?>
    <div class="alert alert--error"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <?= csrf_field() ?>

      <div class="form-group">
        <label for="username"><i class="fas fa-user"></i> Tên đăng nhập</label>
        <input type="text" id="username" name="username"
               value="<?= h($_POST['username'] ?? '') ?>"
               autocomplete="username" required autofocus>
      </div>
      <div class="form-group">
        <label for="password"><i class="fas fa-lock"></i> Mật khẩu</label>
        <input type="password" id="password" name="password" autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn-admin btn-admin--primary" style="width:100%; justify-content:center; padding:.7rem;">
        <i class="fas fa-sign-in-alt"></i> Đăng nhập
      </button>
    </form>
  </div>
</div>
</body>
</html>
