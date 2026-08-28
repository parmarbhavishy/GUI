<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { flash_set('error', 'Session expired.'); header('Location: register.php'); exit; }
    $name = trim((string)($_POST['name'] ?? ''));
    $em   = strtolower(trim((string)($_POST['email'] ?? '')));
    $ph   = trim((string)($_POST['phone'] ?? ''));
    $pw   = (string)($_POST['password'] ?? '');
    $pw2  = (string)($_POST['password_confirm'] ?? '');

    $errors = [];
    if ($name === '') $errors[] = 'Name required.';
    if (!filter_var($em, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (strlen($pw) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($pw !== $pw2) $errors[] = 'Passwords do not match.';
    if (!$errors) {
        $chk = db()->prepare('SELECT id FROM users WHERE email = ?');
        $chk->execute([$em]);
        if ($chk->fetch()) $errors[] = 'Email already registered.';
    }
    if ($errors) { flash_set('error', implode(' ', $errors)); header('Location: register.php'); exit; }

    $ins = db()->prepare('INSERT INTO users (name,email,phone,password_hash,role) VALUES (?,?,?,?,"user")');
    $ins->execute([$name, $em, $ph, password_hash($pw, PASSWORD_BCRYPT)]);
    login_user((int)db()->lastInsertId());
    header('Location: profile.php'); exit;
}

if (current_user()) { header('Location: profile.php'); exit; }

$__title = 'Register · BD Hotel';
require_once __DIR__ . '/includes/header.php';
$err = flash_get('error');
?>
<div class="row g-0" style="min-height:100vh;">
  <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 order-2 order-lg-1">
    <form method="post" class="w-100" style="max-width:420px;" data-validate>
      <?= csrf_field() ?>
      <div class="section-eyebrow">Join us</div>
      <h1 class="font-serif-luxe display-4 mt-3 mb-4">Create your account.</h1>
      <?php if ($err): ?><div class="alert alert-danger alert-luxe"><?= e($err) ?></div><?php endif; ?>
      <div class="mb-3"><div class="label-eyebrow mb-2">Full Name</div><input required name="name" class="bd-input"></div>
      <div class="mb-3"><div class="label-eyebrow mb-2">Email</div><input required type="email" name="email" class="bd-input"></div>
      <div class="mb-3"><div class="label-eyebrow mb-2">Phone</div><input name="phone" class="bd-input"></div>
      <div class="mb-3"><div class="label-eyebrow mb-2">Password</div><input required type="password" name="password" class="bd-input"></div>
      <div class="mb-4"><div class="label-eyebrow mb-2">Confirm Password</div><input required type="password" name="password_confirm" class="bd-input"></div>
      <button class="btn bd-btn-primary w-100 ripple">Create Account</button>
      <div class="mt-3 small"><a class="gold-underline" href="login.php">Already a member? Sign In</a></div>
    </form>
  </div>
  <div class="col-lg-6 d-none d-lg-block position-relative order-1 order-lg-2">
    <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;">
    <div style="position:absolute;inset:0;background:rgba(0,0,0,.3);"></div>
  </div>
</div>
</body></html>
