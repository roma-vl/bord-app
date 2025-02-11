<script setup>
import {defineProps, ref} from "vue";
import Edit from "@/Pages/Admin/Advert/Edit.vue";
import Modal from "@/Components/Modal.vue";
import {Link, router} from "@inertiajs/vue3";

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

const moveUp = (id) => {
    router.post(route("admin.adverts.category.moveUp", id), {}, {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.adverts.category.index")),
    });
};

const moveDown = (id) => {
    router.post(route("admin.adverts.category.moveDown", id), {}, {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.adverts.category.index")),
    });
};

const moveToTop = (id) => {
    router.post(route("admin.adverts.category.moveToTop", id), {}, {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.adverts.category.index")),
    });
};

const moveToBottom = (id) => {
    router.post(route("admin.adverts.category.moveToBottom", id), {}, {
        preserveScroll: true,
        onSuccess: () => router.replace(route("admin.adverts.category.index")),
    });
};
</script>

<template>
    <li>
        <div class="flex justify-between items-center bg-white hover:bg-gray-100 p-3 mb-2 rounded cursor-pointer shadow border-l-[3px] border-gray-300">
            <span class="font-semibold">{{ props.prefix }}{{ props.category.name }}</span>
            <div class="flex items-right">
                <button @click.stop="moveToTop(category.id)" class="text-purple-500 pr-2">⏫</button>
                <button @click.stop="moveUp(category.id)" class="text-green-500 pr-2">🔼</button>
                <button @click.stop="moveDown(category.id)" class="text-orange-500 pr-2">🔽</button>
                <button @click.stop="moveToBottom(category.id)" class="text-purple-500 pr-2">⏬</button>
                <Link :href="route('admin.adverts.category.show', category.id)" class="text-blue-500 pr-2"> Show </Link>
                <button @click.stop="openEditModal(category.id)" class="text-blue-500 pr-2">Редагувати</button>
                <button @click.stop="deleteCategory(category.id)" class="text-red-500 hover:underline">Видалити</button>
            </div>
        </div>

        <div v-if="category.children_recursive?.length" class="ml-6">
            <CategoryItem v-for="child in category.children_recursive" :key="child.id" :category="child" />
        </div>
    </li>

    <Modal :show="isEditModalOpen" maxWidth="2xl" @close="isEditModalOpen = false">
        <Edit :category="selectedCategory" @categoryUpdated="refreshCategories" />
    </Modal>
</template>
