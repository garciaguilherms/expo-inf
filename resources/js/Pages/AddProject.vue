<template>
    <Head title="Criar Projeto" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900">
                            <form @submit.prevent="addProject">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" v-model="projectData.title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <textarea class="form-control" id="description" v-model="projectData.description" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">Imagem de capa</label>
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
                                    <select class="form-control" id="author" v-model="projectData.section_id" required>
                                        <option value="" disabled>Selecione a seção</option>
                                        <option v-for="section in sections" :value="section.id">{{ section.title }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="visibility">Visível</label>
                                    <input type="checkbox" class="form-control" id="visibility" v-model="projectData.visibility" :checked="projectData.visibility">
                                </div>
                                <button type="submit" class="btn btn-primary">Criar projeto</button>
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

export default {
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
            projectData: 'projects/projectData',
            users: 'users/allUsers',
        }),
        sections() {
            return this.$store.state.sections.sections;
        },
    },
    methods: {
        addProject() {
            this.$store.dispatch('projects/addProject', this.projectData)
                .then((response) => {
                    console.log(response);
                })
                .catch((error) => {
                    console.error('Error creating project:', error);
                });
        },
    },
}
</script>

<style scoped>
.form-group {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.album-form-input {
    width: 300px;
    height: 40px;
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
