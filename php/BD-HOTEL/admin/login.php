 <?php
require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { flash_set('error', 'Session expired.'); header('Location: login.php'); exit; }
    $em = strtolower(trim((string)($_POST['email'] ?? '')));
    $pw = (string)($_POST['password'] ?? '');
    $st = db()->prepare('SELECT id,password_hash,role FROM users WHERE email = ? LIMIT 1');
    $st->execute([$em]);
    $u = $st->fetch();
    if ($u && $u['role'] === 'admin' && password_verify($pw, $u['password_hash'])) {
        login_user((int)$u['id']);
        header('Location: dashboard.php'); exit;
    }
    flash_set('error', 'Invalid admin credentials.');
    header('Location: login.php'); exit;
}

$u = current_user();
if ($u && $u['role'] === 'admin') { header('Location: dashboard.php'); exit; }

$err = flash_get('error');
?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <title>Admin Sign In · BD Hotel</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link
         href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&family=Manrope:wght@400;600;700&display=swap"
         rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="<?= base_url() ?>/css/style.css" rel="stylesheet">
 </head>

 <body class="d-flex align-items-center justify-content-center" style="min-height:100vh;background-image:url("");">
     <form method="post" class="p-5" style="width:100%;max-width:420px;background:#fff;">
         <?= csrf_field() ?>
         <div class="section-eyebrow">Admin</div>
         <h1 class="font-serif-luxe display-5 mt-3 mb-4">Sign in.</h1>
         <?php if ($err): ?><div class="alert alert-danger alert-luxe"><?= e($err) ?></div><?php endif; ?>
         <div class="mb-3">
             <div class="label-eyebrow mb-2">Email</div><input required type="email" name="email" class="bd-input"
                 value="admin@bdhotel.com">
         </div>
         <div class="mb-4">
             <div class="label-eyebrow mb-2">Password</div><input required type="password" name="password"
                 class="bd-input">
         </div>
         <button class="btn bd-btn-primary w-100 ripple">Sign In</button>
         <div class="small text-muted mt-3">Default: admin@bdhotel.com / Admin@123</div>
     </form>
 </body>

 </html>