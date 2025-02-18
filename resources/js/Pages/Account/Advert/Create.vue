<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import {computed, ref, watch} from "vue";
import axios from "axios";
import FileUpload from "@/Components/FileUpload.vue";

const user = usePage().props.auth.user;

const props = defineProps({
    categories: Array,
    regions: Array,
});
// const categories = usePage().props.categories;
console.log(props.categories, "categories");

const areas = ref([]);
const villages = ref([]);
const attributes = ref([]);
const uploadedImages = ref([]);
const dragging = ref(false);

const form = useForm({
    category_id: '',
    country_id: '',
    region_id: '',
    area_id: '',
    village_id: '',
    title: '',
    price: '',
    address: '',
    content: '',
});

watch(() => form.region_id, async (newRegionId) => {
    form.area_id = '';
    form.village_id = '';
    areas.value = [];

    if (!newRegionId) return;

    try {
        const response = await axios.get(route("account.adverts.areas", { regionId: newRegionId }));
        areas.value = response.data;
    } catch (error) {
        console.error("Помилка завантаження районів", error);
    }
});

watch(() => form.area_id, async (newVillagesId) => {
    form.village_id = '';
    villages.value = [];

    if (!newVillagesId) return;

    try {
        const response = await axios.get(route("account.adverts.villages", { areaId: newVillagesId }));
        villages.value = response.data;
    } catch (error) {
        console.error("Помилка завантаження сіл", error);
    }
});

watch(() => form.category_id, async (newCategoryId) => {
    // form.village_id = '';
    attributes.value = [];

    if (!newCategoryId) return;

    try {
        const response = await axios.get(route("account.adverts.attributes", { categoryId: newCategoryId }));
        attributes.value = response.data;
        console.log(attributes.value, "attributes");
    } catch (error) {
        console.error("Помилка завантаження сіл", error);
    }
});


const submit = () => {
    form.post(route("account.adverts.store"), {
        onSuccess: () => {
            console.log("Оголошення створено");
            form.reset();
        },
    });
};

const getCategoryOptions = (categories, prefix = "") => {
    let options = [];
    categories.forEach((category) => {
        options.push({ id: category.id, name: prefix + category.name });

        if (category.children_recursive && category.children_recursive.length) {
            options = options.concat(getCategoryOptions(category.children_recursive, prefix + "- "));
        }
    });
    return options;
};
const formattedCategories = computed(() => getCategoryOptions(props.categories));
</script>

<template>
    <Head title="Оголошення" />
    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow sm:rounded-lg p-6">
                    <div class="px-4">
                        <form @submit.prevent="submit">

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Назва</label>
                                <input v-model="form.title" type="text" required class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Ціна</label>
                                <input v-model="form.price" type="number" required class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Категорія</label>
                                <select v-model="form.category_id" class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="category in formattedCategories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Фото</label>
                                <FileUpload uploadUrl="https://example.com/upload" />
                            </div>




                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Область</label>
                                <select v-model="form.region_id" :disabled="props.regions.length === 0" class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="region in props.regions" :key="region.id" :value="region.id">
                                        {{ region.region }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Регіон</label>
                                <select v-model="form.area_id" :disabled="areas.length === 0" class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="area in areas" :key="area.id" :value="area.id">
                                        {{ area.area }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Село</label>
                                <select v-model="form.village_id" :disabled="villages.length === 0" class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="village in villages" :key="village.id" :value="village.id">
                                        {{ village.village }}
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Адреса</label>
                                <input v-model="form.address" type="text" required class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Опис</label>
                                <textarea v-model="form.content" class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Колір</label>
                                    <select class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option>Червоний</option>
                                        <option>Синій</option>
                                        <option>Зелений</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Розмір</label>
                                    <select class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option>Малий</option>
                                        <option>Середній</option>
                                        <option>Великий</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="mt-6 bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Створити</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
