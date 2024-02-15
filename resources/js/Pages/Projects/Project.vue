<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900 flex justify-end flex-col">
                            <div class="image-ranking-box">
                                <img :src="project.image" class="project-image" alt="Imagem do projeto" />
                                <div>
                                    <div class="average-rating">
                                        <font-awesome-icon :icon="['fas', 'star']" size="2xl" style="color: #f2c445" />
                                        {{ project.average_stars }}
                                    </div>
                                </div>
                            </div>
                            <h2 class="project-title">{{ project.title }}</h2>
                            <p class="project-description">
                                {{ project.description }}
                            </p>
                            <p class="project-artist">Autores:</p>
                            <div v-for="author in project.authors">
                                <p class="project-artist">{{ author.name }}</p>
                            </div>
                        </div>
                        <div class="project-comment-box">
                            <h2 class="text-black font-bold">Comentários</h2>
                            <div class="comment-box" v-for="comment in project.comments">
                                <p>{{ comment.user.name }}</p>
                                <p>{{ comment.text }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

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
.project-image {
    width: 50%;
    height: 50%;
    border-radius: 8px;
    object-fit: cover;
    margin-bottom: 10px;
    margin-top: 10px;
}

.project-title {
    font-size: 18px;
    font-weight: bold;
}

.project-description {
    font-size: 14px;
    font-weight: bold;
    color: #888;
}

.project-artist {
    font-size: 12px;
    color: #888;
}

.project-comment-box {
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 1rem;
    margin-bottom: 1rem;
}

.project-comment-box h2 {
    color: #000;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.comment-box {
    background-color: #fff;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
}

.comment-box p {
    margin-bottom: 0.25rem;
}

.image-ranking-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.average-rating {
    font-size: 20px;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-right: 10px;
}
</style>
