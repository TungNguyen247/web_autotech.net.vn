<?php
/**
 * admin/products/edit.php — Edit an existing product.
 */

require_once __DIR__ . '/../../includes/helpers.php';
bootstrap();
require_login();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('index.php');

// Load product
try {
    $stmt = db()->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
} catch (Exception $e) {
    $product = null;
}

if (!$product) {
    flash('error', 'Sản phẩm không tồn tại.');
    redirect('index.php');
}

// Load categories
$categories = [];
try {
    $categories = db()->query('SELECT id, name_vi FROM categories ORDER BY name_vi')->fetchAll();
} catch (Exception $e) {}

$pageTitle  = 'Sửa sản phẩm';
$activePage = 'products';
$errors     = [];
$input      = $product;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();

    $input['category_id']    = (int)($_POST['category_id']    ?? 0) ?: null;
    $input['name_vi']        = trim($_POST['name_vi']         ?? '');
    $input['name_en']        = trim($_POST['name_en']         ?? '');
    $input['short_desc_vi']  = trim($_POST['short_desc_vi']   ?? '');
    $input['short_desc_en']  = trim($_POST['short_desc_en']   ?? '');
    $input['description_vi'] = trim($_POST['description_vi']  ?? '');
    $input['description_en'] = trim($_POST['description_en']  ?? '');
    $input['price']          = trim($_POST['price']           ?? '');
    $input['is_active']      = isset($_POST['is_active']) ? 1 : 0;

    if ($input['name_vi'] === '') $errors[] = 'Tên sản phẩm (VI) không được để trống.';
    if ($input['name_en'] === '') $errors[] = 'Tên sản phẩm (EN) không được để trống.';

    $price = null;
    if ($input['price'] !== '') {
        $price = filter_var($input['price'], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price < 0) {
            $errors[] = 'Giá không hợp lệ.';
        }
    }

    // Handle image upload
    $imagePath = $product['image_path']; // keep existing by default
    if (!empty($_FILES['image']['name'])) {
        try {
            $newPath = upload_product_image($_FILES['image']);
            // Delete old image
            delete_product_image($product['image_path'] ?? '');
            $imagePath = $newPath;
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }
    // Remove image if checkbox checked
    if (isset($_POST['remove_image']) && $product['image_path']) {
        delete_product_image($product['image_path']);
        $imagePath = null;
    }

    if (empty($errors)) {
        try {
            $stmt = db()->prepare(
                'UPDATE products SET
                   category_id=?, name_vi=?, name_en=?,
                   short_desc_vi=?, short_desc_en=?,
                   description_vi=?, description_en=?,
                   price=?, image_path=?, is_active=?
                 WHERE id=?'
            );
            $stmt->execute([
                $input['category_id'],
                $input['name_vi'],
                $input['name_en'],
                $input['short_desc_vi']  ?: null,
                $input['short_desc_en']  ?: null,
                $input['description_vi'] ?: null,
                $input['description_en'] ?: null,
                $price,
                $imagePath,
                $input['is_active'],
                $id,
            ]);
            flash('success', 'Đã cập nhật sản phẩm.');
            redirect('index.php');
        } catch (Exception $e) {
            $errors[] = 'Lỗi khi lưu dữ liệu.';
        }
    }
}

require __DIR__ . '/../includes/layout.php';
?>

<h1 class="page-title">Sửa sản phẩm #<?= $id ?></h1>

<?php foreach ($errors as $e): ?>
<div class="alert alert--error"><?= h($e) ?></div>
<?php endforeach; ?>

<div class="form-card" style="max-width:900px;">
  <form method="POST" action="edit.php?id=<?= $id ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="form-group">
      <label for="category_id">Danh mục</label>
      <select id="category_id" name="category_id">
        <option value="">-- Không chọn --</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= (int)$cat['id'] ?>"
          <?= $input['category_id'] == $cat['id'] ? 'selected' : '' ?>>
          <?= h($cat['name_vi']) ?>
        </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="name_vi">Tên sản phẩm (VI) *</label>
        <input type="text" id="name_vi" name="name_vi" value="<?= h($input['name_vi']) ?>" required>
      </div>
      <div class="form-group">
        <label for="name_en">Tên sản phẩm (EN) *</label>
        <input type="text" id="name_en" name="name_en" value="<?= h($input['name_en']) ?>" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="short_desc_vi">Mô tả ngắn (VI)</label>
        <textarea id="short_desc_vi" name="short_desc_vi" rows="3"><?= h($input['short_desc_vi'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label for="short_desc_en">Mô tả ngắn (EN)</label>
        <textarea id="short_desc_en" name="short_desc_en" rows="3"><?= h($input['short_desc_en'] ?? '') ?></textarea>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="description_vi">Mô tả chi tiết (VI)</label>
        <textarea id="description_vi" name="description_vi" rows="5"><?= h($input['description_vi'] ?? '') ?></textarea>
      </div>
      <div class="form-group">
        <label for="description_en">Mô tả chi tiết (EN)</label>
        <textarea id="description_en" name="description_en" rows="5"><?= h($input['description_en'] ?? '') ?></textarea>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="price">Giá (VNĐ, để trống nếu liên hệ)</label>
        <input type="number" id="price" name="price" min="0" step="1000"
               value="<?= h($input['price'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label>Ảnh hiện tại</label>
        <?php if (!empty($input['image_path'])): ?>
        <img src="../../<?= h($input['image_path']) ?>" alt="" class="img-preview" style="display:block;margin-bottom:.5rem;">
        <label style="display:flex; align-items:center; gap:.4rem; font-size:.85rem; font-weight:400;">
          <input type="checkbox" name="remove_image" value="1"> Xóa ảnh này
        </label>
        <?php else: ?>
        <p style="color:#999;font-size:.85rem;">Chưa có ảnh.</p>
        <?php endif; ?>

        <label for="image" style="margin-top:.5rem;">Tải ảnh mới (jpg, png, webp — tối đa 5MB)</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
      </div>
    </div>

    <div class="form-group">
      <label style="display:flex; align-items:center; gap:.5rem; cursor:pointer;">
        <input type="checkbox" name="is_active" value="1"
               <?= $input['is_active'] ? 'checked' : '' ?>>
        Hiển thị trên trang chủ
      </label>
    </div>

    <div style="display:flex; gap:.75rem;">
      <button type="submit" class="btn-admin btn-admin--primary">
        <i class="fas fa-save"></i> Cập nhật
      </button>
      <a href="index.php" class="btn-admin btn-admin--outline">Hủy</a>
    </div>
  </form>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
