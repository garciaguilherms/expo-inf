<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12 flex">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="mx-auto bg-gray-300 rounded">
                    <ul>
                        <li v-for="project in rankedProjects" :key="project.id">
                            <h2>{{ project.title }}</h2>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mx-auto sm:px-6 lg:px-8 bg-gray-300 mr-10 rounded">
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
    data() {
        return {
            rankedProjects: [],
        }
    },
    mounted() {
        this.getRankedProjects();
    },
    methods: {
        getRankedProjects() {
            axios.get('/projects/ranking')
                .then(response => {
                    this.rankedProjects = response.data;
                })
        },
    },
};

</script>
