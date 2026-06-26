import './bootstrap';
import '../css/app.css';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
Alpine.start();

// Configure SweetAlert2 with modern Tailwind styles
window.Swal = Swal.mixin({
    customClass: {
        popup: 'rounded-2xl border border-surface-200 dark:border-surface-700 bg-white dark:bg-surface-800 shadow-xl',
        title: 'text-lg font-heading font-semibold text-surface-900 dark:text-white',
        htmlContainer: 'text-sm text-surface-600 dark:text-surface-300',
        confirmButton: 'inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors',
        cancelButton: 'inline-flex justify-center items-center px-4 py-2 border border-surface-300 dark:border-surface-600 text-sm font-medium rounded-lg text-surface-700 dark:text-surface-200 bg-white dark:bg-surface-800 hover:bg-surface-50 dark:hover:bg-surface-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors ml-3',
        actions: 'mt-6',
        icon: 'border-0'
    },
    buttonsStyling: false,
    background: 'transparent'
});

// Auto-initialize Lucide if loaded via CDN
document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
import { initTheme, toggleTheme } from './ui/theme.js';
import { initAlerts } from './ui/alerts.js';
import { initFormConfirmations } from './ui/forms.js';

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initAlerts();
    initFormConfirmations();
    
    // Bind theme toggle button if exists
    const themeBtn = document.getElementById('theme-toggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', toggleTheme);
    }
});
