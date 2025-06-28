import './bootstrap.ts';
import '../css/app.css';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import {createI18n} from "vue-i18n";
import messages from './locales';
import { ZiggyVue, route } from 'ziggy-js';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        const i18n = createI18n({
            locale: props.initialPage.props.locale,
            fallbackLocale: "en",
            messages,
            legacy: false,
            globalInjection: true,
        })
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue)

        app.config.globalProperties.$route = route;
        app.mount(el);

        return app;
    },
})
