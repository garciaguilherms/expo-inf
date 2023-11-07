<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template>
            <h2 class="font-semibold text-2xl text-blue-700 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12 flex justify-center items-center">
            <div class="bg-gray-300 shadow-lg rounded-sm">
                <div class="p-6 text-gray-900">
                    <ProjectsIndex :projects="$page.props.projects"/>
                </div>
            </div>
        </div>
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
