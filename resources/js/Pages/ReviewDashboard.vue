<template>
    <Head title="Avaliações" />
    <AuthenticatedLayout>
        <template>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray-300 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900">
                            <h1 class="text-2xl font-semibold mb-4">Lista de Avaliações</h1>
                            <div v-for="review in reviews" :key="review.id" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                                <div class="p-2 text-gray-900">
                                    <div class="p-5 text-gray-900 flex items-center">
                                        <img :src="review.cover_image" alt="Capa do Álbum" class="album-image mr-4" />
                                        <div>
                                            <h2 class="album-title">{{ review.title }}</h2>
                                            <p v-if="review.review" class="review-text">"{{ review.review }}"</p>
                                            <p v-else class="review-text">O usuário não fez uma avaliação textual</p>
                                        </div>
                                    </div>
                                    <div class="p-6 text-gray-900 flex flex-col">
                                        <p class="ml-1">{{ review.name }}</p>
                                        <div class="flex items-center">
                                            <star-rating
                                                :star-size="20"
                                                :read-only="true"
                                                :show-rating="false"
                                                :rating="review.rating">
                                            </star-rating>
                                        </div>
                                        <p class="text-gray-500 ml-1 mt-1">{{ humanizeDate(review.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import StarRating from "vue-star-rating";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import AlbumsIndex from "@/Pages/Albums/Index.vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import moment from 'moment';
import 'moment/locale/pt-br';
export default {
    components: {
        AuthenticatedLayout,
        StarRating,
        AlbumsIndex,
        Head,
    },
    mounted() {
        this.loadReviews();
    },
    props: {
        reviews: Array,
    },
    methods: {
        loadReviews() {
            axios.get('/reviews').then(response => {
                this.reviews = response.data;
            });
        },
        humanizeDate(date) {
            return moment(date).locale('pt-br').fromNow();
        },
    }
};

</script>

<style scoped>
.album-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
}

.album-title {
    font-size: 1.2rem;
    font-weight: bold;
}

.review-text {
    margin-top: 0.5rem;
    font-size: 1rem;
    font-style: italic;
}

.ml-2 {
    margin-left: 0.5rem;
}

.star-rating {
    display: inline-flex;
    align-items: center;
}
</style>

