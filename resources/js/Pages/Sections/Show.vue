<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-12 flex md:flex-row justify-center items-center">
            <div class="w-full">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-4">{{ section.title }}</h1>
                    <p>{{ section.description }}</p>
                </div>
                <div class="p-6 text-gray-900">
                    <h1 class="text-1xl text-gray-700 font-bold mb-4">Projetos pertencentes a essa seção:</h1>
                    <ProjectsIndex :projects="section.projects" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ProjectsIndex from '@/Pages/Projects/Index.vue';

export default {
    name: 'Show',
    components: { AuthenticatedLayout, Head, ProjectsIndex },
    props: ['section'],
    mounted() {
        console.log('Section:', this.section);
    },
};
</script>

<style scoped>
.project-card {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 10px;
    padding: 10px;
}

.project-content {
    flex-grow: 1;
    overflow: hidden;
}

.project-title {
    font-size: 18px;
    font-weight: bold;
}

.project-description {
    font-size: 14px;
}

.project-image {
    height: 50px;
    margin-left: 10px;
    width: 80px;
}
</style>
