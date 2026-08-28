<?php
require_once __DIR__ . '/../includes/config.php';
require_admin();

if (isset($_GET['approve']) && ctype_digit((string)$_GET['approve'])) {
    $st = db()->prepare('UPDATE reviews SET approved = 1 WHERE id = ?');
    $st->execute([(int)$_GET['approve']]);
    flash_set('success','Review approved.');
    header('Location: reviews.php'); exit;
}
if (isset($_GET['delete']) && ctype_digit((string)$_GET['delete'])) {
    $st = db()->prepare('DELETE FROM reviews WHERE id = ?');
    $st->execute([(int)$_GET['delete']]);
    flash_set('success','Review deleted.');
    header('Location: reviews.php'); exit;
}

$rows = db()->query('SELECT * FROM reviews ORDER BY created_at DESC')->fetchAll();
$__title = 'Reviews · Admin';
require __DIR__ . '/_layout.php';
$ok = flash_get('success');
?>
<h1 class="font-serif-luxe display-5 mb-4">Reviews</h1>
<?php if ($ok): ?><div class="alert alert-success alert-luxe"><?= e($ok) ?></div><?php endif; ?>

<div class="row g-3">
  <?php foreach ($rows as $r): ?>
    <div class="col-md-6">
      <div class="bg-white p-4 border rounded-2 h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div class="fw-semibold"><?= e($r['name']) ?></div>
          <div>
            <?php for ($k=1;$k<=5;$k++): ?>
              <i class="fa-solid fa-star <?= $k <= (int)$r['rating'] ? 'star-filled' : 'star-empty' ?>"></i>
            <?php endfor; ?>
          </div>
        </div>
        <p class="text-muted mt-3"><?= e($r['comment']) ?></p>
        <div class="d-flex align-items-center gap-3 mt-3">
          <span class="status-pill <?= $r['approved']?'status-confirmed':'status-pending' ?>"><?= $r['approved']?'Approved':'Pending' ?></span>
          <?php if (!$r['approved']): ?><a class="text-primary small" href="?approve=<?= (int)$r['id'] ?>"><i class="fa-solid fa-check"></i> Approve</a><?php endif; ?>
          <a class="text-danger small ms-auto" href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete review?');"><i class="fa-solid fa-trash"></i> Delete</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <?php if (!$rows): ?><div class="col-12 text-center text-muted py-4">No reviews yet.</div><?php endif; ?>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
