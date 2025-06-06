<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, defineEmits } from 'vue';
import Editor from '@/Components/Editor.vue';

const emit = defineEmits(['pageCreated']);
const pages = usePage().props.pages;

const form = useForm({
  title: '',
  menu_title: '',
  slug: '',
  parent_id: null,
  content: '',
  description: '',
});

const submit = () => {
  form.post(route('admin.pages.store'), {
    onSuccess: () => emit('pageCreated'),
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
  <Head :title="$t('new.page')" />

  <div class="mx-auto p-6 bg-white shadow rounded dark:bg-gray-800 dark:text-gray-200">
    <h1 class="text-2xl font-bold mb-6 dark:text-gray-200">
      {{ $t('new.page') }}
    </h1>

    <form
      class="space-y-6"
      @submit.prevent="submit"
    >
      <div>
        <label class="block text-gray-700 font-medium mb-1 dark:text-gray-200">{{
          $t('title')
        }}</label>
        <input
          v-model="form.title"
          type="text"
          class="w-full p-2 border border-gray-300 dark:border-gray-900 rounded focus:ring focus:border-blue-300 dark:bg-gray-900"
        >
        <div
          v-if="form.errors.title"
          class="text-red-500 text-sm mt-1"
        >
          {{ form.errors.title }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1 dark:text-gray-200">{{
          $t('menu.title')
        }}</label>
        <input
          v-model="form.menu_title"
          type="text"
          class="w-full p-2 border border-gray-300 dark:border-gray-900 rounded focus:ring focus:border-blue-300 dark:bg-gray-900"
        >
        <div
          v-if="form.errors.menu_title"
          class="text-red-500 text-sm mt-1"
        >
          {{ form.errors.menu_title }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1 dark:text-gray-200">{{
          $t('slug')
        }}</label>
        <input
          v-model="form.slug"
          type="text"
          class="w-full p-2 border border-gray-300 dark:border-gray-900 rounded focus:ring focus:border-blue-300 dark:bg-gray-900"
        >
        <div
          v-if="form.errors.slug"
          class="text-red-500 text-sm mt-1"
        >
          {{ form.errors.slug }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1 dark:text-gray-200">{{
          $t('parent.page')
        }}</label>
        <select
          v-model="form.parent_id"
          class="w-full p-2 border border-gray-300 dark:border-gray-900 rounded focus:ring focus:border-blue-300 dark:bg-gray-900"
        >
          <option :value="null">
            — {{ $t('without.parent.page') }}
          </option>
          <option
            v-for="page in formattedPages"
            :key="page.id"
            :value="page.id"
          >
            {{ page.title }}
          </option>
        </select>
        <div
          v-if="form.errors.parent_id"
          class="text-red-500 text-sm mt-1"
        >
          {{ form.errors.parent_id }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1 dark:text-gray-200">{{
          $t('description')
        }}</label>
        <textarea
          v-model="form.description"
          rows="3"
          class="w-full p-2 border border-gray-300 rounded dark:border-gray-900 focus:ring focus:border-blue-300 dark:bg-gray-900"
        />
        <div
          v-if="form.errors.description"
          class="text-red-500 text-sm mt-1"
        >
          {{ form.errors.description }}
        </div>
      </div>

      <div>
        <label class="block text-gray-700 font-medium mb-1 dark:text-gray-200">{{
          $t('content')
        }}</label>
        <Editor v-model="form.content" />
        <div
          v-if="form.errors.content"
          class="text-red-500 text-sm mt-1"
        >
          {{ form.errors.content }}
        </div>
      </div>

      <div class="flex justify-end">
        <button
          type="submit"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
          :disabled="form.processing"
        >
          {{ form.processing ? $t('Saving') + '...' : $t('Save') }}
        </button>
      </div>
    </form>
  </div>
</template>
