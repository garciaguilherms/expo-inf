<template>
    <Head title="Criar Projeto" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="form">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="isEditing ? updateProject() : addProject()">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required:" for="title">Título</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="title"
                                            v-model="projectData.title"
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Descrição</label>
                                        <TipTap v-model="projectData.description" />
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Imagem de capa (URL)</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="image"
                                            v-model="projectData.image"
                                        />
                                    </div>
                                    <div class="form-group-author">
                                        <label>Autores</label>
                                        <input
                                            type="text"
                                            class="form-control search-authors"
                                            placeholder="Buscar autor"
                                            v-model="searchQuery"
                                        />
                                        <div v-if="searchQuery" class="author-list">
                                            <label v-for="user in filteredUsers" :key="user.id" class="author-checkbox">
                                                <input
                                                    type="checkbox"
                                                    v-model="projectData.selectedAuthors"
                                                    :value="user.id"
                                                />
                                                {{ user.name }}
                                            </label>
                                        </div>
                                        <div class="selected-authors" v-if="projectData.selectedAuthors.length">
                                            <h4 class="font-bold">Autores Selecionados</h4>
                                            <ul>
                                                <li v-for="userId in projectData.selectedAuthors" :key="userId">
                                                    {{ getUserById(userId).name }}
                                                    <button @click="removeAuthor(userId)">Remover</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="section_id">Galeria</label>
                                        <select class="form-control" id="section_id" v-model="projectData.section_id">
                                            <option value="" disabled>Selecione a galeria</option>
                                            <option v-for="section in sections" :key="section.id" :value="section.id">
                                                {{ section.title }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <ProjectCustomization :projectData="projectData" />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" :disabled="isLoading">
                                <span v-if="isLoading">{{ isEditing ? 'Atualizando...' : 'Criando...' }}</span>
                                <span v-else>{{ isEditing ? 'Atualizar projeto' : 'Criar projeto' }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProjectCustomization from '@/Components/ProjectPersonalization.vue';
import { Head } from '@inertiajs/vue3';
import { mapGetters } from 'vuex';
import { useToastr } from '@/toastr.js';
import TipTap from '@/Components/TipTap.vue';

export default {
    data() {
        return {
            searchQuery: '',
            projectData: this.isEditing
                ? {
                      ...this.initialProjectData,
                      selectedAuthors: this.initialProjectData.author_id,
                  }
                : {
                      title: '',
                      description: '',
                      image: '',
                      selectedAuthors: [],
                      section_id: '',
                  },
            isLoading: false,
            errors: {},
        };
    },
    props: ['initialProjectData', 'isEditing'],
    components: {
        TipTap,
        AuthenticatedLayout,
        Head,
        ProjectCustomization,
    },
    created() {
        this.$store.dispatch('users/fetchUsers');
        this.$store.dispatch('sections/fetchSections');
    },
    computed: {
        ...mapGetters({
            computedProjectData: 'projects/projectData',
            users: 'users/allUsers',
        }),
        sections() {
            return this.$store.state.sections.sections;
        },
        filteredUsers() {
            return this.users.filter(user => user.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
        },
    },
    methods: {
        addProject() {
            this.isLoading = true;
            this.projectData.author_id = this.projectData.selectedAuthors;
            this.$store
                .dispatch('projects/addProject', this.projectData)
                .then(() => {
                    useToastr().success('Projeto criado com sucesso!');
                    this.$inertia.get('/');
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                        for (const key in this.errors) {
                            this.errors[key].forEach(errorMsg => useToastr().error(errorMsg));
                        }
                    } else {
                        useToastr().error('Erro ao criar projeto!');
                    }
                    this.isLoading = false;
                });
        },
        updateProject() {
            this.isLoading = true;
            this.projectData.author_id = this.projectData.selectedAuthors;
            this.$store
                .dispatch('projects/updateProject', this.projectData)
                .then(() => {
                    useToastr().success('Projeto atualizado com sucesso!');
                    this.$inertia.get('/');
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        this.errors = error.response.data.errors;
                        for (const key in this.errors) {
                            this.errors[key].forEach(errorMsg => useToastr().error(errorMsg));
                        }
                    } else {
                        useToastr().error('Erro ao atualizar projeto!');
                    }
                    this.isLoading = false;
                });
        },
        getUserById(id) {
            return this.users.find(user => user.id === id);
        },
        removeAuthor(id) {
            this.projectData.selectedAuthors = this.projectData.selectedAuthors.filter(authorId => authorId !== id);
        },
    },
};
</script>

<style scoped>
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
}
.form-group input[type='text'],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}
.form-group-author {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    height: auto;
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 20px;
}
.form-group-author-name {
    margin-left: 10px;
}
.form {
    display: flex;
    flex-direction: column;
    background-color: white;
    width: 50%;
    border-radius: 10px;
    margin: 0 auto;
}
.btn {
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    background-color: #000000;
    color: white;
    cursor: pointer;
}
.btn:disabled {
    background-color: #555555;
    cursor: not-allowed;
}
.search-authors {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    width: 100%;
    box-sizing: border-box;
}
.author-list {
    padding: 10px 0;
}
.author-checkbox {
    display: block;
    margin-bottom: 5px;
}
.selected-authors {
    margin-top: 20px;
}
.selected-authors h4 {
    margin-bottom: 10px;
}
.selected-authors ul {
    list-style-type: none;
    padding: 0;
}
.selected-authors li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 5px;
}
.selected-authors button {
    background-color: #ff4d4d;
    border: none;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}
</style>
