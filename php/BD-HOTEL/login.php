<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { flash_set('error', 'Session expired.'); header('Location: login.php'); exit; }
    $em = strtolower(trim((string)($_POST['email'] ?? '')));
    $pw = (string)($_POST['password'] ?? '');
    $st = db()->prepare('SELECT id,password_hash,role FROM users WHERE email = ? LIMIT 1');
    $st->execute([$em]);
    $u = $st->fetch();
    if ($u && password_verify($pw, $u['password_hash'])) {
        login_user((int)$u['id']);
        header('Location: ' . ($u['role'] === 'admin' ? 'admin/dashboard.php' : 'profile.php')); exit;
    }
    flash_set('error', 'Invalid email or password.');
    header('Location: login.php'); exit;
}

if (current_user()) { header('Location: profile.php'); exit; }

$__title = 'Sign In · BD Hotel';
require_once __DIR__ . '/includes/header.php';
$err = flash_get('error'); $ok = flash_get('success');
?>
<div class="row g-0" style="min-height:100vh;">
  <div class="col-lg-6 d-none d-lg-block position-relative">
    <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,.3);"></div>
    <div class="position-relative p-5 h-100 d-flex align-items-end text-white">
      <div class="font-serif-luxe fs-2" style="max-width:420px;">“A quiet place to arrive. A quieter one to return to.”</div>
    </div>
  </div>
  <div class="col-lg-6 d-flex align-items-center justify-content-center p-4">
    <form method="post" class="w-100" style="max-width:420px;" data-validate>
      <?= csrf_field() ?>
      <div class="section-eyebrow">Welcome</div>
      <h1 class="font-serif-luxe display-4 mt-3 mb-4">Sign in.</h1>
      <?php if ($err): ?><div class="alert alert-danger alert-luxe"><?= e($err) ?></div><?php endif; ?>
      <?php if ($ok):  ?><div class="alert alert-success alert-luxe"><?= e($ok)  ?></div><?php endif; ?>
      <div class="mb-4"><div class="label-eyebrow mb-2">Email</div><input required type="email" name="email" class="bd-input"></div>
      <div class="mb-4"><div class="label-eyebrow mb-2">Password</div><input required type="password" name="password" class="bd-input"></div>
      <button class="btn bd-btn-primary w-100 ripple">Sign In</button>
      <div class="d-flex justify-content-between mt-3 small">
        <a class="gold-underline" href="register.php">Create account</a>
        <a class="gold-underline" href="forgot-password.php">Forgot password?</a>
      </div>
      <div class="mt-4 small text-muted">
        <strong>Admin:</strong> admin@bdhotel.com / Admin@123
      </div>
    </form>
  </div>
</div>
</body></html>
