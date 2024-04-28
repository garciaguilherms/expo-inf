<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout>
        <template>
            <h2 class="font-semibold text-2xl text-blue-700 leading-tight">Dashboard</h2>
        </template>
        <div class="py-12 flex justify-center items-center">
            <div class="bg-gray-100 rounded-sm">
                <div class="p-6 text-gray-900">
                    <ProjectsIndex :projects="$page.props.projects" />
                </div>
            </div>
        </div>
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

        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Bem-vindo ao Expo-inf</h2>
                <p class="mb-4">
                    Esta plataforma, ainda em desenvolvimento, visa oferecer um espaço para estudantes de cursos de
                    Computação da UFSM exibirem seus trabalhos, receberem feedback e ganharem reconhecimento. Em seu
                    estágio atual, a plataforma permite ao público pesquisar e visualizar projetos. Além disso, usuários
                    cadastrados podem avaliar projetos, com estrelas ou comentários, e também podem adicionar projetos e
                    criar seções temáticas (por exemplo, para trabalhos de uma disciplina ou de um grupo temático). Com
                    esta plataforma, espera-se aumentar o reconhecimento dos esforços dos estudantes e estimular o
                    desenvolvimento contínuo de habilidades.
                    <br /><br />Convidamos você a explorar esta versão inicial da plataforma e responder ao formulário
                    de feedback na página principal. Suas respostas serão consideradas na continuidade do
                    desenvolvimento, afinal você faz parte do público-alvo!
                </p>
                <div class="flex justify-end">
                    <button class="bg-black text-white font-bold py-2 px-4 rounded" @click="closeModal">Fechar</button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Modal from '@/Components/Modal';
import ProjectsIndex from '@/Pages/Projects/Index';
import SectionsIndex from '@/Pages/Sections/Index';
import { Head } from '@inertiajs/vue3';

export default {
    data() {
        return {
            showModal: false,
        };
    },
    components: {
        AuthenticatedLayout,
        ProjectsIndex,
        SectionsIndex,
        Head,
        Modal,
    },
    props: {
        projects: Array,
    },
    methods: {
        closeModal() {
            this.showModal = false;
            const now = new Date();
            localStorage.setItem('modalShown', now.getTime().toString());
        },
    },
    mounted() {
        const modalShownTime = localStorage.getItem('modalShown');
        const now = new Date();
        const fiveMinutes = 5 * 60 * 1000;

        if (!modalShownTime || now.getTime() - modalShownTime > fiveMinutes) {
            this.showModal = true;
        }
    },
};
</script>
