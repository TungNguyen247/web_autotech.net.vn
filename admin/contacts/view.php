<?php
/**
 * admin/contacts/view.php — View a single contact message.
 */

require_once __DIR__ . '/../../includes/helpers.php';
bootstrap();
require_login();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect('index.php');

try {
    $pdo  = db();
    $stmt = $pdo->prepare('SELECT * FROM contacts WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $contact = $stmt->fetch();
} catch (Exception $e) {
    $contact = null;
}

if (!$contact) {
    flash('error', 'Tin nhắn không tồn tại.');
    redirect('index.php');
}

// Mark as read
if (!$contact['is_read']) {
    try {
        $pdo->prepare('UPDATE contacts SET is_read = 1 WHERE id = ?')->execute([$id]);
        $contact['is_read'] = 1;
    } catch (Exception $e) {}
}

$pageTitle  = 'Xem tin nhắn';
$activePage = 'contacts';

require __DIR__ . '/../includes/layout.php';
?>

<div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
  <h1 class="page-title" style="margin:0;">Tin nhắn #<?= $id ?></h1>
  <a href="index.php" class="btn-admin btn-admin--outline"><i class="fas fa-arrow-left"></i> Quay lại</a>
</div>

<div class="form-card" style="max-width:700px;">
  <table style="box-shadow:none; border:none;">
    <tr>
      <th style="width:140px; background:transparent; border-bottom:1px solid #f0f0f0;">Họ tên</th>
      <td><?= h($contact['name']) ?></td>
    </tr>
    <tr>
      <th style="background:transparent; border-bottom:1px solid #f0f0f0;">Email</th>
      <td><a href="mailto:<?= h($contact['email']) ?>"><?= h($contact['email']) ?></a></td>
    </tr>
    <tr>
      <th style="background:transparent; border-bottom:1px solid #f0f0f0;">Điện thoại</th>
      <td><?= h($contact['phone']) ?></td>
    </tr>
    <tr>
      <th style="background:transparent; border-bottom:1px solid #f0f0f0;">Chủ đề</th>
      <td><?= h($contact['subject'] ?? '—') ?></td>
    </tr>
    <tr>
      <th style="background:transparent; border-bottom:1px solid #f0f0f0;">Ngày gửi</th>
      <td><?= h($contact['created_at']) ?></td>
    </tr>
    <tr>
      <th style="background:transparent; border-bottom:1px solid #f0f0f0;">Trạng thái</th>
      <td>
        <?php if ($contact['is_read']): ?>
        <span class="badge badge--read">Đã đọc</span>
        <?php else: ?>
        <span class="badge badge--unread">Chưa đọc</span>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <th style="background:transparent; vertical-align:top; padding-top:.8rem;">Nội dung</th>
      <td style="white-space:pre-wrap;"><?= h($contact['message']) ?></td>
    </tr>
  </table>

  <div style="margin-top:1.5rem;">
    <form method="POST" action="delete.php" onsubmit="return confirm('Xóa tin nhắn này?');">
      <?= csrf_field() ?>
      <input type="hidden" name="id" value="<?= $id ?>">
      <button type="submit" class="btn-admin btn-admin--danger">
        <i class="fas fa-trash"></i> Xóa tin nhắn
      </button>
    </form>
  </div>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
