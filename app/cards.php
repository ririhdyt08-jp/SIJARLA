<div class="row g-3">
<?php foreach ($stories as $item): ?>
  <div class="col-md-6 col-lg-4">
    <article class="story h-100">
      <div class="story-meta"><span><?= e($item['province'] ?? $item['region']) ?></span><time datetime="<?= e($item['date']) ?>"><?= e(tanggal($item['date'])) ?></time></div>
      <h3><?= e($item['title'] ?? $details[$item['id']]['title'] ?? 'Karhutla di ' . $item['place']) ?></h3>
      <p><?= e($item['summary']) ?></p>
      <a class="btn action primary mt-auto" href="artikel.php?id=<?= e($item['id']) ?>">Baca artikel <span aria-hidden="true">↗</span></a>
    </article>
  </div>
<?php endforeach; ?>
</div>
