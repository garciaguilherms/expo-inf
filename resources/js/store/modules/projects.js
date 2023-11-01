const state = {
    title: '',
    description: '',
    image: '',
    author_id: null,
    section_id: null,
    visibility: false,
    authors: [],
    created_at: '',
    comments: [],
};

const getters = {
    projectData: (state) => state,
};

const mutations = {
    updateProjectData(state, data) {
        state.title = data.title;
        state.description = data.description;
        state.image = data.image;
        state.author_id = data.author_id;
        state.authors = data.authors;
        state.visibility = data.visibility;
    },
    resetProjectData(state) {
        state.title = '';
        state.description = '';
        state.image = '';
        state.author_id = null;
        state.visibility = false;
        state.authors = [];
    },
    addNewProject(state, projectData) {
        state.title = projectData.title;
        state.description = projectData.description;
        state.image = projectData.image;
        state.author_id = projectData.author_id;
        state.visibility = projectData.visibility;
        state.authors = projectData.authors;
    }
};

const actions = {
    setProjectData({ commit }, data) {
        commit('updateProjectData', data);
    },
    clearProjectData({ commit }) {
        commit('resetProjectData');
    },
    addProject({ commit }, projectData) {
        return new Promise((resolve, reject) => {
            axios.post('/projects', projectData)
                .then((response) => {
                    commit('addNewProject', response.data);
                    resolve(response);
                })
                .catch((error) => {
                    reject(error);
                })
                .finally(() => {
                    this.$inertia.visit('/dashboard');
                })
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
