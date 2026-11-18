<?php
require __DIR__ . '/../app/bootstrap.php';
$title = 'Peta';
$active = 'peta';
$mapIncident = null;
$recap = [];
$incidentId = $_GET['kejadian'] ?? null;
foreach ($incidents as $incident) {
    $province = $incident['province'];
    $recap[$province] = ($recap[$province] ?? 0) + 1;
    if ($incident['id'] === $incidentId && !empty($incident['coordinates'])) {
        $mapIncident = $incident;
    }
}
arsort($recap);
$maxCount = max($recap ?: [1]);
require __DIR__ . '/../app/header.php';
?>
<section class="container-fluid map-page" id="peta-bulanan" aria-labelledby="map-title">
  <h1 class="visually-hidden" id="map-title">Peta Satelit Karhutla</h1>
  <div class="map-frame">
    <div id="map" aria-label="Peta interaktif kejadian karhutla"
      <?php if ($mapIncident): ?>
        data-lat="<?= e((string) $mapIncident['coordinates'][0]) ?>"
        data-lng="<?= e((string) $mapIncident['coordinates'][1]) ?>"
        data-place="<?= e($mapIncident['place']) ?>"
      <?php endif; ?>></div>
    <aside class="map-legend" aria-label="Legenda deteksi satelit">
      <strong>Grup deteksi</strong>
      <span><span class="hotspot-cluster" aria-hidden="true">12</span> Tanpa deteksi keyakinan tinggi</span>
      <span><span class="hotspot-cluster has-high-confidence" aria-hidden="true">12</span> Ada deteksi keyakinan tinggi</span>
      <small>Angka = jumlah deteksi dalam grup. Klik untuk zoom.</small>
      <strong>Titik tunggal</strong>
      <span><i class="hotspot-key high" aria-hidden="true"></i> Merah · keyakinan tinggi</span>
      <span><i class="hotspot-key nominal" aria-hidden="true"></i> Oranye · keyakinan nominal</span>
      <span><i class="hotspot-key low" aria-hidden="true"></i> Biru · keyakinan rendah</span>
      <span><i class="hotspot-key unknown" aria-hidden="true"></i> Abu-abu · tanpa kategori</span>
    </aside>
  </div>
  <div class="map-tools map-dashboard">
    <div class="month-picker">
      <label class="form-label" for="hotspot-months">Bulan pengamatan · 2026</label>
      <select class="form-select" id="hotspot-months" data-cutoff="2026-11-16" disabled>
        <?php for ($month = 1; $month <= 11; $month++): ?>
          <?php $value = sprintf('2026-%02d', $month); ?>
          <option value="<?= $value ?>"><?= e(explode(' ', tanggal($value . '-01'))[1]) ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <button id="map-focus" class="action light-outline" type="button" disabled>Kalimantan</button>
    <button id="map-reset" class="action light-outline" type="button" disabled>Seluruh Indonesia</button>
    <p id="hotspot-status" class="month-status mb-0" role="status">Memuat data titik panas terbaru…</p>
  </div>
  <aside class="map-alert mb-3" aria-label="Perhatian deteksi satelit">
    <strong>Perhatian · Deteksi berkeyakinan tinggi</strong>
    <p id="hotspot-alert" class="mb-2">Menunggu data satelit.</p>
    <label><input id="hotspot-high-only" class="form-check-input me-1" type="checkbox" disabled> Tampilkan hanya deteksi berkeyakinan tinggi</label>
  </aside>
  <div class="container px-0 map-note">
    <p>Klik lingkaran bernomor untuk memperbesar peta, atau klik titik untuk melihat waktu deteksi (UTC), koordinat, dan wilayah terdekat. Satelit bisa mendeteksi lokasi yang sama lebih dari sekali.</p>
  </div>
  <noscript><p>Aktifkan JavaScript untuk menggunakan peta interaktif.</p></noscript>
</section>
<section class="container section pt-3" aria-labelledby="judul-rekap">
  <div class="section-head">
    <h2 id="judul-rekap">Rekap data karhutla</h2>
    <a class="action outline" href="artikel.php">Baca laporan kejadian <span class="arrow-icon" aria-hidden="true">&#8594;</span></a>
  </div>
  <p id="cakupan-rekap">Kejadian pilihan di <?= count($recap) ?> provinsi, berdasarkan laporan yang terbit hingga <?= SNAPSHOT ?>. Angka di bawah dihitung dari arsip laporan SIJARLA, terpisah dari deteksi satelit di peta.</p>
  <div class="row g-3">
    <div class="col-lg-7">
      <div class="story mb-3">
        <h3>Laporan terbaru</h3>
        <ul class="list-group list-group-flush w-100">
          <?php foreach (array_slice($incidents, 0, 3) as $incident): ?>
          <li class="list-group-item px-0 d-flex flex-wrap justify-content-between gap-1">
            <a class="text-link" href="artikel.php?id=<?= e($incident['id']) ?>"><?= e($incident['place']) ?></a>
            <time datetime="<?= e($incident['date']) ?>"><?= e(tanggal($incident['date'])) ?></time>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <dl class="row g-3 mb-0">
        <div class="col-sm-4"><div class="story h-100 recap-metric">
          <dt>Perkiraan luas terbakar</dt><dd><?= e(luas(array_sum(array_column($incidents, 'area')))) ?></dd>
        </div></div>
        <div class="col-sm-4"><div class="story h-100 recap-metric">
          <dt>Kejadian dalam arsip</dt><dd><?= count($incidents) ?></dd>
        </div></div>
        <div class="col-sm-4"><div class="story h-100 recap-metric">
          <dt>Provinsi tercakup</dt><dd><?= count($recap) ?></dd>
        </div></div>
      </dl>
    </div>
    <div class="col-lg-5">
      <figure class="story h-100 mb-0">
        <figcaption class="recap-chart-title">Kejadian per provinsi</figcaption>
        <ul class="list-unstyled w-100 mb-0">
          <?php foreach ($recap as $province => $count): ?>
          <li class="recap-bar-row">
            <div class="d-flex justify-content-between gap-2"><span><?= e($province) ?></span><strong><?= $count ?></strong></div>
            <div class="recap-bar" aria-hidden="true"><span style="width: <?= (int) round(100 * $count / $maxCount) ?>%"></span></div>
          </li>
          <?php endforeach; ?>
        </ul>
      </figure>
    </div>
  </div>
</section>
<?php require __DIR__ . '/../app/footer.php'; ?>
