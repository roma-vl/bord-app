<script setup>
import { Head, router, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import Create from "@/Pages/Admin/Permissions/Create.vue";
import Edit from "@/Pages/Admin/Permissions/Edit.vue";

const flash = usePage().props.flash;
const permissions = usePage().props.permissions;

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const selectedPermission = ref(null);

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

const refreshPermissions = () => {
    router.get(route("admin.permissions.index"), {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.permissions.index")),
    });
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

                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                    <tr>
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">Key</th>
                        <th class="py-2 px-4 border">Description</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="permission in permissions" :key="permission.id">
                        <td class="py-2 px-4 border">{{ permission.id }}</td>
                        <td class="py-2 px-4 border">{{ permission.key }}</td>
                        <td class="py-2 px-4 border">{{ permission.description }}</td>
                        <td class="py-2 px-4 border flex gap-2">
                            <button @click="openEditModal(permission.id)" class="bg-yellow-500 px-3 py-1 text-white rounded">Edit</button>
                            <button @click="deletePermission(permission.id)" class="bg-red-500 px-3 py-1 text-white rounded">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <Modal :show="isCreateModalOpen" maxWidth="2xl" @close="isCreateModalOpen = false">
                    <Create @permissionCreated="refreshPermissions" />
                </Modal>

                <Modal :show="isEditModalOpen" maxWidth="2xl" @close="isEditModalOpen = false">
                    <Edit :data="selectedPermission" @permissionUpdated="refreshPermissions" />
                </Modal>
            </div>
        </div>
    </AdminLayout>
</template>
