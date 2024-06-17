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

import { faUserSecret, faComment, faStar, faTrash, faEllipsisV, faReply } from '@fortawesome/free-solid-svg-icons';

library.add(faUserSecret, faComment, faStar, faTrash, faEllipsisV, faReply);

const store = createStore({
    modules: {
        projects,
        sections,
        users,
    },
});

let momentReactive = reactive({ moment });

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
