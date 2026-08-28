<?php
/**
 * BD Hotel · Setup diagnostic
 * Open this in the browser to verify Apache, PHP, MySQL and file paths
 * are all set up correctly BEFORE hitting index.php.
 * Delete this file after successful setup.
 */
declare(strict_types=1);

$root = __DIR__;
$requiredFiles = [
    'index.php', 'rooms.php', 'booking.php', 'includes/config.php',
    'database/db_connect.php', 'database/bdhotel.sql',
    'css/style.css', 'js/script.js', 'admin/dashboard.php',
];
$requiredExts = ['pdo_mysql', 'session'];
$checks = [];

$checks[] = ['PHP version >= 8.0', PHP_VERSION_ID >= 80000, PHP_VERSION];
foreach ($requiredExts as $ext) {
    $checks[] = ["Extension: $ext", extension_loaded($ext), extension_loaded($ext) ? 'loaded' : 'MISSING'];
}
foreach ($requiredFiles as $f) {
    $checks[] = ["File exists: $f", is_file("$root/$f"), is_file("$root/$f") ? 'ok' : 'MISSING'];
}

// Try DB connection
$dbOk = false; $dbMsg = '';
try {
    require_once "$root/database/db_connect.php";
    db()->query('SELECT 1');
    $dbOk = true;
    $tables = db()->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $dbMsg = 'connected · tables: ' . (count($tables) ? implode(', ', $tables) : 'NONE (import bdhotel.sql!)');
} catch (Throwable $e) {
    $dbMsg = 'FAILED — ' . $e->getMessage();
}
$checks[] = ['MySQL connection', $dbOk, $dbMsg];

if ($dbOk && count($tables ?? []) > 0) {
    $rc = (int)db()->query('SELECT COUNT(*) c FROM rooms')->fetch()['c'];
    $uc = (int)db()->query('SELECT COUNT(*) c FROM users WHERE role="admin"')->fetch()['c'];
    $checks[] = ['Seed data · rooms',  $rc >= 6, "$rc rooms"];
    $checks[] = ['Seed data · admin',  $uc >= 1, "$uc admin user"];
}

$allOk = !array_filter($checks, fn($c) => !$c[1]);
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>BD Hotel · Setup Check</title>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&family=Cormorant+Garamond:wght@500&display=swap" rel="stylesheet">
<style>
  body{font-family:Manrope,sans-serif;background:#F8F9FA;color:#212529;margin:0;padding:2rem;}
  .box{max-width:820px;margin:0 auto;background:#fff;padding:2.5rem;box-shadow:0 20px 40px rgba(0,0,0,.06);border-radius:8px;}
  h1{font-family:'Cormorant Garamond',serif;font-size:2.5rem;margin:0 0 .5rem;}
  .row{display:flex;align-items:center;gap:.75rem;padding:.55rem 0;border-bottom:1px dashed #eee;font-size:.95rem;}
  .badge{width:22px;height:22px;border-radius:999px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-size:.75rem;font-weight:700;flex-shrink:0;}
  .ok{background:#198754;} .bad{background:#dc3545;}
  .msg{color:#6c757d;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:.82rem;margin-left:auto;}
  .cta{margin-top:2rem;padding:1.25rem;border-radius:6px;text-align:center;font-weight:600;}
  .cta.ok{background:#d1e7dd;color:#0f5132;}
  .cta.bad{background:#f8d7da;color:#842029;}
  .cta a{color:inherit;text-decoration:underline;}
  code{background:#f3f3f3;padding:.15rem .4rem;border-radius:4px;font-size:.82rem;}
  .hint{margin-top:1.5rem;padding:1rem 1.25rem;background:#fff3cd;border-left:3px solid #C5A059;font-size:.9rem;}
</style></head><body>
<div class="box">
  <div style="letter-spacing:.2em;text-transform:uppercase;font-size:.72rem;color:#C5A059;font-weight:600;">Setup Diagnostic</div>
  <h1>BD Hotel · Environment check</h1>
  <p style="color:#6c757d;">This page verifies your XAMPP setup. Delete <code>check.php</code> once all checks are green.</p>

  <?php foreach ($checks as $c): ?>
    <div class="row">
      <span class="badge <?= $c[1] ? 'ok' : 'bad' ?>"><?= $c[1] ? '✓' : '×' ?></span>
      <span><?= htmlspecialchars($c[0]) ?></span>
      <span class="msg"><?= htmlspecialchars((string)$c[2]) ?></span>
    </div>
  <?php endforeach; ?>

  <?php if ($allOk): ?>
    <div class="cta ok">
      🎉 All checks passed! <a href="index.php">Open the BD Hotel home page →</a>
    </div>
  <?php else: ?>
    <div class="cta bad">Some checks failed. Fix the red rows above, then reload this page.</div>
    <div class="hint">
      <strong>Common fixes:</strong><br>
      · Missing tables → open <a href="http://localhost/phpmyadmin" target="_blank">phpMyAdmin</a>, click Import, choose <code>database/bdhotel.sql</code>, click Go.<br>
      · MySQL connection failed → edit <code>database/db_connect.php</code> and set your MySQL <code>DB_PASS</code>.<br>
      · Missing PHP extension → enable it in <code>php.ini</code> (uncomment the <code>extension=</code> line) and restart Apache.<br>
      · Missing file → re-extract the zip (don't skip files) and make sure the folder is at <code>htdocs/BD-HOTEL/</code>.
    </div>
  <?php endif; ?>

  <div style="margin-top:2rem;font-size:.82rem;color:#6c757d;">
    Base URL detected: <code><?= htmlspecialchars((function_exists('base_url') ? base_url() : 'n/a')) ?></code><br>
    Document root: <code><?= htmlspecialchars((string)($_SERVER['DOCUMENT_ROOT'] ?? '')) ?></code><br>
    Project root: <code><?= htmlspecialchars($root) ?></code>
  </div>
</div>
</body></html>
