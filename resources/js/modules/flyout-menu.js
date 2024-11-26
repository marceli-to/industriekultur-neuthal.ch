(() => {
  'use strict';
  
  const init = () => {

    const menu = document.querySelector('[data-flyout-menu]');
    const menuContainer = document.querySelector('[data-flyout-container]');
    const menuBtnShow = document.querySelectorAll('[data-flyout-show]');
    const menuBtnHide = document.querySelectorAll('[data-flyout-hide]');
    const menuItems = document.querySelectorAll('[data-flyout-items]');
    const menuItemParent = document.querySelectorAll('[data-flyout-item-parent]');
    
    // Currently not in use
    // const menuItemChildren = document.querySelectorAll('[data-flyout-item-children]');

    let isMenuOpen = false;
  
    const showFlyout = (e) => {
      const menuId = e.target.dataset.flyoutShow;
      const triggerRect = e.target.getBoundingClientRect();
      const navRect = e.target.closest('nav').getBoundingClientRect();
      const left = triggerRect.left - navRect.left - 3;

      hideFlyoutChildren();
      resetContainerWidth();

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
      hideFlyoutChildren();
      resetContainerWidth();
    };

    const showFlyoutChildren = (slug, offsetWidth) => {
      const flyoutItemChildren = document.querySelector(`[data-flyout-item-children="${slug}"]`);
      flyoutItemChildren.classList.add('is-open');
      flyoutItemChildren.classList.remove('invisible');
      flyoutItemChildren.style.left = `${offsetWidth}px`;
    };

    const hideFlyoutChildren = () => {
      const flyoutItemChildren = document.querySelectorAll('[data-flyout-item-children]');
      flyoutItemChildren.forEach(child => {
        child.classList.remove('is-open');
        child.classList.add('invisible');
        child.removeAttribute('style');
      });
    };

    const resetContainerWidth = () => {
      const container = document.querySelector('[data-flyout-container] > div');
      container.removeAttribute('style');
    };

    const updateContainerWidth = (childWidth = 0) => {
      const container = document.querySelector('[data-flyout-container] > div');
      container.removeAttribute('style');
      container.style.width = `${container.offsetWidth + childWidth}px`;
    };

    // Event Listeners
    menuBtnShow.forEach(el => {
      el.addEventListener('mouseenter', showFlyout);
    });

    menuBtnHide.forEach(el => {
      el.addEventListener('mouseenter', hideFlyout);
    });

    menuItemParent.forEach(el => {
      el.addEventListener('click', function() {
        console.log(el);
        const slug = el.dataset.flyoutItemParent;
        const children = document.querySelector(`[data-flyout-item-children="${slug}"]`);
        resetContainerWidth();
        hideFlyoutChildren();

        if (children) {
          updateContainerWidth(children.offsetWidth);
          showFlyoutChildren(slug, el.parentElement.offsetWidth);
        }
      });
    });

    menu.addEventListener('mouseleave', hideFlyout);
  };

  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', init);
})();