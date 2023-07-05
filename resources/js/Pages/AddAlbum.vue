<template>
    <Head title="Adicionar Album" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900">
                            <form @submit.prevent="addAlbum" class="album-form">
                                <input type="text" v-model="title" placeholder="Título do álbum" class="album-form-input">
                                <input type="text" v-model="artist" placeholder="Artista do álbum" class="album-form-input">
                                <input type="text" v-model="release_year" placeholder="Ano de lançamento do álbum" class="album-form-input">
                                <input type="text" v-model="cover_image" placeholder="Link da capa do álbum" class="album-form-input">
                                <button type="submit" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-lg">Adicionar álbum</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import axios from 'axios';
export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    data() {
        return {
            title: '',
            artist: '',
            release_year: '',
            cover_image: '',
        };
    },
    methods: {
        addAlbum() {
            axios.post('/albums', {
                title: this.title,
                artist: this.artist,
                release_year: this.release_year,
                cover_image: this.cover_image,
            }).then(response => {
                this.$inertia.visit('/dashboard');
            }).catch(error => {
                console.log(error);
            });
        },
    },
}
</script>

<style scoped>
.album-form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.album-form-input {
    width: 300px;
    height: 40px;
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.album-form-submit {
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    cursor: pointer;
}

.album-form-submit:hover {
    background-color: #0056b3;
}
</style>
