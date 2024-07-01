const state = {
    users: [],
};

const getters = {
    allUsers: state => state.users,
};

const mutations = {
    setUsers(state, users) {
        state.users = users;
    },
};

const actions = {
    fetchUsers({ commit }) {
        axios
            .get('/users')
            .then(response => {
                commit('setUsers', response.data);
            })
            .catch(error => {
                console.error('Erro ao buscar usuários:', error);
            });
    },
};

export default {
    namespaced: true,
    state,
    getters,
    mutations,
    actions,
};
