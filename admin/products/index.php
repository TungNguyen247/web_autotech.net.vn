<?php
/**
 * admin/products/index.php — List products.
 */

require_once __DIR__ . '/../../includes/helpers.php';
bootstrap();
require_login();

$pageTitle  = 'Sản phẩm';
$activePage = 'products';

$products = [];
try {
    $products = db()->query(
        'SELECT p.*, c.name_vi AS cat_name
         FROM products p
         LEFT JOIN categories c ON c.id = p.category_id
         ORDER BY p.id DESC'
    )->fetchAll();
} catch (Exception $e) {}

require __DIR__ . '/../includes/layout.php';
?>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
  <h1 class="page-title" style="margin:0;">Sản phẩm</h1>
  <a href="create.php" class="btn-admin btn-admin--primary">
    <i class="fas fa-plus"></i> Thêm sản phẩm
  </a>
</div>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Ảnh</th>
        <th>Tên sản phẩm (VI)</th>
        <th>Danh mục</th>
        <th>Giá</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($products)): ?>
      <tr><td colspan="7" style="text-align:center;color:#999;">Chưa có sản phẩm nào.</td></tr>
      <?php else: ?>
      <?php foreach ($products as $p): ?>
      <tr>
        <td><?= h((string)$p['id']) ?></td>
        <td>
          <?php if ($p['image_path']): ?>
          <img src="../../<?= h($p['image_path']) ?>" alt="" class="img-preview">
          <?php else: ?>
          <span style="color:#ccc;font-size:1.5rem;"><i class="fas fa-image"></i></span>
          <?php endif; ?>
        </td>
        <td><?= h($p['name_vi']) ?></td>
        <td><?= h($p['cat_name'] ?? '—') ?></td>
        <td><?= $p['price'] !== null ? number_format((float)$p['price'], 0, ',', '.') . ' ₫' : '—' ?></td>
        <td>
          <?php if ($p['is_active']): ?>
          <span class="badge badge--active">Hiện</span>
          <?php else: ?>
          <span class="badge badge--inactive">Ẩn</span>
          <?php endif; ?>
        </td>
        <td style="white-space:nowrap;">
          <a href="edit.php?id=<?= (int)$p['id'] ?>" class="btn-admin btn-admin--outline" style="padding:.3rem .65rem;">
            <i class="fas fa-edit"></i>
          </a>
          <form method="POST" action="delete.php" style="display:inline;"
                onsubmit="return confirm('Xóa sản phẩm này?');">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
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
