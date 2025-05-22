<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import History from '@tiptap/extension-history';
import BulletList from '@tiptap/extension-bullet-list';
import OrderedList from '@tiptap/extension-ordered-list';
import ListItem from '@tiptap/extension-list-item';
import Table from '@tiptap/extension-table';
import TableRow from '@tiptap/extension-table-row';
import TableCell from '@tiptap/extension-table-cell';
import TableHeader from '@tiptap/extension-table-header';
import Image from '@tiptap/extension-image';
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight';
import TextAlign from '@tiptap/extension-text-align';
import Blockquote from '@tiptap/extension-blockquote';
import Color from '@tiptap/extension-color';
import TextStyle from '@tiptap/extension-text-style';

import { common, createLowlight } from 'lowlight';

const lowlight = createLowlight(common);
import MenuBar from './MenuBar.vue';
import { watch } from 'vue';

const props = defineProps({
  modelValue: String,
});
const emit = defineEmits(['update:modelValue']);

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit.configure({ history: false }),
    History,
    Underline,
    Link.configure({ openOnClick: false }),
    BulletList,
    OrderedList,
    ListItem,
    Blockquote,
    Color,
    TextStyle,
    TextAlign.configure({
      types: ['heading', 'paragraph'],
    }),
    Table.configure({
      resizable: true,
    }),
    TableRow,
    TableCell,
    TableHeader,
    Image,
    CodeBlockLowlight.configure({
      lowlight,
    }),
  ],
  onUpdate({ editor }) {
    emit('update:modelValue', editor.getHTML());
  },
});

watch(
  () => props.modelValue,
  (value) => {
    if (editor && value !== editor.getHTML()) {
      editor.commands.setContent(value);
    }
  }
);
</script>
<template>
  <div class="border border-gray-300 rounded p-4">
    <menu-bar v-if="editor" :editor="editor" />
    <editor-content :editor="editor" class="min-h-[200px] h-auto p-2 focus:outline-none" />
  </div>
</template>
