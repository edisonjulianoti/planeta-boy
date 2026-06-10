// Header scroll effect
const header = document.getElementById('header');
if (header) {
    const SCROLLED_CLASSES = ['bg-zinc-900/80', 'backdrop-blur-xl', 'shadow-lg', 'shadow-black/20'];

    function updateHeader() {
        if (window.scrollY > 20) {
            header.classList.add(...SCROLLED_CLASSES);
            header.dataset.scrolled = 'true';
        } else {
            header.classList.remove(...SCROLLED_CLASSES);
            header.dataset.scrolled = 'false';
        }
    }

    window.addEventListener('scroll', updateHeader, { passive: true });
    updateHeader(); // estado inicial consistente
}

// Mobile menu toggle
const btn = document.getElementById('mobile-menu-btn');
const menu = document.getElementById('mobile-menu');
const iconMenu = document.getElementById('icon-menu');
const iconClose = document.getElementById('icon-close');

if (btn && menu && iconMenu && iconClose) {
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        iconMenu.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    });
}

// User dropdown toggle
const dropdownBtn = document.getElementById('user-dropdown-btn');
const dropdownMenu = document.getElementById('user-dropdown-menu');
const chevron = document.getElementById('dropdown-chevron');

if (dropdownBtn && dropdownMenu) {
    // Toggle ao clicar no botao
    dropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        const isHidden = dropdownMenu.classList.contains('hidden');

        if (isHidden) {
            dropdownMenu.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            dropdownMenu.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    });

    // Fechar ao clicar fora
    document.addEventListener('click', (e) => {
        if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    });

    // Fechar ao pressionar ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            dropdownMenu.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    });
}
