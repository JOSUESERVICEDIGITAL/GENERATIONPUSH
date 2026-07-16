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

/**
 * Helper global utilisable partout (Blade inline scripts, Alpine, JS custom) :
 *   notify('success', 'Enregistré avec succès.')
 *   notify('error', 'Une erreur est survenue.')
 *   notify('info', 'Information.')
 *   notify('warning', 'Attention.')
 */
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

Alpine.start();
