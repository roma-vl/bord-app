<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";
import axios from "axios";
import InputError from "@/Components/InputError.vue";
import AdvertFileUpload from "@/Pages/Account/Advert/Partials/AdvertFileUpload.vue";

const props = defineProps({
    categories: Array,
    regions: Array,
    formats: Array
});
const showLocationDropdown = ref(false);
const loadingCities = ref(false);
const citySearchQuery = ref("");
const filteredCities = ref([]);
const form = useForm({
    category_id: '',
    region_id: '',
    name: '',
    limit: '',
    url: '',
    format: '',
    file: ''
});

const submit = () => {
    form.post(route("account.banners.store"), {
        forceFormData: true,
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
const selectCity = (city) => {
    citySearchQuery.value = city.name;
    form.region_id = city.id;
    showLocationDropdown.value = false;
}
const searchCities = async () => {
    if (citySearchQuery.value.length < 2) {
        filteredCities.value = [];
        return;
    }

    loadingCities.value = true;
    try {
        const response = await axios.get(route("adverts.regions.search", { region: citySearchQuery.value }));
        filteredCities.value = response.data.regions;
        if (response.data.regions.length) {
            showLocationDropdown.value = true;
        }
    } finally {
        loadingCities.value = false;
    }
};

const handleClickOutside = (event) => {
    if (!event.target.closest(".search-container")) {
        showLocationDropdown.value = false;
    }
};

const onFileChange = (event) => {
    form.file = event.target.files[0];
};

watch(citySearchQuery, searchCities);

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
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
                                <input v-model="form.name" type="text"  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.name"/>

                            <div class="mb-4 ">
                                <label class="block text-sm font-medium mb-2">Views</label>
                                <input v-model="form.limit" type="number" required class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.limit"/>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Назва</label>
                                <input v-model="form.url" type="text"  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <InputError class="mt-2" :message="form.errors.url"/>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Формат</label>
                                <select v-model="form.format" class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="format in props.formats" :key="format" :value="format">
                                        {{ format }}
                                    </option>
                                </select>
                            </div>
                            <InputError class="mt-2" :message="form.errors.category_id"/>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Категорія</label>
                                <select v-model="form.category_id" class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option v-for="category in formattedCategories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <InputError class="mt-2" :message="form.errors.category_id"/>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Місцезнаходження</label>
                                <div class="relative search-container">
                                    <input v-model="citySearchQuery" type="text"
                                           class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600 transition duration-200"
                                           placeholder="Почніть вводити адресу" />

                                    <div v-if="showLocationDropdown" class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10 h-[400px] overflow-y-auto">
                                        <ul v-if="filteredCities.length">
                                            <li v-for="city in filteredCities" :key="city.id" @click="selectCity(city)"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                {{ city.name }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-2">Фото</label>
                                <input type="file" @change="onFileChange" accept="image/*" class="w-full border-gray-300 p-2 rounded-md shadow-sm" />
                                <InputError class="mt-2" :message="form.errors.image"/>

                            </div>

                            <button type="submit" class="mt-6 bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Створити</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
