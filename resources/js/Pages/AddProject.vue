<template>
    <Head title="Criar Projeto" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="form">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900">
                            <form @submit.prevent="isEditing ? updateProject() : addProject()">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" v-model="projectData.title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <textarea class="form-control" id="description" v-model="projectData.description" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">Imagem de capa (URL)</label>
                                    <input type="text" class="form-control" id="image" v-model="projectData.image" required>
                                </div>
                                <div class="form-group">
                                    <label for="author_id">Autores</label>
                                    <select class="form-control" id="author" v-model="projectData.author_id" required>
                                        <option value="" disabled>Selecione os autores</option>
                                        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="author_id">Seção</label>
                                    <select class="form-control" id="author" v-model="projectData.section_id">
                                        <option value="" disabled>Selecione a seção</option>
                                        <option v-for="section in sections" :value="section.id">{{ section.title }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="visibility">Visível</label>
                                    <input type="checkbox" class="form-control" id="visibility" v-model="projectData.visibility" :checked="projectData.visibility">
                                </div>
                                <button v-if="!isEditing" type="submit" class="btn btn-primary">Criar projeto</button>
                                <button v-else type="submit" class="btn btn-primary">Atualizar projeto</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { mapGetters } from 'vuex';
import { useToastr } from '@/toastr.js';

export default {
    data() {
        return {
            projectData: this.isEditing ? this.initialProjectData : {},
        };
    },
    props: ['initialProjectData', 'isEditing'],
    components: {
        AuthenticatedLayout,
        Head,

    },
    created() {
        this.$store.dispatch('users/fetchUsers');
        this.$store.dispatch('sections/fetchSections');
    },
    computed: {
        ...mapGetters({
            compoutedProjectData: 'projects/projectData',
            users: 'users/allUsers',
        }),
        sections() {
            return this.$store.state.sections.sections;
        },
    },
    methods: {
        addProject() {
            this.$store.dispatch('projects/addProject', this.projectData)
                .then(() => {
                    useToastr().success('Projeto criado com sucesso!');
                })
                .catch(() => {
                    useToastr().error('Erro ao criar projeto!');
                })
                .finally(() => {
                    this.$inertia.get('/dashboard');
                });
        },
        updateProject() {
            this.$store.dispatch('projects/updateProject', this.projectData)
                .then(() => {
                    this.$store.commit('projects/updateProjectData', this.projectData);
                    useToastr().success('Projeto atualizado com sucesso!');
                })
                .catch(() => {
                    useToastr().error('Erro ao atualizar projeto!');
                })
                .finally(() => {
                    this.$inertia.get('/dashboard');
                });
        },
    },
}
</script>

<style scoped>
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
}

.form-group input[type="text"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

.form {
    display: flex;
    flex-direction: column;
    justify-content: center;
    background-color: white;
    width: 50%;
    border-radius: 10px;
}

.btn {
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    background-color: #000000;
    color: white;
    cursor: pointer;
}
</style>
