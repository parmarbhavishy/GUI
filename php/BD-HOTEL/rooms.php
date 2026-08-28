<?php
require_once __DIR__ . '/includes/config.php';

// If ?id=... display room detail with booking form; otherwise list.
$roomId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($roomId > 0):
    $st = db()->prepare('SELECT * FROM rooms WHERE id = ?');
    $st->execute([$roomId]);
    $room = $st->fetch();
    if (!$room) { header('Location: rooms.php'); exit; }
    $images = array_filter(array_map('trim', explode("\n", (string)$room['images'])));
    $amenities = array_filter(array_map('trim', explode(',', (string)$room['amenities'])));
    $__title = e($room['name']) . ' · BD Hotel';
    require_once __DIR__ . '/includes/header.php';
    require_once __DIR__ . '/includes/navbar.php';
    $err = flash_get('error'); $ok = flash_get('success');
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe">
    <?php if ($ok):  ?><div class="alert alert-success alert-luxe"><?= e($ok) ?></div><?php endif; ?>
    <?php if ($err): ?><div class="alert alert-danger alert-luxe"><?= e($err) ?></div><?php endif; ?>
    <div class="row g-5">
      <div class="col-lg-6">
        <div class="overflow-hidden mb-3"><img id="mainImg" src="<?= e($images[0] ?? '') ?>" class="w-100" style="height:520px;object-fit:cover;"></div>
        <div class="row g-2">
          <?php foreach ($images as $i => $im): ?>
            <div class="col-3"><img src="<?= e($im) ?>" class="w-100" style="height:96px;object-fit:cover;cursor:pointer;" onclick="document.getElementById('mainImg').src=this.src;"></div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="label-eyebrow"><?= e($room['category']) ?></div>
        <h1 class="font-serif-luxe display-3 mt-2"><?= e($room['name']) ?></h1>
        <div class="d-flex align-items-baseline gap-3 mt-3">
          <span class="font-serif-luxe fs-1"><?= money((float)$room['price']) ?></span>
          <span class="text-muted">/ night</span>
          <span class="text-muted ms-3"><i class="fa-solid fa-user-group me-2"></i>Up to <?= (int)$room['capacity'] ?> guests</span>
        </div>
        <div class="hairline my-4"></div>
        <p class="text-muted lh-lg"><?= e($room['description']) ?></p>
        <div class="mt-4">
          <div class="label-eyebrow mb-3">Amenities</div>
          <div class="row g-2">
            <?php foreach ($amenities as $a): ?>
              <div class="col-6"><i class="fa-solid fa-check text-gold me-2"></i><?= e($a) ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-5 pt-5 border-top">
      <div class="section-eyebrow">Booking</div>
      <h2 class="font-serif-luxe display-5 mt-2 mb-4">Reserve the <?= e($room['name']) ?></h2>

      <?php $u = current_user(); ?>
      <form id="bookingForm" class="row g-4" method="post" action="booking.php" data-validate data-price="<?= (float)$room['price'] ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="room_id" value="<?= (int)$room['id'] ?>">

        <div class="col-md-6"><div class="label-eyebrow mb-2">Full Name</div><input required name="full_name" class="bd-input" value="<?= e($u['name'] ?? '') ?>"></div>
        <div class="col-md-6"><div class="label-eyebrow mb-2">Email</div><input required type="email" name="email" class="bd-input" value="<?= e($u['email'] ?? '') ?>"></div>
        <div class="col-md-6"><div class="label-eyebrow mb-2">Mobile</div><input required name="mobile" class="bd-input" value="<?= e($u['phone'] ?? '') ?>"></div>
        <div class="col-md-6"><div class="label-eyebrow mb-2">Address</div><input name="address" class="bd-input"></div>
        <div class="col-md-6"><div class="label-eyebrow mb-2">Check-in</div><input required type="date" name="check_in" class="bd-input" min="<?= date('Y-m-d') ?>"></div>
        <div class="col-md-6"><div class="label-eyebrow mb-2">Check-out</div><input required type="date" name="check_out" class="bd-input" min="<?= date('Y-m-d', strtotime('+1 day')) ?>"></div>
        <div class="col-md-3"><div class="label-eyebrow mb-2">Adults</div><select name="adults" class="bd-select"><?php for($i=1;$i<=4;$i++) echo "<option>$i</option>"; ?></select></div>
        <div class="col-md-3"><div class="label-eyebrow mb-2">Children</div><select name="children" class="bd-select"><?php for($i=0;$i<=3;$i++) echo "<option>$i</option>"; ?></select></div>
        <div class="col-md-3">
          <div class="label-eyebrow mb-2">Payment Method</div>
          <select name="payment_method" class="bd-select"><option>Cash</option><option>Card</option><option>UPI</option><option>Bank Transfer</option></select>
        </div>
        <div class="col-md-3"><div class="label-eyebrow mb-2">Room Type</div><input class="bd-input" value="<?= e($room['category']) ?>" readonly></div>
        <div class="col-12"><div class="label-eyebrow mb-2">Special Request</div><input name="special_request" class="bd-input" placeholder="Any preferences?"></div>

        <div class="col-12 pt-4 border-top d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
          <div>
            <div class="label-eyebrow">Total</div>
            <div id="bookingTotal" class="font-serif-luxe display-6"><?= money((float)$room['price']) ?></div>
            <div class="small text-muted"><span id="bookingNights">1 night</span> × <?= money((float)$room['price']) ?></div>
          </div>
          <button class="btn bd-btn-primary ripple">Confirm Booking</button>
        </div>
      </form>
    </div>
  </div>
</section>
<?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
endif;

// ---------- Listing mode ----------
$cats = ['All','Deluxe Room','Super Deluxe','Executive Room','Family Room','Luxury Suite','Presidential Suite'];
$cat  = $_GET['category'] ?? 'All';
$max  = isset($_GET['max']) ? (float)$_GET['max'] : 1000;

$sql    = 'SELECT * FROM rooms WHERE price <= ?';
$params = [$max];
if ($cat !== 'All') { $sql .= ' AND category = ?'; $params[] = $cat; }
$sql   .= ' ORDER BY price ASC';
$st = db()->prepare($sql); $st->execute($params);
$rows = $st->fetchAll();

$__title = 'Rooms · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe">
    <div class="section-eyebrow">Our Rooms</div>
    <h1 class="font-serif-luxe display-2 mt-3">A room for every kind of stay.</h1>
    <p class="text-muted" style="max-width:520px;">From compact Deluxe rooms to the Presidential Suite. Every one of them is quietly, seriously, considered.</p>

    <form method="get" class="row g-4 align-items-end border-top border-bottom py-4 my-5">
      <div class="col-md-4">
        <div class="label-eyebrow mb-2">Search</div>
        <input id="roomsSearch" class="bd-input" placeholder="Search room name...">
      </div>
      <div class="col-md-4">
        <div class="label-eyebrow mb-2">Category</div>
        <select name="category" class="bd-select" onchange="this.form.submit()">
          <?php foreach ($cats as $c): ?><option <?= $c===$cat?'selected':'' ?>><?= e($c) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-4">
        <div class="label-eyebrow mb-2">Max Price: $<span id="maxLabel"><?= (int)$max ?></span></div>
        <input name="max" type="range" min="100" max="1000" value="<?= (int)$max ?>" class="form-range" oninput="document.getElementById('maxLabel').textContent=this.value" onchange="this.form.submit()">
      </div>
    </form>

    <div class="row g-4">
      <?php foreach ($rows as $r):
        $imgs = array_filter(array_map('trim', explode("\n", (string)$r['images'])));
        $img  = $imgs[0] ?? '';
      ?>
        <div class="col-md-6 col-lg-4 room-card-wrap" data-name="<?= e($r['name']) ?>" data-aos="fade-up">
          <a href="rooms.php?id=<?= (int)$r['id'] ?>" class="room-card d-block">
            <div class="overflow-hidden"><img class="room-img w-100" style="height:280px;object-fit:cover;" src="<?= e($img) ?>" alt=""></div>
            <div class="d-flex justify-content-between align-items-start mt-3">
              <div>
                <div class="label-eyebrow"><?= e($r['category']) ?></div>
                <div class="font-serif-luxe fs-3 mt-1"><?= e($r['name']) ?></div>
                <div class="text-muted small mt-1"><i class="fa-regular fa-user me-1"></i>Up to <?= (int)$r['capacity'] ?> guests</div>
              </div>
              <div class="text-end">
                <div class="font-serif-luxe fs-4"><?= money((float)$r['price']) ?></div>
                <div class="small text-muted">per night</div>
              </div>
            </div>
            <span class="link-arrow mt-3 d-inline-flex">View Room →</span>
          </a>
        </div>
      <?php endforeach; ?>
      <?php if (!$rows): ?><div class="col-12 text-center text-muted py-5">No rooms match your criteria.</div><?php endif; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
