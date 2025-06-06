<script setup>
import { computed, defineProps, watchEffect } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
  category: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(['categoryUpdated']);
const categories = usePage().props.categories;
const form = useForm({
  name: '',
  slug: '',
  parent_id: null,
});

watchEffect(() => {
  form.name = props.category?.name || '';
  form.slug = props.category?.slug || '';
  form.parent_id = props.category?.parent_id || null;
});

const submit = () => {
  form.put(route('admin.adverts.category.update', props.category.id), {
    onSuccess: () => emit('categoryUpdated'),
  });
};

const getCategoryOptions = (categories, prefix = '') => {
  let options = [];
  categories.forEach((category) => {
    options.push({ id: category.id, name: prefix + category.name });

    if (category.children_recursive && category.children_recursive.length) {
      options = options.concat(getCategoryOptions(category.children_recursive, prefix + '- '));
    }
  });
  return options;
};

const formattedCategories = computed(() => getCategoryOptions(categories));
</script>

<template>
  <Head :title="$t('edit.category')" />

  <div class="p-6 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200">
    <h1 class="text-2xl font-bold mb-4">
      {{ $t('edit.category') }}
    </h1>

    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-200">{{ $t('title') }}</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full p-2 border rounded dark:bg-gray-900"
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-200">{{ $t('slug') }}</label>
        <input
          v-model="form.slug"
          type="text"
          class="w-full p-2 border rounded dark:bg-gray-900"
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 dark:text-gray-200">{{ $t('parent.category') }}</label>
        <select
          v-model="form.parent_id"
          class="w-full p-2 border rounded dark:bg-gray-900"
        >
          <option :value="null">
            - {{ $t('without.parent.category') }}
          </option>
          <option
            v-for="cat in formattedCategories"
            :key="cat.id"
            :value="cat.id"
          >
            {{ cat.name }}
          </option>
        </select>
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded"
      >
        {{ $t('Save') }}
      </button>
    </form>
  </div>
</template>
