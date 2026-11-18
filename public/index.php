<?php
require __DIR__ . '/../app/bootstrap.php';
$title = 'Beranda';
$active = 'index';
require __DIR__ . '/../app/header.php';
?>
<section class="hero">
  <video id="hero-video" class="hero-video" muted loop playsinline preload="none"
    data-src="assets/video/hero-karhutla.mp4" aria-hidden="true"></video>
  <div class="container hero-content">
    <h1>KETIKA API MENYALA, DUNIA MERASAKAN <em>DAMPAKNYA.</em></h1>
    <p class="hero-copy">Kebakaran hutan dan lahan membawa ancaman yang melampaui batas wilayah. Telusuri persebaran titik panas di Indonesia, kenali dampak kabut asap lintas negara, dan pahami ancamannya bagi lingkungan serta kehidupan.</p>
    <div class="actions"><a class="action primary" href="peta.php">Jelajahi peta <span aria-hidden="true">↗</span></a><a class="action outline" href="artikel.php">Baca laporan <span aria-hidden="true">→</span></a></div>
  </div>
</section>
<aside class="info-ticker" aria-label="Informasi SIJARLA">
  <ul class="container ticker-items list-unstyled mb-0" role="list">
    <li>Data titik panas VIIRS NOAA-20 tersedia hingga <?= SNAPSHOT ?></li>
    <li>Pilih bulan di Peta GIS untuk melihat sebaran titik panas</li>
    <li>Laporkan asap atau titik api melalui kanal resmi di halaman Kontak</li>
  </ul>
</aside>
<section class="container section report-carousel" aria-labelledby="report-heading">
  <div class="section-head">
    <div><p class="eyebrow">DARI LAPANGAN</p><h2 id="report-heading">Laporan karhutla</h2></div>
  </div>
  <?php $stories = array_slice($incidents, 0, 9); require __DIR__ . '/../app/cards.php'; ?>
</section>
<?php require __DIR__ . '/../app/footer.php'; ?>
