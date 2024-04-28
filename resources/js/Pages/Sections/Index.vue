<template>
    <AuthenticatedLayout>
        <div class="section-container">
            <ul class="section-grid justify-content-center">
                <li v-for="section in sectionList" :key="section.id" class="section-item">
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
                    <div class="section-content">
                        <div class="section-info">
                            <h2 class="section-title" @click="$inertia.visit('/sections/' + section.id)">
                                {{ section.title }}
                            </h2>
                            <p class="section-subtitle">
                                {{ section.description.slice(0, 500)
                                }}{{ section.description.length > 500 ? '...' : '' }}
                            </p>
                        </div>
                        <ul class="projects-list">
                            <li v-for="project in section.projects" :key="project.id" class="project-card">
                                <div class="project-content" @click="$inertia.visit('/projects/' + project.id)">
                                    <div class="project-title">
                                        {{ project.title }}
                                    </div>
                                    <div class="project-description">
                                        Autores: {{ project.authors.map(author => author.name).join(', ') }}
                                    </div>
                                </div>
                                <img :src="project.image" class="project-image" alt="Capa do projeto" />
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
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
                <button class="bg-black text-white font-bold py-4 px-4 rounded-full">Formulário de Feedback</button>
            </a>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { useToastr } from '@/toastr';

export default {
    components: { Dropdown, AuthenticatedLayout },
    props: {
        sections: Array,
    },
    data() {
        return {
            sectionList: this.sections,
        };
    },
    methods: {
        deleteSection(id) {
            axios
                .delete('/sections/' + id)
                .then(() => {
                    this.sectionList = this.sectionList.filter(section => section.id !== id);
                })
                .catch(error => {
                    console.error('Error deleting project:', error);
                })
                .finally(() => {
                    useToastr().success('Secão excluída com sucesso!');
                    this.$inertia.get('/sections');
                });
        },
        updateSection(id) {
            this.$inertia.visit('/sections/' + id + '/edit');
        },
    },
};
</script>

<style scoped>
.section-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
    padding: 20px;
}

.section-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
    list-style: none;
    margin: 0;
    padding: 0;
    min-width: 100%;
}

.section-item {
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    padding: 20px;
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
}

.section-subtitle {
    font-size: 16px;
    margin-bottom: 10px;
    overflow: hidden;
}

.projects-list {
    list-style: none;
    padding: 0;
}

.project-card {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 8px;
    cursor: pointer;
    margin-bottom: 10px;
    padding: 10px;
}

.project-content {
    flex-grow: 1;
    overflow: hidden;
}

.project-title {
    font-size: 18px;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.project-description {
    font-size: 14px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.project-image {
    height: 50px;
    margin-left: 10px;
    width: 80px;
}
</style>
