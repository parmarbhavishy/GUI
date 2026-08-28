<?php
require_once __DIR__ . '/../includes/config.php';
require_admin();
$rows = db()->query('SELECT id,name,email,phone,created_at FROM users WHERE role="user" ORDER BY created_at DESC')->fetchAll();
$__title = 'Customers · Admin';
require __DIR__ . '/_layout.php';
?>
<h1 class="font-serif-luxe display-5 mb-4">Customers</h1>
<div class="bg-white border rounded-2 overflow-hidden">
  <table class="table table-borderless mb-0 small align-middle">
    <thead class="bg-light"><tr><th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Phone</th><th class="p-3">Joined</th></tr></thead>
    <tbody>
      <?php foreach ($rows as $u): ?>
        <tr class="border-top">
          <td class="p-3"><?= e($u['name']) ?></td>
          <td class="p-3 text-muted"><?= e($u['email']) ?></td>
          <td class="p-3"><?= e($u['phone'] ?: '—') ?></td>
          <td class="p-3 text-muted"><?= e(date('d M Y', strtotime($u['created_at']))) ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="4" class="p-4 text-center text-muted">No customers yet.</td></tr><?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/_footer.php'; ?>
