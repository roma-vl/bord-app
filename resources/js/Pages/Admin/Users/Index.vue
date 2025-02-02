<script setup>
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Grid from "@/Components/Grid.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import AvatarIcon from "@/Components/Icon/AvatarIcon.vue";
import { computed, ref, watch } from "vue";
import RefreshIcon from "@/Components/Icon/RefreshIcon.vue";
import Modal from "@/Components/Modal.vue";
import Create from "@/Pages/Admin/Users/Create.vue";

const flash = usePage().props.flash;
const users = usePage().props.users.data;
const pagination = computed(() => usePage().props.users.meta);


const headings = [
    { key: "id", value: "ID", sortable: true, disabled: true},
    { key: "name", value: "Name", sortable: true, highlight: true },
    { key: "email", value: "Email", sortable: true, highlight: true },
    { key: "status", value: "Status" },
    { key: "role", value: "Role" },
    { key: "tags", value: "Tags" },
    { key: "created_at", value: "Created" },
    // { key: "updated_at", value: "Updated" },
    { key: "actions", value: "Actions", disabled: true },
];

const routes = [
    { key: "index", value: "admin.users.index" },
    { key: "search", value: "admin.users.search" },
];

const deleteUser = (id) => {
    if (confirm("Ви впевнені, що хочете видалити цього користувача?")) {
        router.delete(route("admin.users.destroy", id), {
            preserveScroll: true,
            onSuccess: () => router.replace(route("admin.users.index")),
        });
    }
};

const restoreUser = (id) => {
    router.put(route("admin.users.restore", id), {}, {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.users.index")),
    });
};
// Управління відкриттям модалки
const isModalOpen = ref(false);

const openModal = () => {
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
};

const refreshUsers = () => {
    router.get(route("admin.users.index"), {
        preserveScroll: true,
        onSuccess: (page) => {
            users.value = page.props.users.data;
            closeModal();
        },
    });
};

</script>

<template>
    <Head title="Users" />
    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage v-if="flash" :flash="flash" />

                <div class="mb-2 flex justify-end">
                    <button @click="openModal" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500">
                        + New User
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
                                    <img :src="row.avatar_url" :alt="row.name" />
                                </div>
                                <div v-else>
                                    <AvatarIcon />
                                </div>
                                <div v-if="row.status">
                                    <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-green-400 ring ring-white"></span>
                                </div>
                                <div v-else>
                                    <span class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-red-400 ring ring-white"></span>
                                </div>
                            </div>
                            <div class="text-sm flex justify-center items-center">
                                <div class="font-medium text-gray-700" v-html="row.name"></div>
                            </div>
                        </div>
                    </template>
                    <template #column-email="{ row }">
                        <div class="text-sm text-gray-600" v-html="row.email"></div>
                    </template>
                    <template #column-status="{ row }">
                        <div v-if="row.deleted_at">
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-semibold text-red-600">
                              <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span> Deleted
                            </span>
                        </div>
                        <div v-else-if="row.status">
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
                              <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span> Active
                            </span>
                        </div>
                        <div v-else>
                            <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-semibold text-yellow-600">
                              <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span> Inactive
                            </span>
                        </div>
                    </template>
                    <template #column-role="{ row }">
                        Product Designer
                    </template>
                    <template #column-tags="{ row }">
                        <div class="flex gap-2">
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600">
                              Design
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-600">
                              Product
                            </span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-1 text-xs font-semibold text-violet-600">
                              Develop
                            </span>
                        </div>
                    </template>
                    <template #column-actions="{ row }">
                        <div class="flex gap-2">
                            <div class="flex justify-end gap-4">
                                <a v-if="!row.deleted_at" @click.prevent="deleteUser(row.id)" class="text-red-600 hover:text-red-900 cursor-pointer">
                                    <TrashIcon />
                                </a>
                                <a v-else @click.prevent="restoreUser(row.id)" class="text-green-600 hover:text-green-900 cursor-pointer">
                                    <RefreshIcon />
                                </a>
                                <a v-if="!row.deleted_at" href="#">
                                    <PencilIcon />
                                </a>
                            </div>
                        </div>
                    </template>
                </Grid>

                <Modal
                    maxWidth="2xl"
                    @close="closeModal"
                    :show="isModalOpen" >
                    <template #default>
                        <Create @userCreated="refreshUsers" />
                    </template>
                </Modal>
            </div>
        </div>
    </AdminLayout>
</template>
