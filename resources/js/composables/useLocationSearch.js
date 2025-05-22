import { ref } from 'vue';
import axios from 'axios';

export function useLocationSearch() {
  const selectedRegion = ref(null);
  const selectedCity = ref(null);
  const regions = ref([]);
  const cities = ref([]);
  const filteredCities = ref([]);
  const citySearchQuery = ref('');
  const loadingRegions = ref(false);
  const loadingCities = ref(false);
  const showLocationDropdown = ref(false);

  const fetchRegions = async () => {
    loadingRegions.value = true;
    try {
      const response = await axios.get(route('adverts.regions'));
      regions.value = response.data.regions;
    } finally {
      loadingRegions.value = false;
    }
    showLocationDropdown.value = true;
  };

  const fetchCities = async (regionId) => {
    selectedRegion.value = regions.value.find((r) => r.id === regionId);
    selectedCity.value = null;
    cities.value = [];
    loadingCities.value = true;
    try {
      const response = await axios.get(route('adverts.cities', { region: regionId }));
      cities.value = response.data.cities;
    } finally {
      loadingCities.value = false;
    }
    showLocationDropdown.value = true;
  };

  const searchCities = async () => {
    if (citySearchQuery.value.length < 2) {
      filteredCities.value = [];
      return;
    }
    loadingCities.value = true;
    try {
      const response = await axios.get(
        route('adverts.regions.search', { region: citySearchQuery.value })
      );
      filteredCities.value = response.data.regions;
    } finally {
      loadingCities.value = false;
    }
  };

  const selectCity = (city) => {
    citySearchQuery.value = city.name;
    showLocationDropdown.value = false;
    return city.slug;
  };

  const resetLocation = () => {
    selectedRegion.value = null;
    selectedCity.value = null;
    cities.value = [];
    fetchRegions();
  };

  return {
    selectedRegion,
    selectedCity,
    regions,
    cities,
    filteredCities,
    citySearchQuery,
    loadingRegions,
    loadingCities,
    showLocationDropdown,
    resetLocation,
    fetchRegions,
    fetchCities,
    searchCities,
    selectCity,
  };
}
