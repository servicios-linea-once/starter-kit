import './bootstrap';
import '../css/app.css';

import { createInertiaApp } from '@inertiajs/react';
import CssBaseline from '@mui/material/CssBaseline';
import { ThemeProvider } from '@mui/material/styles';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { LanguageProvider } from '@/lib/i18n';
import { muiTheme } from '@/lib/muiTheme';

const appName = import.meta.env.VITE_APP_NAME || 'Servicio Linea Once';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        createRoot(el).render(
            <ThemeProvider theme={muiTheme}>
                <CssBaseline enableColorScheme />
                <LanguageProvider>
                    <App {...props} />
                </LanguageProvider>
            </ThemeProvider>,
        );
    },
    progress: {
        color: '#06b6d4',
    },
});
