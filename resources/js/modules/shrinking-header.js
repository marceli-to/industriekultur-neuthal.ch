(() => {

  /* Selectors */
  const header = document.querySelector('[data-header]');
  
  /* State */
  let lastScrollY = window.scrollY;
  const threshold = 250;

  /* Methods */
  const shrink = () => {
    if (!header) return;
    
    const currentScrollY = window.scrollY;
    const scrollDistance = currentScrollY - lastScrollY;

    if (scrollDistance > threshold) {
      header.classList.add('is-shrinked');
      lastScrollY = currentScrollY;
    }
    else if (scrollDistance < -threshold) {
      header.classList.remove('is-shrinked');
      lastScrollY = currentScrollY;
    }
  };

  /* Events */
  window.addEventListener('scroll', shrink, { passive: true });
  window.addEventListener('resize', shrink, { passive: true });

})();