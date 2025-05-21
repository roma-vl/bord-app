// composables/useSearch.js
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export function useSearch() {
    const searchQuery = ref('');
    const cityIdSearchQuery = ref('');
    const showSuggestions = ref(false);
    const searchHistory = ref(["Пилосос", "Квартира", "Машина", "Квартира у Києві"]);
    const searchRecommendations = ref(["iPhone 13", "Ноутбук Dell", "Годинник Apple", "Квартира у Києві"]);

    const selectSuggestion = (query) => {
        console.log('query', query)
        searchQuery.value = query;
        showSuggestions.value = false;
    };


    const removeSuggestion = (index) => {
        searchHistory.value.splice(index, 1);
    };

    const search = () => {
        console.log(searchQuery.value, 'searchQuery')
        console.log(cityIdSearchQuery.value, 'cityIdSearchQuery')
        if (searchQuery.value.trim() === '') return;
        const slug = cityIdSearchQuery.value ? `/${cityIdSearchQuery.value}` : '/list';
        console.log(slug, 'slug')
        router.get(slug, { query: searchQuery.value });
    };

    return {
        searchQuery,
        cityIdSearchQuery,
        showSuggestions,
        searchHistory,
        searchRecommendations,
        selectSuggestion,
        removeSuggestion,
        search,
    };
}
