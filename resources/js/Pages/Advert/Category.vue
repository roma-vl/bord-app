<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import {getDateFormatFromLocale} from "@/helpers.js";

const props = defineProps({
    categories: Object,
    locations: Object,
    childCategories: Object
});

const viewMode = ref('grid');


const testData = {
    categories: [
        { id: 1, name: 'Транспорт', slug: 'transport' },
        { id: 2, name: 'Автомобілі', slug: 'cars' }
    ],
    locations: [
        { id: 1, name: 'Київ', slug: 'kyiv' },
        { id: 2, name: 'Львів', slug: 'lviv' }
    ],
    childCategories: [
        { id: 3, name: 'Легкові автомобілі', slug: 'cars' },
        { id: 4, name: 'Мотоцикли', slug: 'motorcycles' }
    ],
    adverts: {
        data: [
            {
                id: 1,
                title: 'Volkswagen Golf 7 2.0 TDI',
                price: 15000,
                currency: 'USD',
                location: 'Київ',
                created_at: '2024-03-15',
                image: 'https://picsum.photos/400/300',
                description: 'Відмінний стан, повна комплектація, один власник',
                attributes: {
                    year: '2019',
                    mileage: '75000',
                    fuel: 'Дизель'
                }
            },
            {
                id: 2,
                title: 'Toyota Camry 2.5 Hybrid',
                price: 25000,
                currency: 'USD',
                location: 'Львів',
                created_at: '2024-03-14',
                image: 'https://picsum.photos/400/300',
                description: 'Гібрид, максимальна комплектація, без пробігу по Україні',
                attributes: {
                    year: '2020',
                    mileage: '45000',
                    fuel: 'Гібрид'
                }
            },
            {
                id: 3,
                title: 'BMW X5 3.0d',
                price: 35000,
                currency: 'USD',
                location: 'Одеса',
                created_at: '2024-03-13',
                image: 'https://picsum.photos/400/300',
                description: 'M-пакет, панорама, максимальна комплектація',
                attributes: {
                    year: '2021',
                    mileage: '35000',
                    fuel: 'Дизель'
                }
            }
        ],
        meta: {
            total: 50,
            per_page: 10,
            current_page: 1,
            last_page: 5
        }
    }
};

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

                        <div v-if="testData?.adverts?.data?.length" class="space-y-6">
                            <div class="flex justify-between items-center mb-6">
                                <div class="flex items-center gap-4">
                                    <h2 class="text-xl font-semibold text-gray-800">
                                        Знайдено {{ testData?.adverts?.meta?.total }} оголошень
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
                                    Сторінка {{ testData?.adverts?.meta?.current_page }} з {{ testData?.adverts?.meta?.last_page }}
                                </div>
                            </div>

                            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div v-for="advert in testData?.adverts?.data" :key="advert.id"
                                     class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    <img :src="advert.image" :alt="advert.title" class="w-full h-48 object-cover" />

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
                                                <span class="font-medium">Рік:</span> {{ advert.attributes.year }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <span class="font-medium">Пробіг:</span> {{ advert.attributes.mileage }} км
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <span class="font-medium">Паливо:</span> {{ advert.attributes.fuel }}
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
                                <div v-for="advert in testData?.adverts?.data" :key="advert.id"
                                     class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                                    <div class="flex">
                                        <img :src="advert.image" :alt="advert.title" class="w-48 h-48 object-cover" />

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
                                                    <span class="font-medium">Рік:</span> {{ advert.attributes.year }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <span class="font-medium">Пробіг:</span> {{ advert.attributes.mileage }} км
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <span class="font-medium">Паливо:</span> {{ advert.attributes.fuel }}
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

                            <div v-if="testData?.adverts.meta.last_page > 1" class="flex justify-center mt-8">
                                <nav class="flex space-x-2">
                                    <Link v-for="page in testData?.adverts?.meta?.last_page"
                                          :key="page"
                                          :href="`?page=${page}`"
                                          :class="[
                                              'px-4 py-2 rounded-md',
                                              page === testData?.adverts?.meta?.current_page
                                                  ? 'bg-blue-600 text-white'
                                                  : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                          ]"
                                    > {{ page }}
                                    </Link>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
