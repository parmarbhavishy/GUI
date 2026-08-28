<?php
require_once __DIR__ . '/config.php';
$__user   = current_user();
$__base   = base_url();
$__title  = $__title ?? 'BD Hotel · Quiet Luxury';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($__title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BD Hotel — a quiet address of luxury &amp; ceremony.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Swiper -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet">

    <!-- App CSS -->
    <link href="<?= $__base ?>/css/style.css" rel="stylesheet">
    <link href="<?= $__base ?>/css/animation.css" rel="stylesheet">
    <link href="<?= $__base ?>/css/responsive.css" rel="stylesheet">
</head>
<body>
<div id="page-loader"><div class="loader"></div></div>
<div id="scroll-progress"></div>
