// Turn the header navigation into a hamburger menu on small screens.
export function initMobileNav() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-site-nav]');

    if (!toggle || !nav) {
        return;
    }

    const closeNav = () => {
        toggle.setAttribute('aria-expanded', 'false');
        nav.classList.remove('is-open');
    };

    const openNav = () => {
        toggle.setAttribute('aria-expanded', 'true');
        nav.classList.add('is-open');
    };

    toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
            closeNav();
            return;
        }

        openNav();
    });

    nav.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeNav);
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 720) {
            closeNav();
        }
    });
}
