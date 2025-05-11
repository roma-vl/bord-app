
<script setup>
import {computed, onMounted, ref, watch} from 'vue'
import {Head, router, usePage} from '@inertiajs/vue3'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CategoryAdvert from "@/Components/Advert/Category/CategoryAdvert.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import ChildCategories from "@/Components/Advert/Category/ChildCategories.vue";
import CategoryDropdown from "@/Components/CategoryDropdown.vue";
const adverts = usePage().props.adverts;
const queryFilter = ref(usePage().props.query || {});
const props = defineProps({
    categories: Object,
    locations: Object,
    childCategories: Object,
    regionsCounts: Object,
    categoriesCounts:Object,
    attributes: Object,
    categoryFilters: Object,
});

const selectedCategoryId = computed(() => queryFilter.value.category_id)

const selectedCategoryAttributes = computed(() => {
    const selected = findCategoryById(props.categoryFilters, Number(selectedCategoryId.value));
    return selected?.attributes ?? [];
});

console.log(props.categoryFilters, 'categoryTree')
function submit() {
    const filteredQuery = { ...queryFilter.value };

    Object.keys(filteredQuery).forEach((key) => {
        const val = filteredQuery[key];
        if (val === null || val === '' || typeof val === 'undefined') {
            delete filteredQuery[key];
        }
    });
    const currentPath = window.location.pathname;
    router.get(currentPath, filteredQuery);
}

function findCategoryById(categories, id) {
    for (const category of categories) {
        if (category.id === id) return category;
        if (category.children?.length) {
            const found = findCategoryById(category.children, id);
            if (found) return found;
        }
    }
    return null;
}
const handleCategoryChange = (category) => {
    const fullPathArray = getCategoryPathById(props.categoryFilters, category.id);
    const fullPath = fullPathArray.map(c => c.slug).join('/');
    const newPath = `/${fullPath}`;

    if (window.location.pathname === newPath) return; // ⚠️ Запобігає циклу

    router.visit(newPath);
}



watch(() => queryFilter.value.category_id, (newVal) => {
    if (!newVal) return;
    const selected = findCategoryById(props.categoryFilters, Number(newVal))
    console.log(selected,'selected')
    if (selected) {
        handleCategoryChange(selected)
    }
})

function getCategoryPathById(categories, id, path = []) {
    for (const category of categories) {
        const newPath = [...path, category];
        if (category.id === id) {
            return newPath;
        }
        if (category.children?.length) {
            const result = getCategoryPathById(category.children, id, newPath);
            if (result) {
                return result;
            }
        }
    }
    return null;
}

onMounted(() => {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    if (!pathParts.length) return;

    const categorySlug = pathParts[pathParts.length - 1]; // або зробити весь шлях
    const category = findCategoryBySlug(props.categoryFilters, categorySlug);

    if (category) {
        queryFilter.value.category_id = category.id;
    }
});

function findCategoryBySlug(categories, slug) {
    for (const category of categories) {
        if (category.slug === slug) return category;
        if (category.children?.length) {
            const found = findCategoryBySlug(category.children, slug);
            if (found) return found;
        }
    }
    return null;
}

</script>

<template>
    <Head title="Категорії оголошень " />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white">
                        <form @submit.prevent="submit" class="mb-6">
                            <div class="flex gap-4 items-center mb-4">
                                <input v-model="queryFilter.query" placeholder="Що шукаєш?"
                                    class="flex-1 px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                    Пошук
                                </button>
                            </div>
                        </form>

                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Фільтри</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4">
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Обрати категорію
                                </label>
                                <CategoryDropdown
                                    v-model="queryFilter.category_id"
                                    :categoryFilters="props.categoryFilters"
                                />
                            </div>

                            <!-- Атрибути -->
                            <div v-for="(attribute, index) in selectedCategoryAttributes" :key="index"
                                 class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ attribute.name }}
                                </label>

                                <select v-if="attribute.variants && attribute.variants.length" v-model="queryFilter[attribute.id]"
                                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Обрати</option>
                                    <option v-for="(variant, i) in attribute.variants" :key="i" :value="variant">
                                        {{ variant }}
                                    </option>
                                </select>

                                <div v-else-if="attribute.type === 'integer'" class="flex gap-2">
                                    <input type="number" :placeholder="`Від`" v-model="queryFilter[`${attribute.id}_from`]"
                                           class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                    <input type="number" :placeholder="`До`" v-model="queryFilter[`${attribute.id}_to`]"
                                           class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>

                                <input v-else-if="attribute.type === 'string'" type="text" v-model="queryFilter[attribute.id]"
                                       class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>

                        </div>


                        <Breadcrumbs class="mb-6" :categories="props.categories" :locations="props.locations"/>

                        <ChildCategories
                            :childCategories="props.childCategories"
                            :categoriesCounts="props.categoriesCounts"
                            :categories="props.categories"/>

                        Пошук за запитом : {{query}}
                        <div v-if="adverts.data.length">
                            <CategoryAdvert :adverts="adverts"/>
                        </div>
                        <div v-else> Нічого не знайдено. </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
