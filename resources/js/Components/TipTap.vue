<template>
    <div>
        <section
            v-if="editor"
            class="buttons flex items-center flex-wrap gap-x-4 border-t border-l border-r border-gray-300 p-2 rounded-md"
        >
            <button
                @click="editor.chain().focus().toggleBold().run()"
                class="rounded-md p-1"
                :disabled="!editor.can().chain().focus().toggleBold().run()"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('bold') }"
            >
                bold
            </button>
            <button
                @click="editor.chain().focus().toggleItalic().run()"
                class="rounded-md p-1"
                :disabled="!editor.can().chain().focus().toggleItalic().run()"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('italic') }"
            >
                italic
            </button>
            <button
                @click="editor.chain().focus().toggleCode().run()"
                class="rounded-md p-1"
                :disabled="!editor.can().chain().focus().toggleCode().run()"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('code') }"
            >
                code
            </button>
            <button
                @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
                class="rounded-md p-1"
                :class="{
                    'bg-gray-400 rounded-md p-1': editor.isActive('heading', { level: 1 }),
                }"
            >
                h1
            </button>
            <button
                @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
                class="rounded-md p-1"
                :class="{
                    'bg-gray-400 rounded-md p-1': editor.isActive('heading', { level: 2 }),
                }"
            >
                h2
            </button>
            <button
                @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
                class="rounded-md p-1"
                :class="{
                    'bg-gray-400 rounded-md p-1': editor.isActive('heading', { level: 3 }),
                }"
            >
                h3
            </button>
            <button
                @click="editor.chain().focus().toggleBulletList().run()"
                class="rounded-md p-1"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('bulletList') }"
            >
                bullet list
            </button>
            <button
                @click="editor.chain().focus().toggleOrderedList().run()"
                class="rounded-md p-1"
                :class="{ 'bg-gray-400 rounded-md p-1': editor.isActive('orderedList') }"
            >
                ordered list
            </button>
        </section>
    </div>
    <EditorContent :editor="editor" />
</template>

<script>
import { EditorContent, useEditor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';

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
                extensions: [StarterKit],
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
};
</script>
