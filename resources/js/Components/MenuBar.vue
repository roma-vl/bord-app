<template>
  <div class="mb-3 flex flex-wrap gap-2">
    <!-- Текстове форматування -->
    <button
      type="button"
      :class="btnClass(editor.isActive('bold'))"
      @click="editor.chain().focus().toggleBold().run()"
    >
      B
    </button>
    <button
      type="button"
      :class="btnClass(editor.isActive('italic'))"
      @click="editor.chain().focus().toggleItalic().run()"
    >
      I
    </button>
    <button
      type="button"
      :class="btnClass(editor.isActive('underline'))"
      @click="editor.chain().focus().toggleUnderline().run()"
    >
      U
    </button>
    <button
      type="button"
      :class="btnClass(editor.isActive('heading', { level: 1 }))"
      @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
    >
      H1
    </button>
    <button
      type="button"
      :class="btnClass(editor.isActive('heading', { level: 2 }))"
      @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
    >
      H2
    </button>

    <!-- Списки -->
    <button
      type="button"
      :class="btnClass(editor.isActive('bulletList'))"
      @click="editor.chain().focus().toggleBulletList().run()"
    >
      •
    </button>
    <button
      type="button"
      :class="btnClass(editor.isActive('orderedList'))"
      @click="editor.chain().focus().toggleOrderedList().run()"
    >
      1.
    </button>

    <!-- Вирівнювання -->
    <button
      type="button"
      :class="btnClass(editor.isActive({ textAlign: 'left' }))"
      @click="editor.chain().focus().setTextAlign('left').run()"
    >
      ⬅️
    </button>
    <button
      type="button"
      :class="btnClass(editor.isActive({ textAlign: 'center' }))"
      @click="editor.chain().focus().setTextAlign('center').run()"
    >
      ⬅️➡️
    </button>
    <button
      type="button"
      :class="btnClass(editor.isActive({ textAlign: 'right' }))"
      @click="editor.chain().focus().setTextAlign('right').run()"
    >
      ➡️
    </button>

    <!-- Колір -->
    <button
      type="button"
      :class="btnClass(false)"
      @click="setColor(editor)"
    >
      🎨
    </button>

    <!-- Цитата -->
    <button
      type="button"
      :class="btnClass(editor.isActive('blockquote'))"
      @click="editor.chain().focus().toggleBlockquote().run()"
    >
      ❝
    </button>

    <!-- Код-блок -->
    <button
      type="button"
      :class="btnClass(editor.isActive('codeBlock'))"
      @click="editor.chain().focus().toggleCodeBlock().run()"
    >
      ⌨️
    </button>

    <!-- Таблиця -->
    <button
      type="button"
      :class="btnClass(false)"
      @click="insertTable(editor)"
    >
      🧮
    </button>

    <!-- Зображення -->
    <button
      type="button"
      :class="btnClass(false)"
      @click="insertImage(editor)"
    >
      🖼️
    </button>

    <!-- Undo / Redo -->
    <button
      type="button"
      :class="btnClass(false)"
      @click="editor.chain().focus().undo().run()"
    >
      ↺
    </button>
    <button
      type="button"
      :class="btnClass(false)"
      @click="editor.chain().focus().redo().run()"
    >
      ↻
    </button>
  </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
  editor: {
    type: Object,
    default: () => ({}),
  },
});

const btnClass = (active) =>
  `px-2 py-1 border rounded text-sm transition
    ${
      active
        ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'
    }`;

const insertImage = (editor) => {
  const url = prompt('Введи URL зображення');
  if (url) {
    editor.chain().focus().setImage({ src: url }).run();
  }
};

const insertTable = (editor) => {
  editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run();
};

const setColor = (editor) => {
  const color = prompt('Введи HEX або CSS назву кольору', '#e63946');
  if (color) {
    editor.chain().focus().setColor(color).run();
  }
};
</script>
