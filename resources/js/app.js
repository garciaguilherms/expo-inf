import './bootstrap';

import moment from 'moment';
import { createApp, h, reactive } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { createStore } from 'vuex';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import projects from './store/modules/projects';
import users from './store/modules/users';
import sections from './store/modules/sections';

import {
    faUserSecret,
    faComment,
    faStar,
    faTrash,
    faEllipsisV,
    faReply,
    faList,
    faListOl,
    faBold,
    faItalic,
    faCode,
    faLink,
    faLinkSlash,
} from '@fortawesome/free-solid-svg-icons';

library.add(
    faUserSecret,
    faComment,
    faStar,
    faTrash,
    faEllipsisV,
    faReply,
    faList,
    faListOl,
    faBold,
    faItalic,
    faCode,
    faLink,
    faLinkSlash,
);

const store = createStore({
    modules: {
        projects,
        sections,
        users,
    },
});

let momentReactive = reactive({ moment });

// Incluir código de rastreamento do Hotjar antes de criar a aplicação Inertia
(function (h, o, t, j, a, r) {
    h.hj =
        h.hj ||
        function () {
            (h.hj.q = h.hj.q || []).push(arguments);
        };
    h._hjSettings = { hjid: 5058141, hjsv: 6 };
    a = o.getElementsByTagName('head')[0];
    r = o.createElement('script');
    r.async = 1;
    r.src = t + h._hjSettings.hjid + j + h._hjSettings.hjsv;
    a.appendChild(r);
})(window, document, 'https://static.hotjar.com/c/hotjar-', '.js?sv=');

createInertiaApp({
    title: title => `${title}`,
    resolve: name => require(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(store)
            .provide('$moment', momentReactive)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
