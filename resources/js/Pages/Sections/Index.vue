<template>
    <Head title="Galerias" />
    <AuthenticatedLayout>
        <div class="section-container">
            <div class="search-box">
                <div class="input-wrapper">
                    <input
                        type="text"
                        class="search"
                        placeholder="Pesquise galerias por nome ou tag"
                        v-model="term"
                        @keyup.enter="searchSections"
                    />
                </div>
                <button @click="searchSections" class="btn">Pesquisar</button>
            </div>
            <ul class="section-grid">
                <li v-for="section in processedSections" :key="section.id" class="section-item">
                    <Dropdown
                        align="right"
                        width="48"
                        v-if="$page.props.auth.user && section.created_by === $page.props.auth.user.id"
                    >
                        <template #trigger>
                            <span class="dropdown-trigger">
                                <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" size="sm" />
                            </span>
                        </template>
                        <template #content>
                            <div style="display: flex; flex-direction: column">
                                <button type="button" @click="deleteSection(section.id)" class="delete-button">
                                    Excluir
                                </button>
                                <button type="button" @click="updateSection(section.id)" class="delete-button">
                                    Editar
                                </button>
                            </div>
                        </template>
                    </Dropdown>
                    <div class="delete-overlay" v-if="section.deleting">Excluindo...</div>
                    <div class="section-content">
                        <div class="section-info">
                            <h2 class="section-title" @click="$inertia.visit('/sections/' + section.id)">
                                {{ section.title }}
                            </h2>
                            <p class="section-subtitle" v-if="section.projects && Object.keys(section.projects).length">
                                {{ section.description?.slice(0, 200)
                                }}{{ section.description?.length > 200 ? '...' : '' }}
                            </p>
                            <p class="section-subtitle" v-else>
                                {{ section.description?.slice(0, 500)
                                }}{{ section.description?.length > 500 ? '...' : '' }}
                            </p>
                        </div>
                        <p v-if="section.projects && Object.keys(section.projects).length" class="projects-description">
                            Projetos vinculados a essa galeria
                        </p>
                        <p v-else class="projects-description">Nenhum projeto vinculado a essa galeria</p>
                        <ul class="projects-list">
                            <li
                                v-for="(project, index) in section.projects.slice(0, 2)"
                                :key="project.id"
                                class="project-card"
                            >
                                <div class="project-content" @click="$inertia.visit('/projects/' + project.id)">
                                    <div class="project-title">
                                        {{ project.title }}
                                    </div>
                                    <div class="project-description" v-if="project.authors">
                                        Autores: {{ project.authors?.map(author => author.name).join(', ') }}
                                    </div>
                                </div>
                                <img :src="project.image" class="project-image" alt="Capa do projeto" />
                            </li>
                            <li v-if="section.projects.length > 2" class="more-projects">
                                E mais {{ section.projects.length - 2 }} projetos
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { useToastr } from '@/toastr';
import { Head } from '@inertiajs/vue3';

export default {
    components: { Head, Dropdown, AuthenticatedLayout },
    props: {
        sections: Array,
    },
    data() {
        return {
            sectionList: this.sections,
            term: '',
            deletingSectionId: null,
        };
    },
    computed: {
        processedSections() {
            return this.sectionList.map(section => ({
                ...section,
                projects: section.projects || [],
                deleting: section.id === this.deletingSectionId,
            }));
        },
    },
    methods: {
        deleteSection(id) {
            this.deletingSectionId = id;
            axios
                .delete('/sections/' + id)
                .then(() => {
                    this.sectionList = this.sectionList.filter(section => section.id !== id);
                })
                .catch(error => {
                    console.error('Error deleting section:', error);
                })
                .finally(() => {
                    useToastr().success('Galeria excluída com sucesso!');
                    this.$inertia.get('/sections');
                    this.deletingSectionId = null;
                });
        },
        updateSection(id) {
            this.$inertia.visit('/sections/' + id + '/edit');
        },
        searchSections() {
            axios
                .get('/sections/search', {
                    params: { term: this.term },
                })
                .then(response => {
                    this.sectionList = response.data;
                })
                .catch(error => {
                    console.error('Houve um erro ao buscar as galerias:', error);
                });
        },
    },
    watch: {
        term(newTerm, oldTerm) {
            if (!newTerm) {
                this.sectionList = this.sections;
            }
        },
        sectionList(newList, oldList) {
            if (!newList) {
                this.sectionList = this.sections;
            }
        },
    },
};
</script>

<style scoped>
.section-container {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: flex-start;
    margin: 0 auto; /* Remova margem para evitar espaços extras */
    padding: 20px;
    max-width: 1200px;
    min-height: 100vh; /* Garante que o container ocupe toda a altura da viewport */
}

.section-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 20px;
    list-style: none;
    align-content: center;
}

.section-item {
    background-color: #f7f7f7;
    position: relative;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 20px;
    display: flex;
    flex-direction: column;
    min-height: 300px;
    transition:
        transform 0.3s ease,
        box-shadow 0.3s ease;
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

.section-item:hover {
    transform: scale(1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.dropdown-trigger {
    display: flex;
    justify-content: flex-end;
    color: #000000;
    margin-bottom: 10px;
    cursor: pointer;
}

.delete-button {
    max-width: 100%;
    cursor: pointer;
    margin: 5px;
    font-weight: bold;
}

.section-info {
    margin-bottom: 20px;
}

.section-title {
    cursor: pointer;
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.section-subtitle {
    font-size: 16px;
    margin-bottom: 10px;
    overflow: hidden;
    color: #555;
}

.projects-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.project-card {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 10px;
    padding: 10px;
    background-color: #fff;
    transition: transform 0.2s;
}

.project-card:hover {
    transform: scale(1);
}

.project-content {
    flex-grow: 1;
    overflow: hidden;
    padding-right: 10px;
}

.project-title {
    font-size: 18px;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 5px;
    color: #333;
}

.project-description {
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #777;
}

.project-image {
    height: 50px;
    width: 50px;
    border-radius: 4px;
    object-fit: cover;
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

.projects-description {
    font-size: 14px;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 60px;
    margin: 8px;
}

.more-projects {
    font-size: 14px;
    color: #888;
    font-weight: bold;
    text-align: center;
}

@media screen and (max-width: 768px) {
    .section-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    }

    .section-item {
        min-height: auto;
    }

    .search-box {
        width: 100%;
        max-width: 100%;
        min-width: 100%;
        margin: 0 auto;
        padding: 10px;
        box-sizing: border-box;
    }

    .input-wrapper {
        margin-right: 0;
        margin-bottom: 10px;
    }

    .btn {
        margin-left: 0;
    }
}
</style>
