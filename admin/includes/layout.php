<?php
/**
 * admin/includes/layout.php
 * Shared HTML head + sidebar + topbar for all admin pages.
 *
 * Usage:
 *   $pageTitle = 'Dashboard';
 *   $activePage = 'dashboard';
 *   require __DIR__ . '/../includes/layout.php';
 *   // ... page content ...
 *   require __DIR__ . '/../includes/layout_end.php';
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title><?= h($pageTitle ?? 'Admin') ?> — Autotech Admin</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/admin.css">
</head>
<body>

<!-- Topbar -->
<header class="admin-topbar">
  <span class="admin-topbar__logo">⚙️ Autotech Admin</span>
  <span class="admin-topbar__user"><i class="fas fa-user-circle"></i> <?= h(current_admin()) ?></span>
  <a href="../logout.php" class="admin-topbar__logout">
    <i class="fas fa-sign-out-alt"></i> Đăng xuất
  </a>
</header>

<!-- Sidebar -->
<?php $active = $activePage ?? ''; ?>
<nav class="admin-sidebar" aria-label="Admin navigation">
  <div class="admin-sidebar__nav">
    <span class="admin-sidebar__label">Tổng quan</span>
    <a href="../index.php"
       class="admin-sidebar__link <?= $active === 'dashboard' ? 'active' : '' ?>">
      <i class="fas fa-tachometer-alt"></i> Dashboard
    </a>

    <span class="admin-sidebar__label">Quản lý</span>
    <a href="../categories/index.php"
       class="admin-sidebar__link <?= $active === 'categories' ? 'active' : '' ?>">
      <i class="fas fa-tags"></i> Danh mục
    </a>
    <a href="../products/index.php"
       class="admin-sidebar__link <?= $active === 'products' ? 'active' : '' ?>">
      <i class="fas fa-box-open"></i> Sản phẩm
    </a>
    <a href="../contacts/index.php"
       class="admin-sidebar__link <?= $active === 'contacts' ? 'active' : '' ?>">
      <i class="fas fa-envelope"></i> Liên hệ
    </a>

    <span class="admin-sidebar__label">Website</span>
    <a href="../../index.php" class="admin-sidebar__link" target="_blank">
      <i class="fas fa-external-link-alt"></i> Xem trang chủ
    </a>
  </div>
</nav>

<!-- Main content start -->
<main class="admin-content">
<?= render_flashes() ?>
