<template>
    <AuthenticatedLayout>
        <div>
            <div class="section-list">
                <ul class="section-grid">
                    <li v-for="section in sectionList" :key="section.id" class="section-item">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                        <span class="flex justify-end mr-2 mb-2">
                                            <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" size="sm" style="color: #000000;" />
                                        </span>
                            </template>
                            <template #content>
                                <button type="button" @click="deleteSection(section.id)" class="btn btn-primary">
                                    <font-awesome-icon :icon="['fas', 'trash']" style="color: #e34545;" /> Excluir
                                </button>
                            </template>
                        </Dropdown>
                        <div class="section-content">
                            <div class="section-info">
                                <h2 class="section-title">{{ section.title }}</h2>
                                <p class="section-subtitle">{{ section.description }}</p>
                            </div>
                            <ul class="projects-list">
                                <li v-for="project in section.projects" :key="project.id" class="project-card">
                                    <div class="project-content" @click="$inertia.visit('/projects/' + project.id)">
                                        <div class="project-title">{{ project.title }}</div>
                                        <div class="project-description">{{ project.description }}</div>
                                    </div>
                                    <img :src="project.image" class="project-image"  alt="Capa do projeto"/>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import axios from 'axios';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Dropdown from "@/Components/Dropdown.vue";

export default {
    components: {Dropdown, AuthenticatedLayout},
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
            axios.delete('/sections/' + id)
                .then(() => {
                    this.sectionList = this.sectionList.filter(section => section.id !== id);
                })
                .catch((error) => {
                    console.error('Error deleting project:', error);
                })
                .finally(() => {
                    this.$inertia.visit('/dashboard');
                })
        }
    }
};

</script>

<style scoped>

.section-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.section-title {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 10px;
}

.projects-list {
    list-style: none;
    padding: 5px;
    font-size: 14px;
    font-weight: 500;
}

.section-subtitle {
    font-size: 16px;
    margin-bottom: 10px;
}

.section-list {
    display: flex;
    justify-content: center;
}

.section-grid {
    list-style: none;
    padding: 0;
    margin: 20px;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
}

.section-item {
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-height: 400px;
    overflow: hidden;
}

.section-content {
    padding: 20px;
}

.project-card {
    display: flex;
    align-items: center;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
    padding: 10px;
    cursor: pointer;
}
.project-content {
    flex-grow: 1;
}
.project-image {
    position: relative;
    width: 80px;
    height: 50px;
    margin-left: 10px;
}
.project-description {
    display: flex;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 200px;
}
.project-title {
    font-size: 18px;
    font-weight: bold;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 60px;
}
</style>
