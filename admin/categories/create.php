<?php
/**
 * admin/categories/create.php — Create a new category.
 */

require_once __DIR__ . '/../../includes/helpers.php';
bootstrap();
require_login();

$pageTitle  = 'Thêm danh mục';
$activePage = 'categories';

$errors = [];
$input  = ['name_vi' => '', 'name_en' => '', 'slug' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $input['name_vi'] = trim($_POST['name_vi'] ?? '');
    $input['name_en'] = trim($_POST['name_en'] ?? '');
    $input['slug']    = trim($_POST['slug']    ?? '');

    if ($input['name_vi'] === '') $errors[] = 'Tên danh mục (VI) không được để trống.';
    if ($input['name_en'] === '') $errors[] = 'Tên danh mục (EN) không được để trống.';

    // Auto-generate slug if empty
    if ($input['slug'] === '') {
        $input['slug'] = slugify($input['name_vi']);
    } else {
        $input['slug'] = slugify($input['slug']);
    }

    if ($input['slug'] === '') $errors[] = 'Slug không hợp lệ.';

    if (empty($errors)) {
        try {
            $stmt = db()->prepare(
                'INSERT INTO categories (name_vi, name_en, slug) VALUES (?, ?, ?)'
            );
            $stmt->execute([$input['name_vi'], $input['name_en'], $input['slug']]);
            flash('success', 'Đã thêm danh mục "' . $input['name_vi'] . '".');
            redirect('index.php');
        } catch (Exception $e) {
            if ($e->getCode() == 23000) {
                $errors[] = 'Slug đã tồn tại. Hãy chọn slug khác.';
            } else {
                $errors[] = 'Lỗi khi lưu dữ liệu.';
            }
        }
    }
}

require __DIR__ . '/../includes/layout.php';
?>

<h1 class="page-title">Thêm danh mục</h1>

<?php foreach ($errors as $e): ?>
<div class="alert alert--error"><?= h($e) ?></div>
<?php endforeach; ?>

<div class="form-card">
  <form method="POST" action="create.php">
    <?= csrf_field() ?>

    <div class="form-row">
      <div class="form-group">
        <label for="name_vi">Tên danh mục (VI) *</label>
        <input type="text" id="name_vi" name="name_vi" value="<?= h($input['name_vi']) ?>" required>
      </div>
      <div class="form-group">
        <label for="name_en">Tên danh mục (EN) *</label>
        <input type="text" id="name_en" name="name_en" value="<?= h($input['name_en']) ?>" required>
      </div>
    </div>
    <div class="form-group">
      <label for="slug">Slug (tự động từ tên VI nếu để trống)</label>
      <input type="text" id="slug" name="slug" value="<?= h($input['slug']) ?>"
             placeholder="vi-du-slug">
      <p class="form-hint">Chỉ dùng chữ thường, số và dấu gạch ngang.</p>
    </div>

    <div style="display:flex; gap:.75rem;">
      <button type="submit" class="btn-admin btn-admin--primary">
        <i class="fas fa-save"></i> Lưu danh mục
      </button>
      <a href="index.php" class="btn-admin btn-admin--outline">Hủy</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
