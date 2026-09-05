import './bootstrap';

// Mobile menu -----------------------------------------------------------
const menuToggle = document.getElementById('menu-toggle');
const menuClose = document.getElementById('menu-close');
const mobileMenu = document.getElementById('mobile-menu');
const menuOverlay = document.getElementById('menu-overlay');

const setMenu = (open) => {
    if (!mobileMenu) return;
    mobileMenu.classList.toggle('translate-x-full', !open);
    menuOverlay?.classList.toggle('opacity-0', !open);
    menuOverlay?.classList.toggle('pointer-events-none', !open);
    document.body.classList.toggle('overflow-hidden', open);
    menuToggle?.setAttribute('aria-expanded', String(open));
};

menuToggle?.addEventListener('click', () => setMenu(true));
menuClose?.addEventListener('click', () => setMenu(false));
menuOverlay?.addEventListener('click', () => setMenu(false));
mobileMenu?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => setMenu(false));
});
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') setMenu(false);
});

// Sticky header shadow --------------------------------------------------
const header = document.getElementById('site-header');

const onScroll = () => {
    header?.classList.toggle('shadow-xl', window.scrollY > 8);
};

onScroll();
window.addEventListener('scroll', onScroll, { passive: true });

// Reveal on scroll ------------------------------------------------------
const revealables = document.querySelectorAll('.reveal, .reveal-group');
const reveal = (el) => el.classList.add('is-visible');

if ('IntersectionObserver' in window && revealables.length) {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    reveal(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -5% 0px' },
    );

    revealables.forEach((el) => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            reveal(el);
        } else {
            observer.observe(el);
        }
    });
} else {
    revealables.forEach(reveal);
}

// Countdown to the next match -------------------------------------------
const countdown = document.querySelector('[data-countdown]');

if (countdown) {
    const target = new Date(countdown.dataset.countdown).getTime();
    const cells = {
        d: countdown.querySelector('[data-cd="d"]'),
        h: countdown.querySelector('[data-cd="h"]'),
        m: countdown.querySelector('[data-cd="m"]'),
        s: countdown.querySelector('[data-cd="s"]'),
    };
    const pad = (n) => String(n).padStart(2, '0');

    const tick = () => {
        const diff = Math.max(0, target - Date.now());
        const s = Math.floor(diff / 1000);
        if (cells.d) cells.d.textContent = pad(Math.floor(s / 86400));
        if (cells.h) cells.h.textContent = pad(Math.floor((s % 86400) / 3600));
        if (cells.m) cells.m.textContent = pad(Math.floor((s % 3600) / 60));
        if (cells.s) cells.s.textContent = pad(s % 60);
    };

    tick();
    setInterval(tick, 1000);
}

// Copy-to-clipboard for bank details -----------------------------------
document.querySelectorAll('[data-copy]').forEach((button) => {
    button.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(button.dataset.copy);
            const label = button.querySelector('[data-copy-label]') || button;
            const original = label.textContent;
            label.textContent = button.dataset.copied || 'OK';
            button.classList.add('is-copied');
            setTimeout(() => {
                label.textContent = original;
                button.classList.remove('is-copied');
            }, 1800);
        } catch {
            /* clipboard unavailable — the value is visible on the page anyway */
        }
    });
});

// Tabs (first team page) ------------------------------------------------
document.querySelectorAll('[data-tabs]').forEach((root) => {
    const buttons = root.querySelectorAll('[data-tab]');
    const panels = root.querySelectorAll('[data-panel]');

    const activate = (name) => {
        buttons.forEach((b) => {
            const on = b.dataset.tab === name;
            b.classList.toggle('is-active', on);
            b.setAttribute('aria-selected', String(on));
        });
        panels.forEach((p) => {
            p.hidden = p.dataset.panel !== name;
        });
    };

    buttons.forEach((b) => b.addEventListener('click', () => activate(b.dataset.tab)));

    const fromHash = window.location.hash.replace('#', '');
    activate(root.querySelector(`[data-tab="${fromHash}"]`) ? fromHash : buttons[0]?.dataset.tab);
});

// Auto-hide flash toast -------------------------------------------------
const toast = document.getElementById('flash-toast');

if (toast) {
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 500);
    }, 6000);
}
