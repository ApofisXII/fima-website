import './styles/app.css';

function initSlidingIndicator(container, linkSelector, activeSelector, indicatorSelector, excludeActiveSelector) {
    const indicator = container.querySelector(indicatorSelector);
    if (!indicator) return;

    const links = container.querySelectorAll(linkSelector);
    const activeLink = excludeActiveSelector
        ? container.querySelector(activeSelector + ':not(' + excludeActiveSelector + ')')
        : container.querySelector(activeSelector);

    function positionIndicator(link) {
        const containerRect = container.getBoundingClientRect();
        const linkRect = link.getBoundingClientRect();
        indicator.style.left = (linkRect.left - containerRect.left) + 'px';
        indicator.style.width = linkRect.width + 'px';
    }

    if (activeLink) {
        indicator.style.transition = 'none';
        positionIndicator(activeLink);
        indicator.style.opacity = '1';
        requestAnimationFrame(() => requestAnimationFrame(() => {
            indicator.style.transition = '';
        }));
    }

    container.classList.add('has-indicator');

    links.forEach(link => {
        link.addEventListener('mouseenter', () => {
            positionIndicator(link);
            indicator.style.opacity = '1';
        });
    });

    container.addEventListener('mouseleave', () => {
        if (activeLink) {
            positionIndicator(activeLink);
            indicator.style.opacity = '1';
        } else {
            indicator.style.opacity = '0';
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.header__menu-toggle');
    const nav = document.querySelector('.header__nav');

    if (toggle && nav) {
        const toggleLabel = toggle.querySelector('.header__menu-toggle-label');
        const toggleIcon = toggle.querySelector('.header__menu-icon');
        toggle.addEventListener('click', function () {
            const isOpen = nav.classList.toggle('header__nav--open');
            toggleLabel.textContent = isOpen ? 'CHIUDI' : 'MENU';
            toggleIcon.style.display = isOpen ? 'none' : 'block';
        });
    }

    if (nav) {
        initSlidingIndicator(nav, '.header__nav-link', '.header__nav-link--active', '.header__nav-indicator', '.header__nav-link--no-border');
    }

    const umaToggle = document.querySelector('.uma-header__menu-toggle');
    const umaLinks = document.querySelector('.uma-header__links');

    if (umaToggle && umaLinks) {
        const umaToggleLabel = umaToggle.querySelector('.uma-header__menu-toggle-label');
        const umaToggleIcon = umaToggle.querySelector('.uma-header__menu-icon');
        umaToggle.addEventListener('click', function () {
            const isOpen = umaLinks.classList.toggle('uma-header__links--open');
            umaToggleLabel.textContent = isOpen ? 'Chiudi' : 'Urbino Musica Antica';
            umaToggleIcon.style.display = isOpen ? 'none' : 'block';
        });
    }

    if (umaLinks) {
        initSlidingIndicator(umaLinks, '.uma-header__link', '.uma-header__link--active', '.uma-header__indicator', null);
    }
});
