<?php
require_once __DIR__ . '/../includes/config.php';
require_admin();

if (isset($_GET['read']) && ctype_digit((string)$_GET['read'])) {
    $st = db()->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
    $st->execute([(int)$_GET['read']]);
    header('Location: messages.php'); exit;
}
if (isset($_GET['delete']) && ctype_digit((string)$_GET['delete'])) {
    $st = db()->prepare('DELETE FROM contact_messages WHERE id = ?');
    $st->execute([(int)$_GET['delete']]);
    header('Location: messages.php'); exit;
}

$rows = db()->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
$__title = 'Messages · Admin';
require __DIR__ . '/_layout.php';
?>
<h1 class="font-serif-luxe display-5 mb-4">Messages</h1>
<div class="row g-3">
  <?php foreach ($rows as $m): ?>
    <div class="col-12">
      <div class="bg-white p-4 border rounded-2">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="fw-semibold"><?= e($m['subject']) ?> <?php if (!$m['is_read']): ?><span class="badge bg-warning text-dark ms-2">New</span><?php endif; ?></div>
            <div class="small text-muted mt-1">From <?= e($m['name']) ?> · <?= e($m['email']) ?> · <?= e(date('d M Y H:i', strtotime($m['created_at']))) ?></div>
          </div>
          <div>
            <?php if (!$m['is_read']): ?><a class="btn btn-sm btn-outline-primary" href="?read=<?= (int)$m['id'] ?>">Mark read</a><?php endif; ?>
            <a class="btn btn-sm btn-outline-danger" href="?delete=<?= (int)$m['id'] ?>" onclick="return confirm('Delete?');">Delete</a>
          </div>
        </div>
        <p class="mt-3 mb-0"><?= nl2br(e($m['message'])) ?></p>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (!$rows): ?><div class="col-12 text-center text-muted py-4">No messages.</div><?php endif; ?>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
