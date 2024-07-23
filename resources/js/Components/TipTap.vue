<template>
    <div>
        <section
            v-if="editor"
            class="buttons flex items-center flex-wrap gap-x-4 border-t border-l border-r border-gray-300 p-2 rounded-md"
        >
            <button
                type="button"
                @click="editor.chain().focus().toggleBold().run()"
                class="rounded-md p-1"
                :disabled="!editor.can().chain().focus().toggleBold().run()"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('bold') }"
            >
                <font-awesome-icon :icon="['fas', 'bold']" size="sm" />
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleItalic().run()"
                class="rounded-md p-1"
                :disabled="!editor.can().chain().focus().toggleItalic().run()"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('italic') }"
            >
                <font-awesome-icon :icon="['fas', 'italic']" size="sm" />
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleCode().run()"
                class="rounded-md p-1"
                :disabled="!editor.can().chain().focus().toggleCode().run()"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('code') }"
            >
                <font-awesome-icon :icon="['fas', 'code']" size="sm" />
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                class="rounded-md p-1"
                :class="{
                    'bg-gray-400 rounded-md p-1': editor.isActive('heading', { level: 1 }),
                }"
            >
                <strong>H1</strong>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                class="rounded-md p-1"
                :class="{
                    'bg-gray-400 rounded-md p-1': editor.isActive('heading', { level: 2 }),
                }"
            >
                <strong>H2</strong>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                class="rounded-md p-1"
                :class="{
                    'bg-gray-400 rounded-md p-1': editor.isActive('heading', { level: 3 }),
                }"
            >
                <strong>H3</strong>
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleBulletList().run()"
                class="rounded-md p-1"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('bulletList') }"
            >
                <font-awesome-icon :icon="['fas', 'list']" size="sm" />
            </button>
            <button
                type="button"
                @click="editor.chain().focus().toggleOrderedList().run()"
                class="rounded-md p-1"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('orderedList') }"
            >
                <font-awesome-icon :icon="['fas', 'list-ol']" size="sm" />
            </button>
            <button type="button" @click="setLink()" class="rounded-md p-1">
                <font-awesome-icon :icon="['fas', 'link']" />
            </button>
            <button
                type="button"
                @click="unsetLink()"
                class="rounded-md p-1"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('link') }"
            >
                <font-awesome-icon :icon="['fas', 'link-slash']" />
            </button>
        </section>
    </div>
    <EditorContent :editor="editor" />
</template>

<script>
import { EditorContent, useEditor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';

export default {
    name: 'TipTap',
    props: {
        modelValue: {
            type: String,
            default: '',
        },
    },
    components: {
        EditorContent,
    },
    data() {
        return {
            editor: useEditor({
                content: this.modelValue,
                onUpdate: ({ editor }) => {
                    this.$emit('update:modelValue', editor.getHTML());
                },
                extensions: [
                    StarterKit,
                    Link.configure({
                        openOnClick: false,
                    }),
                ],
                editorProps: {
                    attributes: {
                        class: 'border border-gray-300 p-2 rounded-md min-h-[300px] max-h-[300px] overflow-auto prose max-w-none',
                    },
                    transformPastedText(text) {
                        return text.toUpperCase();
                    },
                },
            }),
        };
    },
    methods: {
        setLink() {
            const url = prompt('Enter the URL');
            if (url) {
                this.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
            }
        },
        unsetLink() {
            this.editor.chain().focus().unsetLink().run();
        },
    },
};
</script>
