<?php
// app/cards.php
?>
<div class="row g-3" id="article-grid">
<?php foreach ($stories as $item): ?>
  <?php
    $isRegional = isset($item['sections']) || !isset($item['province']);
    $itemTitle = $item['title'] ?? (($details ?? [])[$item['id']]['title'] ?? 'Karhutla di ' . ($item['place'] ?? $item['region']));
    $itemRegion = $item['province'] ?? $item['region'];
    $categoryTag = $isRegional ? 'LINTAS BATAS' : 'INSIDEN LAPANGAN';
    $categoryClass = $isRegional ? 'transboundary' : 'field';
    
    $island = 'lainnya';
    if ($isRegional) {
        $island = 'regional';
    } else {
        $prov = $item['province'] ?? '';
        if (str_contains($prov, 'Jawa')) $island = 'jawa';
        elseif (str_contains($prov, 'Kalimantan')) $island = 'kalimantan';
        elseif (str_contains($prov, 'Sulawesi')) $island = 'sulawesi';
        elseif (str_contains($prov, 'Sumatera') || str_contains($prov, 'Riau')) $island = 'sumatera';
        elseif (str_contains($prov, 'Maluku')) $island = 'maluku';
    }
  ?>
  <div class="col-md-6 col-lg-4 article-card-col" 
       data-category="<?= $isRegional ? 'regional' : 'field' ?>" 
       data-island="<?= $island ?>"
       data-month="<?= substr($item['date'], 0, 7) ?>"
       data-search="<?= strtolower(e($itemTitle . ' ' . $itemRegion . ' ' . ($item['place'] ?? '') . ' ' . $item['summary'])) ?>">
    <article class="story <?= $isRegional ? 'is-regional' : 'is-field' ?> h-100">
      <div class="story-badges mb-2">
        <span class="badge-tag <?= $categoryClass ?>"><?= $categoryTag ?></span>
        <span class="badge-tag region"><?= e($itemRegion) ?></span>
      </div>
      
      <div class="story-date-row mb-2">
        <time class="story-date" datetime="<?= e($item['date']) ?>"><?= e(tanggal($item['date'])) ?></time>
      </div>

      <h3 class="story-title">
        <a href="artikel.php?id=<?= e($item['id']) ?>"><?= e($itemTitle) ?></a>
      </h3>

      <p class="story-summary"><?= e($item['summary']) ?></p>

      <div class="story-chips mt-auto">
        <?php if (!empty($item['area'])): ?>
          <span class="data-chip"><?= icon('area') ?> <?= e(luas($item['area'])) ?></span>
        <?php endif; ?>
        <?php if (!empty($item['coordinates'])): ?>
          <span class="data-chip"><?= icon('pin') ?> <?= e($item['coordinates'][0]) ?>, <?= e($item['coordinates'][1]) ?></span>
        <?php endif; ?>
      </div>

      <a class="action primary w-100 mt-3" href="artikel.php?id=<?= e($item['id']) ?>">
        BACA LAPORAN <span class="arrow-icon" aria-hidden="true">&#8594;</span>
      </a>
    </article>
  </div>
<?php endforeach; ?>
</div>
