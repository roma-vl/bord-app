<script setup>
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useLocationSearch } from '@/composables/useLocationSearch.js';

const {
  citySearchQuery,
  showLocationDropdown,
  selectedRegion,
  loadingRegions,
  loadingCities,
  filteredCities,
  regions,
  cities,
  resetLocation,
  fetchCities,
  searchCities,
  selectCity,
} = useLocationSearch();
const props = defineProps({
  modelValue: {
    type: [Object, String],
    default: () => '',
  },
});

watch(citySearchQuery, searchCities);

defineExpose({ selectCity, citySearchQuery });

const handleClickOutside = (event) => {
  if (!event.target.closest('.search-container')) {
    showLocationDropdown.value = false;
    selectedRegion.value = false;
  }
};
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

const emit = defineEmits(['update:modelValue', 'select-city']);

const inputValue = ref(props.modelValue.name);

watch(
  () => props.modelValue,
  (newVal) => {
    if (newVal?.name) {
      citySearchQuery.value = newVal.name;
      inputValue.value = newVal.slug;
    }
  },
  { immediate: true }
);

watch(inputValue, (newVal) => {
  emit('update:modelValue', newVal);
});
</script>

<template>
  <div class="relative w-[250px]">
    <input
      v-model="citySearchQuery"
      type="text"
      placeholder="Оберіть область"
      class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600 transition duration-200"
      @focus="resetLocation"
    >
    <div
      v-if="showLocationDropdown"
      class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10 h-[400px] overflow-y-auto"
    >
      <ul v-if="filteredCities.length">
        <li
          v-for="city in filteredCities"
          :key="city.id"
          class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
          @click="$emit('select-city', selectCity(city))"
        >
          {{ city.name }}
        </li>
      </ul>
      <div v-else>
        <ul v-if="regions.length && !cities.length">
          <li
            v-if="loadingRegions"
            class="px-4 py-2 text-gray-400"
          >
            Завантаження...
          </li>
          <li
            v-for="region in regions"
            :key="region.id"
            class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
            @click="fetchCities(region.id)"
          >
            {{ region.name }}
          </li>
        </ul>

        <ul v-else>
          <li
            v-if="loadingCities"
            class="px-4 py-2 text-gray-400"
          >
            Завантаження міст...
          </li>
          <li
            class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
            @click="resetLocation"
          >
            <span class="flex">&lt; Назад </span>
          </li>
          <li
            class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
            @click="$emit('select-city', selectCity(selectedRegion))"
          >
            <span class="flex">{{ selectedRegion.name }}</span>
            <span class="text-[12px] font-bold text-gray-700 border-b-black"> Вся область </span>
          </li>
          <span class="flex px-4 text-[12px] font-bold text-gray-700"> Виберіть місто </span>
          <li
            v-for="city in cities"
            :key="city.id"
            class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
            @click="$emit('select-city', selectCity(city))"
          >
            {{ city.name }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
