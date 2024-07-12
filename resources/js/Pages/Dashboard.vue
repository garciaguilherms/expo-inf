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
            href="https://forms.gle/RREDBShcDnevLkmY9"
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
            <button class="bg-black text-white font-bold py-4 px-4 rounded-full">Formulário de Usabilidade</button>
        </a>

        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Bem-vindo ao Expo-inf</h2>
                <p class="mb-4">
                    Esta plataforma visa oferecer um espaço para estudantes de cursos de Computação da UFSM exibirem
                    seus trabalhos, receberem feedback e ganharem reconhecimento. A plataforma permite ao público
                    pesquisar e visualizar projetos. Além disso, usuários cadastrados podem deixar comentários, e também
                    podem adicionar projetos e criar galerias de projetos (tais ações podem ser acessadas clicando no
                    seu nome de usuário logado no canto superior direito). Com esta plataforma, espera-se aumentar o
                    reconhecimento dos esforços dos estudantes e estimular o desenvolvimento contínuo de habilidades e
                    seus projetos.
                    <br /><br />Convido você a explorar esta versão da plataforma e responder ao formulário de
                    usabilidade na página principal.
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
    mounted() {
        this.showModal = true;
    },
    methods: {
        closeModal() {
            this.showModal = false;
            const now = new Date();
            localStorage.setItem('modalShown', now.getTime().toString());
        },
    },
};
</script>
