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
                    console.log(response);
                })
                .catch((error) => {
                    console.error('Error creating section:', error);
                });
        },
    }
}
</script>
