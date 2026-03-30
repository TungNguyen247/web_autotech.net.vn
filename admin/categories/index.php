<?php
/**
 * admin/categories/index.php — List categories.
 */

require_once __DIR__ . '/../../includes/helpers.php';
bootstrap();
require_login();

$pageTitle  = 'Danh mục';
$activePage = 'categories';

$cats = [];
try {
    $cats = db()->query('SELECT * FROM categories ORDER BY id')->fetchAll();
} catch (Exception $e) {}

require __DIR__ . '/../includes/layout.php';
?>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
  <h1 class="page-title" style="margin:0;">Danh mục sản phẩm</h1>
  <a href="create.php" class="btn-admin btn-admin--primary">
    <i class="fas fa-plus"></i> Thêm danh mục
  </a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Tên (VI)</th>
        <th>Tên (EN)</th>
        <th>Slug</th>
        <th>Ngày tạo</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($cats)): ?>
      <tr><td colspan="6" style="text-align:center;color:#999;">Chưa có danh mục nào.</td></tr>
      <?php else: ?>
      <?php foreach ($cats as $cat): ?>
      <tr>
        <td><?= h((string)$cat['id']) ?></td>
        <td><?= h($cat['name_vi']) ?></td>
        <td><?= h($cat['name_en']) ?></td>
        <td><code><?= h($cat['slug']) ?></code></td>
        <td><?= h($cat['created_at']) ?></td>
        <td style="white-space:nowrap;">
          <a href="edit.php?id=<?= (int)$cat['id'] ?>" class="btn-admin btn-admin--outline" style="padding:.3rem .65rem;">
            <i class="fas fa-edit"></i>
          </a>
          <form method="POST" action="delete.php" style="display:inline;"
                onsubmit="return confirm('Xóa danh mục này? Sản phẩm thuộc danh mục sẽ không bị xóa.');">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$cat['id'] ?>">
            <button type="submit" class="btn-admin btn-admin--danger" style="padding:.3rem .65rem;">
              <i class="fas fa-trash"></i>
            </button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
