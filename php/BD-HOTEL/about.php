<?php
$__title = 'About · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe row g-5">
    <div class="col-lg-6" data-aos="fade-right">
      <div class="section-eyebrow">Our Story</div>
      <h1 class="font-serif-luxe display-2 mt-3">Fifty years of small, careful gestures.</h1>
      <div class="hairline my-4"></div>
      <p class="text-muted lh-lg">BD Hotel opened in 1974 on a stretch of coastline that most maps still did not name. Three generations later, we are still here, on the same corner, quietly refining what it means to receive a guest.</p>
      <p class="text-muted lh-lg mt-3">Our team is small. Our menus change with the tide. Our idea of luxury is a room prepared exactly as you like it, before you know you like it that way.</p>
    </div>
    <div class="col-lg-6" data-aos="fade-left">
      <img src="https://images.pexels.com/photos/460537/pexels-photo-460537.jpeg" class="w-100" style="height:640px;object-fit:cover;">
    </div>
  </div>

  <div class="container-luxe row g-4 mt-5">
    <?php foreach ([['1974','A doorway opens on a quiet coastal lane.'],['1998','Our Michelin-listed dining room welcomes its first table.'],['2019','The Presidential Suite is completed after four years of restoration.']] as $t): ?>
      <div class="col-md-4">
        <div class="border-top pt-4" style="border-color:var(--bd-gold) !important;">
          <div class="font-serif-luxe display-4"><?= e($t[0]) ?></div>
          <p class="text-muted small mt-2"><?= e($t[1]) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
