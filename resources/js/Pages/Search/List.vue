<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CategoryAdvert from '@/Components/Advert/Category/CategoryAdvert.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import ChildCategories from '@/Components/Advert/Category/ChildCategories.vue';
import CategoryDropdown from '@/Components/CategoryDropdown.vue';
import Get from '@/Pages/Banner/Get.vue';
const adverts = usePage().props.adverts;
const queryFilter = ref(usePage().props.query || {});
const props = defineProps({
  categories: Object,
  locations: Object,
  childCategories: Object,
  regionsCounts: Object,
  categoriesCounts: Object,
  attributes: Object,
  categoryFilters: Object,
  activeCategory: Object,
  activeRegion: Object,
});

import { useSearch } from '@/composables/useSearch.js';
import SearchInput from '@/Components/Search/SearchInput.vue';
import LocationSelector from '@/Components/Search/LocationSelector.vue';

const { searchQuery, cityIdSearchQuery, search } = useSearch();
const handleCitySelect = (slug) => {
  cityIdSearchQuery.value = slug;
};
const handleSearch = (text) => {
  searchQuery.value = text;
};

const selectedCategoryId = computed(() => queryFilter.value.category_id);

const lastLocation = computed(() => {
  const entries = Object.entries(props.locations);
  if (!entries.length) return null;
  const [_, lastValue] = entries[entries.length - 1];
  return lastValue;
});

const selectedCategoryAttributes = computed(() => {
  const selected = findCategoryById(props.categoryFilters, Number(selectedCategoryId.value));
  return selected?.attributes ?? [];
});

function findCategoryById(categories, id) {
  for (const category of categories) {
    if (category.id === id) return category;
    if (category.children?.length) {
      const found = findCategoryById(category.children, id);
      if (found) return found;
    }
  }
  return null;
}
const handleCategoryChange = (category) => {
  const fullPath = getCategorySlugsPathById(props.categoryFilters, category.id);
  const categories = getCategorySlugs(props.categoryFilters);
  const path = window.location.pathname;

  const cleanPath = path
    .split('/')
    .filter((segment) => segment && !categories.includes(segment))
    .filter((segment) => segment && segment !== 'list')
    .join('/');

  const newPath = `/${fullPath.join('/')}`;

  if (window.location.pathname === newPath + '/' + cleanPath) return;

  router.visit(newPath + '/' + cleanPath + window.location.search);
};

watch(
  () => queryFilter.value.category_id,
  (newVal) => {
    if (!newVal) return;
    const selected = findCategoryById(props.categoryFilters, Number(newVal));
    if (selected) {
      handleCategoryChange(selected);
    }
  }
);

function getCategorySlugs(categories) {
  const slugs = [];

  for (const category of categories) {
    slugs.push(category.slug);

    if (category.children?.length) {
      slugs.push(...getCategorySlugs(category.children));
    }
  }

  return slugs;
}

function getCategorySlugsPathById(categories, id, path = []) {
  for (const category of categories) {
    const newPath = [...path, category.slug];
    if (category.id === id) {
      return newPath;
    }
    if (category.children?.length) {
      const result = getCategorySlugsPathById(category.children, id, newPath);
      if (result) {
        return result;
      }
    }
  }
  return null;
}

onMounted(() => {
  const pathParts = window.location.pathname.split('/').filter(Boolean);

  if (!pathParts.length) return;

  cityIdSearchQuery.value = props.activeRegion;

  if (queryFilter.value.category_id !== props.activeCategory.id) {
    queryFilter.value.category_id = props.activeCategory.id;
  }

  if (queryFilter.value.query) {
    searchQuery.value = queryFilter.value.query;
  }
});

const banner = ref(null);

onMounted(async () => {
  const res = await axios.get('/banner/get', {
    params: {
      category: selectedCategoryId?.value,
      region: lastLocation?.value?.id,
      format: '240x400',
    },
  });
  banner.value = res.data.banner;
});
</script>

<template>
  <Head title="Категорії оголошень " />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white">
            <div
              class="flex items-center gap-4 bg-gray-100 p-4 rounded-lg shadow-md search-container"
            >
              <SearchInput v-model="searchQuery" @select-suggestion="handleSearch" />
              <div class="flex items-center gap-4">
                <LocationSelector v-model="cityIdSearchQuery" @select-city="handleCitySelect" />
                <button
                  @click="search(getCategorySlugs(props.categoryFilters))"
                  class="px-8 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl hover:shadow-2xl transition"
                >
                  Пошук
                </button>
              </div>
            </div>

            <h2 class="text-lg font-semibold text-gray-800 mb-4">Фільтри</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4">
              <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Обрати категорію
                </label>
                <CategoryDropdown
                  v-model="queryFilter.category_id"
                  :categoryFilters="props.categoryFilters"
                />
              </div>

              <!-- Атрибути -->
              <div
                v-for="(attribute, index) in selectedCategoryAttributes"
                :key="index"
                class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm"
              >
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  {{ attribute.name }}
                </label>

                <select
                  v-if="attribute.variants && attribute.variants.length"
                  v-model="queryFilter[attribute.id]"
                  class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Обрати</option>
                  <option v-for="(variant, i) in attribute.variants" :key="i" :value="variant">
                    {{ variant }}
                  </option>
                </select>

                <div v-else-if="attribute.type === 'integer'" class="flex gap-2">
                  <input
                    type="number"
                    :placeholder="`Від`"
                    v-model="queryFilter[`${attribute.id}_from`]"
                    class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <input
                    type="number"
                    :placeholder="`До`"
                    v-model="queryFilter[`${attribute.id}_to`]"
                    class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>

                <input
                  v-else-if="attribute.type === 'string'"
                  type="text"
                  v-model="queryFilter[attribute.id]"
                  class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
            <Breadcrumbs class="mb-6" :categories="props.categories" :locations="props.locations" />

            <ChildCategories
              :childCategories="props.childCategories"
              :categoriesCounts="props.categoriesCounts"
              :categories="props.categories"
            />
            <h2 v-if="queryFilter.query" class="text-lg font-semibold text-gray-800 mb-4 mt-4">
              Пошук за запитом : {{ queryFilter.query }}
            </h2>

            <div v-if="adverts.data.length">
              <CategoryAdvert :adverts="adverts" />
              <Get :banner="banner" />
            </div>
            <div v-else class="m-32 text-lg">
              <h2 class="text-xl mb-4 text-center">Нічого не знайдено.</h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
