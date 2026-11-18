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
    <h1>SAAT HUTAN<br>MENJADI <em>BARA.</em></h1>
    <p class="hero-copy">Asap karhutla Indonesia bisa terbawa angin hingga ke negara tetangga. Telusuri titik panasnya dan baca dampaknya bagi kualitas udara di Asia Tenggara.</p>
    <div class="actions"><a class="action primary" href="peta.php">Jelajahi peta <span aria-hidden="true">↗</span></a><a class="action outline" href="artikel.php">Baca laporan <span aria-hidden="true">→</span></a></div>
  </div>
</section>
<aside class="info-ticker" aria-label="Informasi SIJARLA">
  <input type="checkbox" class="btn-check" id="pause-ticker" autocomplete="off">
  <div class="ticker-window">
    <div class="ticker-track">
      <?php for ($copy = 0; $copy < 2; $copy++): ?>
      <ul class="ticker-items list-unstyled mb-0"<?= $copy ? ' aria-hidden="true"' : '' ?>>
        <li>Data titik panas VIIRS NOAA-20 tersedia hingga <?= SNAPSHOT ?></li>
        <li>Pilih bulan di Peta GIS untuk melihat sebaran titik panas</li>
        <li>Laporkan asap atau titik api melalui kanal resmi di halaman Kontak</li>
      </ul>
      <?php endfor; ?>
    </div>
  </div>
  <label class="ticker-pause" for="pause-ticker">Jeda teks</label>
</aside>
<section class="container section">
  <article class="story regional-feature">
    <div>
      <p class="eyebrow">SINGAPURA · 15 SEPTEMBER 2026</p>
      <h2>Asap Kalimantan dan Sumatra mencapai Singapura.</h2>
      <p class="mb-0">Badan Lingkungan Nasional Singapura mencatat asap terbawa angin dari timur-tenggara dan tenggara. Laporan hari itu mencatat PSI 24 jam di kisaran 92–131, dari kategori sedang hingga tidak sehat.</p>
    </div>
    <a class="action primary" href="artikel.php?id=asap-singapura-2026">Baca kasus Singapura ↗</a>
  </article>
</section>
<section class="container section pt-0">
  <div class="section-head"><div><p class="eyebrow">DARI LAPANGAN</p><h2>Laporan karhutla</h2></div><a class="text-link" href="artikel.php">Semua laporan ↗</a></div>
  <?php $stories = array_slice($incidents, 0, 3); require __DIR__ . '/../app/cards.php'; ?>
</section>
<?php require __DIR__ . '/../app/footer.php'; ?>
