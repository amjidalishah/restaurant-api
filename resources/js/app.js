import './bootstrap';
import Alpine from 'alpinejs';

import './modules/app.js';

window.Alpine = Alpine;

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});
