<?php
require_once __DIR__ . '/includes/config.php';
$bid = (int)($_GET['id'] ?? 0);
$st = db()->prepare('SELECT b.*, r.name AS room_name, r.category, r.images FROM bookings b JOIN rooms r ON r.id = b.room_id WHERE b.id = ?');
$st->execute([$bid]);
$b = $st->fetch();
if (!$b) { header('Location: index.php'); exit; }

$img = trim(explode("\n", (string)$b['images'])[0] ?? '');
$__title = 'Booking Confirmation · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe">
    <div class="row g-5">
      <div class="col-lg-7">
        <div class="section-eyebrow">Confirmation</div>
        <h1 class="font-serif-luxe display-3 mt-2 mb-3">Thank you, <?= e(explode(' ', $b['full_name'])[0]) ?>.</h1>
        <p class="text-muted">Your reservation has been received. Our concierge will confirm shortly.</p>

        <div class="glass p-4 p-md-5 mt-4">
          <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
              <div class="label-eyebrow">Booking Reference</div>
              <div class="font-serif-luxe fs-3">BD-<?= str_pad((string)$b['id'], 6, '0', STR_PAD_LEFT) ?></div>
            </div>
            <span class="status-pill status-<?= e($b['status']) ?>"><?= e(strtoupper($b['status'])) ?></span>
          </div>

          <div class="row g-4">
            <div class="col-md-6"><div class="label-eyebrow mb-1">Guest</div><div><?= e($b['full_name']) ?></div></div>
            <div class="col-md-6"><div class="label-eyebrow mb-1">Contact</div><div><?= e($b['email']) ?> · <?= e($b['mobile']) ?></div></div>
            <div class="col-md-6"><div class="label-eyebrow mb-1">Room</div><div><?= e($b['room_name']) ?> · <?= e($b['category']) ?></div></div>
            <div class="col-md-6"><div class="label-eyebrow mb-1">Guests</div><div><?= (int)$b['adults'] ?> adults · <?= (int)$b['children'] ?> children</div></div>
            <div class="col-md-6"><div class="label-eyebrow mb-1">Check-in</div><div><?= e(date('D, d M Y', strtotime($b['check_in']))) ?></div></div>
            <div class="col-md-6"><div class="label-eyebrow mb-1">Check-out</div><div><?= e(date('D, d M Y', strtotime($b['check_out']))) ?></div></div>
            <?php if ($b['special_request']): ?><div class="col-12"><div class="label-eyebrow mb-1">Special Request</div><div class="text-muted"><?= e($b['special_request']) ?></div></div><?php endif; ?>
          </div>

          <div class="hairline my-4"></div>
          <div class="d-flex align-items-end justify-content-between">
            <div>
              <div class="label-eyebrow">Total</div>
              <div class="font-serif-luxe display-6"><?= money((float)$b['total_price']) ?></div>
              <div class="small text-muted"><?= nights_between($b['check_in'], $b['check_out']) ?> night(s) · <?= e($b['payment_method']) ?></div>
            </div>
            <a href="javascript:window.print()" class="btn bd-btn-outline"><i class="fa-solid fa-print me-2"></i>Print Invoice</a>
          </div>
        </div>

        <div class="mt-4 d-flex gap-3">
          <a href="index.php" class="btn bd-btn-primary">Back to Home</a>
          <?php if (current_user()): ?><a href="profile.php" class="btn bd-btn-outline">My Bookings</a><?php endif; ?>
        </div>
      </div>
      <div class="col-lg-5">
        <img src="<?= e($img) ?>" class="w-100" style="height:520px;object-fit:cover;">
      </div>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
