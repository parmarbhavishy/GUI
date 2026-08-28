<?php
require_once __DIR__ . '/../includes/config.php';
require_admin();

if (isset($_GET['delete']) && ctype_digit((string)$_GET['delete'])) {
    $st = db()->prepare('DELETE FROM gallery WHERE id = ?');
    $st->execute([(int)$_GET['delete']]);
    flash_set('success','Gallery image removed.');
    header('Location: gallery.php'); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $urls = array_filter(array_map('trim', explode("\n", (string)($_POST['urls'] ?? ''))));
    $cat  = trim((string)($_POST['category'] ?? 'General'));
    $count = 0;
    $ins = db()->prepare('INSERT INTO gallery (url, category) VALUES (?, ?)');
    foreach ($urls as $u) { $ins->execute([$u, $cat]); $count++; }
    flash_set('success', $count . ' image(s) added.');
    header('Location: gallery.php'); exit;
}

$rows = db()->query('SELECT * FROM gallery ORDER BY created_at DESC')->fetchAll();
$__title = 'Gallery · Admin';
require __DIR__ . '/_layout.php';
$ok = flash_get('success');
?>
<h1 class="font-serif-luxe display-5 mb-4">Gallery</h1>
<?php if ($ok): ?><div class="alert alert-success alert-luxe"><?= e($ok) ?></div><?php endif; ?>

<div class="bg-white p-4 border rounded-2 mb-5">
  <div class="font-serif-luxe fs-4 mb-3">Add Images</div>
  <form method="post" class="row g-3">
    <?= csrf_field() ?>
    <div class="col-md-3"><div class="label-eyebrow mb-2">Category</div><input class="bd-input" name="category" value="General"></div>
    <div class="col-md-9"><div class="label-eyebrow mb-2">Image URLs (one per line)</div><textarea rows="4" class="bd-input" name="urls" style="resize:none;" required></textarea></div>
    <div class="col-12"><button class="btn bd-btn-primary">Add</button></div>
  </form>
</div>

<div class="row g-3">
  <?php foreach ($rows as $g): ?>
    <div class="col-6 col-md-3">
      <div class="position-relative bg-white border rounded-2 overflow-hidden">
        <img src="<?= e($g['url']) ?>" style="width:100%;height:180px;object-fit:cover;">
        <div class="p-2 d-flex justify-content-between align-items-center">
          <span class="small text-muted"><?= e($g['category']) ?></span>
          <a class="text-danger small" href="?delete=<?= (int)$g['id'] ?>" onclick="return confirm('Delete?');"><i class="fa-solid fa-trash"></i></a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
