<?php
require __DIR__ . '/../app/bootstrap.php';

$id = $_GET['id'] ?? null;
$regionalArticles = json_decode(file_get_contents(__DIR__ . '/../data/regional-articles.json'), true, 512, JSON_THROW_ON_ERROR);
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
$details = json_decode(file_get_contents(__DIR__ . '/../data/report-details.json'), true, 512, JSON_THROW_ON_ERROR);
$report = $selected ? ($details[$selected['id']] ?? $selected) : null;
$title = $report['title'] ?? ($id === null ? 'Artikel' : 'Artikel tidak ditemukan');
$active = 'artikel';
require __DIR__ . '/../app/header.php';
?>
<?php if (isset($report['sections'])): ?>
<section class="page-intro"><div class="container">
  <p class="eyebrow"><?= e($report['region']) ?> · ASAP LINTAS NEGARA</p>
  <h1><?= e($title) ?></h1>
  <p><time datetime="<?= e($report['date']) ?>"><?= e(tanggal($report['date'])) ?></time></p>
</div></section>
<article class="container section reading">
  <a class="text-link" href="artikel.php">← Semua artikel</a>
  <?php foreach ($report['sections'] as $heading => $paragraph): ?>
    <section><h2><?= e($heading) ?></h2><p><?= e($paragraph) ?></p></section>
  <?php endforeach; ?>
  <section>
    <h2>Sumber pengamatan</h2>
    <ul>
      <?php foreach ($report['sources'] as $source): ?>
        <li><a class="text-link" href="<?= e($source['url']) ?>"><?= e($source['label']) ?> ↗</a></li>
      <?php endforeach; ?>
    </ul>
  </section>
  <a class="action primary" href="peta.php?bulan=<?= e(substr($report['date'], 0, 7)) ?>#peta-bulanan">Lihat titik panas pada bulan pengamatan ↗</a>
</article>
<?php elseif ($selected): ?>
<section class="page-intro"><div class="container"><p class="eyebrow"><?= e($selected['region']) ?></p><h1><?= e($title) ?></h1><p><?= e(tanggal($selected['date'])) ?> · Perkiraan luas <?= e(luas($selected['area'])) ?></p></div></section>
<article class="container section reading">
  <a class="text-link" href="artikel.php">← Semua artikel</a>
  <section><h2>Kronologi</h2><p><?= e($report['chronology']) ?></p></section>
  <section><h2>Dampak</h2><p><?= e($report['impact']) ?></p></section>
  <section><h2>Penanganan dan kondisi terakhir</h2><p><?= e($report['response']) ?></p></section>
  <div class="actions"><a class="action primary" href="peta.php?bulan=<?= e(substr($selected['date'], 0, 7)) ?>#peta-bulanan">Lihat titik panas bulan ini ↗</a><a class="action light-outline" href="<?= e($selected['source']) ?>">Sumber laporan ↗</a></div>
</article>
<?php elseif ($id !== null): ?>
<section class="container section"><h1>Artikel tidak ditemukan</h1><a class="text-link" href="artikel.php">← Semua artikel</a></section>
<?php else: ?>
<section class="page-intro"><div class="container"><h1>Artikel</h1><p><?= count($stories) ?> artikel tentang karhutla di Indonesia dan dampak asapnya di Asia Tenggara.</p></div></section>
<section class="container section" aria-label="Daftar artikel">
  <?php
  usort($stories, static fn (array $a, array $b): int => strcmp($b['date'], $a['date']));
  require __DIR__ . '/../app/cards.php';
  ?>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../app/footer.php'; ?>
