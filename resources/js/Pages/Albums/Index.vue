<template>
    <div>
        <div class="search">
            <input type="text" v-model="searchTerm" placeholder="Pesquisar">
        </div>
        <div class="album-list">
            <ul class="album-grid">
                <li v-for="album in filteredAlbums" :key="album.id" class="album-item">
                    <div class="album-content">
                        <img :src="album.cover_image" alt="Capa do Álbum" class="album-image" />
                        <div class="album-info">
                            <h2 class="album-title">{{ album.title }}</h2>
                            <p class="album-artist">{{ album.artist }}</p>
                            <div class="album-avg">
                                <font-awesome-icon icon="star" size="sm" style="color: #4a4a4a; margin-bottom: 2.5px; margin-right: 5px;" />
                                <p v-if="album.avg_rating" class="album-avg-rating">{{ album.avg_rating }}</p>
                                <p v-else class="album-avg-rating">0</p>
                            </div>
                        </div>
                        <star-rating
                            :star-size="20"
                            :read-only="false"
                            :show-rating="false"
                            :rating="album.rating"
                            @update:rating="(rating) => handleRatingSelected(album.id, rating, user_id)">
                        </star-rating>
                        <a :href="route('review', album.id)" class="review-link">
                            Deixar uma avaliação
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import StarRating from 'vue-star-rating';
import axios from 'axios';

export default {
    data() {
        return {
            searchTerm: '',
            searchResults: [],
        };
    },
    props: {
        albums: Array,
        user_id: Number
    },
    components: {
        StarRating,
    },
    computed: {
        filteredAlbums() {
            if (this.searchTerm === '') {
                return this.albums;
            } else {
                const term = this.searchTerm.toLowerCase();
                return this.albums.filter(album => {
                    const titleMatch = album.title.toLowerCase().includes(term);
                    const artistMatch = album.artist.toLowerCase().includes(term);
                    return titleMatch || artistMatch;
                });
            }
        },
    },
    methods: {
        handleRatingSelected(album, rating, user_id) {
            axios.post(`/albums/${album}/ratings`, {
                rating: rating,
                user_id: user_id
            })
        },
    },
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

.album-info {
    display: flex;
    flex-direction: column;
}

.album-avg {
    display: flex;
    align-items: center;
    justify-content: end;
    margin: 2px;
}

.album-list {
    display: flex;
    justify-content: center;
}

.album-avg-rating {
    font-size: 14px;
    color: #888;
}

.album-grid {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 20px;
}

.album-item {
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.album-content {
    padding: 20px;
}

.album-image {
    width: 100%;
    height: auto;
    border-radius: 8px;
    object-fit: cover;
    margin-bottom: 10px;
}

.album-title {
    font-size: 18px;
    font-weight: bold;
}

.album-artist {
    font-size: 14px;
    color: #888;
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
