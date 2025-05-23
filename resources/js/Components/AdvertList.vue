<script setup>
import { getDateFormatFromLocale, getFullPathForImage } from '@/helpers.js';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  adverts: {
    type: Object,
    default: () => ({}),
  },
  routes: {
    type: Object,
    default: () => ({}),
  },
});
const remove = (advertId) => {
  router.delete(route(props.routes.remove, { advert: advertId }));
};
</script>

<template>
  <div class="bg-white rounded shadow overflow-hidden mb-4">
    <div
      v-if="props.adverts.data.length"
      class="divide-y divide-gray-100"
    >
      <div
        v-for="advert in adverts.data"
        :key="advert.id"
        class="p-3 hover:bg-gray-100 transition duration-150 ease-in-out"
      >
        <div class="flex justify-between gap-6 min-h-36">
          <div class="w-48">
            <img
              :src="getFullPathForImage(advert.first_photo?.file)"
              :alt="advert.title"
              class="w-full h-40"
            >
          </div>
          <div class="flex-grow flex flex-col justify-between">
            <div>
              <a
                :href="route('adverts.show', advert.id)"
                class="block group"
              >
                <h3
                  class="text-2xl font-semibold text-gray-800 group-hover:text-violet-600 transition-colors duration-200"
                >
                  {{ advert.title }}
                </h3>
              </a>
              <p class="text-xl font-medium text-violet-600">
                {{ advert.price }} ₴
              </p>
            </div>
            <div class="flex items-center gap-6 text-sm mt-4">
              <span class="flex items-center gap-2 text-gray-600">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path
                    fill-rule="evenodd"
                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                    clip-rule="evenodd"
                  />
                </svg>
                6 переглядів
              </span>
              <span class="flex items-center gap-2 text-gray-600">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-5 w-5"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                </svg>
                2 в закладках
              </span>
              <p class="text-sm text-gray-500">
                Опубліковано: {{ getDateFormatFromLocale(advert.created_at) }}
              </p>
            </div>
          </div>
          <div class="flex flex-col items-end justify-between">
            <span
              class="px-4 py-2 rounded-full text-sm font-medium"
              :class="{
                'bg-green-100 text-green-800': advert.status === 'active',
                'bg-green-100 text-green-400': advert.status === 'moderation',
                'bg-yellow-100 text-yellow-800': advert.status === 'pending',
                'bg-yellow-200 text-yellow-700': advert.status === 'draft',
                'bg-red-100 text-red-800': advert.status === 'inactive',
              }"
            >
              {{ advert.status }}
            </span>
            <div
              v-if="props.routes"
              class="flex items-center gap-3 mt-auto"
            >
              <a
                v-if="props.routes.edit"
                :href="route(props.routes.edit, advert.id)"
                title="Редагувати"
                class="p-1.5 text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition duration-150 group relative"
              >
                <span
                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                >
                  Редагувати
                </span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"
                  />
                  <path
                    fill-rule="evenodd"
                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                    clip-rule="evenodd"
                  />
                </svg>
              </a>
              <a
                v-if="props.routes.show"
                :href="route(props.routes.show, advert.id)"
                title="Переглянути"
                class="p-1.5 text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition duration-150 group relative"
              >
                <span
                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                >
                  Переглянути
                </span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                  <path
                    fill-rule="evenodd"
                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                    clip-rule="evenodd"
                  />
                </svg>
              </a>
              <button
                v-if="props.routes.remove"
                title="Видалити"
                class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-150 group relative"
                @click="remove(advert.id)"
              >
                <span
                  class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap"
                >
                  Видалити
                </span>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  class="h-4 w-4"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <p
      v-else
      class="py-12 text-center text-gray-500 text-lg"
    >
      У вас немає оголошень.
    </p>
  </div>
</template>
<style scoped></style>
