(() => {

  /* Selectors */
  const header = document.querySelector('[data-header]');

  /* Methods */
  const shrink = () => {
    if (!header) return;
    if (window.scrollY > header.offsetHeight) {
      header.classList.add('is-shrinked');
    }
    else if (window.scrollY < header.offsetHeight) {
      header.classList.remove('is-shrinked');
    }
  };

  /* Events */
  window.addEventListener('scroll', shrink, { passive: true });
  window.addEventListener('resize', shrink, { passive: true });

})();