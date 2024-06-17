<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <div
            class="py-12"
            :style="{
                backgroundColor: project.background_image ? '' : project.background_color,
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
                        <form class="comment-form" @submit.prevent="postComment(project.id)">
                            <textarea
                                class="comment-input"
                                placeholder="Deixe seu comentário aqui..."
                                v-model="newComment"
                            ></textarea>
                            <button type="submit" class="comment-submit-btn">Postar</button>
                        </form>
                        <div class="project-comment-box">
                            <p class="comment-section-title">Seção de comentários</p>
                            <div class="comment-box" v-for="comment in project.comments">
                                <p class="comment-user">{{ comment.user.name }}</p>
                                <p class="comment-text">{{ comment.text }}</p>
                                <div class="comment-actions">
                                    <button @click="showReplyForm(comment.id)" class="reply-btn">
                                        <font-awesome-icon icon="reply" size="sm" />
                                    </button>
                                    <p class="comment-date">Criado {{ formatDate(comment.created_at) }}</p>
                                </div>
                                <div v-if="replyingTo === comment.id" class="reply-form">
                                    <textarea
                                        class="reply-input"
                                        placeholder="Escreva sua resposta para o comentário aqui..."
                                        v-model="newReply"
                                    ></textarea>
                                    <button @click="postReply(comment.id)" class="reply-submit-btn">Enviar</button>
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
        };
    },
    methods: {
        postComment(projectId) {
            if (!this.newComment) {
                console.error('O campo de texto é obrigatório.');
                return;
            }

            axios
                .post('/projects/' + projectId + '/comments', {
                    text: this.newComment,
                })
                .then(() => {
                    this.newComment = '';
                })
                .catch(error => {
                    console.error(error.response);
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

.comment-section-title {
    font-size: 20px;
    font-weight: bold;
    border-bottom: 1px solid #000000;
    margin-bottom: 1.5rem;
    margin-left: 0.5rem;
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
    margin: 0.5rem;
}

.comment-text {
    color: #555555;
}

.image-ranking-box {
    display: flex;
    justify-content: center;
    max-width: 100%;
    height: 500px;
    position: relative;
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

.comment-actions {
    display: flex;
    align-items: center;
}

.reply-btn {
    color: #505050;
    cursor: pointer;
    margin-left: 0.5rem;
    margin-top: 0.5rem;
}

.reply-form {
    display: flex;
    flex-direction: column;
    margin-top: 0.5rem;
}

.reply-input {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
}

.reply-submit-btn {
    background-color: #000000;
    border: none;
    color: white;
    padding: 5px 5px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
}

.reply-submit-btn:hover {
    background-color: #505050;
}

.prose {
    word-wrap: break-word;
}
</style>
