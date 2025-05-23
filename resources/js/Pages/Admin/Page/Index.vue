<script setup>
import { ref } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import Create from '@/Pages/Admin/Page/Create.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import PageItem from '@/Components/PageItem.vue';

const pages = usePage().props.pages;
const flash = usePage().props.flash;
const isCreateModalOpen = ref(false);
const selectedPages = ref(null);

const openCreateModal = async () => {
  const { data } = await axios.get(route('admin.pages.create'));
  selectedPages.value = data;
  isCreateModalOpen.value = true;
};
const refreshPages = () => {
  router.get(route('admin.pages.index'), {
    preserveScroll: true,
    onSuccess: () => router.replace(route('admin.pages.index')),
  });
};
</script>

<template>
  <Head title="Категорії" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <FlashMessage :flash="flash" />
        <div class="mb-2 flex justify-end">
          <button
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
            @click="openCreateModal"
          >
            + Додати pages
          </button>
        </div>
        <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px]">
          <ul class="space-y-2">
            <PageItem
              v-for="page in pages"
              :key="page.id"
              :page="page"
            />
          </ul>
        </div>
      </div>
    </div>
    <Modal
      :show="isCreateModalOpen"
      max-width="5xl"
      @close="isCreateModalOpen = false"
    >
      <Create
        :pages="selectedPages"
        @page-created="refreshPages"
      />
    </Modal>
  </AdminLayout>
</template>
