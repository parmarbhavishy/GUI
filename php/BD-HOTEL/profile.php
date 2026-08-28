<?php
require_once __DIR__ . '/includes/config.php';
$user = require_login();

// Cancel booking
if (isset($_GET['cancel']) && ctype_digit((string)$_GET['cancel'])) {
    $bid = (int)$_GET['cancel'];
    $st = db()->prepare('UPDATE bookings SET status = "cancelled" WHERE id = ? AND user_id = ?');
    $st->execute([$bid, $user['id']]);
    flash_set('success', 'Booking cancelled.');
    header('Location: profile.php'); exit;
}

// Change password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    if (!csrf_verify()) { flash_set('error', 'Session expired.'); header('Location: profile.php'); exit; }
    $cur = (string)($_POST['current_password'] ?? '');
    $new = (string)($_POST['new_password'] ?? '');
    $st = db()->prepare('SELECT password_hash FROM users WHERE id = ?');
    $st->execute([$user['id']]);
    $row = $st->fetch();
    if (!$row || !password_verify($cur, $row['password_hash'])) { flash_set('error', 'Current password is incorrect.'); header('Location: profile.php'); exit; }
    if (strlen($new) < 6) { flash_set('error', 'New password must be at least 6 characters.'); header('Location: profile.php'); exit; }
    $upd = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $upd->execute([password_hash($new, PASSWORD_BCRYPT), $user['id']]);
    flash_set('success', 'Password updated.');
    header('Location: profile.php'); exit;
}

// Submit review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!csrf_verify()) { flash_set('error', 'Session expired.'); header('Location: profile.php'); exit; }
    $rating = max(1, min(5, (int)($_POST['rating'] ?? 5)));
    $comment = trim((string)($_POST['comment'] ?? ''));
    if ($comment === '') { flash_set('error', 'Review comment cannot be empty.'); header('Location: profile.php'); exit; }
    $st = db()->prepare('INSERT INTO reviews (user_id,name,rating,comment,approved) VALUES (?,?,?,?,0)');
    $st->execute([$user['id'], $user['name'], $rating, $comment]);
    flash_set('success', 'Review submitted for approval.');
    header('Location: profile.php'); exit;
}

$st = db()->prepare('SELECT b.*, r.name AS room_name FROM bookings b JOIN rooms r ON r.id = b.room_id WHERE b.user_id = ? ORDER BY b.created_at DESC');
$st->execute([$user['id']]);
$bookings = $st->fetchAll();

$__title = 'My Profile · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$ok = flash_get('success'); $err = flash_get('error');
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe">
    <?php if ($ok):  ?><div class="alert alert-success alert-luxe"><?= e($ok)  ?></div><?php endif; ?>
    <?php if ($err): ?><div class="alert alert-danger  alert-luxe"><?= e($err) ?></div><?php endif; ?>

    <div class="section-eyebrow">Guest Account</div>
    <h1 class="font-serif-luxe display-3 mt-2">Good evening, <?= e(explode(' ', $user['name'])[0]) ?>.</h1>
    <p class="text-muted">Your reservations, in one quiet place.</p>

    <div class="row g-5 mt-4">
      <div class="col-lg-8">
        <div class="label-eyebrow mb-3">Your Bookings</div>
        <?php if (!$bookings): ?><div class="border border-dashed p-5 text-center text-muted">No bookings yet.</div><?php endif; ?>
        <?php foreach ($bookings as $b): ?>
          <div class="bg-white p-4 mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
              <div class="font-serif-luxe fs-3"><?= e($b['room_name']) ?></div>
              <div class="text-muted small mt-1"><?= e($b['check_in']) ?> → <?= e($b['check_out']) ?> · <?= (int)$b['adults'] ?>A / <?= (int)$b['children'] ?>C</div>
              <div class="small mt-1">Ref: <span class="text-muted">BD-<?= str_pad((string)$b['id'], 6, '0', STR_PAD_LEFT) ?></span></div>
            </div>
            <div class="text-md-end">
              <span class="status-pill status-<?= e($b['status']) ?>"><?= e(strtoupper($b['status'])) ?></span>
              <div class="font-serif-luxe fs-4 mt-2"><?= money((float)$b['total_price']) ?></div>
              <div class="d-flex gap-2 justify-content-md-end mt-2">
                <a href="invoice.php?id=<?= (int)$b['id'] ?>" class="small gold-underline">Invoice</a>
                <?php if ($b['status'] !== 'cancelled'): ?>
                  <a href="profile.php?cancel=<?= (int)$b['id'] ?>" class="small text-danger" onclick="return confirm('Cancel this booking?');">Cancel</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="col-lg-4">
        <div class="mb-5">
          <div class="label-eyebrow mb-3">Change Password</div>
          <form method="post" data-validate>
            <?= csrf_field() ?>
            <input type="hidden" name="change_password" value="1">
            <input required type="password" name="current_password" placeholder="Current password" class="bd-input mb-3">
            <input required type="password" name="new_password"     placeholder="New password"     class="bd-input mb-3">
            <button class="btn bd-btn-outline w-100">Update</button>
          </form>
        </div>
        <div>
          <div class="label-eyebrow mb-3">Leave a Review</div>
          <form method="post" data-validate>
            <?= csrf_field() ?>
            <input type="hidden" name="submit_review" value="1">
            <select name="rating" class="bd-select mb-3">
              <?php for ($k=5;$k>=1;$k--) echo "<option value=\"$k\">$k star".($k>1?'s':'').'</option>'; ?>
            </select>
            <textarea required name="comment" rows="4" placeholder="Share your experience..." class="bd-input mb-3" style="resize:none;"></textarea>
            <button class="btn bd-btn-outline w-100">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
