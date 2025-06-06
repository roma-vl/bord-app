<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Modal from '@/Components/Modal.vue';
import { computed, ref } from 'vue';
import Create from '@/Pages/Admin/Roles/Create.vue';
import Edit from '@/Pages/Admin/Roles/Edit.vue';
import TrashIcon from '@/Components/Icon/TrashIcon.vue';
import PencilIcon from '@/Components/Icon/PencilIcon.vue';
import Grid from '@/Components/Grid.vue';
import RefreshIcon from '@/Components/Icon/RefreshIcon.vue';
import { useI18n } from 'vue-i18n';

const roles = computed(() => usePage().props.roles.data);
const pagination = computed(() => usePage().props.roles);
const { t } = useI18n();
const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const selectedRole = ref(null);

const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'name', value: t('role') },
  { key: 'is_enabled', value: t('status') },
  { key: 'actions', value: t('actions'), disabled: true },
];

const routes = [
  { key: 'index', value: 'admin.roles.index' },
  { key: 'search', value: 'admin.roles.search' },
];
const openCreateModal = async () => {
  try {
    const response = await axios.get(route('admin.roles.create'));
    if (response.status === 200) {
      selectedRole.value = response.data;
      isCreateModalOpen.value = true;
    }
  } catch (error) {
    showForbidden.value = true;
    errorForbidden.value = error.response;
  }
};

const openEditModal = async (id) => {
  try {
    const response = await axios.get(route('admin.roles.edit', id));
    if (response.status === 200) {
      selectedRole.value = response.data;
      isEditModalOpen.value = true;
    }
  } catch (error) {
    showForbidden.value = true;
    errorForbidden.value = error.response;
  }
};
const restoreRole = (id) => {
  router.put(
    route('admin.roles.restore', id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.roles.index')),
    }
  );
};
const deleteRole = (id) => {
  if (confirm('Are you sure you want to delete this role?')) {
    router.delete(route('admin.roles.destroy', id), {
      onSuccess: () => router.replace(route('admin.roles.index')),
    });
  }
};
</script>

<template>
  <Head :title="t('roles')" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 py-4 dark:bg-gray-900">
        <div class="mb-2 flex justify-end">
          <button
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
            @click="openCreateModal"
          >
            + {{ t('new.role') }}
          </button>
        </div>
        <Grid
          :items="roles"
          :pagination="pagination"
          :headings="headings"
          :routes="routes"
        >
          <template #column-is_enabled="{ row }">
            <div class="flex gap-2">
              <div class="flex justify-end gap-4">
                <span
                  v-if="row.is_enabled"
                  class="text-green-600 bg-green-200 border-green-200 px-1 rounded-md items-center"
                >
                  enable
                </span>
                <span
                  v-else
                  class="text-red-600 bg-red-200 border-red-200 px-1 rounded-md items-center"
                >
                  disable
                </span>
              </div>
            </div>
          </template>
          <template #column-actions="{ row }">
            <div class="flex gap-2">
              <div class="flex justify-end gap-4">
                <a
                  v-if="!row.deleted_at"
                  class="text-blue-600 hover:text-blue-900 cursor-pointer"
                  @click.prevent="openEditModal(row.id)"
                >
                  <PencilIcon />
                </a>
                <a
                  v-if="!row.deleted_at"
                  class="text-red-600 hover:text-red-900 cursor-pointer"
                  @click.prevent="deleteRole(row.id)"
                >
                  <TrashIcon />
                </a>
                <a
                  v-else
                  class="text-green-600 hover:text-green-900 cursor-pointer"
                  @click.prevent="restoreRole(row.id)"
                >
                  <RefreshIcon />
                </a>
              </div>
            </div>
          </template>
        </Grid>

        <Modal
          :show="isCreateModalOpen"
          max-width="2xl"
          @close="isCreateModalOpen = false"
        >
          <Create
            :data="selectedRole"
            @user-created="isCreateModalOpen = false"
          />
        </Modal>

        <Modal
          :show="isEditModalOpen"
          max-width="2xl"
          @close="isEditModalOpen = false"
        >
          <Edit
            :data="selectedRole"
            @role-updated="isEditModalOpen = false"
          />
        </Modal>
      </div>
    </div>
  </AdminLayout>
</template>
