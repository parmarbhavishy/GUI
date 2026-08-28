<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $em = strtolower(trim((string)($_POST['email'] ?? '')));
    if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
        $st = db()->prepare('SELECT id FROM users WHERE email = ?');
        $st->execute([$em]);
        if ($u = $st->fetch()) {
            $token   = bin2hex(random_bytes(24));
            $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour
            $upd = db()->prepare('UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?');
            $upd->execute([$token, $expires, $u['id']]);
            $link = base_url() . '/reset-password.php?token=' . urlencode($token);
            // In production: send this link by email. For now display it.
            flash_set('success', 'Reset link generated. Open it below to set a new password. (In production this would be emailed.)');
            $_SESSION['reset_link_preview'] = $link;
        } else {
            flash_set('success', 'If an account exists for that email, a reset link has been sent.');
        }
    } else {
        flash_set('error', 'Please provide a valid email.');
    }
    header('Location: forgot-password.php'); exit;
}

$__title = 'Forgot Password · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$err = flash_get('error'); $ok = flash_get('success');
$preview = $_SESSION['reset_link_preview'] ?? null; unset($_SESSION['reset_link_preview']);
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe" style="max-width:520px;">
    <div class="section-eyebrow">Recovery</div>
    <h1 class="font-serif-luxe display-4 mt-3 mb-4">Reset your password.</h1>
    <?php if ($err): ?><div class="alert alert-danger alert-luxe"><?= e($err) ?></div><?php endif; ?>
    <?php if ($ok):  ?><div class="alert alert-success alert-luxe"><?= e($ok)  ?></div><?php endif; ?>
    <?php if ($preview): ?>
      <div class="alert alert-luxe" style="background:#fff3cd;">
        <div class="label-eyebrow mb-2">Reset Link (preview)</div>
        <a class="gold-underline" href="<?= e($preview) ?>"><?= e($preview) ?></a>
      </div>
    <?php endif; ?>
    <form method="post" data-validate>
      <?= csrf_field() ?>
      <div class="mb-4"><div class="label-eyebrow mb-2">Your Email</div><input required type="email" name="email" class="bd-input"></div>
      <button class="btn bd-btn-primary w-100 ripple">Send Reset Link</button>
    </form>
    <div class="mt-3 small"><a class="gold-underline" href="login.php">← Back to sign in</a></div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
