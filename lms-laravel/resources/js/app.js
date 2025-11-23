import './bootstrap';

import { createIcons, icons } from 'lucide';
function initIcons() {
    createIcons({
        icons,
        attrs: {
            'stroke-width': 1.5,
        }
    });
}

initIcons();
document.addEventListener('livewire:navigated', () => {
    initIcons();
});
