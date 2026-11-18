<?php
require __DIR__ . '/../app/bootstrap.php';

$topics = [
    'sijarla' => ['Apa itu SIJARLA?', 'Kenali Sistem Informasi Jaga Rimba dan Lahan serta fitur yang bisa Anda gunakan.'],
    'apa-itu-karhutla' => ['Apa Itu Karhutla?', 'Pahami apa itu karhutla dan kenapa kebakaran di lahan gambut sulit dipadamkan.'],
    'penyebab' => ['Bagaimana Karhutla Terjadi?', 'Pelajari bagaimana cuaca, aktivitas manusia, dan kondisi gambut memicu karhutla.'],
    'dampak' => ['Dampak Karhutla terhadap Lingkungan dan Kehidupan', 'Ketahui dampak karhutla bagi kesehatan, ekosistem, iklim, dan kehidupan masyarakat.'],
    'lintas-negara' => ['Karhutla sebagai Isu Lintas Negara', 'Pahami bagaimana asap melintasi batas negara dan mengapa kerja sama regional diperlukan.'],
    'pencegahan' => ['Upaya Pencegahan dan Penanggulangan Karhutla', 'Kenali langkah pencegahan, deteksi dini, pemadaman, dan pemulihan setelah kebakaran.'],
    'metodologi' => ['Peran Teknologi dan Metodologi Data', 'Ketahui cara SIJARLA membaca deteksi satelit dan menyusun arsip laporan kejadian.'],
];
$topicId = $_GET['topik'] ?? null;
$topic = is_string($topicId) ? ($topics[$topicId] ?? null) : null;
if ($topicId !== null && $topic === null) {
    http_response_code(404);
}

$title = $topic[0] ?? ($topicId === null ? 'Tentang' : 'Topik tidak ditemukan');
$active = 'tentang';
require __DIR__ . '/../app/header.php';
?>
<?php if ($topicId === null): ?>
<section class="page-intro intro-about"><div class="container"><p class="eyebrow">TENTANG <?= SITE ?></p><h1>MENGENAL KEBAKARAN HUTAN DAN LAHAN (KARHUTLA).</h1><p>Portal edukasi independen yang mengangkat karhutla Indonesia sebagai isu lingkungan global.</p></div></section>
<section class="container section" id="topik" aria-label="Topik tentang SIJARLA dan karhutla">
  <div class="row g-3">
    <?php foreach ($topics as $slug => [$topicTitle, $summary]): ?>
      <div class="col-md-6 col-lg-4" id="<?= e($slug) ?>">
        <article class="story h-100">
          <h2 class="h3"><?= e($topicTitle) ?></h2>
          <p class="story-summary"><?= e($summary) ?></p>
          <a class="action outline mt-auto" href="tentang.php?topik=<?= e($slug) ?>" aria-label="Baca selengkapnya: <?= e($topicTitle) ?>">
            Baca selengkapnya <span class="arrow-icon" aria-hidden="true">&#8594;</span>
          </a>
        </article>
      </div>
    <?php endforeach; ?>
  </div>
</section>
<?php else: ?>
<section class="page-intro intro-about">
  <div class="container">
    <p class="eyebrow">TENTANG <?= SITE ?></p>
    <h1><?= e($title) ?></h1>
  </div>
</section>
<section class="container section reading">
  <a class="action outline mb-4" href="tentang.php<?= $topic ? '#' . e($topicId) : '#topik' ?>">
    <span aria-hidden="true">&#8592;</span> Kembali ke topik
  </a>
  <?php if ($topic): ?>
    <?php require __DIR__ . '/../app/about-topic.php'; ?>
  <?php else: ?>
    <p>Pilih salah satu topik di halaman Tentang.</p>
  <?php endif; ?>
</section>
<?php endif; ?>
<?php require __DIR__ . '/../app/footer.php'; ?>
