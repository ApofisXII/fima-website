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
            if (isOpen) {
                toggleLabel.classList.remove('toggle-label--animate');
                void toggleLabel.offsetWidth;
                toggleLabel.classList.add('toggle-label--animate');
            }
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
        const themeColorMeta = document.querySelector('meta[name="theme-color"]');
        umaToggle.addEventListener('click', function () {
            const isOpen = umaLinks.classList.toggle('uma-header__links--open');
            umaToggleLabel.textContent = isOpen ? 'CHIUDI' : 'Urbino Musica Antica';
            umaToggleIcon.style.display = isOpen ? 'none' : 'block';
            document.body.classList.toggle('uma-menu-open', isOpen);
            if (themeColorMeta) {
                themeColorMeta.setAttribute('content', isOpen ? '#FBBB21' : '#18407E');
            }
            if (isOpen) {
                umaToggleLabel.classList.remove('toggle-label--animate');
                void umaToggleLabel.offsetWidth;
                umaToggleLabel.classList.add('toggle-label--animate');
            }
        });
    }

    if (umaLinks) {
        initSlidingIndicator(umaLinks, '.uma-header__link', '.uma-header__link--active', '.uma-header__indicator', null);
    }
});
