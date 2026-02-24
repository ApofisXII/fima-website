import './styles/app.css';

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
});
