<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grid from '@/Components/Grid.vue';
import TrashIcon from '@/Components/Icon/TrashIcon.vue';
import PencilIcon from '@/Components/Icon/PencilIcon.vue';
import AvatarIcon from '@/Components/Icon/AvatarIcon.vue';
import { computed, ref } from 'vue';
import RefreshIcon from '@/Components/Icon/RefreshIcon.vue';
import Modal from '@/Components/Modal.vue';
import Create from '@/Pages/Admin/Users/Create.vue';
import Edit from '@/Pages/Admin/Users/Edit.vue';
import Show from '@/Pages/Admin/Users/Show.vue';
import Forbidden from '@/Components/Forbidden.vue';
import { useI18n } from 'vue-i18n';
const users = usePage().props.users.data;
const pagination = computed(() => usePage().props.users.meta);
const { t } = useI18n();

const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'name', value: t('name'), sortable: true, highlight: true },
  { key: 'email', value: t('email'), sortable: true, highlight: true },
  { key: 'status', value: t('status') },
  { key: 'role', value: t('role') },
  { key: 'created_at', value: t('Created At') },
  { key: 'updated_at', value: t('Updated At') },
  { key: 'actions', value: t('actions'), disabled: true },
];

const routes = [
  { key: 'index', value: 'admin.users.index' },
  { key: 'search', value: 'admin.users.search' },
];

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const showForbidden = ref(false);
const errorForbidden = ref(false);
const isShowModalOpen = ref(false);
const selectedUser = ref(null);

const openCreateModal = async () => {
  isCreateModalOpen.value = true;
  try {
    const response = await axios.get(route('admin.users.create'));
    if (response.status === 200) {
      selectedUser.value = response.data;
      isCreateModalOpen.value = true;
    }
  } catch (error) {
    showForbidden.value = true;
    errorForbidden.value = error.response;
  }
};

const openEditModal = async (id) => {
  try {
    const response = await axios.get(route('admin.users.edit', id));
    if (response.status === 200) {
      selectedUser.value = response.data;
      isEditModalOpen.value = true;
    }
  } catch (error) {
    showForbidden.value = true;
    errorForbidden.value = error.response;
  }
};

const openShowModal = async (id) => {
  try {
    const response = await axios.get(route('admin.users.show', id));
    selectedUser.value = response.data;
    isShowModalOpen.value = true;
  } catch (error) {
    console.error('Помилка при завантаженні користувача', error);
  }
};

const refreshUsers = () => {
  router.get(route('admin.users.index'), {
    preserveScroll: true,
    onSuccess: () => router.replace(route('admin.users.index')),
  });
};

const deleteUser = (id) => {
  if (confirm('Ви впевнені, що хочете видалити цього користувача?')) {
    router.delete(route('admin.users.destroy', id), {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.users.index')),
    });
  }
};

const restoreUser = (id) => {
  router.put(
    route('admin.users.restore', id),
    {},
    {
      preserveScroll: true,
      onSuccess: () => router.replace(route('admin.users.index')),
    }
  );
};
</script>

<template>
  <Head title="Users" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 rounded-md pt-2 dark:bg-gray-900">
        <div class="mb-2 flex justify-end">
          <button
            v-can="'user.create'"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
            @click="openCreateModal"
          >
            + {{ t('new.user') }}
          </button>
        </div>
        <Grid
          :items="users"
          :pagination="pagination"
          :headings="headings"
          :routes="routes"
        >
          <template #column-name="{ row }">
            <div class="flex gap-2 font-normal">
              <div class="relative h-10 w-10">
                <div v-if="row.avatar_url">
                  <img
                    :src="row.avatar_url"
                    :alt="row.name"
                    class="rounded"
                  >
                </div>
                <div v-else>
                  <AvatarIcon />
                </div>
              </div>
              <div class="text-sm flex justify-center items-center">
                <!-- eslint-disable vue/no-v-html -->
                <span
                  class="font-medium hover:underline cursor-pointer"
                  @click.prevent="openShowModal(row.id)"
                  v-html="row.name"
                />
                <!-- eslint-enable vue/no-v-html -->
              </div>
            </div>
          </template>
          <template #column-email="{ row }">
            <!-- eslint-disable vue/no-v-html -->
            <span
              class="font-medium hover:underline cursor-pointer"
              @click.prevent="openShowModal(row.id)"
              v-html="row.email"
            />
            <!-- eslint-enable vue/no-v-html -->
          </template>
          <template #column-status="{ row }">
            <div v-if="row.deleted_at">
              <span
                class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-600"
              >
                <span class="h-1.5 w-1.5 rounded-full bg-red-600" /> Deleted
              </span>
            </div>
            <div v-else-if="row.status">
              <span
                class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600"
              >
                <span class="h-1.5 w-1.5 rounded-full bg-green-600" /> Active
              </span>
            </div>
            <div v-else>
              <span
                class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-semibold text-yellow-600"
              >
                <span class="h-1.5 w-1.5 rounded-full bg-yellow-600" /> Inactive
              </span>
            </div>
          </template>
          <template #column-role="{ row }">
            <div class="flex gap-2">
              <span
                v-for="(role, index) in row.roles"
                :key="index"
                class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-1 text-xs font-semibold text-violet-600"
              >
                {{ role }}
              </span>
            </div>
          </template>
          <template #column-actions="{ row }">
            <div class="flex gap-2">
              <div class="flex justify-end gap-4">
                <div v-can="'user.delete'">
                  <a
                    v-if="!row.deleted_at"
                    class="text-red-600 hover:text-red-900 cursor-pointer"
                    @click.prevent="deleteUser(row.id)"
                  >
                    <TrashIcon />
                  </a>
                  <a
                    v-else
                    class="text-green-600 hover:text-green-900 cursor-pointer"
                    @click.prevent="restoreUser(row.id)"
                  >
                    <RefreshIcon />
                  </a>
                </div>
                <div v-can="'user.edit'">
                  <a
                    v-if="!row.deleted_at"
                    class="text-blue-600 hover:text-blue-900 cursor-pointer"
                    @click.prevent="openEditModal(row.id)"
                  >
                    <PencilIcon />
                  </a>
                </div>
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
            v-if="selectedUser"
            :roles="selectedUser.roles"
            @user-created="refreshUsers"
          />
        </Modal>
        <Modal
          :show="isEditModalOpen"
          @close="isEditModalOpen = false"
        >
          <Edit
            v-if="selectedUser"
            :user="selectedUser.user"
            :roles="selectedUser.roles"
            :user-roles="selectedUser.userRoles"
            @user-updated="refreshUsers"
          />
        </Modal>
        <Modal
          :show="isShowModalOpen"
          max-width="2xl"
          @close="isShowModalOpen = false"
        >
          <Show
            v-if="selectedUser"
            :user="selectedUser"
          />
        </Modal>
        <Modal
          :show="showForbidden"
          max-width="2xl"
          @close="showForbidden = false"
        >
          <Forbidden :data="errorForbidden" />
        </Modal>
      </div>
    </div>
  </AdminLayout>
</template>
