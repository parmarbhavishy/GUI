<?php
$__cur = basename($_SERVER['SCRIPT_NAME'] ?? '');
$__nav = [
    'index.php'   => 'Home',
    'rooms.php'   => 'Rooms',
    'gallery.php' => 'Gallery',
    'services.php'=> 'Services',
    'about.php'   => 'About',
    'contact.php' => 'Contact',
];
?>
<nav class="bd-nav navbar navbar-expand-lg fixed-top" id="mainNav">
  <div class="container-xxl">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $__base ?>/index.php">
      <span class="brand-word">BD</span>
      <span class="brand-dot"></span>
      <span class="brand-word">HOTEL</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav mx-auto gap-lg-4">
        <?php foreach ($__nav as $file => $label): ?>
          <li class="nav-item">
            <a class="nav-link gold-underline <?= $__cur === $file ? 'active' : '' ?>"
               href="<?= $__base ?>/<?= $file ?>"><?= e($label) ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <?php if ($__user): ?>
          <a class="nav-link gold-underline" href="<?= $__base ?>/<?= $__user['role'] === 'admin' ? 'admin/dashboard.php' : 'profile.php' ?>">
            <i class="fa-regular fa-user me-1"></i> <?= e(explode(' ', $__user['name'])[0]) ?>
          </a>
          <a class="btn bd-btn-outline" href="<?= $__base ?>/logout.php">Logout</a>
        <?php else: ?>
          <a class="nav-link gold-underline" href="<?= $__base ?>/login.php">Sign In</a>
          <a class="btn bd-btn-primary" href="<?= $__base ?>/rooms.php">Book Now</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
