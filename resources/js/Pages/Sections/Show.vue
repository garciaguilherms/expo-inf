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
                    <h1 class="text-1xl text-gray-700 font-bold mb-4">Projetos dessa seção:</h1>
                    <ProjectsIndex :projects="$page.props.projects" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ProjectsIndex from '@/Pages/Projects/Index';

export default {
    name: 'Show',
    components: { AuthenticatedLayout, Head, ProjectsIndex },
    props: ['section', 'projects'],
};
</script>

<style scoped>
.section-bg {
    background-color: black;
    height: 100vh;
}
</style>
