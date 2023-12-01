<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Expo-Inf" />
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-red-500 selection:text-white">
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            <Link
                :href="route('dashboard')"
                class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
            >Dashboard
            </Link>
            <Link
                :href="route('login')"
                class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
            >Entrar
            </Link>
            <Link
                :href="route('register')"
                class="ml-5 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
            >Registrar-se
            </Link>
        </div>
        <div class="title-box">
            <h1 class="title">Expo-Inf</h1>
            <p class="footer">
                plataforma em desenvolvimento
            </p>
        </div>
    </div>
</template>

<style>
.title {
    font-size: 4rem;
    font-weight: 100;
}

.title-box {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.footer {
    position: fixed;
    bottom: 20px;
    justify-content: center;
    font-size: 0.75rem;
    line-height: 1rem;
    font-weight: 400;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}
</style>
