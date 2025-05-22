import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export function useSearch() {
  const searchQuery = ref('');
  const cityIdSearchQuery = ref('');
  const showSuggestions = ref(false);
  const searchHistory = ref(['Пилосос', 'Квартира', 'Машина', 'Квартира у Києві']);
  const searchRecommendations = ref([
    'iPhone 13',
    'Ноутбук Dell',
    'Годинник Apple',
    'Квартира у Києві',
  ]);

  const selectSuggestion = (query) => {
    searchQuery.value = query;
    showSuggestions.value = false;
  };

  const removeSuggestion = (index) => {
    searchHistory.value.splice(index, 1);
  };

  const search = (params) => {
    const path = window.location.pathname;

    const cleanPath = path
      .split('/')
      .filter((segment) => segment && params.includes(segment))
      .join('/');

    if (searchQuery.value.trim() === '') return;

    const category = cleanPath ? `/${cleanPath}` : '';
    const region = cityIdSearchQuery.value ? `/${cityIdSearchQuery.value}` : '';
    router.get(category + region, { query: searchQuery.value });
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
