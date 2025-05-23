<script setup>
import { defineProps, ref } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import Modal from '@/Components/Modal.vue';
import Create from '@/Pages/Admin/Advert/Attribute/Create.vue';
import Edit from '@/Pages/Admin/Advert/Attribute/Edit.vue';

const props = defineProps({
  category: {
    type: Object,
    default: () => ({}),
  },
  attributes: {
    type: Array,
    default: () => [],
  },
  parentAttributes: {
    type: Array,
    default: () => [],
  },
});

const flash = usePage().props.flash;
const isCreateModalOpen = ref(false);
const selectedCategory = ref(null);
const isEditModalOpen = ref(false);

const openCreateModal = async () => {
  const { data } = await axios.get(
    route('admin.adverts.category.attributes.create', props.category.id)
  );
  selectedCategory.value = data;
  isCreateModalOpen.value = true;
};

const openEditModal = async (attributeId) => {
  const { data } = await axios.get(
    route('admin.adverts.category.attributes.edit', {
      category: props.category.id,
      attribute: attributeId,
    })
  );
  selectedCategory.value = data;
  isEditModalOpen.value = true;
};

const refreshAttributes = () => {
  router.get(route('admin.adverts.category.show', { category: props.category.id }), {
    preserveScroll: true,
    onSuccess: () =>
      router.replace(route('admin.adverts.category.show', { category: props.category.id })),
  });
};

const deleteCategory = (attributeId) => {
  if (confirm('Are you sure you want to delete this Category?')) {
    router.delete(
      route('admin.adverts.category.attributes.destroy', {
        category: props.category.id,
        attribute: attributeId,
      }),
      {
        preserveScroll: true,
        onSuccess: () =>
          router.replace(route('admin.adverts.category.show', { category: props.category.id })),
      }
    );
  }
};
</script>

<template>
  <Head title="Категорія - Атрибути" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <FlashMessage :flash="flash" />

        <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px]">
          <h1 class="text-2xl font-bold">
            Категорія: {{ props.category.name }}
          </h1>
          <p class="text-gray-600">
            Slug: {{ props.category.slug }}
          </p>

          <div class="mb-2 flex justify-end">
            <button
              class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
              @click="openCreateModal"
            >
              + Додати Атрибут
            </button>
          </div>
          <p>Батьківські</p>
          <table class="w-full mt-4 border-collapse border border-gray-200">
            <thead>
              <tr class="bg-gray-100">
                <th class="border p-2">
                  ID
                </th>
                <th class="border p-2">
                  Назва
                </th>
                <th class="border p-2">
                  Тип
                </th>
                <th class="border p-2">
                  Обов’язковий
                </th>
                <th class="border p-2">
                  Сортування
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="attr in parentAttributes"
                :key="attr.id"
              >
                <td class="border p-2">
                  {{ attr.id }}
                </td>
                <td class="border p-2">
                  {{ attr.name }}
                </td>
                <td class="border p-2">
                  {{ attr.type }}
                </td>
                <td class="border p-2">
                  {{ attr.required ? 'Так' : 'Ні' }}
                </td>
                <td class="border p-2">
                  {{ attr.sort }}
                </td>
              </tr>
            </tbody>
          </table>

          <p>Власні</p>
          <table class="w-full mt-4 border-collapse border border-gray-200">
            <thead>
              <tr class="bg-gray-100">
                <th class="border p-2">
                  ID
                </th>
                <th class="border p-2">
                  Назва
                </th>
                <th class="border p-2">
                  Тип
                </th>
                <th class="border p-2">
                  Обов’язковий
                </th>
                <th class="border p-2">
                  Сортування
                </th>
                <th class="border p-2">
                  Дії
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="attr in attributes"
                :key="attr.id"
              >
                <td class="border p-2">
                  {{ attr.id }}
                </td>
                <td class="border p-2">
                  {{ attr.name }}
                </td>
                <td class="border p-2">
                  {{ attr.type }}
                </td>
                <td class="border p-2">
                  {{ attr.required ? 'Так' : 'Ні' }}
                </td>
                <td class="border p-2">
                  {{ attr.sort }}
                </td>
                <td class="border p-2">
                  <button
                    class="text-blue-500 pr-2 hover:underline"
                    @click.stop="openEditModal(attr.id)"
                  >
                    Редагувати
                  </button>
                  <button
                    class="text-red-500 hover:underline"
                    @click.stop="deleteCategory(attr.id)"
                  >
                    Видалити
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <Modal
      :show="isCreateModalOpen"
      max-width="2xl"
      @close="isCreateModalOpen = false"
    >
      <Create
        :data="selectedCategory"
        @attribute-created="refreshAttributes"
      />
    </Modal>

    <Modal
      :show="isEditModalOpen"
      max-width="2xl"
      @close="isEditModalOpen = false"
    >
      <Edit
        :data="selectedCategory"
        @attribute-updated="refreshAttributes"
      />
    </Modal>
  </AdminLayout>
</template>
