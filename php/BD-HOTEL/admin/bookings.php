<?php
require_once __DIR__ . '/../includes/config.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int)($_POST['id'] ?? 0);
    $status = (string)($_POST['status'] ?? 'pending');
    if (in_array($status, ['pending','confirmed','cancelled'], true) && $id > 0) {
        $st = db()->prepare('UPDATE bookings SET status = ? WHERE id = ?');
        $st->execute([$status, $id]);
        flash_set('success','Status updated.');
    }
    header('Location: bookings.php'); exit;
}

$rows = db()->query('SELECT b.*, r.name AS room_name FROM bookings b JOIN rooms r ON r.id=b.room_id ORDER BY b.created_at DESC')->fetchAll();
$__title = 'Bookings · Admin';
require __DIR__ . '/_layout.php';
$ok = flash_get('success');
?>
<h1 class="font-serif-luxe display-5 mb-4">Bookings</h1>
<?php if ($ok): ?><div class="alert alert-success alert-luxe"><?= e($ok) ?></div><?php endif; ?>

<div class="bg-white border rounded-2 overflow-auto">
  <table class="table table-borderless mb-0 align-middle small" style="min-width:1000px;">
    <thead class="bg-light"><tr>
      <th class="p-3">Ref</th><th class="p-3">Guest</th><th class="p-3">Contact</th><th class="p-3">Room</th>
      <th class="p-3">Dates</th><th class="p-3">Guests</th><th class="p-3">Total</th><th class="p-3">Status</th>
    </tr></thead>
    <tbody>
      <?php foreach ($rows as $b): ?>
        <tr class="border-top">
          <td class="p-3">BD-<?= str_pad((string)$b['id'], 6, '0', STR_PAD_LEFT) ?></td>
          <td class="p-3"><?= e($b['full_name']) ?></td>
          <td class="p-3 text-muted"><?= e($b['email']) ?><br><?= e($b['mobile']) ?></td>
          <td class="p-3"><?= e($b['room_name']) ?></td>
          <td class="p-3 text-muted"><?= e($b['check_in']) ?><br>→ <?= e($b['check_out']) ?></td>
          <td class="p-3"><?= (int)$b['adults'] ?>A / <?= (int)$b['children'] ?>C</td>
          <td class="p-3"><?= money((float)$b['total_price']) ?></td>
          <td class="p-3">
            <form method="post" class="d-flex align-items-center gap-2">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
              <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <?php foreach (['pending','confirmed','cancelled'] as $s): ?>
                  <option <?= $b['status']===$s?'selected':'' ?>><?= $s ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="8" class="p-4 text-center text-muted">No bookings yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
