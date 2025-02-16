import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import Lara from '@primevue/themes/lara';
import 'primeicons/primeicons.css';
import '../css/custom.css';
import { Badge, ConfirmationService, Toast, ToastService, Tooltip } from 'primevue';

const appName = import.meta.env.VITE_APP_NAME;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin)
            .use(PrimeVue, { theme: { preset: Lara } })
            .use(ToastService)
            .use(ZiggyVue)
            .use(ConfirmationService)
            .component('Toast', Toast)
            .component('Badge', Badge)
            .directive('tooltip', Tooltip)
            .mount(el);
    },

    progress: {
        color: '#4B5563',
    },
});
