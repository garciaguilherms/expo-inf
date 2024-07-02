const state = {
    title: '',
    description: '',
    sections: [],
    created_by: '',
    created_at: '',
    projects: [],
};

const getters = {
    sectionData: state => state,
};

const mutations = {
    updateSectionData(state, data) {
        state.title = data.title;
        state.description = data.description;
        state.projects = data.projects;
        state.created_by = data.created_by;
        state.created_at = data.created_at;
    },
    setSections(state, sections) {
        state.sections = sections;
    },
};

const actions = {
    setSectionData({ commit }, data) {
        commit('updateSectionData', data);
    },
    fetchSections({ commit }) {
        return new Promise((resolve, reject) => {
            axios
                .get('/all-sections')
                .then(response => {
                    commit('setSections', response.data);
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    addSection({ commit }, sectionData) {
        return new Promise((resolve, reject) => {
            axios
                .post('/sections', sectionData)
                .then(response => {
                    commit('setSections', response.data);
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
        });
    },
    updateSection({ commit }, sectionData) {
        return new Promise((resolve, reject) => {
            axios
                .put('/sections/' + sectionData.id, sectionData)
                .then(response => {
                    commit('setSections', response.data);
                    resolve(response.data);
                })
                .catch(error => {
                    reject(error);
                });
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
