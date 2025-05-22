<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  childCategories: {
    type: Array,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
  categoriesCounts: {
    type: Object,
    required: false,
  },
});

const generateChildCategoriesLink = (slug) => {
  const path = props.categories.map((c) => c.slug).join('/');
  const queryParams = window.location.search;
  return `/${path}/${slug}${queryParams}`;
};

const count = (id) => {
  return props.categoriesCounts && props.categoriesCounts[id]
    ? '(' + props.categoriesCounts[id] + ')'
    : '';
};
</script>

<template>
  <div v-if="childCategories.length" class="space-y-2 mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Підкатегорії</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <Link
        v-for="category in childCategories"
        :key="category.id"
        :href="generateChildCategoriesLink(category.slug)"
        class="p-3 border border-gray-100 rounded-lg transition-colors duration-200 hover:shadow-md"
      >
        <div class="text-blue-600 hover:text-blue-800">
          {{ category.name }}
          <span class="font-bold">
            {{ count(category.id) }}
          </span>
        </div>
      </Link>
    </div>
  </div>
</template>
