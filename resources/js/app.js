// Header scroll effect
const header = document.getElementById('header');
if (header) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            header.classList.add('bg-black/70', 'backdrop-blur-xl', 'border-b', 'border-zinc-800/50', 'shadow-lg', 'shadow-black/20');
            header.classList.remove('bg-transparent');
        } else {
            header.classList.remove('bg-black/70', 'backdrop-blur-xl', 'border-b', 'border-zinc-800/50', 'shadow-lg', 'shadow-black/20');
            header.classList.add('bg-transparent');
        }
    });
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
