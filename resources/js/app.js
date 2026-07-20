import './bootstrap';

import Alpine from 'alpinejs';

import iziToast from 'izitoast';
import 'izitoast/dist/css/iziToast.min.css';

window.Alpine = Alpine;

// Configuration par défaut d'iziToast, alignée sur le thème Generation PUSH.
iziToast.settings({
    position: 'topRight',
    timeout: 5000,
    progressBar: true,
    close: true,
    transitionIn: 'fadeInDown',
    transitionOut: 'fadeOutUp',
    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
});

window.notify = function (type, message, title = null) {
    const options = { message };

    if (title) {
        options.title = title;
    }

    switch (type) {
        case 'success':
            iziToast.success(options);
            break;
        case 'error':
        case 'danger':
            iziToast.error(options);
            break;
        case 'warning':
            iziToast.warning(options);
            break;
        default:
            iziToast.info(options);
            break;
    }
};

/**
 * Directive x-reveal : anime un élément (fade + translate) quand il entre
 * dans le viewport au scroll. Utilisation :
 *   <div x-reveal>...</div>
 *   <div x-reveal.delay.200>...</div>   (délai en ms)
 *   <div x-reveal="'up'">...</div>      (direction: up | down | left | right | zoom)
 */
Alpine.directive('reveal', (el, { expression, modifiers }, { evaluate }) => {
    const direction = expression ? evaluate(expression) : 'up';
    const delayModifierIndex = modifiers.indexOf('delay');
    const delay = delayModifierIndex !== -1 ? parseInt(modifiers[delayModifierIndex + 1] || '0', 10) : 0;

    const hiddenTransforms = {
        up: 'translateY(24px)',
        down: 'translateY(-24px)',
        left: 'translateX(24px)',
        right: 'translateX(-24px)',
        zoom: 'scale(0.95)',
    };

    el.style.opacity = '0';
    el.style.transform = hiddenTransforms[direction] || hiddenTransforms.up;
    el.style.transition = `opacity 700ms ease-out ${delay}ms, transform 700ms ease-out ${delay}ms`;

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0) translateX(0) scale(1)';
                    observer.unobserve(el);
                }
            });
        },
        { threshold: 0.15 }
    );

    observer.observe(el);
});

Alpine.start();
