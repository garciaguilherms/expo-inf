<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- Mobile navigation -->
            <nav class="bg-white border-b border-gray-100 sm:hidden">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <!-- Left side menu for projects and sections -->
                    <div class="flex py-2">
                        <!-- Dropdown for projects and sections -->
                        <div class="relative">
                            <button
                                type="button"
                                @click="toggleDropdown"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                            >
                                Menu <span class="ml-1"><i class="fas fa-caret-down"></i></span>
                            </button>
                            <div
                                v-if="dropdownOpen"
                                class="absolute left-0 mt-3 w-48 bg-white rounded-md shadow-lg z-10"
                            >
                                <DropdownLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Projetos
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('sections.index')"
                                    :active="route().current('sections.index')"
                                >
                                    Seções
                                </DropdownLink>
                            </div>
                        </div>
                    </div>
                    <!-- Centered title for mobile view -->
                    <h1 class="font-semibold text-xl text-gray-800 leading-tight">Expo-Inf</h1>
                    <!-- Right side menu for user -->
                    <div class="flex space-x-6 py-2">
                        <div v-if="$page.props.auth.user">
                            <div class="relative">
                                <Dropdown align="right" width="36">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.name }}
                                            </button>
                                        </span>
                                    </template>
                                    <template #content>
                                        <DropdownLink :href="route('projects.create')" method="get" as="button">
                                            Criar Projeto
                                        </DropdownLink>
                                        <DropdownLink :href="route('sections.create')" method="get" as="button">
                                            Criar Seção
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Sair
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                        <div v-else>
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
                    </div>
                </div>
            </nav>

            <!-- Desktop navigation -->
            <nav class="bg-white border-b border-gray-100 hidden sm:block">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left side menu for projects and sections -->
                        <div class="flex items-center">
                            <div class="flex space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Projetos
                                </NavLink>
                                <NavLink :href="route('sections.index')" :active="route().current('sections.index')">
                                    Seções
                                </NavLink>
                            </div>
                        </div>
                        <!-- Centered title for desktop view -->
                        <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center hidden sm:flex">
                            Expo-Inf
                        </h1>
                        <!-- Right side menu for user actions -->
                        <div class="flex items-center">
                            <div class="hidden sm:flex sm:items-center sm:ml-6" v-if="$page.props.auth.user">
                                <div class="ml-3 relative">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none"
                                                >
                                                    {{ $page.props.auth.user.name }}
                                                </button>
                                            </span>
                                        </template>
                                        <template #content>
                                            <DropdownLink :href="route('projects.create')" method="get" as="button">
                                                Criar Projeto
                                            </DropdownLink>
                                            <DropdownLink :href="route('sections.create')" method="get" as="button">
                                                Criar Seção
                                            </DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button">
                                                Sair
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                            <div class="hidden sm:flex sm:items-center sm:ml-6" v-else>
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
                        </div>
                    </div>
                </div>
            </nav>
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';

const { ref } = require('vue');

// Estado do dropdown
const dropdownOpen = ref(false);

// Método para alternar estado do dropdown
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};
</script>

<style scoped>
/* Ajustes de estilo para o dropdown */
</style>
