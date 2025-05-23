<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumbs from '@/Components/Breadcrumbs.vue';
import { Head } from '@inertiajs/vue3';
import ChildCategories from '@/Components/Advert/Category/ChildCategories.vue';
import CategoryAdvert from '@/Components/Advert/Category/CategoryAdvert.vue';

const props = defineProps({
  categories: {
    type: Object,
    default: () => ({}),
  },
  locations: {
    type: Object,
    default: () => ({}),
  },
  childCategories: {
    type: Object,
    default: () => ({}),
  },
  adverts: {
    type: Object,
    default: () => ({}),
  },
});
</script>

<template>
  <Head title="Категорії оголошень " />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white">
            <Breadcrumbs
              class="mb-6"
              :categories="categories"
              :locations="locations"
            />

            <ChildCategories
              :child-categories="childCategories"
              :categories="categories"
            />

            <CategoryAdvert :adverts="adverts" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
