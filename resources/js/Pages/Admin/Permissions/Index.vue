<script setup>
import { Head, router, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import Modal from "@/Components/Modal.vue";
import {computed, ref} from "vue";
import Create from "@/Pages/Admin/Permissions/Create.vue";
import Edit from "@/Pages/Admin/Permissions/Edit.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";
import Grid from "@/Components/Grid.vue";

const flash = computed(() => usePage().props.flash);
const permissions = computed(() => usePage().props.permissions.data);
const pagination = computed(() => usePage().props.permissions);

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const selectedPermission = ref(null);

const headings = [
    { key: "id", value: "ID", sortable: true, disabled: true},
    { key: "key", value: "Key" },
    { key: "description", value: "Description" },
    { key: "actions", value: "Actions", disabled: true },
];

const routes = [
    { key: "index", value: "admin.permissions.index" },
    { key: "search", value: "admin.permissions.search" },
];


const openCreateModal = () => {
    isCreateModalOpen.value = true;
};

const openEditModal = async (id) => {
    try {
        const response = await axios.get(route("admin.permissions.edit", id));
        if (response.status === 200) {
            selectedPermission.value = response.data;
            isEditModalOpen.value = true;
        }
    } catch (error) {
        console.error(error);
    }
};

const deletePermission = (id) => {
    if (confirm("Are you sure you want to delete this permission?")) {
        router.delete(route("admin.permissions.destroy", id), {
            preserveScroll: true,
            onSuccess: () => router.replace(route("admin.permissions.index")),
        });
    }
};

</script>

<template>
    <Head title="Permissions" />
    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage v-if="flash" :flash="flash" />

                <div class="mb-2 flex justify-end">
                    <button @click="openCreateModal" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">
                        + New Permission
                    </button>
                </div>
                <Grid
                    :items="permissions"
                    :pagination="pagination"
                    :headings="headings"
                    :routes="routes"
                >
                    <template #column-actions="{ row }">
                        <div class="flex gap-2">
                            <div class="flex justify-end gap-4">
                                <a @click.prevent="openEditModal(row.id)" class="text-blue-600 hover:text-blue-900 cursor-pointer">
                                    <PencilIcon />
                                </a>
                                <a @click.prevent="deletePermission(row.id)" class="text-red-600 hover:text-red-900 cursor-pointer">
                                    <TrashIcon />
                                </a>

                            </div>
                        </div>
                    </template>
                </Grid>

                <Modal :show="isCreateModalOpen" maxWidth="2xl" @close="isCreateModalOpen = false">
                    <Create @permissionCreated="isCreateModalOpen = false" />
                </Modal>

                <Modal :show="isEditModalOpen" maxWidth="2xl" @close="isEditModalOpen = false">
                    <Edit :data="selectedPermission" @permissionUpdated="isEditModalOpen = false" />
                </Modal>
            </div>
        </div>
    </AdminLayout>
</template>
