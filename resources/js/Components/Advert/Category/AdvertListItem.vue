<script setup>
import { getDateFormatFromLocale, getFullPathForImage, truncateContent } from '@/helpers.js';
import HeartIcon from '@/Components/Icon/HeartIcon.vue';
import HeartSolidIcon from '@/Components/Icon/HeartSolidIcon.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  advert: {
    type: Object,
    default: () => ({}),
  },
  viewMode: {
    type: String,
    default: '',
  },
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
    class="flex bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-200"
  >
    <img
      class="object-cover w-48 h-48"
      :src="getFullPathForImage(advert.first_photo?.file)"
      :alt="advert.title"
    >
    <div class="p-4 flex-1">
      <div class="flex justify-between items-start m1-2">
        <h3 class="text-lg font-medium text-gray-900 hover:text-blue-600">
          {{ advert.title }}
        </h3>
        <span class="text-lg font-bold text-green-600">
          {{ advert.price }} {{ advert.currency }}грн.
        </span>
      </div>
      <div class="flex justify-between items-center text-sm text-gray-600 mb-3">
        <span>{{ advert.region.name }} - {{ getDateFormatFromLocale(advert.created_at) }}</span>
      </div>
      <p class="text-gray-600 text-sm">
        {{ truncateContent(advert.content, 100) }}
      </p>
      <button
        class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition"
        @click="toggleLike(advert)"
      >
        <HeartIcon
          v-if="!advert.is_favorited"
          class="w-6 h-6"
        />
        <HeartSolidIcon
          v-else
          class="w-6 h-6 text-red-500"
        />
      </button>
    </div>
  </div>
</template>
