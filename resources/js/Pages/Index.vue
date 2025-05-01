<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";
import {Head, Link, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";

const categories = usePage().props.categories;
const news = usePage().props.news;
const vip = usePage().props.vip;


const selectedRegion = ref(null);
const selectedCity = ref(null);
const regions = ref([]);
const cities = ref([]);
const searchQuery = ref("");
const searchHistory = ref(["iPhone 13", "Ноутбук Dell", "Годинник Apple", "Квартира у Києві"]);
const searchRecommendations = ref(["iPhone 13", "Ноутбук Dell", "Годинник Apple", "Квартира у Києві"]);
const showSuggestions = ref(false);
const openCategory = ref(null);
const showLocationDropdown = ref(false);
const loadingRegions = ref(false);
const loadingCities = ref(false);
const citySearchQuery = ref("");
const cityIdSearchQuery = ref("");
const filteredCities = ref([]);

const searchCities = async () => {

    if (citySearchQuery.value.length < 2) {
        filteredCities.value = [];
        return;
    }
    cities.value = [];
    regions.value = [];
    loadingCities.value = true;
    try {
        const response = await axios.get(route("adverts.regions.search", { region: citySearchQuery.value }));
        filteredCities.value = response.data.regions;
    } finally {
        loadingCities.value = false;
    }
};
const fetchRegions = async () => {
    if (regions.value && citySearchQuery.value.length === 0) {
        loadingRegions.value = true;
        try {
            const response = await axios.get(route("adverts.regions"));
            regions.value = response.data.regions;
        } finally {
            loadingRegions.value = false;
        }
    }
    showLocationDropdown.value = true;
};
const fetchCities = async (regionId) => {
    selectedRegion.value = regions.value.find(r => r.id === regionId);
    selectedCity.value = null;
    cities.value = [];
    loadingCities.value = true;

    try {
        const response = await axios.get(route("adverts.cities", { region: regionId }));
        cities.value = response.data.cities;
    } finally {
        loadingCities.value = false;
    }
    showLocationDropdown.value = true;
};

const selectCity = (city) => {
    citySearchQuery.value = city.name;
    cityIdSearchQuery.value = city.id;
    showLocationDropdown.value = false;
}
const selectSuggestion = (query) => {
    searchQuery.value = query;
    showSuggestions.value = false;
};
const removeSuggestion = (index) => {
    searchHistory.value.splice(index, 1);
};

const handleClickOutside = (event) => {
    if (!event.target.closest(".search-container")) {
        cities.value = [];
        regions.value = [];
        showLocationDropdown.value = false;
    }
};
const toggleCategory = (categoryId) => {
    if (openCategory.value === categoryId) {
        openCategory.value = null;
    } else {
        openCategory.value = categoryId;
    }
};


const selectedCategory = computed(() => {
    const category = categories.find((c) => c.id === openCategory.value);
    return category ? category : "";
});



const subCategories = computed(() => {
    const category = categories.find(c => c.id === openCategory.value);
    return category ? category.root_with_one_children : [];
});
const search = () => {
    if (searchQuery.value.trim() === "") return;
    console.log(searchQuery.value, "searchQuery");
    console.log(cityIdSearchQuery.value, "citySearchQuery");
}
watch(citySearchQuery, searchCities);
onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <Head title="Головна | Оголошення" />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <div class="container mx-auto p-4">
                        <div class="flex items-center gap-4 bg-gray-100 p-4 rounded-lg shadow-md search-container">
                            <div class="relative w-full">
                                <input v-model="searchQuery" type="text" placeholder="Що шукаєте?"
                                    class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600"
                                    @focus="showSuggestions = true"/>
                                <ul v-if="showSuggestions && searchHistory.length"
                                    class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10">
                                    <div class="text-sm text-gray-400 uppercase p-1 pl-4">Ви нещодавно шукали</div>
                                    <li v-for="(suggestion, index) in searchHistory" :key="index"
                                        class="flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
                                        @click="selectSuggestion(suggestion)">
                                        {{ suggestion }}
                                        <button @click.stop="removeSuggestion(index)" class="text-red-500 text-lg hover:text-red-700 transition duration-200">
                                            ×
                                        </button>
                                    </li>

                                    <div class="text-sm text-gray-400 uppercase p-1 pl-4">Рекомендації</div>
                                    <li v-for="(suggestion, index) in searchRecommendations" :key="index"
                                        class="flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
                                        @click="selectSuggestion(suggestion)">
                                        {{ suggestion }}
                                    </li>
                                </ul>
                            </div>

                            <div class="flex items-center gap-4">
                                <div class="relative w-[250px]">
                                    <input v-model="citySearchQuery" @focus="fetchRegions" type="text"
                                           class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600 transition duration-200"
                                           placeholder="Оберіть область" />

                                    <div v-if="showLocationDropdown" class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10 h-[400px] overflow-y-auto">
                                        <ul v-if="filteredCities.length">
                                            <li v-for="city in filteredCities" :key="city.id" @click="selectCity(city)"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                {{ city.name }}
                                            </li>
                                        </ul>

                                        <ul v-if="(regions && cities.length === 0)">
                                            <li v-if="loadingRegions" class="px-4 py-2 text-gray-400">Завантаження...</li>
                                            <li v-for="region in regions" :key="region.id"
                                                @click="fetchCities(region.id)"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                {{ region.name }}
                                            </li>
                                        </ul>

                                        <ul v-else>
                                            <li v-if="loadingCities" class="px-4 py-2 text-gray-400">Завантаження міст...</li>
                                            <li v-for="city in cities" :key="city.id"
                                                @click="selectCity(city)"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                {{ city.name }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <button
                                    @click="search"
                                    class="bg-green-600 text-white px-8 py-3 rounded-lg text-lg focus:outline-none hover:bg-green-700 transition duration-300">
                                    Пошук
                                </button>
                            </div>
                        </div>

                        <section class="my-8">
                            <h2 class="text-xl font-semibold mb-4 text-center">Розділи на сервісі</h2>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-4 items-start">
                                <template v-for="category in categories" :key="category.id">
                                    <div class="text-center">
                                        <button class="p-3 shadow rounded bg-gray-100 hover:bg-gray-200 w-full min-h-[120px]"
                                            @click="toggleCategory(category.id)">
                                            <img src="https://categories.olxcdn.com/assets/categories/olxua/arenda-prokat-3428-1x.png" alt="Іконка" class="w-12 h-12 mx-auto" />
                                            <span class="text-sm mt-2 hover:underline">{{ category.name }}</span>
                                        </button>
                                    </div>
                                </template>

                                <div v-if="openCategory && subCategories.length > 0" class="col-span-full">
                                    <transition name="fade">
                                        <div class="bg-white shadow-md rounded p-4 mt-2">
                                            <p class="pb-3">
                                                <span class="font-bold text-sm"> > Переглянути всі оголошення в </span>
                                                <a :href="'adverts/' + selectedCategory.slug" class="text-sm hover:underline cursor-pointer">{{ selectedCategory.name }}</a>
                                            </p><hr>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3">
                                                <template v-for="subCategory in subCategories" :key="subCategory.id">
                                                    <p class="text-sm hover:underline cursor-pointer">
                                                        <a :href="'adverts/' +selectedCategory.slug + '/' + subCategory.slug">{{ subCategory.name }}</a>
                                                    </p>
                                                </template>
                                            </div>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </section>

                        <section class="bg-gray-100 p-6 rounded">
                            <h2 class="text-xl font-semibold mb-4">VIP-оголошення</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div v-for="listing in vip" :key="listing.id" class="border p-4 rounded shadow">
                                    <img src="/storage/images/adverts/info/empty.jpg" alt="Фото" class="w-full  object-cover rounded" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <Link :href="route('adverts.show', listing.id)"  class="text-blue-500 hover:underline">
                                        Детальніше
                                    </Link>
                                </div>
                            </div>
                        </section>

                        <section class="p-6 rounded mt-6">
                            <h2 class="text-xl font-semibold mb-4">Останні оголошення</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div v-for="listing in news" :key="listing.id" class="border p-4 rounded shadow">
                                    <img src="/storage/images/adverts/info/empty.jpg" alt="Фото" class="w-full  object-cover rounded" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <Link :href="route('adverts.show', listing.id)" class="text-blue-500 hover:underline">
                                        Детальніше
                                    </Link>
                                </div>
                            </div>
                        </section>

                        <section class="p-6 rounded mt-6">
                            <h2 class="text-xl font-semibold mb-4">Ви переглядали</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div v-for="listing in news" :key="listing.id" class="border p-4 rounded shadow">
                                    <img src="/storage/images/adverts/info/empty.jpg" alt="Фото" class="w-full  object-cover rounded" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <Link :href="route('adverts.show', listing.id)" class="text-blue-500 hover:underline">
                                        Детальніше
                                    </Link>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

