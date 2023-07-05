<script setup>
import DropdownLink from '@/Components/DropdownLink.vue';
import Dropdown from "@/Components/Dropdown.vue";

defineProps(['post']);
</script>

<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">{{ post.user.name }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ new Date(post.created_at).toLocaleString() }}</small>
                </div>
                <Dropdown v-if="post.user.id === $page.props.auth.user.id">
                    <template #trigger>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink as="button" :href="route('posts.destroy', post.id)" method="delete">
                            Delete
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
            <p class="mt-4 text-lg text-gray-900">{{ post.message }}</p>
        </div>
    </div>
</template>
