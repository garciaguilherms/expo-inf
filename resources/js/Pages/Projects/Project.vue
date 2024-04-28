<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div
            class="py-12"
            :style="{
                backgroundColor: project.background_image ? '' : project.background_color,
                fontFamily: project.font_family || 'sans-serif',
                backgroundImage: project.background_image ? `url(${project.background_image})` : '',
                backgroundSize: 'cover',
                backgroundRepeat: 'no-repeat',
            }"
        >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="p-6 text-gray-900 flex justify-end flex-col">
                            <div class="image-ranking-box">
                                <img :src="project.image" class="project-image" alt="Imagem do projeto" />
                                <div class="rating-box">
                                    <div class="average-rating">
                                        <font-awesome-icon :icon="['fas', 'star']" size="2xl" style="color: #f2c445" />
                                        <span class="rating-text">{{ project.average_stars }}</span>
                                    </div>
                                </div>
                            </div>
                            <h2 class="project-title">{{ project.title }}</h2>
                            <div
                                class="my-8 prose max-w-none"
                                style="word-wrap: break-word"
                                v-html="project.description"
                            ></div>
                            <p class="project-artist">Autores:</p>
                            <div v-for="author in project.authors">
                                <p class="project-artist">{{ author.name }}</p>
                            </div>
                        </div>
                        <div class="project-comment-box">
                            <h2 class="text-black font-bold">Comentários</h2>
                            <div class="comment-box" v-for="comment in project.comments">
                                <p class="comment-user">{{ comment.user.name }}</p>
                                <p class="comment-text">{{ comment.text }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a
                href="https://forms.gle/Uh2JyVJotumpwKTP6"
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
                <button class="bg-black text-white font-bold py-4 px-4 rounded-full">
                    Formulário de Feedback
                </button>
            </a>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    props: {
        project: Object,
    },
};
</script>
<style scoped>
.project-image {
    object-fit: cover;
    width: 100%;
    height: 100%;
}
.project-title {
    display: flex;
    justify-content: center;
    margin: 10px;
    font-size: 25px;
    font-weight: bold;
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
    justify-content: center;
    max-width: 100%;
    height: 500px;
    position: relative;
}
.average-rating {
    font-size: 20px;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-right: 10px;
}
.rating-box {
    position: absolute;
    bottom: 0px;
    right: 0px;
    background-color: #888;
    padding: 8px;
    border-radius: 4px 0px 0px 0px;
}
.rating-text {
    margin-left: 5px;
    color: white;
}
.comment-user {
    font-weight: bold;
}
.comment-text {
    margin-left: 10px;
}
.prose {
    word-wrap: break-word;
}
</style>
