<script setup>
import {onBeforeUnmount, onMounted, ref, watch} from 'vue';
import { useSearch } from '@/composables/useSearch.js';

const {
    searchQuery,
    showSuggestions,
    searchHistory,
    searchRecommendations,
    removeSuggestion,
} = useSearch();

defineExpose({ searchQuery });

const handleClickOutside = (event) => {
    if (!event.target.closest(".search-container")) {
        showSuggestions.value = false;
    }
};
onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue', 'select-suggestion']);

const inputValue = ref(props.modelValue);

watch(() => props.modelValue, (newVal) => {
    inputValue.value = newVal;
});

watch(inputValue, (newVal) => {
    emit('update:modelValue', newVal);
});

const selectSuggestion = (text) => {
    emit('select-suggestion', text);
    emit('update:modelValue', text);
    showSuggestions.value = false;
};
</script>

<template>
    <div class="relative w-full">
        <input
            v-model="inputValue"
            @focus="showSuggestions = true"
            type="text"
            placeholder="Що шукаєте?"
            class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600"
        />
        <ul v-if="showSuggestions && searchHistory.length" class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10 search-container">
            <div class="text-sm text-gray-400 uppercase p-1 pl-4">Ви нещодавно шукали</div>
            <li
                v-for="(suggestion, index) in searchHistory"
                :key="index"
                @click="selectSuggestion(suggestion)"
                class="flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
            >
                {{ suggestion }}
                <button @click.stop="removeSuggestion(index)" class="text-red-500 text-lg hover:text-red-700 transition duration-200">×</button>
            </li>

            <div class="text-sm text-gray-400 uppercase p-1 pl-4">Рекомендації</div>
            <li
                v-for="(suggestion, index) in searchRecommendations"
                :key="index"
                @click="selectSuggestion(suggestion)"
                class="flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
            >
                {{ suggestion }}
            </li>
        </ul>
    </div>
</template>
