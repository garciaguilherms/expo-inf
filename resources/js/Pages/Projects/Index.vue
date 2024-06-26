<template>
    <div>
        <div class="search-box">
            <input type="text" class="search" placeholder="Pesquisar projetos" v-model="term"/>
            <button @click="searchProjects" class="btn">Pesquisar</button>
        </div>
        <div class="project-list">
            <ul class="flex justify-center flex-col md:flex-row flex-wrap p-0 m-8 gap-4 list-none">
                <li v-for="project in projectList" :key="project.id" class="project-item">
                    <div class="project-content">
                        <Dropdown align="right" width="48"
                                  v-if="$page.props.auth.user && (project.created_by === $page.props.auth.user.id || (project.authors && project.authors.some(author => author.user_id === $page.props.auth.user.id)))">
                            <template #trigger>
                                <span class="dropdown-trigger">
                                    <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" size="sm"/>
                                </span>
                            </template>
                            <template #content>
                                <div style="display: flex; flex-direction: column;">
                                    <button type="button" @click="deleteProject(project.id)" class="delete-button">
                                        Excluir
                                    </button>
                                    <button type="button" @click="updateProject(project.id)" class="delete-button">
                                        Editar
                                    </button>
                                </div>
                            </template>
                        </Dropdown>
                        <div class="cursor-pointer" @click="$inertia.visit('/projects/' + project.id)">
                            <img :src="project.image" class="project-image" alt="Imagem do projeto"/>
                        </div>
                        <div class="project-info">
                            <h2 class="project-title">{{ project.title }}</h2>
                            <p class="project-author">
                                Criado {{ formatDate(project.created_at) }}
                            </p>
                            <p class="project-author">
                                <ul>Autores:
                                    <li v-for="author in project.authors" :key="author.id">{{ author.name }}</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import StarRating from 'vue-star-rating';
import axios from 'axios';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import moment from 'moment';
import {useToastr} from "@/toastr";

export default {
    props: {
        projects: Array,
    },
    data() {
        return {
            projectList: this.projects,
            showCommentBox: [],
            newComment: '',
            selectedRating: 0,
            userRating: 0,
            term: '',
        };
    },
    components: {
        Dropdown, DropdownLink,
        FontAwesomeIcon,
        StarRating,
        moment,
    },
    methods: {
        deleteProject(id) {
            axios.delete('/projects/' + id)
                .then(() => {
                    this.projectList = this.projectList.filter(project => project.id !== id);
                })
                .catch((error) => {
                    console.error('Error deleting project:', error);
                })
                .finally(() => {
                    useToastr().success('Projeto excluído com sucesso!');
                })
        },
        updateProject(id) {
            this.$inertia.get('/projects/' + id + '/edit');
        },
        formatDate(date) {
            return moment(date).locale('pt-br').fromNow();
        },
        searchProjects() {
            axios.get('/projects/search/' + this.term)
                .then((response) => {
                    this.projectList = response.data;
                })
                .catch((error) => {
                    console.error(error.response);
                });
        }
    }
};

</script>

<style scoped>

.dropdown-trigger {
    display: flex;
    justify-content: flex-end;
    color: #000000;
    margin-bottom: 10px;
    cursor: pointer;
}

input {
    width: 40%;
    border: none;
    border-radius: 8px;
    margin-bottom: 40px;
}

.search-box {
    display: flex;
    justify-content: center;
    padding: 20px;
}

.search {
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}

.btn {
    margin-left: 10px;
    padding: 10px;
    border-radius: 5px;
    border: none;
    background-color: #000000;
    color: white;
    cursor: pointer;
    height: 100%;
}

.project-info {
    display: flex;
    flex-direction: column;
}

.project-list {
    display: flex;
    justify-content: center;
}

.project-item {
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    width: 350px;
    overflow: hidden;
}

.project-content {
    padding: 20px;
}

.project-image {
    height: 300px;
    border-radius: 8px;
    object-fit: cover;
    margin-bottom: 10px;
}

.project-title {
    font-size: 18px;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 60px;
}

.project-author {
    font-size: 14px;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 60px;
}

.review-link i {
    margin-right: 5px;
}

.delete-button {
    max-width: 100%;
    cursor: pointer;
    margin: 5px;
    font-weight: bold;
}

</style>

