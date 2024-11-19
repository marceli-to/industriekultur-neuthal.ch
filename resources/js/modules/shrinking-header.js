(() => {

  const header = document.querySelector('[data-header]');
  

  if (!header) return;

  const shrink = () => {
    if (window.scrollY > header.offsetHeight) {
      header.classList.add('is-shrinked');
    }
    else if (window.scrollY < header.offsetHeight) {
      header.classList.remove('is-shrinked');
    }
  };

  const debounce = (func, wait) => {
    let timeout;
    return () => {
      clearTimeout(timeout);
      timeout = setTimeout(func, wait);
    };
  };

  window.addEventListener('scroll', shrink, { passive: true });
  window.addEventListener('resize', shrink, { passive: true });

})();