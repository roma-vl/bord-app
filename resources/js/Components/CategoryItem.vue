<script setup>
import {defineProps, ref} from "vue";
import Edit from "@/Pages/Admin/Advert/Edit.vue";
import Modal from "@/Components/Modal.vue";
import {router} from "@inertiajs/vue3";

const isEditModalOpen = ref(false);
const selectedCategory = ref(null);

const props = defineProps({
    category: Object,
    prefix: {
        type: String,
        default: "",
    },
});
const category = props.category;


const openEditModal = async (categoryId) => {
    const { data } = await axios.get(route("admin.adverts.category.edit", categoryId));
    selectedCategory.value = data.category;
    isEditModalOpen.value = true;
};

const refreshCategories = () => {
    router.get(route("admin.adverts.category.index"), {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.adverts.category.index")),
    });
};

const deleteCategory = (id) => {
    if (confirm("Are you sure you want to delete this Category?")) {
        router.delete(route("admin.adverts.category.destroy", id), {
            preserveScroll: true,
            onSuccess: () => router.replace(route("admin.adverts.category.index")),
        });
    }
};
</script>

<template>
    <li>
        <div class="flex justify-between items-center bg-white hover:bg-gray-100 p-3 mb-2 rounded cursor-pointer shadow">
            <span class="font-semibold">{{ props.prefix }}{{ props.category.name }}</span>
            <div class="flex items-right">
                <button @click.stop="openEditModal(category.id)" class="text-blue-500 pr-2">
                    Редагувати
                </button>
                <button @click.stop="deleteCategory(category.id)" class="text-red-500 hover:underline">
                    Видалити
                </button>
            </div>
        </div>

        <ul v-if="category.children && category.children.length" class="ml-6">
            <CategoryItem v-for="child in category.children" :key="child.id" :category="child" :prefix="prefix + ' '" />
        </ul>
    </li>

    <Modal :show="isEditModalOpen" maxWidth="2xl" @close="isEditModalOpen = false">
        <Edit :category="selectedCategory" @categoryUpdated="refreshCategories" />
    </Modal>
</template>
