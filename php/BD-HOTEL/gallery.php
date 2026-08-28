<?php
require_once __DIR__ . '/includes/config.php';
$rows = db()->query('SELECT * FROM gallery ORDER BY created_at ASC')->fetchAll();
$cats = ['All'];
foreach ($rows as $r) if (!in_array($r['category'], $cats, true)) $cats[] = $r['category'];

$__title = 'Gallery · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe">
    <div class="section-eyebrow">Gallery</div>
    <h1 class="font-serif-luxe display-2 mt-3">Moments, mostly in silence.</h1>

    <div class="my-5 d-flex flex-wrap">
      <?php foreach ($cats as $c): ?>
        <button class="gallery-filter-btn <?= $c==='All'?'active':'' ?>" data-filter="<?= e($c) ?>"><?= e($c) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="row g-4">
      <?php foreach ($rows as $r): ?>
        <div class="col-md-4 gallery-item" data-cat="<?= e($r['category']) ?>" data-src="<?= e($r['url']) ?>" data-aos="zoom-in">
          <img src="<?= e($r['url']) ?>" alt="<?= e($r['category']) ?>">
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
