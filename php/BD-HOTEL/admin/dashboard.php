<?php
require_once __DIR__ . '/../includes/config.php';
require_admin();

$stats = [
  'total_bookings'     => (int)db()->query('SELECT COUNT(*) c FROM bookings')->fetch()['c'],
  'pending_bookings'   => (int)db()->query('SELECT COUNT(*) c FROM bookings WHERE status="pending"')->fetch()['c'],
  'confirmed_bookings' => (int)db()->query('SELECT COUNT(*) c FROM bookings WHERE status="confirmed"')->fetch()['c'],
  'total_rooms'        => (int)db()->query('SELECT COUNT(*) c FROM rooms')->fetch()['c'],
  'total_customers'    => (int)db()->query('SELECT COUNT(*) c FROM users WHERE role="user"')->fetch()['c'],
  'total_messages'     => (int)db()->query('SELECT COUNT(*) c FROM contact_messages')->fetch()['c'],
  'total_revenue'      => (float)db()->query('SELECT COALESCE(SUM(total_price),0) s FROM bookings WHERE status="confirmed"')->fetch()['s'],
];
$recent = db()->query('SELECT b.*, r.name AS room_name FROM bookings b JOIN rooms r ON r.id=b.room_id ORDER BY b.created_at DESC LIMIT 8')->fetchAll();

$__title = 'Dashboard · BD Hotel Admin';
require __DIR__ . '/_layout.php';
?>
<div class="label-eyebrow" style="color:var(--bd-blue);">Overview</div>
<h1 class="font-serif-luxe display-5 mt-2 mb-4">Dashboard</h1>

<div class="row g-3">
  <?php $cards = [
    ['total_revenue','Total Revenue','fa-dollar-sign','$'],
    ['total_bookings','Total Bookings','fa-calendar-check',''],
    ['pending_bookings','Pending','fa-clock',''],
    ['confirmed_bookings','Confirmed','fa-check',''],
    ['total_rooms','Rooms','fa-bed',''],
    ['total_customers','Customers','fa-users',''],
    ['total_messages','Messages','fa-envelope',''],
  ]; foreach ($cards as $c): $v = $stats[$c[0]]; ?>
    <div class="col-6 col-md-3">
      <div class="stat-card h-100">
        <div class="d-flex justify-content-between align-items-center">
          <div class="label-eyebrow"><?= e($c[1]) ?></div>
          <i class="fa-solid <?= $c[2] ?> text-muted"></i>
        </div>
        <div class="fs-big mt-3"><?= e($c[3]) ?><?= number_format((float)$v, is_float($v) && floor($v) !== $v ? 2 : 0) ?></div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="mt-5">
  <div class="label-eyebrow mb-3">Recent Bookings</div>
  <div class="bg-white border rounded-2 overflow-hidden">
    <table class="table table-borderless mb-0 align-middle small">
      <thead class="bg-light"><tr>
        <th class="p-3">Guest</th><th class="p-3">Room</th><th class="p-3">Dates</th><th class="p-3">Total</th><th class="p-3">Status</th>
      </tr></thead>
      <tbody>
        <?php foreach ($recent as $b): ?>
          <tr class="border-top">
            <td class="p-3"><?= e($b['full_name']) ?></td>
            <td class="p-3"><?= e($b['room_name']) ?></td>
            <td class="p-3 text-muted"><?= e($b['check_in']) ?> → <?= e($b['check_out']) ?></td>
            <td class="p-3"><?= money((float)$b['total_price']) ?></td>
            <td class="p-3"><span class="status-pill status-<?= e($b['status']) ?>"><?= e($b['status']) ?></span></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$recent): ?><tr><td colspan="5" class="p-4 text-center text-muted">No bookings yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
