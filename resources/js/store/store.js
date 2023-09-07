import { createStore } from 'vuex'
import projects from './modules/projects'
import users from './modules/users'

const store = createStore({
    modules: {
        projects,
        users,
    },
})

export default store
