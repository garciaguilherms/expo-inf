<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900">
                            <img :src="project.image" class="album-image" />
                            <h2 class="album-title">{{ project.title }}</h2>
                            <p class="album-artist">{{ project.authors }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script>
import {Head} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: {
        AuthenticatedLayout,
        Head
    },
    props: {
        project: Object
    }
}
</script>

<style scoped>

.album-image {
    width: 20%;
    height: 20%;
    border-radius: 8px;
    object-fit: cover;
    margin-bottom: 10px;
    margin-top: 10px;
}

.album-title {
    font-size: 18px;
    font-weight: bold;
}

.album-artist {
    font-size: 14px;
    color: #888;
}

</style>
