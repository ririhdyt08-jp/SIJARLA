(() => {
  const track = document.querySelector('.report-carousel #article-grid');
  if (!track || track.children.length < 2) return;

  const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const interval = 5500;
  let visible = false;
  let pointerInside = false;
  let lastInteraction = 0;

  track.addEventListener('pointerdown', () => { lastInteraction = Date.now(); });
  track.addEventListener('pointerenter', event => { pointerInside = event.pointerType === 'mouse'; });
  track.addEventListener('pointerleave', () => { pointerInside = false; });
  new IntersectionObserver(([entry]) => {
    visible = entry.intersectionRatio >= 0.5;
  }, { threshold: 0.5 }).observe(track);

  window.setInterval(() => {
    if (motion.matches || !visible || document.hidden || pointerInside ||
        track.contains(document.activeElement) || Date.now() - lastInteraction < interval) return;

    const max = track.scrollWidth - track.clientWidth;
    if (max <= 2) return;

    const step = track.children[1].offsetLeft - track.children[0].offsetLeft;
    const wrap = track.scrollLeft >= max - 2;
    track.scrollTo({ left: wrap ? 0 : Math.min(track.scrollLeft + step, max), behavior: wrap ? 'instant' : 'smooth' });
  }, interval);
})();
