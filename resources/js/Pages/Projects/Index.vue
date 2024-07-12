<template>
    <Head title="Projetos" />
    <div>
        <div class="search-box">
            <div class="input-wrapper">
                <input
                    type="text"
                    class="search"
                    placeholder="Pesquise projetos"
                    v-model="term"
                    @keyup.enter="searchProjects"
                />
            </div>
            <button @click="searchProjects" class="btn">Pesquisar</button>
        </div>
        <div class="project-list">
            <ul class="flex justify-center md:flex-row flex-wrap p-0 m-8 gap-4 list-none">
                <li
                    v-for="project in projectList"
                    :key="project.id"
                    class="project-item"
                    :class="{ 'is-deleting': project.id === deletingProjectId }"
                >
                    <div class="project-content">
                        <Dropdown
                            align="right"
                            width="48"
                            v-if="
                                $page.props.auth.user &&
                                (project.created_by === $page.props.auth.user.id ||
                                    (project.authors &&
                                        project.authors.some(author => author.id === $page.props.auth.user.id)))
                            "
                        >
                            <template #trigger>
                                <span class="dropdown-trigger">
                                    <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" size="sm" />
                                </span>
                            </template>
                            <template #content>
                                <div style="display: flex; flex-direction: column">
                                    <button type="button" @click="deleteProject(project.id)" class="delete-button">
                                        <span v-if="deletingProjectId === project.id && isLoading">Excluindo...</span>
                                        <span v-else>Excluir</span>
                                    </button>
                                    <button type="button" @click="updateProject(project.id)" class="delete-button">
                                        Editar
                                    </button>
                                </div>
                            </template>
                        </Dropdown>
                        <div class="cursor-pointer" @click="$inertia.visit('/projects/' + project.id)">
                            <img :src="project.image" class="project-image" alt="Imagem do projeto" />
                            <div class="delete-overlay" v-if="deletingProjectId === project.id && isLoading">
                                Excluindo...
                            </div>
                        </div>
                        <div class="project-info">
                            <h2 class="project-title">{{ project.title }}</h2>
                            <p class="project-author">Criado em {{ formatDate(project.created_at) }}</p>
                            <p class="project-author">
                                Autores:
                                <template v-if="project.authors && project.authors.length > 0">
                                    <span v-for="(author, index) in project.authors" :key="author.id">
                                        {{ author.name }}
                                        <span v-if="index < project.authors.length - 1">, </span>
                                    </span>
                                </template>
                                <template v-else> Nenhum autor associado. </template>
                            </p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Dropdown from '@/Components/Dropdown.vue';
import moment from 'moment';
import { useToastr } from '@/toastr';
import { Head } from '@inertiajs/vue3';

export default {
    props: {
        projects: Array,
    },
    data() {
        return {
            projectList: this.projects,
            term: '',
            isLoading: false,
            deletingProjectId: null,
        };
    },
    components: {
        Head,
        Dropdown,
        FontAwesomeIcon,
    },
    methods: {
        deleteProject(id) {
            this.deletingProjectId = id;
            this.isLoading = true;
            axios
                .delete('/projects/' + id)
                .then(() => {
                    this.projectList = this.projectList.filter(project => project.id !== id);
                    useToastr().success('Projeto excluído com sucesso!');
                })
                .catch(error => {
                    console.error('Erro ao deletar projeto', error);
                })
                .finally(() => {
                    this.isLoading = false;
                    this.deletingProjectId = null;
                });
        },
        updateProject(id) {
            this.$inertia.get('/projects/' + id + '/edit');
        },
        formatDate(date) {
            return moment(date).locale('pt-br').fromNow();
        },
        searchProjects() {
            axios
                .get('/projects/search', {
                    params: {
                        term: this.term,
                    },
                })
                .then(response => {
                    this.projectList = response.data;
                })
                .catch(error => {
                    console.error('Houve um erro ao buscar os projetos:', error);
                });
        },
    },
    watch: {
        term(newTerm, oldTerm) {
            if (!newTerm) {
                this.projectList = this.projects;
            }
        },
        projectList(newList, oldList) {
            if (!newList) {
                this.projectList = this.projects;
            }
        },
    },
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
    padding: 10px;
    font-size: 16px;
}

.search-box {
    display: flex;
    justify-content: center;
    padding: 20px;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    min-width: 500px;
    margin: 0 auto;
}

.input-wrapper {
    flex-grow: 1;
    margin-right: 10px;
}

.input-wrapper input {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
}

.search {
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    padding: 10px;
    flex-grow: 1;
}

.btn {
    margin-left: 10px;
    margin-top: 1px;
    padding: 10px;
    border-radius: 5px;
    border: none;
    background-color: #000000;
    color: white;
    cursor: pointer;
    height: 100%;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #1f1f1f;
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
    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
}

.project-item:hover {
    transform: scale(1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

.delete-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    font-size: 24px;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1;
}

.project-item.is-deleting {
    filter: brightness(70%);
}

@media screen and (max-width: 768px) {
    .search-box {
        width: 100%;
        max-width: 100%;
        min-width: 100%;
        margin: 0 auto;
        padding: 10px;
        box-sizing: border-box;
    }

    .project-list {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 10px;
        box-sizing: border-box;
    }

    .project-item {
        width: calc(100% - 20px);
        max-width: 100%;
        margin-bottom: 20px;
        box-sizing: border-box;
    }

    .project-image {
        width: 100%;
        border-radius: 8px;
        object-fit: cover;
    }

    .input-wrapper {
        width: 100%;
        margin-right: 0;
    }

    .search {
        width: 100%;
        margin-bottom: 10px;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 16px;
    }
}
</style>
