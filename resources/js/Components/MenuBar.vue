<template>
    <div class="mb-3 flex flex-wrap gap-2">
        <!-- Текстове форматування -->
        <button type="button" @click="editor.chain().focus().toggleBold().run()" :class="btnClass(editor.isActive('bold'))">B</button>
        <button type="button" @click="editor.chain().focus().toggleItalic().run()" :class="btnClass(editor.isActive('italic'))">I</button>
        <button type="button" @click="editor.chain().focus().toggleUnderline().run()" :class="btnClass(editor.isActive('underline'))">U</button>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="btnClass(editor.isActive('heading', { level: 1 }))">H1</button>
        <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="btnClass(editor.isActive('heading', { level: 2 }))">H2</button>

        <!-- Списки -->
        <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :class="btnClass(editor.isActive('bulletList'))">•</button>
        <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :class="btnClass(editor.isActive('orderedList'))">1.</button>

        <!-- Вирівнювання -->
        <button type="button" @click="editor.chain().focus().setTextAlign('left').run()" :class="btnClass(editor.isActive({ textAlign: 'left' }))">⬅️</button>
        <button type="button" @click="editor.chain().focus().setTextAlign('center').run()" :class="btnClass(editor.isActive({ textAlign: 'center' }))">⬅️➡️</button>
        <button type="button" @click="editor.chain().focus().setTextAlign('right').run()" :class="btnClass(editor.isActive({ textAlign: 'right' }))">➡️</button>

        <!-- Колір -->
        <button type="button" @click="setColor(editor)" :class="btnClass(false)">🎨</button>

        <!-- Цитата -->
        <button type="button" @click="editor.chain().focus().toggleBlockquote().run()" :class="btnClass(editor.isActive('blockquote'))">❝</button>

        <!-- Код-блок -->
        <button type="button" @click="editor.chain().focus().toggleCodeBlock().run()" :class="btnClass(editor.isActive('codeBlock'))">⌨️</button>

        <!-- Таблиця -->
        <button type="button" @click="insertTable(editor)" :class="btnClass(false)">🧮</button>

        <!-- Зображення -->
        <button type="button" @click="insertImage(editor)" :class="btnClass(false)">🖼️</button>

        <!-- Undo / Redo -->
        <button type="button" @click="editor.chain().focus().undo().run()" :class="btnClass(false)">↺</button>
        <button type="button" @click="editor.chain().focus().redo().run()" :class="btnClass(false)">↻</button>
    </div>
</template>

<script setup>
defineProps(['editor'])

const btnClass = (active) =>
    `px-2 py-1 border rounded text-sm transition
    ${active
        ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-100'}`

const insertImage = (editor) => {
    const url = prompt('Введи URL зображення')
    if (url) {
        editor.chain().focus().setImage({ src: url }).run()
    }
}

const insertTable = (editor) => {
    editor.chain().focus().insertTable({ rows: 3, cols: 3, withHeaderRow: true }).run()
}

const setColor = (editor) => {
    const color = prompt('Введи HEX або CSS назву кольору', '#e63946')
    if (color) {
        editor.chain().focus().setColor(color).run()
    }
}
</script>
