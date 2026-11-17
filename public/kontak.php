<?php
require __DIR__ . '/../app/bootstrap.php';
$title = 'Kontak';
$active = 'kontak';
$email = filter_var(getenv('SIJARLA_EMAIL') ?: getenv('JEJAK_BARA_EMAIL'), FILTER_VALIDATE_EMAIL);
require __DIR__ . '/../app/header.php';
?>
<section class="page-intro"><div class="container"><p class="eyebrow">KONTAK</p><h1>KONTAK <?= SITE ?>.</h1><p>Temukan kanal untuk melaporkan karhutla atau menghubungi tim situs.</p></div></section>
<section class="container section" id="pelaporan">
  <div class="section-head"><h2>Kanal resmi pelaporan karhutla</h2></div>
  <div class="row g-3">
    <div class="col-md-6"><article class="story h-100">
      <h3>Posko Karhutla Kementerian Kehutanan</h3>
      <p>Laporkan asap, titik api, atau dugaan kebakaran hutan dan lahan.</p>
      <ul class="list-unstyled mb-3">
        <li>WhatsApp <a class="text-link" href="https://wa.me/6281310035000">0813-1003-5000</a></li>
        <li>Telepon <a class="text-link" href="tel:+62215704618">021-5704618</a></li>
        <li>Email <a class="text-link text-break" href="mailto:posko.karhutla@kehutanan.go.id">posko.karhutla@kehutanan.go.id</a></li>
      </ul>
      <a class="text-link mt-auto" href="https://www.kehutanan.go.id/news/cek-langsung-si-pongi-akses-informasi-karhutla-terbaru-dan-transparan">Informasi Posko Karhutla ↗</a>
    </article></div>
    <div class="col-md-6"><article class="story h-100">
      <h3>Panggilan darurat</h3>
      <p class="mb-2"><a class="text-link" href="tel:110">110</a> · Call Center Polri, 24 jam dan bebas pulsa.</p>
      <p><a class="text-link" href="tel:112">112</a> · Layanan darurat terpadu, bebas pulsa di kabupaten atau kota yang sudah mengaktifkannya.</p>
      <div class="d-flex flex-wrap gap-3 mt-auto">
        <a class="text-link" href="https://tribratanews.polri.go.id/article/polri-ajak-masyarakat-tak-ragu-lapor-melalui-call-center-110">Informasi 110 ↗</a>
        <a class="text-link" href="https://layanan112.komdigi.go.id/tentang">Informasi 112 ↗</a>
      </div>
    </article></div>
    <div class="col-md-6"><article class="story h-100">
      <h3>Pengaduan Gakkum Kehutanan</h3>
      <p>Sampaikan dugaan pelanggaran hukum di bidang kehutanan melalui portal pengaduan resmi.</p>
      <ul class="list-unstyled mb-3">
        <li>Telepon <a class="text-link" href="tel:+622157902925">(021) 5790 2925</a></li>
        <li>Email <a class="text-link text-break" href="mailto:pengaduan.gakkum@kehutanan.go.id">pengaduan.gakkum@kehutanan.go.id</a></li>
      </ul>
      <a class="text-link mt-auto" href="https://pengaduan.gakkum.kehutanan.go.id/">Buka portal pengaduan ↗</a>
    </article></div>
    <div class="col-md-6"><article class="story h-100">
      <h3>BPBD atau Damkar setempat</h3>
      <p class="mb-0">Hubungi BPBD atau Dinas Pemadam Kebakaran di kabupaten atau kota tempat kejadian. Sampaikan lokasi, waktu, dan kondisi yang terlihat dari tempat aman.</p>
    </article></div>
  </div>
  <div class="row g-3 mt-4">
    <div class="col-md-6"><article class="story h-100">
      <h2>Tim <?= SITE ?></h2>
      <?php if ($email): ?>
        <p>Untuk koreksi, sertakan tautan artikel atau tanggal pengamatan pada peta.</p>
        <a class="text-link" href="mailto:<?= e($email) ?>"><?= e($email) ?> ↗</a>
      <?php else: ?>
        <p>Alamat email tim belum dipublikasikan.</p>
        <a class="text-link" href="tentang.php#metodologi">Sumber dan metodologi ↗</a>
      <?php endif; ?>
    </article></div>
  </div>
</section>
<?php require __DIR__ . '/../app/footer.php'; ?>
