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
                                        <label for="title">Título</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="title"
                                            v-model="projectData.title"
                                            required
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
                                            required
                                        />
                                    </div>
                                    <div class="form-group-author">
                                        <label>Autores</label>
                                        <div>
                                            <label v-for="user in users" :key="user.id">
                                                <input
                                                    type="checkbox"
                                                    v-model="projectData.selectedAuthors"
                                                    :value="user.id"
                                                    class="form-group-author-name"
                                                />
                                                {{ user.name }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="section_id">Seção</label>
                                        <select
                                            class="form-control"
                                            id="section_id"
                                            v-model="projectData.section_id"
                                            required
                                        >
                                            <option value="" disabled>Selecione a seção</option>
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
            projectData: this.isEditing
                ? this.initialProjectData
                : {
                      title: '',
                      description: '',
                      image: '',
                      selectedAuthors: [],
                      section_id: '',
                  },
            isLoading: false,
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
    },
    methods: {
        addProject() {
            this.isLoading = true;
            this.projectData.author_id = this.projectData.selectedAuthors;
            this.$store
                .dispatch('projects/addProject', this.projectData)
                .then(() => {
                    useToastr().success('Projeto criado com sucesso!');
                })
                .catch(() => {
                    useToastr().error('Erro ao criar projeto!');
                    this.isLoading = false;
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$inertia.get('/');
                });
        },
        updateProject() {
            this.isLoading = true;
            this.projectData.author_id = this.projectData.selectedAuthors;
            this.$store
                .dispatch('projects/updateProject', this.projectData)
                .then(() => {
                    useToastr().success('Projeto atualizado com sucesso!');
                })
                .catch(() => {
                    useToastr().error('Erro ao atualizar projeto!');
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$inertia.get('/');
                });
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
</style>
