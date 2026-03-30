<?php
/**
 * admin/index.php — Dashboard.
 */

require_once __DIR__ . '/../includes/helpers.php';
bootstrap();
require_login();

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';

// Fetch counts
$counts = ['categories' => 0, 'products' => 0, 'contacts' => 0, 'unread' => 0];
try {
    $pdo = db();
    $counts['categories'] = (int)$pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
    $counts['products']   = (int)$pdo->query('SELECT COUNT(*) FROM products WHERE is_active = 1')->fetchColumn();
    $counts['contacts']   = (int)$pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
    $counts['unread']     = (int)$pdo->query('SELECT COUNT(*) FROM contacts WHERE is_read = 0')->fetchColumn();
} catch (Exception $e) {
    // ignore on error
}

require __DIR__ . '/includes/layout.php';
?>

<h1 class="page-title">Dashboard</h1>

<div class="stats-grid">
  <div class="stat-card">
    <span class="stat-card__value"><?= $counts['categories'] ?></span>
    <span class="stat-card__label"><i class="fas fa-tags"></i> Danh mục</span>
  </div>
  <div class="stat-card">
    <span class="stat-card__value"><?= $counts['products'] ?></span>
    <span class="stat-card__label"><i class="fas fa-box-open"></i> Sản phẩm hoạt động</span>
  </div>
  <div class="stat-card">
    <span class="stat-card__value"><?= $counts['contacts'] ?></span>
    <span class="stat-card__label"><i class="fas fa-envelope"></i> Liên hệ</span>
  </div>
  <div class="stat-card">
    <span class="stat-card__value"><?= $counts['unread'] ?></span>
    <span class="stat-card__label"><i class="fas fa-envelope-open"></i> Chưa đọc</span>
  </div>
</div>

<div style="display:flex; gap:1rem; flex-wrap:wrap;">
  <a href="categories/index.php" class="btn-admin btn-admin--outline">
    <i class="fas fa-tags"></i> Quản lý danh mục
  </a>
  <a href="products/index.php" class="btn-admin btn-admin--outline">
    <i class="fas fa-box-open"></i> Quản lý sản phẩm
  </a>
  <a href="contacts/index.php" class="btn-admin btn-admin--outline">
    <i class="fas fa-envelope"></i> Xem liên hệ
  </a>
</div>

<?php require __DIR__ . '/includes/layout_end.php'; ?>
