<script setup>
import { ref } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import Create from '@/Pages/Admin/Advert/Category/Create.vue';
import CategoryItem from '@/Pages/Admin/Advert/Category/Components/CategoryItem.vue';
import { useI18n } from 'vue-i18n';

const categories = usePage().props.categories;
const isCreateModalOpen = ref(false);
const selectedCategory = ref(null);
const { t } = useI18n();
const openCreateModal = async () => {
  const { data } = await axios.get(route('admin.adverts.category.create'));
  selectedCategory.value = data;
  isCreateModalOpen.value = true;
};
const refreshCategories = () => {
  router.get(route('admin.adverts.category.index'), {
    preserveScroll: true,
    onSuccess: () => router.replace(route('admin.adverts.category.index')),
  });
};
</script>

<template>
  <Head title="Категорії" />
  <AdminLayout>
    <div class="py-4 dark:bg-gray-900">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="mb-2 flex justify-end">
          <button
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
            @click="openCreateModal"
          >
            + {{ t('add.category') }}
          </button>
        </div>
        <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px] dark:bg-gray-700">
          <ul class="space-y-2">
            <CategoryItem
              v-for="category in categories"
              :key="category.id"
              :category="category"
            />
          </ul>
        </div>
      </div>
    </div>
    <Modal
      :show="isCreateModalOpen"
      max-width="2xl"
      @close="isCreateModalOpen = false"
    >
      <Create
        :categories="selectedCategory"
        @category-created="refreshCategories"
      />
    </Modal>
  </AdminLayout>
</template>
