<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template>
            <h2 class="font-semibold text-2xl text-blue-700 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12 flex justify-center items-center">
            <div class="bg-gray-100 rounded-sm">
                <div class="p-6 text-gray-900">
                    <ProjectsIndex :projects="$page.props.projects" />
                </div>
            </div>
        </div>
        <a
            href="https://forms.gle/678df4o4VqkyJ4dp6"
            target="_blank"
            style="
                text-decoration: none;
                position: fixed;
                bottom: 20px;
                right: 20px;
                word-break: break-word;
                max-width: 150px;
                z-index: 9999;
            "
        >
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-4 rounded-full">
                Formulário de Feedback
            </button>
        </a>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ProjectsIndex from '@/Pages/Projects/Index';
import SectionsIndex from '@/Pages/Sections/Index';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        ProjectsIndex,
        SectionsIndex,
        Head,
    },
    props: {
        projects: Array,
    },
};
</script>
