import { createStore } from 'vuex'
import projects from './modules/projects'
import sections from './modules/sections'
import users from './modules/users'

const store = createStore({
    modules: {
        projects,
        sections,
        users,
    },
})
export default store
