<?php
$__title = 'BD Hotel · Quiet Luxury';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';

$rooms   = db()->query('SELECT * FROM rooms ORDER BY price ASC LIMIT 6')->fetchAll();
$reviews = db()->query('SELECT * FROM reviews WHERE approved = 1 ORDER BY created_at DESC LIMIT 6')->fetchAll();

$flash_ok  = flash_get('success');
$flash_err = flash_get('error');
?>

<?php if ($flash_ok):  ?><div class="container-luxe pt-5"><div class="alert alert-success alert-luxe mt-5"><?= e($flash_ok)  ?></div></div><?php endif; ?>
<?php if ($flash_err): ?><div class="container-luxe pt-5"><div class="alert alert-danger alert-luxe mt-5"><?= e($flash_err) ?></div></div><?php endif; ?>

<!-- HERO -->
<section class="bd-hero">
  <div class="swiper hero-slider">
    <div class="swiper-wrapper">
      <?php foreach ([
        'https://images.pexels.com/photos/29602700/pexels-photo-29602700.jpeg?auto=compress&cs=tinysrgb&w=1920',
        'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=1920&q=80',
        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1920&q=80',
      ] as $i => $img): ?>
        <div class="swiper-slide"><img class="bd-hero-img" src="<?= e($img) ?>" alt="BD Hotel"></div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-pagination hero-pagination"></div>
  </div>
  <div class="bd-hero-overlay"></div>
  <div class="container-luxe bd-hero-content">
    <div class="section-eyebrow text-gold" data-aos="fade-up">Est. 1974 · Coastal District</div>
    <h1 class="mt-3 mb-4" data-aos="fade-up" data-aos-delay="100">
      An address of quiet<br>luxury &amp; ceremony.
    </h1>
    <p class="text-white-50 lead" style="max-width:520px;" data-aos="fade-up" data-aos-delay="200">
      Sixty-four rooms, six suites, one Michelin-listed dining room and an obsessive attention to the small things — pressed linens, chilled sheets, morning coffee, exactly as you like it.
    </p>
    <div class="d-flex flex-wrap gap-3 mt-4" data-aos="fade-up" data-aos-delay="300">
      <a href="rooms.php" class="btn bd-btn-light ripple">Reserve a Room</a>
      <a href="gallery.php" class="btn bd-btn-light-outline">View Gallery</a>
    </div>
  </div>
</section>

<!-- BOOKING WIDGET -->
<div class="container-luxe" style="margin-top:-70px;position:relative;z-index:5;">
  <form class="glass p-4 p-md-5 row g-4 align-items-end" data-aos="fade-up" method="get" action="rooms.php">
    <div class="col-6 col-md-3"><div class="label-eyebrow mb-2">Check-in</div><input type="date" class="bd-input" name="ci"></div>
    <div class="col-6 col-md-3"><div class="label-eyebrow mb-2">Check-out</div><input type="date" class="bd-input" name="co"></div>
    <div class="col-6 col-md-2"><div class="label-eyebrow mb-2">Adults</div>
      <select class="bd-select" name="adults">
        <?php for ($i=1;$i<=4;$i++) echo '<option>'.$i.'</option>'; ?>
      </select>
    </div>
    <div class="col-6 col-md-2"><div class="label-eyebrow mb-2">Children</div>
      <select class="bd-select" name="children">
        <?php for ($i=0;$i<=3;$i++) echo '<option>'.$i.'</option>'; ?>
      </select>
    </div>
    <div class="col-12 col-md-2"><button class="btn bd-btn-primary w-100 ripple">Check Availability</button></div>
  </form>
</div>

<!-- MARQUEE RIBBON -->
<section class="my-5 py-4 border-top border-bottom overflow-hidden" style="background:#fff;">
  <div class="marquee-track">
    <?php $words = ['SPA','FINE DINING','CONCIERGE','PRIVATE POOL','CIGAR LOUNGE','BUTLER','ATELIER']; ?>
    <?php for ($k=0;$k<2;$k++) foreach ($words as $w): ?>
      <span class="marquee-serif"><?= $w ?> <span class="text-gold">·</span></span>
    <?php endforeach; ?>
  </div>
</section>

<!-- FEATURED ROOMS -->
<section class="py-luxe">
  <div class="container-luxe">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-5">
      <div>
        <div class="section-eyebrow">Signature Rooms</div>
        <h2 class="font-serif-luxe mt-3 display-4" data-aos="fade-up">Six ways to stay.<br>One idea of luxury.</h2>
      </div>
      <a href="rooms.php" class="link-arrow mt-3 mt-md-0">Explore All Rooms →</a>
    </div>

    <div class="row g-4">
      <?php foreach ($rooms as $i => $r):
        $imgs = array_filter(array_map('trim', explode("\n", (string)$r['images'])));
        $img  = $imgs[0] ?? '';
        $col  = $i === 0 ? 'col-md-8' : 'col-md-4';
      ?>
        <div class="<?= $col ?> col-12" data-aos="fade-up" data-aos-delay="<?= $i*80 ?>">
          <a href="rooms.php?id=<?= (int)$r['id'] ?>" class="room-card d-block">
            <div class="overflow-hidden"><img class="room-img w-100" style="height:<?= $i===0?'520px':'260px' ?>;object-fit:cover;" src="<?= e($img) ?>" alt="<?= e($r['name']) ?>"></div>
            <div class="d-flex align-items-end justify-content-between mt-3">
              <div>
                <div class="label-eyebrow"><?= e($r['category']) ?></div>
                <div class="font-serif-luxe fs-3 mt-1"><?= e($r['name']) ?></div>
              </div>
              <div class="text-end">
                <div class="label-eyebrow">From</div>
                <div class="font-serif-luxe fs-4"><?= money((float)$r['price']) ?></div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="py-luxe bg-white">
  <div class="container-luxe">
    <div class="section-eyebrow">Amenities</div>
    <h2 class="font-serif-luxe display-5 mt-3 mb-5">Small ceremonies. Rare privileges.</h2>
    <div class="row g-4">
      <?php $services = [
        ['fa-utensils','Fine Dining','Two restaurants including our Michelin-listed dining room, and 24-hour in-room dining.'],
        ['fa-bed','Butler Service','A dedicated butler for suite guests. Unpacking, pressing, restaurant reservations.'],
        ['fa-bath','Wellness & Spa','A 2,000 sqm sanctuary with hammam, cold plunge and hand-poured aromatherapy.'],
        ['fa-wifi','Discreet Tech','Enterprise-grade Wi-Fi, private meeting suites and concierge-managed workspaces.'],
      ]; foreach ($services as $i => $s): ?>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="<?= $i*80 ?>">
          <div class="border-top pt-4" style="border-color:var(--bd-gold) !important;">
            <i class="fa-solid <?= $s[0] ?> fs-3"></i>
            <div class="font-serif-luxe fs-3 mt-3"><?= e($s[1]) ?></div>
            <p class="text-muted small mt-2"><?= e($s[2]) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="py-5 border-top border-bottom">
  <div class="container-luxe row g-4 text-center text-md-start">
    <?php foreach ([[64,'Rooms'],[6,'Signature Suites'],[12,'Years of Ceremony'],[4.9,'Guest Rating']] as $s): ?>
      <div class="col-6 col-md-3">
        <div class="font-serif-luxe" style="font-size:4rem;line-height:1;" data-counter="<?= $s[0] ?>">0</div>
        <div class="label-eyebrow mt-2"><?= e($s[1]) ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- REVIEWS -->
<section class="py-luxe">
  <div class="container-luxe">
    <div class="section-eyebrow">Guest Impressions</div>
    <h2 class="font-serif-luxe display-5 mt-3 mb-5">What our guests say, in their words.</h2>
    <div class="swiper reviews-slider">
      <div class="swiper-wrapper">
        <?php foreach ($reviews as $rv): ?>
          <div class="swiper-slide">
            <div class="p-4 h-100 border-top" style="border-color:rgba(33,37,41,.15) !important;">
              <div class="mb-3">
                <?php for ($k=1;$k<=5;$k++): ?>
                  <i class="fa-solid fa-star <?= $k <= (int)$rv['rating'] ? 'star-filled' : 'star-empty' ?>"></i>
                <?php endfor; ?>
              </div>
              <p class="font-serif-luxe fs-4 lh-sm">“<?= e($rv['comment']) ?>”</p>
              <div class="label-eyebrow mt-3">— <?= e($rv['name']) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="swiper-pagination reviews-pagination mt-4"></div>
    </div>
  </div>
</section>

<!-- CTA / SPECIAL OFFER -->
<section class="position-relative py-luxe text-white" style="overflow:hidden;">
  <img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?auto=format&fit=crop&w=1920&q=80" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" alt="">
  <div style="position:absolute;inset:0;background:rgba(0,0,0,.5);"></div>
  <div class="container-luxe position-relative">
    <div class="section-eyebrow text-gold">Special Offer</div>
    <h2 class="font-serif-luxe display-3 mt-3 mb-3">Stay three nights.<br>The fourth is ours.</h2>
    <p class="lead text-white-50" style="max-width:520px;">Complimentary breakfast, spa credit and a private terrace tasting. Valid through the season, subject to availability.</p>
    <div class="mt-4">
      <div class="label-eyebrow text-gold mb-2">Offer ends in</div>
      <div class="font-serif-luxe fs-3" data-countdown-to="<?= date('Y-m-d', strtotime('+30 days')) ?>"></div>
    </div>
    <a class="btn bd-btn-light ripple mt-4" href="rooms.php">Reserve Now</a>
  </div>
</section>

<!-- FAQ / WHY -->
<section class="py-luxe bg-white">
  <div class="container-luxe">
    <div class="row g-5">
      <div class="col-lg-5">
        <div class="section-eyebrow">Why BD Hotel</div>
        <h2 class="font-serif-luxe display-5 mt-3">Five decades of quiet detail.</h2>
      </div>
      <div class="col-lg-7">
        <div class="accordion" id="faqAcc">
          <?php $faqs = [
            ['Is breakfast included?','Yes — our à la carte breakfast is complimentary for all suite guests, and available at concierge rate for room guests.'],
            ['Do you offer airport transfers?','A chauffeured Mercedes S-Class is available on request, 24 hours in advance.'],
            ['What is your cancellation policy?','Free cancellation up to 48 hours before arrival. After that, a one-night charge applies.'],
            ['Are pets allowed?','Small, well-behaved companions are warmly welcomed in ground-floor rooms.'],
          ]; foreach ($faqs as $i => $q): ?>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button <?= $i>0?'collapsed':'' ?>" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                  <?= e($q[0]) ?>
                </button>
              </h2>
              <div id="faq<?= $i ?>" class="accordion-collapse collapse <?= $i===0?'show':'' ?>" data-bs-parent="#faqAcc">
                <div class="accordion-body text-muted"><?= e($q[1]) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
