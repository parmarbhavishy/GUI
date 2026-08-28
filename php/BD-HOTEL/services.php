<?php
$__title = 'Services · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$svc = [
  ['fa-utensils','Fine Dining','Two restaurants including our Michelin-listed dining room, and 24-hour in-room dining.'],
  ['fa-spa','Wellness & Spa','A 2,000 sqm sanctuary with hammam, cold plunge and hand-poured aromatherapy.'],
  ['fa-bed','Butler Service','A dedicated butler for suite guests. Unpacking, pressing, restaurant reservations.'],
  ['fa-water','Private Pool','A heated 25-metre pool with cabanas and daytime cocktail service.'],
  ['fa-briefcase','Meeting Suites','Enterprise-grade Wi-Fi, private meeting suites and concierge-managed workspaces.'],
  ['fa-car','Airport Transfer','Chauffeured Mercedes S-Class transfers on request, 24 hours in advance.'],
];
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe">
    <div class="section-eyebrow">Services</div>
    <h1 class="font-serif-luxe display-2 mt-3">Everything, quietly attended to.</h1>
    <div class="row g-4 mt-4">
      <?php foreach ($svc as $i => $s): ?>
        <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?= $i*80 ?>">
          <div class="hover-lift p-4 bg-white h-100">
            <i class="fa-solid <?= $s[0] ?> fs-2 text-gold"></i>
            <div class="font-serif-luxe fs-3 mt-3"><?= e($s[1]) ?></div>
            <p class="text-muted small mt-2"><?= e($s[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
