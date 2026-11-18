<?php
require __DIR__ . '/../app/bootstrap.php';
$title = 'Peta GIS';
$active = 'peta';
$mapIncident = null;
$incidentId = $_GET['kejadian'] ?? null;
foreach ($incidents as $incident) {
    if ($incident['id'] === $incidentId && !empty($incident['coordinates'])) {
        $mapIncident = $incident;
    }
}
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
    <aside class="map-legend" aria-label="Legenda warna titik panas">
      <strong>Legenda warna</strong>
      <small>Keyakinan deteksi VIIRS NOAA-20</small>
      <span><i class="hotspot-key high" aria-hidden="true"></i> Merah · Tinggi</span>
      <span><i class="hotspot-key nominal" aria-hidden="true"></i> Oranye · Nominal</span>
      <span><i class="hotspot-key low" aria-hidden="true"></i> Biru · Rendah</span>
      <span><i class="hotspot-key unknown" aria-hidden="true"></i> Abu-abu · Tanpa kategori</span>
      <span><i class="hotspot-key grouped" aria-hidden="true"></i> Lingkaran angka · Grup deteksi</span>
      <span><i class="hotspot-key high" aria-hidden="true"></i> Cincin merah · Grup memuat deteksi tinggi</span>
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
    <a class="action outline" href="artikel.php">Baca laporan kejadian <span class="arrow-icon" aria-hidden="true">&#8594;</span></a>
    <p id="hotspot-status" class="month-status mb-0" role="status">Memuat data titik panas terbaru…</p>
  </div>
  <aside class="map-alert mb-3" aria-label="Perhatian deteksi satelit">
    <strong>Perhatian · Deteksi berkeyakinan tinggi</strong>
    <p id="hotspot-alert" class="mb-2">Menunggu data satelit.</p>
    <label><input id="hotspot-high-only" class="form-check-input me-1" type="checkbox" disabled> Tampilkan hanya deteksi berkeyakinan tinggi</label>
  </aside>
  <noscript><p>Aktifkan JavaScript untuk menggunakan peta interaktif.</p></noscript>
</section>
<?php require __DIR__ . '/../app/footer.php'; ?>
