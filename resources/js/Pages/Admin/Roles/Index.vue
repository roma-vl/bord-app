<script setup>
import { Head, router, usePage } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import Create from "@/Pages/Admin/Roles/Create.vue";
import Edit from "@/Pages/Admin/Roles/Edit.vue";
import {useAcl} from "@/composables/useAcl.js";

const { can } = useAcl();

const flash = usePage().props.flash;
const roles = usePage().props.roles;

const isCreateModalOpen = ref(false);
const isEditModalOpen = ref(false);
const selectedRole = ref(null);

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
        console.log('dd')
    }
};


const openEditModal = async (id) => {
    console.log(id, 'id')
    try {
        const response = await axios.get(route("admin.roles.edit", id));
        if (response.status === 200) {
            selectedRole.value = response.data;
            isEditModalOpen.value = true;
        }

    } catch (error) {1
        showForbidden.value = true;
        errorForbidden.value = error.response;
        console.log('dd')
    }
};

const deleteRole = (id) => {
    if (confirm("Are you sure you want to delete this role?")) {
        router.delete(route("admin.roles.destroy", id), {
            preserveScroll: true,
            onSuccess: () => router.replace(route("admin.roles.index")),
        });
    }
};

const refreshRoles = () => {
    router.get(route("admin.roles.index"), {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.roles.index")),
    });
};
//v-if="can('user.edit')"

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

                <table class="min-w-full bg-white border border-gray-300">
                    <thead>
                    <tr>
                        <th class="py-2 px-4 border">ID</th>
                        <th class="py-2 px-4 border">Name</th>
                        <th class="py-2 px-4 border">Enabled</th>
                        <th class="py-2 px-4 border">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="role in roles" :key="role.id">
                        <td class="py-2 px-4 border">{{ role.id }}</td>
                        <td class="py-2 px-4 border">{{ role.name }}</td>
                        <td class="py-2 px-4 border">{{ role.is_enabled ? '✅' : '❌' }}</td>
                        <td class="py-2 px-4 border flex gap-2">
                            <button  @click="openEditModal(role.id)" class="bg-yellow-500 px-3 py-1 text-white rounded">Edit</button>
                            <button @click="deleteRole(role.id)" class="bg-red-500 px-3 py-1 text-white rounded">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <Modal :show="isCreateModalOpen" maxWidth="2xl" @close="isCreateModalOpen = false">
                    <Create :data="selectedRole" @roleCreated="refreshRoles" />
                </Modal>

                <Modal :show="isEditModalOpen" maxWidth="2xl" @close="isEditModalOpen = false">
                    <Edit :data="selectedRole" @roleUpdated="refreshRoles" />
                </Modal>
            </div>
        </div>
    </AdminLayout>
</template>
