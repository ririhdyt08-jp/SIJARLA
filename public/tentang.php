<?php
require __DIR__ . '/../app/bootstrap.php';
$title = 'Tentang';
$active = 'tentang';
require __DIR__ . '/../app/header.php';
?>
<section class="page-intro"><div class="container"><p class="eyebrow">TENTANG <?= SITE ?></p><h1><?= SITE_FULL ?></h1><p>Portal edukasi independen yang mengangkat karhutla Indonesia sebagai isu lingkungan global.</p></div></section>
<section class="container section reading">
  <section class="mt-0">
    <h2>Bagaimana karhutla bermula?</h2>
    <p>Kemarau membuat vegetasi dan tanah lebih kering, sehingga api lebih mudah menyebar setelah menyala. Pembakaran sisa panen, sampah, atau pembukaan lahan dapat menjadi pemicu. Pada lahan gambut, bara dapat bertahan di bawah permukaan sehingga petugas perlu mengecek kembali area setelah api di permukaan padam.</p>
  </section>
  <section>
    <h2>Bagaimana asap melintasi negara?</h2>
    <p>Angin dapat membawa partikel asap jauh dari lokasi kebakaran. Pada Agustus–September 2026, pengamatan regional mencatat asap dari kawasan Kalimantan atau Sumatra bergerak ke Singapura, Malaysia, Brunei, dan Filipina bagian barat. Arah dan jangkauan kepulan berubah mengikuti cuaca. Pengukuran kualitas udara setempat seperti PM2.5 membantu menilai dampak di permukaan.</p>
  </section>
  <section>
    <h2>Dampak pada iklim dan habitat</h2>
    <p>Kebakaran melepaskan gas rumah kaca, termasuk karbon dioksida. Pada lahan gambut, kebakaran juga dapat melepaskan karbon yang tersimpan di tanah. Di lokasi kebakaran, hilangnya vegetasi dapat mengurangi tempat berlindung dan sumber pakan satwa. Asap lintas negara, emisi, dan kerusakan habitat membuat dampak karhutla relevan di tingkat regional dan global.</p>
  </section>
  <section id="metodologi">
    <h2>Sumber dan metodologi</h2>
    <p>Arsip kejadian Indonesia memuat <?= count($incidents) ?> kasus pilihan dari laporan BNPB hingga <?= SNAPSHOT ?>. Artikel lintas negara menggunakan pengamatan ASEAN Specialised Meteorological Centre (ASMC) dan Badan Lingkungan Nasional Singapura (NEA). Setiap artikel mencantumkan sumbernya.</p>
    <p>Peta GIS memuat deteksi panas VIIRS NOAA-20 dari NASA FIRMS/GIBS per bulan pengamatan UTC. Satu lokasi dapat terdeteksi beberapa kali. Untuk menelusuri arah asap, artikel menggunakan citra kepulan dan pengamatan cuaca dari sumber regional.</p>
    <div class="actions">
      <a class="action primary" href="peta.php">Buka peta titik panas ↗</a>
      <a class="action light-outline" href="kontak.php#pelaporan">Kanal pelaporan karhutla ↗</a>
    </div>
  </section>
</section>
<?php require __DIR__ . '/../app/footer.php'; ?>
