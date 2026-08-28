<?php
require_once __DIR__ . '/includes/config.php';

$token = (string)($_GET['token'] ?? $_POST['token'] ?? '');
$user  = null;
if ($token !== '') {
    $st = db()->prepare('SELECT id, reset_expires FROM users WHERE reset_token = ? LIMIT 1');
    $st->execute([$token]);
    $u = $st->fetch();
    if ($u && strtotime((string)$u['reset_expires']) > time()) $user = $u;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify() || !$user) { flash_set('error', 'Invalid or expired token.'); header('Location: forgot-password.php'); exit; }
    $pw  = (string)($_POST['password'] ?? '');
    $pw2 = (string)($_POST['password_confirm'] ?? '');
    if (strlen($pw) < 6)  { flash_set('error', 'Password must be at least 6 characters.'); header('Location: reset-password.php?token=' . urlencode($token)); exit; }
    if ($pw !== $pw2)     { flash_set('error', 'Passwords do not match.');                header('Location: reset-password.php?token=' . urlencode($token)); exit; }

    $upd = db()->prepare('UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?');
    $upd->execute([password_hash($pw, PASSWORD_BCRYPT), $user['id']]);
    flash_set('success', 'Password updated. Please sign in.');
    header('Location: login.php'); exit;
}

$__title = 'Reset Password · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$err = flash_get('error');
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe" style="max-width:520px;">
    <div class="section-eyebrow">Recovery</div>
    <h1 class="font-serif-luxe display-4 mt-3 mb-4">Set a new password.</h1>
    <?php if ($err): ?><div class="alert alert-danger alert-luxe"><?= e($err) ?></div><?php endif; ?>
    <?php if (!$user): ?>
      <div class="alert alert-danger alert-luxe">The reset link is invalid or expired. <a class="gold-underline" href="forgot-password.php">Request a new one</a>.</div>
    <?php else: ?>
      <form method="post" data-validate>
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= e($token) ?>">
        <div class="mb-3"><div class="label-eyebrow mb-2">New Password</div><input required type="password" name="password" class="bd-input"></div>
        <div class="mb-4"><div class="label-eyebrow mb-2">Confirm Password</div><input required type="password" name="password_confirm" class="bd-input"></div>
        <button class="btn bd-btn-primary w-100 ripple">Update Password</button>
      </form>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
