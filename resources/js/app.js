import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUserSecret, faComment, faStar, faCertificate} from '@fortawesome/free-solid-svg-icons'
import { createStore } from 'vuex'
import projects from './store/modules/projects'
import users from './store/modules/users'

library.add(faUserSecret, faComment, faStar, faCertificate)

const store = createStore({
    modules: {
        projects,
        users,
    },
})

createInertiaApp({
    title: (title) => `${title}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(store)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
