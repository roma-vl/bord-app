<script setup>
import {Head, router, usePage} from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import TrashIcon from "@/Components/Icon/TrashIcon.vue";
import PencilIcon from "@/Components/Icon/PencilIcon.vue";
import Grid from "@/Components/Grid.vue";
import RefreshIcon from "@/Components/Icon/RefreshIcon.vue";
import {computed} from "vue";

const flash = computed(() => usePage().props.flash);
const adverts_moderation = computed(() => usePage().props.adverts_moderation.data);
const pagination = computed(() => usePage().props.adverts_moderation);

const headings = [
    { key: "id", value: "ID", sortable: true, disabled: true},
    { key: "title", value: "Title" },
    { key: "price", value: "Price" },
    { key: "status", value: "Status" },
    { key: "content", value: "Content" },
    { key: "created_at", value: "Created" },
    { key: "updated_at", value: "Updated" },
    { key: "actions", value: "Actions", disabled: true },
];

const routes = [
    { key: "index", value: "admin.adverts.moderation" },
    { key: "search", value: "admin.adverts.moderation.search" },
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
const activate = async (advert) => {
    router.post(route("admin.adverts.actions.moderation.active", { advert: advert }), {
        onSuccess: () => router.replace(route("admin.users.index")),
    });
    console.log(id, "id");
};

const reject = async (id) => {

    console.log(id, "id");
};
const openEditModal = async (id) => {

    console.log(id, "id");
};
</script>

<template>
    <Head title="Оголошення для модерації" />
    <AdminLayout>
        <div class="py-2 ">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 ">
                <FlashMessage :flash="flash" />
                <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px]">
                    Модерація оголошень
                    <Grid
                        :items="adverts_moderation"
                        :pagination="pagination"
                        :headings="headings"
                        :routes="routes"
                    >
                        <template #column-actions="{ row }">
                            <div class="flex gap-2">
                                <div class="flex justify-end gap-4">
                                        <a :href="route('adverts.show', row.id)" class="text-blue-600 hover:underline" >Переглянути</a>
                                        <a @click.prevent="activate(row.id)" class="text-green-600 hover:text-green-900 cursor-pointer">активувати</a>
                                        <a @click.prevent="reject(row.id)" class="text-red-600 hover:text-red-900 cursor-pointer">завернути</a>
                                        <a v-if="!row.deleted_at" @click.prevent="openEditModal(row.id)" class="text-blue-600 hover:text-blue-900 cursor-pointer">
                                            <PencilIcon />
                                        </a>
                                        <a  v-if="!row.deleted_at" @click.prevent="deleteUser(row.id)" class="text-red-600 hover:text-red-900 cursor-pointer">
                                            <TrashIcon />
                                        </a>
                                        <a v-else  @click.prevent="restoreUser(row.id)" class="text-green-600 hover:text-green-900 cursor-pointer">
                                            <RefreshIcon />
                                        </a>

                                </div>
                            </div>
                        </template>
                    </Grid>
                </div>
            </div>
        </div>
<!--        <Modal :show="isCreateModalOpen" maxWidth="2xl" @close="isCreateModalOpen = false">-->
<!--            <Create :categories="selectedCategory" @categoryCreated="refreshCategories" />-->
<!--        </Modal>-->
    </AdminLayout>
</template>
