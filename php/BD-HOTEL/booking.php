<?php
require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: rooms.php'); exit; }
if (!csrf_verify()) { flash_set('error', 'Session expired. Please try again.'); header('Location: rooms.php'); exit; }

$roomId = (int)($_POST['room_id'] ?? 0);
$name   = trim((string)($_POST['full_name'] ?? ''));
$email  = trim((string)($_POST['email'] ?? ''));
$mobile = trim((string)($_POST['mobile'] ?? ''));
$addr   = trim((string)($_POST['address'] ?? ''));
$in     = (string)($_POST['check_in'] ?? '');
$out    = (string)($_POST['check_out'] ?? '');
$ad     = max(1, (int)($_POST['adults']   ?? 1));
$ch     = max(0, (int)($_POST['children'] ?? 0));
$pay    = (string)($_POST['payment_method'] ?? 'Cash');
$req    = trim((string)($_POST['special_request'] ?? ''));

// Validation
$errors = [];
if ($roomId <= 0)              $errors[] = 'Invalid room.';
if ($name === '')              $errors[] = 'Full name is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
if ($mobile === '')            $errors[] = 'Mobile number is required.';
if (!$in || !$out)             $errors[] = 'Both dates are required.';
if ($in && $out && strtotime($out) <= strtotime($in)) $errors[] = 'Check-out must be after check-in.';
if (!in_array($pay, ['Cash','Card','UPI','Bank Transfer'], true)) $errors[] = 'Invalid payment method.';

$room = null;
if (!$errors) {
    $st = db()->prepare('SELECT * FROM rooms WHERE id = ?');
    $st->execute([$roomId]);
    $room = $st->fetch();
    if (!$room)                                    $errors[] = 'Room not found.';
    elseif (!is_room_available($roomId, $in, $out)) $errors[] = 'Selected dates are not available for this room. Please choose different dates.';
}

if ($errors) {
    flash_set('error', implode(' ', $errors));
    header('Location: rooms.php?id=' . $roomId);
    exit;
}

$nights = nights_between($in, $out);
$total  = round((float)$room['price'] * $nights, 2);
$userId = current_user()['id'] ?? null;

$ins = db()->prepare('INSERT INTO bookings
    (user_id, room_id, full_name, email, mobile, address, check_in, check_out, adults, children, payment_method, special_request, total_price, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "pending")');
$ins->execute([$userId, $roomId, $name, $email, $mobile, $addr, $in, $out, $ad, $ch, $pay, $req, $total]);
$bookingId = (int)db()->lastInsertId();

// Redirect to invoice page
header('Location: invoice.php?id=' . $bookingId);
exit;
