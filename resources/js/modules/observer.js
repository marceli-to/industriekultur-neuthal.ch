document.addEventListener('DOMContentLoaded', () => {

  const selector = '[data-observe]';
  const visibleClass = 'is-visible';
  const rootMargin = '0px';
  const threshold = 0.25;

  const observerOptions = {
    root: null,
    rootMargin,
    threshold
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add(visibleClass);
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  document.querySelectorAll(selector).forEach(element => {
    observer.observe(element);
  });
});
