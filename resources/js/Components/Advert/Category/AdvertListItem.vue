<script setup>
import { getDateFormatFromLocale, getFullPathForImage } from "@/helpers.js";

const props = defineProps({
    advert: Object,
    viewMode: String
});
</script>

<template>
    <div class=" flex bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200">
        <img class="object-cover w-48 h-48"
            :src="getFullPathForImage(advert.first_photo?.file)"
            :alt="advert.title"/>
        <div class="p-4" :class="viewMode === 'list' ? 'flex-1' : ''">
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
</template>
