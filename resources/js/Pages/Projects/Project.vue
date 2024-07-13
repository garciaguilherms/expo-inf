<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div
            class="py-12 background-container"
            :style="{
                backgroundImage: project.background_image ? `url(${project.background_image})` : '',
                backgroundSize: 'cover',
                backgroundRepeat: 'no-repeat',
            }"
        >
            <div class="project-container">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 flex flex-col lg:flex-row">
                        <!-- Adicionei flex-col e flex-row para controlar o layout responsivo -->
                        <div class="w-full lg:w-1/3 pr-4">
                            <!-- Condicional para mostrar a imagem de capa apenas em telas maiores que md -->
                            <div class="image-box md:flex justify-center">
                                <img :src="project.image" class="project-image" alt="Imagem do projeto" />
                            </div>
                            <h2 class="project-title">{{ project.title }}</h2>
                            <p class="project-author">Autores do projeto:</p>
                            <div v-for="author in project.authors.slice(0, 2)" :key="author.id">
                                <!-- Mostra apenas os dois primeiros autores -->
                                <p class="project-author">{{ author.name }}</p>
                            </div>
                            <p v-if="project.authors.length > 2" class="project-author">
                                e mais {{ project.authors.length - 2 }}
                            </p>
                            <!-- Mostra "e mais X" se houver mais autores -->
                            <p class="project-date">Criado {{ formatDate(project.created_at) }}</p>
                        </div>
                        <div class="w-full lg:w-2/3 pl-4">
                            <div
                                class="my-8 prose max-w-none"
                                style="word-wrap: break-word"
                                v-html="project.description"
                            ></div>
                            <form class="comment-form" @submit.prevent="postComment(project.id)">
                                <textarea
                                    class="comment-input"
                                    placeholder="Deixe seu comentário aqui..."
                                    v-model="newComment"
                                ></textarea>
                                <button type="submit" class="comment-submit-btn" :disabled="commenting">
                                    <span v-if="commenting">Comentando...</span>
                                    <span v-else>Enviar</span>
                                </button>
                            </form>
                            <div class="project-comment-box">
                                <p class="comment-section-title">Seção de comentários</p>
                                <div class="comment-box" v-for="comment in project.comments" :key="comment.id">
                                    <p class="comment-user">{{ comment.user_name }}</p>
                                    <p class="comment-text">{{ comment.text }}</p>
                                    <p class="comment-date">{{ formatDate(comment.created_at) }}</p>
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
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import moment from 'moment/moment';

export default {
    components: {
        AuthenticatedLayout,
        Head,
    },
    props: {
        project: Object,
    },
    data() {
        return {
            newComment: '',
            newReply: '',
            replyingTo: null,
            commenting: false,
        };
    },
    methods: {
        postComment(projectId) {
            if (!this.newComment) {
                console.error('O campo de texto é obrigatório.');
                return;
            }

            this.commenting = true;

            axios
                .post('/projects/' + projectId + '/comments', {
                    text: this.newComment,
                })
                .then(() => {
                    this.newComment = '';
                })
                .catch(error => {
                    console.error(error.response);
                })
                .finally(() => {
                    this.commenting = false;
                    this.$inertia.get('/projects/' + projectId);
                });
        },
        showReplyForm(commentId) {
            this.replyingTo = commentId;
        },
        formatDate(date) {
            return moment(date).locale('pt-br').fromNow();
        },
    },
};
</script>

<style scoped>
.background-container {
    position: relative;
    overflow: hidden;
    min-height: 100vh;
}

.background-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    pointer-events: none;
}

.project-container {
    max-width: 100rem;
    margin-left: auto;
    margin-right: auto;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    position: relative;
    z-index: 1;
}

.project-image {
    object-fit: cover;
    width: 100%;
    height: auto;
}

.project-title {
    text-align: center;
    margin: 10px 0;
    font-size: 25px;
    font-weight: bold;
}

.project-author {
    font-size: 14px;
    color: #888;
}

.project-date {
    font-size: 12px;
    color: #888;
    margin-top: 10px;
}

.comment-section-title {
    font-size: 20px;
    font-weight: bold;
    border-bottom: 1px solid #000000;
    margin-bottom: 1.5rem;
}

.project-comment-box {
    background-color: #f8f8f8;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

.comment-box {
    margin-top: 0.5rem;
    border-bottom: 1px solid #e1e1e1;
    padding-bottom: 0.5rem;
    margin-bottom: 0.5rem;
}

.comment-user {
    color: #333333;
    font-weight: bold;
}

.comment-text {
    color: #555555;
}

.image-box {
    display: flex;
    justify-content: center;
    max-width: 100%;
    height: 500px;
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    margin-top: 1.5rem;
}

.comment-form {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    margin-top: 1rem;
    margin-bottom: 1rem;
    background-color: #f3f3f3;
    border-radius: 8px;
    padding: 1rem;
}

.comment-input {
    width: 100%;
    margin-bottom: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 0.5rem;
}

.comment-submit-btn {
    background-color: #000000;
    border: none;
    color: white;
    padding: 8px 8px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    cursor: pointer;
    border-radius: 4px;
}

.comment-submit-btn:hover {
    background-color: #505050;
}

.comment-user {
    font-weight: bold;
}

.comment-text {
    margin-left: 10px;
}

.comment-date {
    font-size: 12px;
    color: #888;
    margin-top: 8px;
    margin-left: 8px;
}

.prose {
    word-wrap: break-word;
}

@media (min-width: 1024px) {
    .project-container {
        padding-left: 2rem;
        padding-right: 2rem;
    }
}
</style>
