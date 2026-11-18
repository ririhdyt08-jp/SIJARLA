</main>
<footer class="site-footer">
  <div class="container d-flex flex-column flex-sm-row flex-sm-wrap gap-3 justify-content-between align-items-start align-items-sm-center">
    <a class="brand" href="index.php"><span><?= SITE ?></span></a>
    <span>Portal edukasi karhutla independen · 2026</span>
    <a href="tentang.php#metodologi">Sumber dan metodologi ↗</a>
  </div>
</footer>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/js/bootstrap.bundle.min.js" integrity="sha384-WyOxWvTyr1QaMPfiPJSv76iReVthorl5uWlFtZPyK0IEB0hGzo9y5Bp9mYanr8Bu" crossorigin="anonymous"></script>
<?php if ($active === 'index'): ?>
<script defer src="assets/js/hero-video.js"></script>
<?php endif; ?>
<?php if ($active === 'peta'): ?>
<script defer src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>
<script defer src="assets/js/map.js?v=incident-focus"></script>
<?php endif; ?>
</body>
</html>
