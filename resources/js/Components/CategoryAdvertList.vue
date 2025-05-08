<script setup>
import {ref} from 'vue';
import Pagination from "@/Components/Pagination.vue";
import {getDateFormatFromLocale, getFullPathForImage} from "@/helpers.js";

const props = defineProps({
    adverts: Object
});

const viewMode = ref('grid');
const toggleViewMode = () => {
    viewMode.value = viewMode.value === 'grid' ? 'list' : 'grid';
};
</script>

<template>
    <div class="space-y-6" v-if="adverts?.data.length">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-semibold text-gray-800">
                    Знайдено {{ adverts.total }} оголошень
                </h2>
                <div class="flex items-center gap-2 ml-4">
                    <button
                        @click="toggleViewMode"
                        :class="[
                            'p-2 rounded-md transition-colors duration-200',
                            viewMode === 'grid'
                                ? 'bg-blue-600 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        ]"
                        title="Сітка">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="text-sm text-gray-500">
                Сторінка {{ adverts.current_page }} з {{ adverts.last_page }}
            </div>
        </div>

        <!-- Grid View -->
        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="advert in adverts.data" :key="advert.id"
                 class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                <img :src="getFullPathForImage(advert.first_photo?.file)" :alt="advert.title" class="w-full h-48 object-cover"/>
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
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <span>{{ advert.location }}</span>
                        <span>{{ getDateFormatFromLocale(advert.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div v-else class="space-y-4">
            <div v-for="advert in adverts.data" :key="advert.id"
                 class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
                <div class="flex">
                    <img :src="getFullPathForImage(advert.first_photo?.file)" :alt="advert.title" class="w-48 h-48 object-cover"/>
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
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>{{ advert.location }}</span>
                            <span>{{ getDateFormatFromLocale(advert.created_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Pagination :pagination="adverts"/>
    </div>
</template>
