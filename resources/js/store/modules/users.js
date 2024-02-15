const state = {
    users: []
}

const getters = {
    allUsers: state => state.users
}

const mutations = {
    setUsers(state, users) {
        state.users = users
    }
}

const actions = {
    fetchUsers({ commit }) {
        return new Promise((resolve, reject) => {
            axios
                .get('/users')
                .then(response => {
                    commit('setUsers', response.data)
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
        })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions
}
