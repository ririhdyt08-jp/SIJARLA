(() => {
  const video = document.getElementById('hero-video');
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  function playVideo() {
    if (!video.getAttribute('src')) video.src = video.dataset.src;
    video.play().catch(() => video.classList.remove('is-visible'));
  }

  video.addEventListener('playing', () => {
    if (!reducedMotion.matches) video.classList.add('is-visible');
  });
  reducedMotion.addEventListener('change', () => {
    if (reducedMotion.matches) {
      video.pause();
      video.classList.remove('is-visible');
    } else {
      playVideo();
    }
  });

  if (!reducedMotion.matches) playVideo();
})();
