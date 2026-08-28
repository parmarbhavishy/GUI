<?php
require_once __DIR__ . '/includes/config.php';

// Newsletter subscription handler
if (isset($_GET['newsletter']) && $_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $em = trim((string)($_POST['newsletter_email'] ?? ''));
    if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
        $st = db()->prepare('INSERT IGNORE INTO newsletter (email) VALUES (?)');
        $st->execute([$em]);
        flash_set('success', 'Subscribed. Welcome to the BD Hotel circle.');
    } else {
        flash_set('error', 'Please provide a valid email.');
    }
    header('Location: contact.php'); exit;
}

// Contact form handler
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify() && !isset($_GET['newsletter'])) {
    $name = trim((string)($_POST['name'] ?? ''));
    $em   = trim((string)($_POST['email'] ?? ''));
    $sub  = trim((string)($_POST['subject'] ?? ''));
    $msg  = trim((string)($_POST['message'] ?? ''));
    if ($name === '' || !filter_var($em, FILTER_VALIDATE_EMAIL) || $sub === '' || $msg === '') {
        flash_set('error', 'Please fill in all fields with a valid email.');
    } else {
        $st = db()->prepare('INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)');
        $st->execute([$name, $em, $sub, $msg]);
        flash_set('success', "Thank you. We'll be in touch shortly.");
    }
    header('Location: contact.php'); exit;
}

$__title = 'Contact · BD Hotel';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
$ok = flash_get('success'); $err = flash_get('error');
?>
<section class="pt-5 mt-5 py-luxe">
  <div class="container-luxe row g-5">
    <div class="col-lg-5" data-aos="fade-right">
      <div class="section-eyebrow">Concierge</div>
      <h1 class="font-serif-luxe display-3 mt-3">Write to us.<br>We reply, always.</h1>
      <div class="hairline my-4"></div>
      <ul class="list-unstyled">
        <li class="mb-3"><i class="fa-solid fa-location-dot me-2 text-gold"></i>42 Palm Avenue, Coastal District</li>
        <li class="mb-3"><i class="fa-solid fa-phone me-2 text-gold"></i>+1 (555) 020 9917</li>
        <li class="mb-3"><i class="fa-solid fa-envelope me-2 text-gold"></i>concierge@bdhotel.com</li>
      </ul>
      <div class="ratio ratio-16x9 mt-4">
        <iframe src="https://www.google.com/maps?q=coastal+resort&output=embed" style="border:0;"></iframe>
      </div>
    </div>
    <div class="col-lg-7" data-aos="fade-left">
      <?php if ($ok):  ?><div class="alert alert-success alert-luxe"><?= e($ok)  ?></div><?php endif; ?>
      <?php if ($err): ?><div class="alert alert-danger  alert-luxe"><?= e($err) ?></div><?php endif; ?>

      <form method="post" class="row g-4" data-validate>
        <?= csrf_field() ?>
        <div class="col-md-6"><div class="label-eyebrow mb-2">Name</div><input name="name" required class="bd-input"></div>
        <div class="col-md-6"><div class="label-eyebrow mb-2">Email</div><input name="email" type="email" required class="bd-input"></div>
        <div class="col-12"><div class="label-eyebrow mb-2">Subject</div><input name="subject" required class="bd-input"></div>
        <div class="col-12"><div class="label-eyebrow mb-2">Message</div><textarea name="message" rows="5" required class="bd-input" style="resize:none;"></textarea></div>
        <div class="col-12"><button class="btn bd-btn-primary ripple">Send Message</button></div>
      </form>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
