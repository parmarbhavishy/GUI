<?php
/**
 * BD Hotel - Global config, session, CSRF, auth & helper functions
 */
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => false,   // set true if serving over HTTPS
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/../database/db_connect.php';

/**
 * Ensure the seeded admin password matches the plaintext we advertise.
 * If the stored hash is a placeholder (or verify fails), we replace it once.
 */
function ensure_admin_seed(): void {
    try {
        $st = db()->prepare('SELECT id, password_hash FROM users WHERE email = ? LIMIT 1');
        $st->execute(['admin@bdhotel.com']);
        $row = $st->fetch();
        if ($row && !password_verify('Admin@123', $row['password_hash'])) {
            $upd = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
            $upd->execute([password_hash('Admin@123', PASSWORD_BCRYPT), $row['id']]);
        }
    } catch (Throwable) { /* silent - not fatal */ }
}
ensure_admin_seed();

// ------------------------ CSRF -------------------------------------
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}
function csrf_verify(): bool {
    $t = $_POST['csrf_token'] ?? '';
    return is_string($t) && hash_equals($_SESSION['csrf_token'] ?? '', $t);
}

// ------------------------ AUTH -------------------------------------
function current_user(): ?array {
    if (empty($_SESSION['user_id'])) return null;
    $st = db()->prepare('SELECT id,name,email,phone,role,created_at FROM users WHERE id = ? LIMIT 1');
    $st->execute([$_SESSION['user_id']]);
    $u = $st->fetch();
    return $u ?: null;
}
function require_login(string $redirect = '/BD-HOTEL/login.php'): array {
    $u = current_user();
    if (!$u) { header('Location: ' . $redirect); exit; }
    return $u;
}
function require_admin(string $redirect = '/BD-HOTEL/admin/login.php'): array {
    $u = current_user();
    if (!$u || $u['role'] !== 'admin') { header('Location: ' . $redirect); exit; }
    return $u;
}
function login_user(int $userId): void {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
}
function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

// ------------------------ HELPERS ----------------------------------
function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function old(string $key, string $default = ''): string { return e($_SESSION['old'][$key] ?? $default); }
function flash_set(string $key, string $msg): void { $_SESSION['flash'][$key] = $msg; }
function flash_get(string $key): ?string {
    if (isset($_SESSION['flash'][$key])) { $m = $_SESSION['flash'][$key]; unset($_SESSION['flash'][$key]); return $m; }
    return null;
}
function money(float $n): string { return '₹ ' . number_format($n, 0); }
function nights_between(string $inDate, string $outDate): int {
    try {
        $d1 = new DateTimeImmutable($inDate);
        $d2 = new DateTimeImmutable($outDate);
        return max(1, (int)$d1->diff($d2)->format('%a'));
    } catch (Throwable) { return 1; }
}
function is_room_available(int $roomId, string $in, string $out, ?int $excludeBookingId = null): bool {
    $sql = 'SELECT COUNT(*) AS c FROM bookings
            WHERE room_id = ? AND status <> "cancelled"
              AND NOT (check_out <= ? OR check_in >= ?)';
    $params = [$roomId, $in, $out];
    if ($excludeBookingId) { $sql .= ' AND id <> ?'; $params[] = $excludeBookingId; }
    $st = db()->prepare($sql);
    $st->execute($params);
    return ((int)$st->fetch()['c']) === 0;
}
function base_url(): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Project root on disk (parent of /includes)
    $projRoot = str_replace('\\', '/', dirname(__DIR__));
    $docRoot  = str_replace('\\', '/', rtrim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''), "/\\"));

    // Compute URL path from disk paths (works no matter how deep the folder is)
    $path = '';
    if ($docRoot !== '' && str_starts_with($projRoot, $docRoot)) {
        $path = substr($projRoot, strlen($docRoot));
    } else {
        // Fallback: derive from the current request URI
        $script = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
        $path   = rtrim(dirname($script), '/');
        // strip a trailing /admin if we're inside the admin folder
        if (str_ends_with($path, '/admin')) $path = substr($path, 0, -6);
    }
    return $scheme . '://' . $host . $path;
}