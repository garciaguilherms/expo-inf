<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-300 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900">
                            <AlbumsIndex :albums="albums"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import AlbumsIndex from '@/Pages/Albums/Index';
import { Head } from '@inertiajs/vue3';


export default {
    components: {
        AuthenticatedLayout,
        AlbumsIndex,
        Head,
    },
    props: {
        albums: Array,
    },
};

</script>
