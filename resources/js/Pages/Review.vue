<template>
    <Head title="Avaliações" />
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

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    props: {
        album: Object,
        user_id: Number
    },
    data() {
        return {
            review: '',
        };
    },
    methods: {
        handleSubmit() {
            axios.post(`/albums/${this.album.id}/reviews`, {
                review: this.review,
            }).then(() => {
                this.$inertia.visit('/dashboard');
            });
        },
    },
};
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

.texarea-review {
    width: 100%;
    height: 100px;
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 10px;
    margin-top: 10px;
}

</style>
