<?php
require __DIR__ . '/../app/bootstrap.php';

$id = $_GET['id'] ?? null;
$regionalArticles = json_decode(file_get_contents(__DIR__ . '/../data/regional-articles.json'), true, 512, JSON_THROW_ON_ERROR);
$details = json_decode(file_get_contents(__DIR__ . '/../data/report-details.json'), true, 512, JSON_THROW_ON_ERROR);
$stories = array_merge($incidents, $regionalArticles);

$selected = null;
foreach ($stories as $item) {
    if ($item['id'] === $id) {
        $selected = $item;
        break;
    }
}

if ($id !== null && $selected === null) {
    http_response_code(404);
}

$report = $selected ? ($details[$selected['id']] ?? $selected) : null;
$title = $report['title'] ?? ($id === null ? 'Arsip Laporan Karhutla' : 'Laporan Tidak Ditemukan');
$active = 'artikel';

$totalReports = count($stories);
$totalRegional = count($regionalArticles);
$totalField = count($incidents);
$totalArea = array_sum(array_column($incidents, 'area'));

require __DIR__ . '/../app/header.php';
?>

<?php if ($selected): ?>
  <?php
    $isRegional = isset($report['sections']);
    $itemTitle = $report['title'] ?? ($selected['title'] ?? 'Karhutla di ' . ($selected['place'] ?? $selected['region']));
    $itemRegion = $selected['province'] ?? $selected['region'];
    $categoryTag = $isRegional ? 'ASAP LINTAS BATAS &middot; REGIONAL ASEAN' : 'KARHUTLA LAPANGAN &middot; TERVERIFIKASI';
    $monthParam = substr($selected['date'], 0, 7);
    $mapQuery = ['bulan' => $monthParam];
    $mapAnchor = '#peta-bulanan';
    if (!empty($selected['coordinates'])) {
        $mapQuery['kejadian'] = $selected['id'];
        $mapAnchor = '#map';
    }
    $mapUrl = 'peta.php?' . http_build_query($mapQuery) . $mapAnchor;
  ?>
  <div class="reading-progress-track" aria-hidden="true">
    <div class="reading-progress-bar" id="reading-bar"></div>
  </div>

  <section class="page-intro">
    <div class="container">
      <nav class="article-breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Beranda</a>
        <span aria-hidden="true">/</span>
        <a href="artikel.php">Artikel</a>
        <span aria-hidden="true">/</span>
        <span class="text-truncate text-white-50" style="max-width: 280px;"><?= e($itemTitle) ?></span>
      </nav>
      
      <p class="eyebrow"><span class="signal"></span> <?= $categoryTag ?></p>
      <h1><?= e($itemTitle) ?></h1>
      
      <div class="article-byline">
        <span><?= icon('calendar') ?> <time datetime="<?= e($selected['date']) ?>"><?= e(tanggal($selected['date'])) ?></time></span>
        <span>&middot;</span>
        <span><?= icon('pin') ?> <?= e($itemRegion) ?></span>
        <span>&middot;</span>
        <span><?= icon('clock') ?> &plusmn; 2 Menit Baca</span>
      </div>
    </div>
  </section>

  <main class="container section pt-4">
    <div class="row g-4 g-lg-5">
      <!-- Main Content Column -->
      <div class="col-lg-8">
        <div class="mb-4">
          <a class="action light-outline btn-sm d-inline-flex align-items-center gap-1" href="artikel.php">
            <span aria-hidden="true">&#8592;</span> Kembali ke Semua Artikel
          </a>
        </div>

        <!-- Executive Summary Box -->
        <div class="article-lead-box" role="region" aria-label="Ringkasan Laporan">
          <strong>Ringkasan Eksekutif:</strong> <?= e($selected['summary']) ?>
        </div>

        <?php if ($isRegional): ?>
          <!-- Regional Transboundary Article Content -->
          <div class="regional-content-sections">
            <?php foreach ($report['sections'] as $heading => $paragraph): ?>
              <section class="story-section-card mb-4">
                <h2 class="section-card-title"><?= e($heading) ?></h2>
                <p class="section-card-text"><?= e($paragraph) ?></p>
              </section>
            <?php endforeach; ?>
          </div>

        <?php else: ?>
          <!-- Field Incident Content with Structured Timeline -->
          <div class="incident-timeline-wrapper">
            <h2 class="h3 mb-3">Laporan Penanganan Kejadian</h2>
            
            <div class="incident-timeline">
              <div class="timeline-item active">
                <div class="timeline-node" aria-hidden="true"></div>
                <div class="timeline-content">
                  <div class="timeline-title">
                    <span>1. Kronologi &amp; Pemicu</span>
                    <span class="timeline-badge">AWAL KEJADIAN</span>
                  </div>
                  <p class="mb-0"><?= e($report['chronology']) ?></p>
                </div>
              </div>

              <div class="timeline-item">
                <div class="timeline-node" aria-hidden="true"></div>
                <div class="timeline-content">
                  <div class="timeline-title">
                    <span>2. Dampak Area &amp; Kerusakan</span>
                    <span class="timeline-badge">DAMPAK FISIK</span>
                  </div>
                  <p class="mb-2"><?= e($report['impact']) ?></p>
                  <?php if (!empty($selected['area'])): ?>
                    <div class="stat-highlight-box">
                      <span class="stat-highlight-val"><?= e(luas($selected['area'])) ?></span>
                      <span class="stat-highlight-lbl">Estimasi Luas Lahan Terbakar (&plusmn;<?= number_format($selected['area'] * 10000, 0, ',', '.') ?> m&sup2;)</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <div class="timeline-item">
                <div class="timeline-node" aria-hidden="true"></div>
                <div class="timeline-content">
                  <div class="timeline-title">
                    <span>3. Penanganan Tim &amp; Status Akhir</span>
                    <span class="timeline-badge">RESPONS LAPANGAN</span>
                  </div>
                  <p class="mb-0"><?= e($report['response']) ?></p>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- Action Toolbar -->
        <div class="article-action-toolbar mt-5 p-4">
          <h3 class="h4 mb-3">Tindakan Lanjutan</h3>
          <div class="actions">
            <a class="action primary" href="<?= e($mapUrl) ?>">
              Lihat Titik Panas di Peta <span class="arrow-icon" aria-hidden="true">&#8599;</span>
            </a>
            <button class="action light-outline" type="button" id="copy-link-btn" onclick="copyCurrentUrl()">
              Salin Tautan <?= icon('copy') ?>
            </button>
          </div>
          <div id="copy-toast" class="copy-feedback-toast" role="status" aria-live="polite"></div>
        </div>
      </div>

      <!-- Sidebar Column -->
      <div class="col-lg-4">
        <aside class="incident-factsheet" aria-label="Lembar Fakta Kejadian">
          <div class="factsheet-title">
            <span>DATA KEJADIAN</span>
            <span class="status-pill resolved">TERVERIFIKASI</span>
          </div>

          <table class="factsheet-table">
            <tbody>
              <tr>
                <th scope="row">Wilayah</th>
                <td><?= e($selected['place'] ?? $selected['region']) ?></td>
              </tr>
              <tr>
                <th scope="row">Provinsi / Negara</th>
                <td><?= e($itemRegion) ?></td>
              </tr>
              <tr>
                <th scope="row">Tanggal Laporan</th>
                <td><?= e(tanggal($selected['date'])) ?></td>
              </tr>
              <?php if (!empty($selected['area'])): ?>
                <tr>
                  <th scope="row">Taksiran Luas</th>
                  <td><span class="text-warning fw-bold"><?= e(luas($selected['area'])) ?></span></td>
                </tr>
              <?php endif; ?>
              <?php if (!empty($selected['coordinates'])): ?>
                <tr>
                  <th scope="row">Koordinat GIS</th>
                  <td><?= e($selected['coordinates'][0]) ?>&deg;, <?= e($selected['coordinates'][1]) ?>&deg;</td>
                </tr>
              <?php endif; ?>
              <tr>
                <th scope="row">Satelit Pantau</th>
                <td>VIIRS NOAA-20 (NASA)</td>
              </tr>
              <tr>
                <th scope="row">Status Operasi</th>
                <td><span class="status-pill resolved">Selesai / Terkendali</span></td>
              </tr>
            </tbody>
          </table>

          <?php if (!empty($selected['coordinates'])): ?>
            <div class="factsheet-cta mt-3">
              <a class="action outline w-100" href="<?= e($mapUrl) ?>">
                Buka di Peta Satelit <span class="arrow-icon" aria-hidden="true">&#8594;</span>
              </a>
            </div>
          <?php endif; ?>

          <div class="factsheet-emergency-box">
            <strong>Posko Siaga Karhutla</strong>
            <p>Melihat tanda kebakaran hutan atau asap pekat di sekitar Anda? Laporkan segera:</p>
            <div class="mt-2 pt-2 border-top border-secondary">
              <span class="d-block fw-bold text-white">WhatsApp: 0813-1003-5000</span>
              <span class="d-block fw-bold text-white">Call Center Darurat: 112 / 110</span>
            </div>
            <a class="action outline w-100 mt-3" href="kontak.php">
              Lihat Semua Kanal Darurat <span class="arrow-icon" aria-hidden="true">&#8594;</span>
            </a>
          </div>
        </aside>
      </div>
    </div>

    <!-- Related Articles Section -->
    <section class="related-articles-section">
      <div class="section-head">
        <div>
          <p class="eyebrow">DOKUMENTASI TERKAIT</p>
          <h2>Laporan Karhutla Lainnya</h2>
        </div>
        <a class="action light-outline btn-sm" href="artikel.php">Lihat Semua (<?= count($stories) ?>) &#8594;</a>
      </div>

      <div class="row g-3">
        <?php
          $related = array_filter($stories, static fn($s) => $s['id'] !== $selected['id']);
          $related = array_slice($related, 0, 3);
          foreach ($related as $relItem):
            $relRegional = isset($relItem['sections']) || !isset($relItem['province']);
            $relTitle = $relItem['title'] ?? ($details[$relItem['id']]['title'] ?? 'Karhutla di ' . ($relItem['place'] ?? $relItem['region']));
        ?>
          <div class="col-md-4">
            <article class="story <?= $relRegional ? 'is-regional' : 'is-field' ?> h-100">
              <div class="story-badges mb-2">
                <span class="badge-tag <?= $relRegional ? 'transboundary' : 'field' ?>"><?= $relRegional ? 'LINTAS BATAS' : 'LAPANGAN' ?></span>
                <span class="badge-tag region"><?= e($relItem['province'] ?? $relItem['region']) ?></span>
              </div>
              <h3 class="story-title fs-5 mb-2">
                <a href="artikel.php?id=<?= e($relItem['id']) ?>"><?= e($relTitle) ?></a>
              </h3>
              <p class="story-summary small text-muted mb-3"><?= e(mb_strimwidth($relItem['summary'], 0, 110, '...')) ?></p>
              <a class="action light-outline btn-sm w-100 mt-auto" href="artikel.php?id=<?= e($relItem['id']) ?>">
                BACA DETAIL &#8594;
              </a>
            </article>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <script>
    window.addEventListener('scroll', () => {
      const bar = document.getElementById('reading-bar');
      if (!bar) return;
      const winScroll = document.documentElement.scrollTop;
      const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      bar.style.width = (height > 0 ? (winScroll / height) * 100 : 0) + '%';
    });

    function copyCurrentUrl() {
      navigator.clipboard.writeText(window.location.href).then(() => {
        const toast = document.getElementById('copy-toast');
        if (toast) { toast.textContent = 'Tautan berhasil disalin!'; toast.classList.add('show'); setTimeout(() => toast.classList.remove('show'), 3500); }
      }).catch(() => { alert('Tautan: ' + window.location.href); });
    }
  </script>

<?php elseif ($id !== null): ?>
  <!-- 404 Page -->
  <section class="page-intro">
    <div class="container">
      <p class="eyebrow">STATUS 404</p>
      <h1>Laporan Tidak Ditemukan</h1>
      <p>Laporan dengan ID <code><?= e($id) ?></code> tidak tercatat dalam arsip kami.</p>
    </div>
  </section>
  <div class="container section text-center">
    <p class="mb-4">Silakan kembali ke direktori utama untuk melihat seluruh laporan karhutla Indonesia.</p>
    <a class="action primary" href="artikel.php">Kembali ke Semua Artikel &#8594;</a>
  </div>

<?php else: ?>
  <!-- List View -->
  <section class="page-intro">
    <div class="container">
      <p class="eyebrow"><span class="signal"></span> ARSIP INVESTIGASI &amp; JURNAL LINGKUNGAN / 2026</p>
      <h1>ARSIP LAPORAN KARHUTLA.</h1>
      <p>Dokumentasi komprehensif kejadian kebakaran hutan dan lahan di berbagai wilayah Indonesia serta jejak persebaran kabut asap lintas batas di Asia Tenggara.</p>
    </div>
  </section>

  <section class="container section pt-4" aria-label="Daftar artikel karhutla">
    <!-- Stats Bar -->
    <div class="intel-stats-bar" role="region" aria-label="Statistik Data Karhutla">
      <div class="intel-stat-card">
        <span class="intel-stat-label">Total Laporan</span>
        <div class="intel-stat-value"><?= $totalReports ?> Data</div>
        <p class="intel-stat-desc"><?= $totalField ?> Kejadian Lapangan &amp; <?= $totalRegional ?> Lintas Batas</p>
      </div>
      <div class="intel-stat-card">
        <span class="intel-stat-label">Cakupan Wilayah</span>
        <div class="intel-stat-value">7 Provinsi + 3 Negara</div>
        <p class="intel-stat-desc">Indonesia, Singapura, Malaysia &amp; Filipina</p>
      </div>
      <div class="intel-stat-card">
        <span class="intel-stat-label">Luas Terbakar Terdokumentasi</span>
        <div class="intel-stat-value">~<?= number_format($totalArea, 1, ',', '.') ?> ha</div>
        <p class="intel-stat-desc">Taksiran luasan insiden lapangan terdata</p>
      </div>
      <div class="intel-stat-card">
        <span class="intel-stat-label">Periode Data</span>
        <div class="intel-stat-value">Agt &ndash; Nov 2026</div>
        <p class="intel-stat-desc">3 periode bulan, diperbarui berkala</p>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="article-toolbar" role="search" aria-label="Pencarian dan Filter Artikel">
      <div class="row g-3 align-items-center mb-3">
        <div class="col-lg-7">
          <div class="article-search">
            <?= icon('search') ?>
            <input type="search" id="article-search" class="article-search-input" placeholder="Cari nama desa, kabupaten, provinsi, atau kata kunci..." aria-label="Cari artikel">
          </div>
        </div>
        <div class="col-lg-5 text-lg-end">
          <div class="filter-count" id="filter-count-display">
            Menampilkan <strong id="visible-count"><?= $totalReports ?></strong> dari <?= $totalReports ?> laporan
          </div>
        </div>
      </div>

      <div class="filter-pill-group mb-2" role="group" aria-label="Filter kategori artikel">
        <span class="text-muted small fw-bold text-uppercase me-1" style="font-size:0.75rem;letter-spacing:0.05em">Kategori:</span>
        <button type="button" class="filter-btn active" data-filter="all">Semua (<?= $totalReports ?>)</button>
        <button type="button" class="filter-btn" data-filter="category:field"><?= icon('flame') ?> Lapangan (<?= $totalField ?>)</button>
        <button type="button" class="filter-btn" data-filter="category:regional"><?= icon('globe') ?> Lintas Batas (<?= $totalRegional ?>)</button>
        <span class="text-muted small fw-bold text-uppercase ms-lg-2 me-1" style="font-size:0.75rem;letter-spacing:0.05em">Pulau:</span>
        <button type="button" class="filter-btn" data-filter="island:jawa">Jawa</button>
        <button type="button" class="filter-btn" data-filter="island:kalimantan">Kalimantan</button>
        <button type="button" class="filter-btn" data-filter="island:sulawesi">Sulawesi</button>
        <button type="button" class="filter-btn" data-filter="island:maluku">Maluku</button>
      </div>

      <div class="filter-pill-group pt-2 border-top" role="group" aria-label="Filter periode bulan kejadian">
        <span class="text-muted small fw-bold text-uppercase me-1" style="font-size:0.75rem;letter-spacing:0.05em">Periode Bulan:</span>
        <button type="button" class="filter-btn" data-filter="month:2026-11">November 2026 (13)</button>
        <button type="button" class="filter-btn" data-filter="month:2026-09">September 2026 (2)</button>
        <button type="button" class="filter-btn" data-filter="month:2026-08">Agustus 2026 (1)</button>
      </div>
    </div>

    <!-- Empty State -->
    <div id="no-results-msg" class="text-center py-5 d-none">
      <h3 class="h4 text-muted">Tidak ada laporan yang sesuai dengan pencarian.</h3>
      <p class="text-muted">Coba gunakan kata kunci lain atau klik tombol "Semua" pada filter di atas.</p>
      <button type="button" class="action primary btn-sm mt-2" onclick="resetFilters()">Reset Filter</button>
    </div>

    <?php
      usort($stories, static fn (array $a, array $b): int => strcmp($b['date'], $a['date']));
      require __DIR__ . '/../app/cards.php';
    ?>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.getElementById('article-search');
      const filterBtns = document.querySelectorAll('.filter-btn');
      const cards = document.querySelectorAll('.article-card-col');
      const visibleCountElem = document.getElementById('visible-count');
      const noResultsMsg = document.getElementById('no-results-msg');
      let currentFilter = 'all', currentQuery = '';

      function applyFilters() {
        let count = 0;
        cards.forEach(card => {
          const category = card.getAttribute('data-category');
          const island = card.getAttribute('data-island');
          const text = card.getAttribute('data-search') || '';
          let matchesCategory = true;
          if (currentFilter.startsWith('category:')) matchesCategory = category === currentFilter.replace('category:', '');
          else if (currentFilter.startsWith('island:')) matchesCategory = island === currentFilter.replace('island:', '');
          else if (currentFilter.startsWith('month:')) matchesCategory = card.getAttribute('data-month') === currentFilter.replace('month:', '');
          const matchesQuery = !currentQuery || text.includes(currentQuery.toLowerCase());
          if (matchesCategory && matchesQuery) { card.classList.remove('d-none'); count++; }
          else card.classList.add('d-none');
        });
        if (visibleCountElem) visibleCountElem.textContent = count;
        if (noResultsMsg) noResultsMsg.classList.toggle('d-none', count !== 0);
      }

      if (searchInput) searchInput.addEventListener('input', e => { currentQuery = e.target.value.trim(); applyFilters(); });
      filterBtns.forEach(btn => btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        currentFilter = btn.getAttribute('data-filter') || 'all';
        applyFilters();
      }));
      window.resetFilters = function() {
        if (searchInput) searchInput.value = '';
        currentQuery = ''; currentFilter = 'all';
        filterBtns.forEach(b => b.classList.remove('active'));
        const allBtn = document.querySelector('.filter-btn[data-filter="all"]');
        if (allBtn) allBtn.classList.add('active');
        applyFilters();
      };
    });
  </script>
<?php endif; ?>

<?php require __DIR__ . '/../app/footer.php'; ?>
