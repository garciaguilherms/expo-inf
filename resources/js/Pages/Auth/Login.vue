<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Entrar" />
    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gray-100">
        <h1 class="text-xl">Entrar no <b>Expo-Inf</b></h1>
        <form
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
            @submit.prevent="submit"
        >
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Senha" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="errors.password" />
            </div>

            <div class="flex mt-4 items-center justify-center">
                <a :href="route('register')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Não tem uma conta?
                </a>
            </div>

            <div class="flex mt-4 items-center justify-center">
                <a href="/auth/google/redirect" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Entrar com e-mail institucional (@inf.ufsm.br)
                </a>
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Entrar
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
