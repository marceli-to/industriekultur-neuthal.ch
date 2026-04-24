(() => {

  /* Methods */
  const getTarget = () => {
    const hash = window.location.hash;
    if (!hash || hash.length < 2) return null;
    const id = decodeURIComponent(hash.slice(1));
    return document.getElementById(id)
      || document.querySelector(`a[name="${CSS.escape(id)}"]`);
  };

  /* State */
  let userInteracted = false;
  let observer;
  let stopTimer;

  const markUserInteraction = () => { userInteracted = true; };

  const rescroll = () => {
    if (userInteracted) return;
    const target = getTarget();
    if (!target) return;
    target.scrollIntoView({ block: 'start', behavior: 'instant' });
  };

  const startSettlingWindow = (duration = 3000) => {
    rescroll();
    if (observer) observer.disconnect();
    clearTimeout(stopTimer);
    observer = new ResizeObserver(rescroll);
    observer.observe(document.body);
    stopTimer = setTimeout(() => observer.disconnect(), duration);
  };

  /* Events */
  window.addEventListener('wheel', markUserInteraction, { passive: true });
  window.addEventListener('touchmove', markUserInteraction, { passive: true });
  window.addEventListener('keydown', (e) => {
    if (['ArrowUp', 'ArrowDown', 'PageUp', 'PageDown', 'Home', 'End', ' '].includes(e.key)) {
      markUserInteraction();
    }
  });

  if (getTarget()) {
    if (document.readyState === 'complete') {
      startSettlingWindow();
    } else {
      window.addEventListener('load', () => startSettlingWindow(), { once: true });
    }
  }

  window.addEventListener('hashchange', () => {
    userInteracted = false;
    startSettlingWindow(2000);
  });

})();
