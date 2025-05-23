<script setup>
import { defineProps, ref } from 'vue';
import Edit from '@/Pages/Admin/Page/Edit.vue';
import Modal from '@/Components/Modal.vue';
import { Link, router } from '@inertiajs/vue3';

const isEditModalOpen = ref(false);
const selectedPage = ref(null);

const props = defineProps({
  page: {
    type: Object,
    default: () => ({}),
  },
  prefix: {
    type: String,
    default: '',
  },
});
const page = props.page;

const openEditModal = async (pageId) => {
  const { data } = await axios.get(route('admin.pages.edit', pageId));
  selectedPage.value = data.page;
  isEditModalOpen.value = true;
};

const refreshPages = () => {
  router.get(route('admin.pages.index'), {
    preserveScroll: true,
    onSuccess: () => router.replace(route('admin.pages.index')),
  });
};

const deletePage = (id) => {
  if (confirm('Are you sure you want to delete this Page?')) {
    router.delete(route('admin.pages.destroy', id), {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.pages.index')),
    });
  }
};

const moveUp = (id) => {
  router.post(
    route('admin.pages.up', id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.pages.index')),
    }
  );
};

const moveDown = (id) => {
  router.post(
    route('admin.pages.down', id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.pages.index')),
    }
  );
};

const moveToTop = (id) => {
  router.post(
    route('admin.pages.first', id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.pages.index')),
    }
  );
};

const moveToBottom = (id) => {
  router.post(
    route('admin.pages.last', id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.pages.index')),
    }
  );
};
</script>

<template>
  <li>
    <div
      class="flex justify-between items-center bg-white hover:bg-gray-100 p-3 mb-2 rounded cursor-pointer shadow border-l-[3px] border-gray-300"
    >
      <span class="font-semibold">{{ props.prefix }}{{ props.page.title }}</span>
      <div class="flex items-right">
        <button
          class="text-purple-500 pr-2"
          @click.stop="moveToTop(page.id)"
        >
          ⏫
        </button>
        <button
          class="text-green-500 pr-2"
          @click.stop="moveUp(page.id)"
        >
          🔼
        </button>
        <button
          class="text-orange-500 pr-2"
          @click.stop="moveDown(page.id)"
        >
          🔽
        </button>
        <button
          class="text-purple-500 pr-2"
          @click.stop="moveToBottom(page.id)"
        >
          ⏬
        </button>
        <Link
          :href="route('admin.pages.show', page.id)"
          class="text-blue-500 pr-2"
        >
          Show
        </Link>
        <button
          class="text-blue-500 pr-2"
          @click.stop="openEditModal(page.id)"
        >
          Редагувати
        </button>
        <button
          class="text-red-500 hover:underline"
          @click.stop="deletePage(page.id)"
        >
          Видалити
        </button>
      </div>
    </div>

    <div
      v-if="page.children_recursive?.length"
      class="ml-6"
    >
      <PageItem
        v-for="child in page.children_recursive"
        :key="child.id"
        :page="child"
      />
    </div>
  </li>

  <Modal
    :show="isEditModalOpen"
    max-width="5xl"
    @close="isEditModalOpen = false"
  >
    <Edit
      :page="selectedPage"
      @page-updated="refreshPages"
    />
  </Modal>
</template>
