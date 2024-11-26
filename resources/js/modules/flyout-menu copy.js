(() => {
  'use strict';
  
  const init = () => {
    const menuShowEls = document.querySelectorAll('[data-flyout-show]');
    const menuHideEls = document.querySelectorAll('[data-flyout-hide]');
    const menuContainer = document.querySelector('[data-flyout-container]');
    const menuItems = document.querySelectorAll('[data-flyout-items]');

    let isMenuOpen = false;
  
    const showFlyout = (e) => {
      const menuId = e.target.dataset.flyoutShow;
      const triggerRect = e.target.getBoundingClientRect();
      const navRect = e.target.closest('nav').getBoundingClientRect();
      const left = triggerRect.left - navRect.left - 3;

      if (isMenuOpen) {
        menuContainer.classList.add('transition-all', 'duration-200');
      } 
      else {
        menuContainer.classList.remove('transition-all', 'duration-200');
      }

      menuContainer.style.left = `${left}px`;
      menuContainer.classList.remove('opacity-0', 'pointer-events-none');

      menuItems.forEach(item => {
        if (item.dataset.flyoutItems === menuId) {
          item.classList.remove('hidden');
          item.classList.add('is-visible');
        } else {
          item.classList.add('hidden');
          item.classList.remove('is-visible');
        }
      });

      isMenuOpen = true;
    };

    const hideFlyout = () => {
      menuContainer.classList.remove('transition-all', 'duration-200');
      menuContainer.classList.add('opacity-0', 'pointer-events-none');
      isMenuOpen = false;
      menuItems.forEach(item => {
        item.classList.add('hidden');
        item.classList.remove('is-visible');
      });
    };

    // Event Listeners
    menuShowEls.forEach(el => {
      el.addEventListener('mouseenter', showFlyout);
    });

    menuHideEls.forEach(el => {
      el.addEventListener('mouseenter', hideFlyout);
    });

    const nav = document.querySelector('nav');
    nav.addEventListener('mouseleave', hideFlyout);

    document.addEventListener('click', (e) => {
      if (!menuContainer.contains(e.target)) {
        hideFlyout();
      }
    });
  };

  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', init);
})();
