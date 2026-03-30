<?php
/**
 * admin/contacts/index.php — List contact messages.
 */

require_once __DIR__ . '/../../includes/helpers.php';
bootstrap();
require_login();

$pageTitle  = 'Liên hệ';
$activePage = 'contacts';

$contacts = [];
try {
    $contacts = db()->query(
        'SELECT * FROM contacts ORDER BY created_at DESC'
    )->fetchAll();
} catch (Exception $e) {}

require __DIR__ . '/../includes/layout.php';
?>

<h1 class="page-title">Tin nhắn liên hệ</h1>

<div class="table-wrap">
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>SĐT</th>
        <th>Chủ đề</th>
        <th>Ngày gửi</th>
        <th>Trạng thái</th>
        <th>Thao tác</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($contacts)): ?>
      <tr><td colspan="8" style="text-align:center;color:#999;">Chưa có tin nhắn nào.</td></tr>
      <?php else: ?>
      <?php foreach ($contacts as $c): ?>
      <tr>
        <td><?= h((string)$c['id']) ?></td>
        <td>
          <a href="view.php?id=<?= (int)$c['id'] ?>">
            <?= h($c['name']) ?>
          </a>
        </td>
        <td><?= h($c['email']) ?></td>
        <td><?= h($c['phone']) ?></td>
        <td><?= h($c['subject'] ?? '—') ?></td>
        <td><?= h($c['created_at']) ?></td>
        <td>
          <?php if ($c['is_read']): ?>
          <span class="badge badge--read">Đã đọc</span>
          <?php else: ?>
          <span class="badge badge--unread">Chưa đọc</span>
          <?php endif; ?>
        </td>
        <td style="white-space:nowrap;">
          <a href="view.php?id=<?= (int)$c['id'] ?>" class="btn-admin btn-admin--outline" style="padding:.3rem .65rem;">
            <i class="fas fa-eye"></i>
          </a>
          <form method="POST" action="delete.php" style="display:inline;"
                onsubmit="return confirm('Xóa tin nhắn này?');">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
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
