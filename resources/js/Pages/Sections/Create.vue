<template>
    <Head title="Criar galeria" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="isEditing ? updateSection() : addSection()">
                            <div class="form-group">
                                <label for="title">Título</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="title"
                                    v-model="sectionData.title"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea
                                    class="form-control"
                                    id="description"
                                    v-model="sectionData.description"
                                    required
                                ></textarea>
                            </div>
                            <div class="form-group">
                                <label for="tag">Tag (ex: ufsm; disciplina; projetos; etc...)</label>
                                <input type="text" class="form-control" id="tag" v-model="sectionData.tags" required />
                            </div>
                            <button v-if="!isEditing" type="submit" class="btn btn-primary" :disabled="isLoading">
                                <span v-if="isLoading">Criando...</span>
                                <span v-else>Criar galeria</span>
                            </button>
                            <button v-else type="submit" class="btn btn-primary" :disabled="isLoading">
                                <span v-if="isLoading">Atualizando...</span>
                                <span v-else>Atualizar galeria</span>
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
import { Head } from '@inertiajs/vue3';
import { useToastr } from '@/toastr';

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    data() {
        return {
            sectionData: this.isEditing ? this.initialSectionData : {},
            isLoading: false,
        };
    },
    props: ['initialSectionData', 'isEditing'],
    methods: {
        addSection() {
            this.isLoading = true;
            this.$store
                .dispatch('sections/addSection', this.sectionData)
                .then(() => {
                    useToastr().success('Galeria criada com sucesso!');
                    this.isLoading = false;
                    this.$inertia.get('/sections');
                })
                .catch(error => {
                    useToastr().error('Erro ao criar galeria!');
                    console.error('Error creating section:', error);
                    this.isLoading = false;
                });
        },
        updateSection() {
            this.isLoading = true;
            this.$store
                .dispatch('sections/updateSection', this.sectionData)
                .then(() => {
                    this.$store.commit('sections/updateSectionData', this.sectionData);
                    useToastr().success('Galeria atualizada com sucesso!');
                })
                .catch(() => {
                    useToastr().error('Erro ao atualizar galeria!');
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$inertia.get('/sections');
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
