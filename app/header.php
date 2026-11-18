<!doctype html>
<html lang="id" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#151515">
<meta name="description" content="Jelajahi artikel dan peta satelit kebakaran hutan dan lahan di Indonesia pada 2026.">
<title><?= e($title) ?> · <?= SITE ?></title>
<link rel="icon" href="assets/img/brand.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css" rel="stylesheet">
<?php if ($active === 'peta'): ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous">
<?php endif; ?>
<link rel="stylesheet" href="assets/css/site.css?v=map-legend-static-info">
</head>
<body>
<a class="skip-link" href="#konten">Lewati ke konten</a>
<header class="site-header">
  <nav class="container navbar navbar-expand-lg navbar-dark" aria-label="Navigasi utama">
    <a class="navbar-brand brand" href="index.php"><span><?= SITE ?></span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Buka menu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav mx-lg-auto gap-1 py-2 py-lg-0">
        <?php foreach (['index' => 'Beranda', 'tentang' => 'Tentang', 'artikel' => 'Artikel', 'peta' => 'Peta GIS', 'kontak' => 'Kontak'] as $page => $label): ?>
        <li class="nav-item"><a class="nav-link<?= $active === $page ? ' active' : '' ?>" href="<?= $page ?>.php" <?= $active === $page ? 'aria-current="page"' : '' ?>><?= $label ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </nav>
</header>
<main id="konten">
