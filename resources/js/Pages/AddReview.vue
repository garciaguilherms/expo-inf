<template>
    <template>
        <Head title="Avaliação" />
        <AuthenticatedLayout>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="p-6 text-gray-900">
                                <img :src="album.cover_image" alt="Capa do Álbum" class="album-image" />
                                <h2 class="album-title">{{ album.title }}</h2>
                                <p class="album-artist">{{ album.artist }}</p>
                                <form @submit.prevent="handleSubmit">
                                    <textarea v-model="review" rows="5" class="texarea-review" placeholder="Deixe sua avaliação"></textarea>
                                    <button type="submit" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg">Enviar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </template>
</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
</script>

<style scoped>

</style>
