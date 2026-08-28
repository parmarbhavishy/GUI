<?php
// Shared admin layout partial. Include AFTER require_admin().
$__base = base_url();
$__cur  = basename($_SERVER['SCRIPT_NAME'] ?? '');
$items = [
  'dashboard.php' => ['fa-gauge','Dashboard'],
  'rooms.php'     => ['fa-bed','Rooms'],
  'bookings.php'  => ['fa-calendar-check','Bookings'],
  'customers.php' => ['fa-users','Customers'],
  'reviews.php'   => ['fa-star','Reviews'],
  'gallery.php'   => ['fa-images','Gallery'],
  'messages.php'  => ['fa-envelope','Messages'],
  'settings.php'  => ['fa-gear','Settings'],
];
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><title><?= e($__title ?? 'Admin · BD Hotel') ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
<link href="<?= $__base ?>/css/style.css" rel="stylesheet">
<link href="<?= $__base ?>/css/responsive.css" rel="stylesheet">
</head><body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="brand">
      <div class="font-serif-luxe fs-3">BD Hotel</div>
      <div class="label-eyebrow" style="color:rgba(255,255,255,.5);">Admin Console</div>
    </div>
    <nav>
      <?php foreach ($items as $file => $it): ?>
        <a class="<?= $__cur === $file ? 'active' : '' ?>" href="<?= $__base ?>/admin/<?= $file ?>">
          <i class="fa-solid <?= $it[0] ?>"></i><?= e($it[1]) ?>
        </a>
      <?php endforeach; ?>
      <a href="<?= $__base ?>/logout.php"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
    </nav>
  </aside>
  <main class="admin-main">
