<template>
    <div>
        <div class="search">
        </div>
        <div class="project-list">
            <ul class="project-grid">
                <li v-for="project in projectList" :key="project.id" class="project-item">
                    <div class="project-content">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                        <span class="flex justify-end mr-2 mb-2">
                                            <font-awesome-icon :icon="['fas', 'ellipsis-vertical']" size="md" style="color: #000000;" />
                                        </span>
                            </template>
                            <template #content>
                                <button type="button" @click="deleteProject(project.id)" class="btn btn-primary">
                                    <font-awesome-icon :icon="['fas', 'trash']" style="color: #e34545;" /> Excluir
                                </button>
                            </template>
                        </Dropdown>
                        <div @click="$inertia.visit('/projects/' + project.id)">
                            <img :src="project.image" class="project-image" />
                        </div>
                        <div class="project-info">
                            <h2 class="project-title">{{ project.title }}</h2>
                            <p class="project-artist">
                                <ul>
                                    <li v-for="author in project.authors" :key="author.id">Autores: {{ author.name }}</li>
                                </ul>
                            </p>
                            <div class="project-avg">
                                <font-awesome-icon icon="star" size="sm" style="color: #4a4a4a;" />
                                <p class="project-avg-rating">0</p>
                            </div>
                        </div>
<!--                        <a class="review-link">-->
<!--                            Deixar uma avaliação-->
<!--                        </a>-->
<!--                        <div v-if="project.author_id === $page.props.auth.user.id">-->
<!--                            <button type="button" @click="deleteProject(project.id)" class="btn btn-primary">-->
<!--                                <font-awesome-icon :icon="['fas', 'trash']" style="color: #e34545;" /> Excluir-->
<!--                            </button>-->
<!--                        </div>-->
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

export default {
    props: {
        projects: Array,
    },
    data() {
        return {
            projectList: this.projects,
        };
    },
    components: {
        Dropdown, DropdownLink,
        FontAwesomeIcon,
        StarRating,
    },
    methods: {
        deleteProject(id) {
            axios.delete('/projects/' + id)
                .then((response) => {
                    this.projectList = this.projectList.filter(project => project.id !== id);
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

input {
    width: 40%;
    border: none;
    border-radius: 8px;
    margin-bottom: 40px;
}

.search {
    display: flex;
    justify-content: center;
}

.project-info {
    display: flex;
    flex-direction: column;
}

.project-avg {
    display: flex;
    align-items: center;
}

.project-list {
    display: flex;
    justify-content: center;
}

.project-avg-rating {
    margin-top: 3px;
    font-size: 14px;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-right: 10px;
}

.project-grid {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
}

.project-item {
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-height: 400px;
    overflow: hidden;
}

.project-content {
    padding: 20px;
}

.project-image {
    max-width: 100%;
    height: auto;
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

.project-artist {
    font-size: 14px;
    color: #888;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-height: 60px;
}

.review-link {
    display: flex;
    align-items: center;
    color: #888;
    text-decoration: none;
    margin-top: 10px;
}

.review-link i {
    margin-right: 5px;
}
</style>
