<template>
    <Head title="Criar Projeto" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900">
                            <form @submit.prevent="addSection()">
                                <div class="form-group">
                                    <label for="title">Título</label>
                                    <input type="text" class="form-control" id="title" v-model="sectionData.title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Descrição</label>
                                    <textarea class="form-control" id="description" v-model="sectionData.description" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="tag">Tag</label>
                                    <input type="text" class="form-control" id="tag" v-model="sectionData.tags" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Criar seção</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import {mapGetters} from "vuex";
import {useToastr} from "@/toastr";

export default {
    components: {
        AuthenticatedLayout,
        Head
    },
    computed: {
        ...mapGetters({
            sectionData: 'sections/sectionData',
        }),
    },
    methods: {
        addSection() {
            this.$store.dispatch('sections/addSection', this.sectionData)
                .then((response) => {
                    useToastr().success('Seção criada com sucesso!');
                })
                .catch((error) => {
                    useToastr().error('Erro ao criar seção!');
                    console.error('Error creating section:', error);
                })
                .finally(() => {
                    this.$inertia.get('/dashboard');
                });
        },
    }
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
