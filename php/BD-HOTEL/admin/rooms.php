<?php
require_once __DIR__ . '/../includes/config.php';
require_admin();

// Handle delete
if (isset($_GET['delete']) && ctype_digit((string)$_GET['delete'])) {
    $st = db()->prepare('DELETE FROM rooms WHERE id = ?');
    $st->execute([(int)$_GET['delete']]);
    flash_set('success', 'Room deleted.');
    header('Location: rooms.php'); exit;
}

// Create / update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id       = (int)($_POST['id'] ?? 0);
    $name     = trim((string)($_POST['name'] ?? ''));
    $cat      = trim((string)($_POST['category'] ?? ''));
    $price    = (float)($_POST['price'] ?? 0);
    $capacity = (int)($_POST['capacity'] ?? 1);
    $desc     = trim((string)($_POST['description'] ?? ''));
    $amen     = trim((string)($_POST['amenities'] ?? ''));
    $imgs     = trim((string)($_POST['images'] ?? ''));
    $avail    = isset($_POST['available']) ? 1 : 0;

    if ($id > 0) {
        $st = db()->prepare('UPDATE rooms SET name=?,category=?,price=?,capacity=?,description=?,amenities=?,images=?,available=? WHERE id=?');
        $st->execute([$name,$cat,$price,$capacity,$desc,$amen,$imgs,$avail,$id]);
        flash_set('success','Room updated.');
    } else {
        $st = db()->prepare('INSERT INTO rooms (name,category,price,capacity,description,amenities,images,available) VALUES (?,?,?,?,?,?,?,?)');
        $st->execute([$name,$cat,$price,$capacity,$desc,$amen,$imgs,$avail]);
        flash_set('success','Room created.');
    }
    header('Location: rooms.php'); exit;
}

// Load edit target
$edit = null;
if (isset($_GET['edit']) && ctype_digit((string)$_GET['edit'])) {
    $st = db()->prepare('SELECT * FROM rooms WHERE id = ?');
    $st->execute([(int)$_GET['edit']]);
    $edit = $st->fetch();
}
$adding = isset($_GET['add']);
$rows   = db()->query('SELECT * FROM rooms ORDER BY id DESC')->fetchAll();

$__title = 'Rooms · Admin';
require __DIR__ . '/_layout.php';
$ok = flash_get('success');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <h1 class="font-serif-luxe display-5">Rooms</h1>
  <a class="btn bd-btn-primary ripple" href="?add=1"><i class="fa-solid fa-plus me-2"></i>Add Room</a>
</div>
<?php if ($ok): ?><div class="alert alert-success alert-luxe"><?= e($ok) ?></div><?php endif; ?>

<?php if ($adding || $edit): ?>
<div class="bg-white p-4 mb-5 border rounded-2">
  <h3 class="font-serif-luxe fs-3 mb-4"><?= $edit ? 'Edit Room' : 'Add Room' ?></h3>
  <form method="post" class="row g-3" data-validate>
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
    <div class="col-md-6"><div class="label-eyebrow mb-2">Name</div><input required class="bd-input" name="name" value="<?= e($edit['name'] ?? '') ?>"></div>
    <div class="col-md-6"><div class="label-eyebrow mb-2">Category</div>
      <select class="bd-select" name="category">
        <?php foreach (['Deluxe Room','Super Deluxe','Executive Room','Family Room','Luxury Suite','Presidential Suite'] as $c): ?>
          <option <?= ($edit['category'] ?? '')===$c?'selected':'' ?>><?= e($c) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3"><div class="label-eyebrow mb-2">Price</div><input required type="number" step="0.01" class="bd-input" name="price" value="<?= e($edit['price'] ?? '') ?>"></div>
    <div class="col-md-3"><div class="label-eyebrow mb-2">Capacity</div><input required type="number" class="bd-input" name="capacity" value="<?= e($edit['capacity'] ?? '2') ?>"></div>
    <div class="col-md-6 d-flex align-items-end pb-2"><label class="d-flex align-items-center gap-2"><input type="checkbox" name="available" <?= (!$edit || (int)$edit['available']===1)?'checked':'' ?>> Available</label></div>
    <div class="col-12"><div class="label-eyebrow mb-2">Description</div><textarea required rows="3" class="bd-input" name="description" style="resize:none;"><?= e($edit['description'] ?? '') ?></textarea></div>
    <div class="col-12"><div class="label-eyebrow mb-2">Amenities (comma-separated)</div><input class="bd-input" name="amenities" value="<?= e($edit['amenities'] ?? '') ?>"></div>
    <div class="col-12"><div class="label-eyebrow mb-2">Image URLs (one per line)</div><textarea rows="3" class="bd-input" name="images" style="resize:none;"><?= e($edit['images'] ?? '') ?></textarea></div>
    <div class="col-12"><button class="btn bd-btn-primary">Save</button> <a class="btn bd-btn-outline" href="rooms.php">Cancel</a></div>
  </form>
</div>
<?php endif; ?>

<div class="bg-white border rounded-2 overflow-hidden">
  <table class="table table-borderless mb-0 align-middle small">
    <thead class="bg-light"><tr><th class="p-3">Name</th><th class="p-3">Category</th><th class="p-3">Price</th><th class="p-3">Capacity</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
        <tr class="border-top">
          <td class="p-3 fw-semibold"><?= e($r['name']) ?></td>
          <td class="p-3 text-muted"><?= e($r['category']) ?></td>
          <td class="p-3"><?= money((float)$r['price']) ?></td>
          <td class="p-3"><?= (int)$r['capacity'] ?></td>
          <td class="p-3">
            <a class="text-primary me-3" href="?edit=<?= (int)$r['id'] ?>"><i class="fa-solid fa-pen"></i></a>
            <a class="text-danger" href="?delete=<?= (int)$r['id'] ?>" onclick="return confirm('Delete this room?');"><i class="fa-solid fa-trash"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
