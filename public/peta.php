<?php
require __DIR__ . '/../app/bootstrap.php';
$title = 'Peta GIS';
$active = 'peta';
$recap = [];
foreach ($incidents as $incident) {
    $province = $incident['province'];
    $recap[$province] = ($recap[$province] ?? 0) + 1;
}
arsort($recap);
$maxCount = max($recap ?: [1]);
require __DIR__ . '/../app/header.php';
?>
<section class="page-intro"><div class="container"><p class="eyebrow">PETA GIS / 2026</p><h1>PETA SATELIT KARHUTLA.</h1><p>Telusuri sebaran titik panas satelit per bulan di Indonesia dan sekitarnya.</p></div></section>
<section class="container section" id="peta-bulanan">
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
  <div class="map-frame">
    <div id="map" aria-label="Peta interaktif kejadian karhutla"></div>
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
  <div class="map-source d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2">
    <a class="text-link" href="https://worldview.earthdata.nasa.gov/?l=VIIRS_NOAA20_Thermal_Anomalies_375m_All&v=94,-12,142,8" target="_blank" rel="noopener">Buka Citra di NASA Worldview ↗</a>
    <span class="text-muted small">Satelit: VIIRS NOAA-20 (Sensor 375m) · Sumber Resmi Sipongi KLHK & NASA FIRMS</span>
  </div>

  <!-- Monthly Intelligence & BMKG FDRS Risk Benchmark Card -->
  <div class="story monthly-climate-card mt-3 mb-3 w-100" id="monthly-climate-card">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2 pb-2 border-bottom w-100">
      <div>
        <span class="badge-tag field me-1">ANALISIS IKLIM & RISIKO BULANAN</span>
        <strong id="bmkg-month-title" class="text-uppercase">November 2026</strong>
      </div>
      <div id="bmkg-badge-wrapper">
        <span class="status-pill active" id="bmkg-status-badge">BMKG FDRS: Sedang</span>
      </div>
    </div>
    
    <div class="row g-3 w-100 mt-0">
      <div class="col-md-7">
        <h3 class="h5 mb-1" id="monthly-summary-title">Transisi Musim Hujan & Titik Panas Pertanian</h3>
        <p class="text-muted small mb-2" id="monthly-summary-text">
          Memasuki pertengahan November, curah hujan meningkat di bagian barat Indonesia. Berdasarkan data Sipongi KLHK dan BMKG, titik panas satelit VIIRS/NOAA-20 terkonsentrasi pada pembersihan sisa panen di Jawa Timur dan lereng pegunungan di Sulawesi.
        </p>
        <div class="small">
          <strong class="text-dark">Fokus Sebaran Wilayah:</strong> 
          <span id="monthly-top-regions" class="text-muted">Jawa Timur, Sulawesi Selatan, Kalimantan Utara</span>
        </div>
      </div>
      
      <div class="col-md-5">
        <div class="bg-light p-2 border rounded-1 h-100">
          <span class="d-block text-muted fw-bold small text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.04em;">Portal Rujukan Resmi Data Nyata:</span>
          <ul class="list-unstyled mb-0 small d-flex flex-column gap-1">
            <li><a class="text-link" href="https://sipongi.gakkum.kehutanan.go.id/" target="_blank" rel="noopener">Sipongi KLHK (Data Satelit & Luas) ↗</a></li>
            <li><a class="text-link" href="https://www.bmkg.go.id/cuaca/karhutla" target="_blank" rel="noopener">BMKG Karhutla (Indeks FDRS/FWI) ↗</a></li>
            <li><a class="text-link" href="https://pantaukarhutla.com/" target="_blank" rel="noopener">Pantau Karhutla (Kawasan Gambut) ↗</a></li>
            <li><a class="text-link" href="https://karhutlawatch.web.id/" target="_blank" rel="noopener">Karhutla Watch (Rekap Multi-Sensor) ↗</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <p class="map-note">Klik lingkaran angka untuk memperbesar peta. Klik titik untuk melihat waktu deteksi (UTC), koordinat, dan wilayah terdekat. Jumlah menunjukkan deteksi satelit, yang bisa berulang di lokasi yang sama.</p>
  <p class="map-note">Peta ini menunjukkan lokasi deteksi panas. Untuk mengikuti arah kepulan di luar lokasi kebakaran, baca <a class="text-link" href="artikel.php?id=asap-filipina-2026">jejak asap Kalimantan ke Filipina ↗</a>.</p>
  <noscript><p>Aktifkan JavaScript untuk menggunakan peta interaktif.</p></noscript>
</section>

<script>
const monthlyBenchmarks = {
  "2026-11": {
    title: "Transisi Musim Hujan & Titik Panas Pertanian",
    statusText: "BMKG FDRS: Sedang",
    statusClass: "active",
    summary: "Memasuki pertengahan November, curah hujan meningkat di bagian barat Indonesia. Berdasarkan pantauan Sipongi KLHK dan BMKG, titik panas satelit VIIRS/NOAA-20 terkonsentrasi pada pembersihan sisa panen di Jawa Timur dan lereng pegunungan Sulawesi.",
    regions: "Jawa Timur, Sulawesi Selatan, Kalimantan Utara"
  },
  "2026-10": {
    title: "Fase Akhir Puncak Kemarau & Pendinginan Gambut",
    statusText: "BMKG FDRS: Tinggi",
    statusClass: "active",
    summary: "Satgas Gabungan Manggala Agni KLHK, BPBD, dan TNI/Polri memfokuskan patroli mandiri dan pendinginan area gambut (ground cooling) di Ogan Komering Ilir dan Kubu Raya.",
    regions: "Kalimantan Barat, Sumatera Selatan, Riau, Kalimantan Tengah"
  },
  "2026-09": {
    title: "Puncak Kemarau, Angin Monsun & Asap Lintas Batas",
    statusText: "BMKG FDRS: Sangat Tinggi / Ekstrem",
    statusClass: "active",
    summary: "Puncak musim kemarau diiringi angin monsun tenggara yang kuat. Satelit NOAA-20 dan Himawari-9 merekam kepulan kabut asap tebal melintasi Selat Malaka ke Singapura (PSI mencapai 131) dan Filipina Barat.",
    regions: "Kalimantan Barat, Kalimantan Tengah, Sumatera Selatan, Jambi"
  },
  "2026-08": {
    title: "Kondisi Kering Berkepanjangan & Siaga Darurat Level 3",
    statusText: "BMKG FDRS: Tinggi - Sangat Tinggi",
    statusClass: "active",
    summary: "ASMC mengaktifkan status Peringatan Siaga Darurat Karhutla Level 3 untuk kawasan ASEAN selatan menyusul lonjakan titik panas gambut di Kalimantan Barat dan Riau.",
    regions: "Kalimantan Barat, Riau, Jambi, Sumatera Selatan"
  },
  "2026-07": {
    title: "Kemarau Meluas di Bagian Selatan Ekuator",
    statusText: "BMKG FDRS: Tinggi",
    statusClass: "active",
    summary: "Kawasan sabana di NTT, NTB, dan Jawa Timur mencatat peningkatan kebakaran padang rumput, sementara Riau siaga kebakaran gambut pesisir.",
    regions: "Nusa Tenggara Timur, Jawa Timur, Riau, Kalimantan Selatan"
  },
  "2026-06": {
    title: "Awal Peralihan Musim Kemarau",
    statusText: "BMKG FDRS: Sedang - Tinggi",
    statusClass: "active",
    summary: "Pengecekan sekat kanal (canal blocking) di area gambut mulai digencarkan oleh Satgas Karhutla dan Masyarakat Peduli Api.",
    regions: "Riau, Sumatera Utara, Nusa Tenggara Barat"
  },
  "2026-05": {
    title: "Pancaroba Menuju Musim Kemarau",
    statusText: "BMKG FDRS: Sedang",
    statusClass: "resolved",
    summary: "Curah hujan bervariasi. Beberapa wilayah timur mulai mencatat hari tanpa hujan berturut-turut. Titik panas satelit terpantau rendah dan terkendali.",
    regions: "Nusa Tenggara Timur, Jawa Timur, Aceh"
  },
  "2026-04": {
    title: "Fase Akhir Musim Hujan",
    statusText: "BMKG FDRS: Aman - Rendah",
    statusClass: "resolved",
    summary: "Kelembapan tanah dan gambut masih tinggi di sebagian besar pulau besar Indonesia, menekan potensi penyebaran api bawah tanah.",
    regions: "Riau, Kalimantan Barat"
  },
  "2026-03": {
    title: "Periode Kering Singkat Pesisir Timur Riau",
    statusText: "BMKG FDRS: Aman - Rendah",
    statusClass: "resolved",
    summary: "Fase kering musiman pertama di pesisir Riau. Satelit mendeteksi beberapa titik panas lokal sebelum curah hujan kembali normal.",
    regions: "Riau, Aceh, Sumatera Utara"
  },
  "2026-02": {
    title: "Kewaspadaan Karhutla Pesisir",
    statusText: "BMKG FDRS: Sedang",
    statusClass: "resolved",
    summary: "Pembersihan lahan perkebunan lokal di pesisir utara Riau terpantau oleh patroli udara dan segera diatasi oleh regu darat.",
    regions: "Riau, Kalimantan Barat"
  },
  "2026-01": {
    title: "Puncak Musim Hujan",
    statusText: "BMKG FDRS: Aman / Sangat Rendah",
    statusClass: "resolved",
    summary: "Puncak musim hujan di seluruh wilayah kepulauan Indonesia. Ancaman karhutla berada pada tingkat paling rendah sepanjang tahun.",
    regions: "Sulawesi Tenggara, Sumatera Utara"
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const monthSelect = document.getElementById('hotspot-months');
  if (!monthSelect) return;

  function updateMonthlyBench(val) {
    const data = monthlyBenchmarks[val];
    if (!data) return;
    const optText = monthSelect.options[monthSelect.selectedIndex]?.text || val;
    const titleElem = document.getElementById('bmkg-month-title');
    if (titleElem) titleElem.textContent = optText + ' 2026';
    const sumTitle = document.getElementById('monthly-summary-title');
    if (sumTitle) sumTitle.textContent = data.title;
    const sumText = document.getElementById('monthly-summary-text');
    if (sumText) sumText.textContent = data.summary;
    const topReg = document.getElementById('monthly-top-regions');
    if (topReg) topReg.textContent = data.regions;
    const badge = document.getElementById('bmkg-status-badge');
    if (badge) {
      badge.textContent = data.statusText;
      badge.className = 'status-pill ' + data.statusClass;
    }
  }

  monthSelect.addEventListener('change', (e) => {
    updateMonthlyBench(e.target.value);
  });
});
</script>
<section class="container section pt-0" aria-labelledby="judul-rekap">
  <div class="section-head">
    <h2 id="judul-rekap">Rekap data karhutla</h2>
    <a class="text-link" href="artikel.php">Baca laporan kejadian ↗</a>
  </div>
  <p id="cakupan-rekap">Kejadian pilihan di <?= count($recap) ?> provinsi, berdasarkan laporan yang terbit hingga <?= SNAPSHOT ?>. Luas di bawah merupakan perkiraan dari kasus dalam arsip SIJARLA.</p>
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
