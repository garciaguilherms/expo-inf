<template>
    <div class="form-group">
        <div class="mt-8 mb-2 text-gray-500">
            <h3>Personalização da página do projeto:</h3>
        </div>
        <div class="form-group">
            <label for="background_image">Imagem de fundo (URL)</label>
            <input type="text" class="form-control" id="background_image" v-model="projectData.background_image" />
        </div>
    </div>
</template>

<script>
export default {
    props: ['projectData', 'availableFonts'],
};
</script>

<style scoped>
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
}
.form-group input[type='text'],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
}
</style>
