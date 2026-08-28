<?php
require_once __DIR__ . '/../includes/config.php';
$user = require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $name = trim((string)($_POST['name']  ?? $user['name']));
    $ph   = trim((string)($_POST['phone'] ?? ''));
    $cur  = (string)($_POST['current_password'] ?? '');
    $new  = (string)($_POST['new_password'] ?? '');

    $st = db()->prepare('UPDATE users SET name = ?, phone = ? WHERE id = ?');
    $st->execute([$name, $ph, $user['id']]);

    if ($cur !== '' && $new !== '') {
        $row = db()->query('SELECT password_hash FROM users WHERE id = ' . (int)$user['id'])->fetch();
        if (!$row || !password_verify($cur, $row['password_hash'])) {
            flash_set('error','Current password is incorrect.');
            header('Location: settings.php'); exit;
        }
        if (strlen($new) < 6) { flash_set('error','New password must be at least 6 characters.'); header('Location: settings.php'); exit; }
        $upd = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $upd->execute([password_hash($new, PASSWORD_BCRYPT), $user['id']]);
    }
    flash_set('success','Settings updated.');
    header('Location: settings.php'); exit;
}

$__title = 'Settings · Admin';
require __DIR__ . '/_layout.php';
$ok = flash_get('success'); $err = flash_get('error');
?>
<h1 class="font-serif-luxe display-5 mb-4">Settings</h1>
<?php if ($ok):  ?><div class="alert alert-success alert-luxe"><?= e($ok) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger  alert-luxe"><?= e($err) ?></div><?php endif; ?>

<div class="bg-white p-4 border rounded-2" style="max-width:640px;">
  <form method="post" data-validate class="row g-3">
    <?= csrf_field() ?>
    <div class="col-md-6"><div class="label-eyebrow mb-2">Name</div><input required class="bd-input" name="name" value="<?= e($user['name']) ?>"></div>
    <div class="col-md-6"><div class="label-eyebrow mb-2">Email</div><input class="bd-input" value="<?= e($user['email']) ?>" readonly></div>
    <div class="col-md-6"><div class="label-eyebrow mb-2">Phone</div><input class="bd-input" name="phone" value="<?= e($user['phone']) ?>"></div>
    <div class="col-12 mt-3"><div class="label-eyebrow mb-2">Change Password (optional)</div></div>
    <div class="col-md-6"><input type="password" name="current_password" placeholder="Current password" class="bd-input"></div>
    <div class="col-md-6"><input type="password" name="new_password"     placeholder="New password"     class="bd-input"></div>
    <div class="col-12"><button class="btn bd-btn-primary">Save Settings</button></div>
  </form>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
