<script setup>
import {onBeforeUnmount, onMounted, ref, watch} from 'vue';
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

watch(citySearchQuery, searchCities);

defineExpose({ selectCity, citySearchQuery });

const handleClickOutside = (event) => {
    if (!event.target.closest(".search-container")) {
        showLocationDropdown.value = false;
        selectedRegion.value = false;
    }
};
onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

const emit = defineEmits(['update:modelValue', 'select-suggestion']);

const props = defineProps({
    modelValue: [Object, String],
});
const inputValue = ref(props.modelValue.name);
watch(() => props.modelValue, (newVal) => {
    inputValue.value = newVal;

});

watch(
    () => props.modelValue,
    (newVal) => {
        if (newVal?.name) {
            citySearchQuery.value = newVal.name;
            inputValue.value = newVal.name;
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
            @focus="resetLocation"
            type="text"
            placeholder="Оберіть область"
            class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600 transition duration-200"
        />
        <div v-if="showLocationDropdown" class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10 h-[400px] overflow-y-auto">
            <ul v-if="filteredCities.length">
                <li v-for="city in filteredCities" :key="city.id" @click="$emit('select-city', selectCity(city))" class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                    {{ city.name }}
                </li>
            </ul>
            <div v-else>
                <ul v-if="regions.length && !cities.length">
                    <li v-if="loadingRegions" class="px-4 py-2 text-gray-400">Завантаження...</li>
                    <li v-for="region in regions" :key="region.id" @click="fetchCities(region.id)" class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                        {{ region.name }}
                    </li>
                </ul>

                <ul v-else>
                    <li v-if="loadingCities" class="px-4 py-2 text-gray-400">Завантаження міст...</li>
                        <li  class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
                             @click="resetLocation" >
                            <span class="flex">< Назад </span>
                        </li>
                        <li  class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
                             @click="$emit('select-city', selectCity(selectedRegion))" >
                            <span class="flex ">{{selectedRegion.name}}</span>
                            <span class="text-[12px] font-bold text-gray-700 border-b-black"> Вся область </span>
                        </li>
                    <span class=" flex px-4 text-[12px] font-bold text-gray-700"> Виберіть місто </span>
                    <li v-for="city in cities" :key="city.id" @click="$emit('select-city', selectCity(city))"
                        class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                        {{ city.name }}
                    </li>
                </ul>
            </div>


        </div>
    </div>
</template>
