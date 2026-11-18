(() => {
  const carousel = document.querySelector('.report-carousel');
  if (!carousel) return;
  const track = carousel.querySelector('#article-grid');
  const controls = carousel.querySelector('.report-carousel-controls');
  const rotate = carousel.querySelector('[data-rotate]');
  const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const cards = track.children;
  if (cards.length < 2) return;

  let autoPlay = !motion.matches;
  let visible = false;
  let pointerInside = false;
  let lastInteraction = 0;

  function updateControls() {
    controls.hidden = track.scrollWidth <= track.clientWidth + 1;
    rotate.textContent = autoPlay ? 'Jeda' : 'Putar';
    rotate.setAttribute('aria-label', autoPlay ? 'Jeda gulir otomatis' : 'Mulai gulir otomatis');
  }

  function slide(direction) {
    const max = track.scrollWidth - track.clientWidth;
    const position = track.scrollLeft;
    const wrap = direction > 0 ? position >= max - 2 : position <= 2;
    const step = cards[1].offsetLeft - cards[0].offsetLeft;
    const target = wrap ? (direction > 0 ? 0 : max) : position + direction * step;
    track.scrollTo({ left: target, behavior: wrap || motion.matches ? 'instant' : 'smooth' });
  }

  for (const button of controls.querySelectorAll('[data-slide]')) {
    button.addEventListener('click', () => {
      lastInteraction = Date.now();
      slide(Number(button.dataset.slide));
    });
  }
  rotate.addEventListener('click', () => {
    autoPlay = !autoPlay;
    lastInteraction = Date.now();
    updateControls();
  });
  motion.addEventListener('change', () => {
    if (motion.matches) autoPlay = false;
    updateControls();
  });
  track.addEventListener('pointerdown', () => { lastInteraction = Date.now(); });
  track.addEventListener('pointerenter', event => { pointerInside = event.pointerType === 'mouse'; });
  track.addEventListener('pointerleave', () => { pointerInside = false; });
  new ResizeObserver(updateControls).observe(track);
  new IntersectionObserver(([entry]) => { visible = entry.intersectionRatio >= 0.5; }, { threshold: 0.5 }).observe(track);
  updateControls();

  window.setInterval(() => {
    if (autoPlay && visible && !controls.hidden && !document.hidden && !pointerInside &&
        !track.contains(document.activeElement) && Date.now() - lastInteraction >= 5500) {
      slide(1);
    }
  }, 5500);
})();
