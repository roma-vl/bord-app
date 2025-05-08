<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {getDateFormatFromLocale, getFullPathForImage} from "@/helpers.js";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    categories: Object,
    locations: Object,
    childCategories: Object,
    adverts: Object
});
const viewMode = ref('grid');
const generateChildCategoriesLink = (slug) => {
    const path = props.categories.map(c => c.slug).join("/");
    return `/adverts/${path}/${slug}`;
};

const toggleViewMode = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};
</script>

<template>
    <Head title="Категорії оголошень 2 " />

    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white">

                        <Breadcrumbs :categories="categories" :locations="locations" class="mb-6"/>

                        <div v-if="childCategories?.length" class="space-y-2 mb-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                                Підкатегорії
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <Link v-for="category in childCategories" :key="category.id"
                                    :href="generateChildCategoriesLink(category.slug)"
                                    class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    <span class="text-blue-600 hover:text-blue-800">
                                        {{ category.name }}
                                    </span>
                                </Link>
                            </div>
                        </div>

                        <div v-if="props.adverts.data.length" class="space-y-6">
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center gap-4">
                                    <h2 class="text-xl font-semibold text-gray-800">
                                        Знайдено {{ props.adverts.total }} оголошень
                                    </h2>
                                    <div class="flex items-center gap-2 ml-4">
                                        <button
                                            @click="toggleViewMode"
                                            :class="[
                                                'p-2 rounded-md transition-colors duration-200',
                                                viewMode === 'grid'
                                                    ? 'bg-blue-600 text-white'
                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            ]" title="Сітка">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                        </button>
                                        <button
                                            @click="toggleViewMode"
                                            :class="[
                                                'p-2 rounded-md transition-colors duration-200',
                                                viewMode === 'list'
                                                    ? 'bg-blue-600 text-white'
                                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                            ]"
                                            title="Список">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    Сторінка {{ props?.adverts.current_page }} з {{ props?.adverts.last_page }}
                                </div>
                            </div>

                            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div v-for="advert in props.adverts.data" :key="advert.id"
                                     class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    <img :src="getFullPathForImage(advert.first_photo?.file)" :alt="advert.title" class="w-full h-48 object-cover" />

                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-medium text-gray-900 hover:text-blue-600">
                                                {{ advert.title }}
                                            </h3>
                                            <span class="text-lg font-bold text-green-600">
                                                {{ advert.price }} {{ advert.currency }}
                                            </span>
                                        </div>

                                        <p class="text-gray-600 text-sm mb-3">{{ advert.description }}</p>

                                        <div class="grid grid-cols-3 gap-2 mb-3">
                                            <div class="text-sm text-gray-500">
                                                <span class="font-medium">Рік:</span>
                                            </div>
                                        </div>

                                        <div class="flex justify-between items-center text-sm text-gray-500">
                                            <span>{{ advert.location }}</span>
                                            <span>{{ getDateFormatFromLocale(advert.created_at) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="space-y-4">
                                <div v-for="advert in props.adverts?.data" :key="advert.id"
                                     class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    <div class="flex">
                                        <img :src="getFullPathForImage(advert.first_photo?.file)" :alt="advert.title" class="w-48 h-48 object-cover" />

                                        <div class="flex-1 p-4">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="text-xl font-medium text-gray-900 hover:text-blue-600">
                                                    {{ advert.title }}
                                                </h3>
                                                <span class="text-xl font-bold text-green-600">
                                                    {{ advert.price }} {{ advert.currency }}
                                                </span>
                                            </div>

                                            <p class="text-gray-600 mb-4">{{ advert.description }}</p>

                                            <div class="grid grid-cols-3 gap-4 mb-4">
                                                <div class="text-sm text-gray-500">
                                                    <span class="font-medium">Рік:</span>
                                                </div>
                                            </div>

                                            <div class="flex justify-between items-center text-sm text-gray-500">
                                                <span>{{ advert.location }}</span>
                                                <span>{{ getDateFormatFromLocale(advert.created_at) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <Pagination :pagination="props.adverts"  />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
