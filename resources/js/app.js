import './bootstrap';
import '../css/app.css';
import './chartSetup.js'; // Import Chart.js setup to register all components

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import axios from 'axios';

const appName = import.meta.env.VITE_APP_NAME || 'CICTE CertChain';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// Global error handler for CSRF token issues
window.addEventListener('unhandledrejection', function (event) {
    if (event.reason && event.reason.response && event.reason.response.status === 419) {
        console.log('Unhandled 419 error detected, refreshing page...');
        event.preventDefault();
        window.location.reload();
    }
});
