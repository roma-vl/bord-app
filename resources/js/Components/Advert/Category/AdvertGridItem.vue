<script setup>
import { getDateFormatFromLocale, getFullPathForImage, truncateContent } from '@/helpers.js';
import { router } from '@inertiajs/vue3';
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';

const props = defineProps({
  advert: Object,
});

const toggleLike = (advert) => {
  if (advert.is_favorited === true) {
    router.delete(route('account.favorites.remove', { advert: advert.id }));
  } else {
    router.post(route('account.favorites.add', { advert: advert.id }));
  }
  advert.is_favorited = !advert.is_favorited;
};
</script>

<template>
  <div
    class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200"
  >
    <img
      :src="getFullPathForImage(advert.first_photo?.file)"
      :alt="advert.title"
      class="object-cover w-full h-48"
    />
    <div class="p-4">
      <div class="flex justify-between items-start mb-1">
        <h3 class="text-lg font-medium text-gray-900 hover:text-blue-600">
          {{ advert.title }}
        </h3>
        <span class="text-lg font-bold text-green-600">
          {{ advert.price }} {{ advert.currency }} грн.
        </span>
      </div>
      <div class="flex justify-between items-center text-sm text-gray-500 pb-2">
        <span>{{ advert.region.name }} - {{ getDateFormatFromLocale(advert.created_at) }}</span>
      </div>
      <p class="text-gray-600 text-sm mb-3">{{ truncateContent(advert.content, 100) }}</p>
      <button
        @click="toggleLike(advert)"
        class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition"
      >
        <HeartIcon v-if="!advert.is_favorited" class="w-6 h-6" />
        <HeartSolidIcon v-else class="w-6 h-6 text-red-500" />
      </button>
    </div>
  </div>
</template>
