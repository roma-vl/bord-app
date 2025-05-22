<script setup>
import { Head, router, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import Modal from "@/Components/Modal.vue";
import {computed, ref} from "vue";
import Create from "@/Pages/Admin/Roles/Create.vue";
import Edit from "@/Pages/Admin/Roles/Edit.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";
import Grid from "@/Components/Grid.vue";
import RefreshIcon from "@/Components/Icon/RefreshIcon.vue";

const flash = computed(() => usePage().props.flash);
const roles = computed(() => usePage().props.roles.data);
const pagination = computed(() => usePage().props.roles);

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const selectedRole = ref(null);

const headings = [
    { key: "id", value: "ID", sortable: true, disabled: true},
    { key: "name", value: "Title" },
    { key: "is_enabled", value: "Enabled" },
    { key: "actions", value: "Actions", disabled: true },
];

const routes = [
    { key: "index", value: "admin.roles.index" },
    { key: "search", value: "admin.roles.search" },
];
const openCreateModal = async () => {

    try {
        const response = await axios.get(route("admin.roles.create"));
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
        const response = await axios.get(route("admin.roles.edit", id));
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
    router.put(route("admin.roles.restore", id), {}, {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.roles.index")),
    });
};
const deleteRole = (id) => {
    if (confirm("Are you sure you want to delete this role?")) {
        router.delete(route("admin.roles.destroy", id), {
            onSuccess: () => router.replace(route("admin.roles.index")),
        });
    }
};

</script>

<template>
    <Head title="Roles" />
    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage v-if="flash" :flash="flash" />

                <div class="mb-2 flex justify-end">
                    <button  @click="openCreateModal" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">
                        + New Role
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
                                <span v-if="row.is_enabled" class="text-green-600 bg-green-200 border-green-200 px-1 rounded-md  items-center"> enable </span>
                                <span v-else class="text-red-600 bg-red-200 border-red-200 px-1 rounded-md  items-center"> disable </span>
                            </div>
                        </div>
                    </template>
                    <template #column-actions="{ row }">
                        <div class="flex gap-2">
                            <div class="flex justify-end gap-4">
                                <a v-if="!row.deleted_at" @click.prevent="openEditModal(row.id)" class="text-blue-600 hover:text-blue-900 cursor-pointer">
                                    <PencilIcon />
                                </a>
                                <a  v-if="!row.deleted_at" @click.prevent="deleteRole(row.id)" class="text-red-600 hover:text-red-900 cursor-pointer">
                                    <TrashIcon />
                                </a>
                                <a v-else  @click.prevent="restoreRole(row.id)" class="text-green-600 hover:text-green-900 cursor-pointer">
                                    <RefreshIcon />
                                </a>

                            </div>
                        </div>
                    </template>
                </Grid>

                <Modal :show="isCreateModalOpen" maxWidth="2xl" @close="isCreateModalOpen = false">
                    <Create :data="selectedRole" @userCreated="isCreateModalOpen = false" />
                </Modal>

                <Modal :show="isEditModalOpen" maxWidth="2xl" @close="isEditModalOpen = false">
                    <Edit :data="selectedRole" @roleUpdated="isEditModalOpen = false" />
                </Modal>
            </div>
        </div>
    </AdminLayout>
</template>
