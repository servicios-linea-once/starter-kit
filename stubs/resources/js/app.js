import './bootstrap';
import '../css/app.css';
import 'primeicons/primeicons.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import PrimeVue from 'primevue/config';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import NexoPremiumPreset from './presets/nexo-premium.preset';

const appName = import.meta.env.VITE_APP_NAME || 'KitLauncher';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {
                theme: {
                    preset: NexoPremiumPreset,
                    options: {
                        prefix: 'p',
                        darkModeSelector: '.app-dark',
                        cssLayer: false
                    }
                },
                inputVariant: 'filled',
                ripple: true
            })
            .mount(el);
    },
    progress: {
        color: '#10b981',
    },
});
