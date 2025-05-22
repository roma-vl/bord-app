import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h, watch } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createI18n } from 'vue-i18n';
import { usePage } from '@inertiajs/vue3';
import messages from '@/lang';
import CanDirective from '@/directives/can.js';
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const i18n = createI18n({
      // legacy: false,
      locale: props.initialPage.props.locale || 'uk',
      messages,
    });

    watch(
      () => usePage().props?.locale,
      (newLocale) => {
        i18n.global.locale = newLocale;
      }
    );

    return createApp({ render: () => h(App, props) })
      .use(i18n)
      .directive('can', CanDirective)
      .use(plugin)
      .use(ZiggyVue)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
