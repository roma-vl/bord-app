<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, defineEmits, defineProps } from 'vue';
import Editor from '@/Components/Editor.vue';
const props = defineProps({
  page: {
    type: Object,
    required: true,
  },
});
const emit = defineEmits(['pageUpdated']);
const pages = usePage().props.pages;

const form = useForm({
  title: props.page.title,
  menu_title: props.page.menu_title,
  slug: props.page.slug,
  parent_id: props.page.parent_id,
  content: props.page.content,
  description: props.page.description,
});

const submit = () => {
  form.put(route('admin.pages.update', props.page.id), {
    onSuccess: () => emit('pageUpdated'),
  });
};

const getPagesOptions = (pages, prefix = '') => {
  let options = [];

  pages.forEach((page) => {
    options.push({ id: page.id, title: prefix + page.title });

    if (page.children_recursive && page.children_recursive.length) {
      options = options.concat(getPagesOptions(page.children_recursive, prefix + '- '));
    }
  });
  return options;
};
const formattedPages = computed(() => getPagesOptions(pages));
</script>

<template>
  <Head title="Додати сторінку" />

  <div class="mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-6">Додати сторінку</h1>

    <form @submit.prevent="submit" class="space-y-6">
      <div>
        <label class="block text-gray-700 font-medium mb-1">Назва</label>
        <input
          v-model="form.title"
          type="text"
          class="w-full p-2 border border-gray-300 rounded focus:ring focus:border-blue-300"
        />
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">
          {{ form.errors.title }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Назва в меню</label>
        <input
          v-model="form.menu_title"
          type="text"
          class="w-full p-2 border border-gray-300 rounded focus:ring focus:border-blue-300"
        />
        <div v-if="form.errors.menu_title" class="text-red-500 text-sm mt-1">
          {{ form.errors.menu_title }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Slug</label>
        <input
          v-model="form.slug"
          type="text"
          class="w-full p-2 border border-gray-300 rounded focus:ring focus:border-blue-300"
        />
        <div v-if="form.errors.slug" class="text-red-500 text-sm mt-1">{{ form.errors.slug }}</div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Батьківська сторінка</label>
        <select
          v-model="form.parent_id"
          class="w-full p-2 border border-gray-300 rounded focus:ring focus:border-blue-300"
        >
          <option :value="null">— Без батьківської сторінки</option>
          <option v-for="page in formattedPages" :key="page.id" :value="page.id">
            {{ page.title }}
          </option>
        </select>
        <div v-if="form.errors.parent_id" class="text-red-500 text-sm mt-1">
          {{ form.errors.parent_id }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Опис</label>
        <textarea
          v-model="form.description"
          rows="3"
          class="w-full p-2 border border-gray-300 rounded focus:ring focus:border-blue-300"
        ></textarea>
        <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
          {{ form.errors.description }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1">Контент</label>
        <Editor v-model="form.content" />
        <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">
          {{ form.errors.content }}
        </div>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
          :disabled="form.processing"
        >
          {{ form.processing ? 'Збереження...' : 'Зберегти' }}
        </button>
      </div>
    </form>
  </div>
</template>
