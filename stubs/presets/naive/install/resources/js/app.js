import './bootstrap';
import '../css/app.css';
import 'vfonts/Lato.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import {
    darkTheme,
    NConfigProvider,
    NDialogProvider,
    NMessageProvider,
    NNotificationProvider,
} from 'naive-ui';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';

const appName = import.meta.env.VITE_APP_NAME || 'Servicio Linea Once';

const themeOverrides = {
    common: {
        borderRadius: '8px',
        borderRadiusSmall: '6px',
        primaryColor: '#06b6d4',
        primaryColorHover: '#22d3ee',
        primaryColorPressed: '#0891b2',
        primaryColorSuppl: '#22d3ee',
        successColor: '#10b981',
        warningColor: '#f59e0b',
        errorColor: '#f43f5e',
        fontFamily: "'Lato', ui-sans-serif, system-ui, sans-serif",
    },
    Card: {
        color: 'rgba(15, 23, 42, 0.78)',
        borderColor: 'rgba(148, 163, 184, 0.16)',
        titleTextColor: '#f8fafc',
    },
    Button: {
        fontWeight: '700',
        borderRadiusMedium: '8px',
    },
    Input: {
        color: 'rgba(15, 23, 42, 0.9)',
        colorFocus: 'rgba(15, 23, 42, 0.95)',
        border: '1px solid rgba(148, 163, 184, 0.22)',
        borderFocus: '1px solid #22d3ee',
    },
};

const RootProviders = {
    render: () =>
        h(
            NConfigProvider,
            { theme: darkTheme, themeOverrides },
            {
                default: () =>
                    h(NNotificationProvider, null, {
                        default: () =>
                            h(NDialogProvider, null, {
                                default: () =>
                                    h(NMessageProvider, null, {
                                        default: () => h(AppShell),
                                    }),
                            }),
                    }),
            },
        ),
};

let AppShell;

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        AppShell = { render: () => h(App, props) };

        createApp(RootProviders)
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#06b6d4',
    },
});
