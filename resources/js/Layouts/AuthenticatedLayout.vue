<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Projetos
                                </NavLink>
                                <NavLink :href="route('sections.index')" :active="route().current('sections.index')">
                                    Seções
                                </NavLink>
                            </div>
                        </div>
                        <h1 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">Expo-Inf</h1>
                        <div class="hidden sm:flex sm:items-center sm:ml-6" v-if="$page.props.auth.user">
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
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
import {Link} from "@inertiajs/vue3";
</script>
