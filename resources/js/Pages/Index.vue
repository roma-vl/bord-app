<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";
import {fullPath, getFullPathForImage} from "@/helpers.js";
import HeartIcon from "@/Components/Icon/HeartIcon.vue";
import HeartSolidIcon from "@/Components/Icon/HeartSolidIcon.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import CookieBanner from "@/Components/CookieBanner.vue";

const categories = usePage().props.categories;
const news = usePage().props.news;
const vip = usePage().props.vip;

const selectedRegion = ref(null);
const selectedCity = ref(null);
const regions = ref([]);
const cities = ref([]);
const searchQuery = ref("");
const searchHistory = ref(["Пилосос", "Квартира", "Машина", "Квартира у Києві"]);
const searchRecommendations = ref(["iPhone 13", "Ноутбук Dell", "Годинник Apple", "Квартира у Києві"]);
const showSuggestions = ref(false);
const openCategory = ref(null);
const showLocationDropdown = ref(false);
const loadingRegions = ref(false);
const loadingCities = ref(false);
const citySearchQuery = ref("");
const cityIdSearchQuery = ref("");
const filteredCities = ref([]);
const flash = computed(() => usePage().props.flash);
const toggleLike = (advert) => {
    if (advert.is_favorited === true) {
        router.delete(route("account.favorites.remove", {advert: advert.id}));
    } else {
        router.post(route("account.favorites.add", {advert: advert.id}));
    }
    advert.is_favorited = !advert.is_favorited;
};

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
console.log(categories, 'categories')
const subCategories = computed(() => {
    const category = categories.find(c => c.id === openCategory.value);
    return category ? category.children : [];
});
const search = () => {
    if (searchQuery.value.trim() === "") return;
    console.log(searchQuery.value, "searchQuery");
    console.log(cityIdSearchQuery.value, "citySearchQuery");
    router.get('/list', { query: searchQuery.value })
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
                        <FlashMessage :flash="flash" />
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
                                            <li v-for="region in regions" :key="region.id" @click="fetchCities(region.id)"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                {{ region.name }}
                                            </li>
                                        </ul>

                                        <ul v-else>
                                            <li v-if="loadingCities" class="px-4 py-2 text-gray-400">Завантаження міст...</li>
                                            <li v-for="city in cities" :key="city.id" @click="selectCity(city)"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                {{ city.name }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <button @click="search"
                                    class="px-8 focus:outline-none hover:bg-green-700 transition duration-300 py-2 my-8 text-lg bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl text-white hover:shadow-2xl">
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
                                                <a :href="fullPath() + '/' + selectedCategory.slug" class="text-sm hover:underline cursor-pointer">{{ selectedCategory.name }}</a>
                                            </p><hr>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3">
                                                <template v-for="subCategory in subCategories" :key="subCategory.id">
                                                    <p class="text-sm hover:underline cursor-pointer">
                                                        <a :href="fullPath() + '/' + selectedCategory.slug + '/' + subCategory.slug">{{ subCategory.name }}</a>
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
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div v-for="listing in vip" :key="listing.id" class="border p-2 rounded shadow">
                                    <img :src="getFullPathForImage(listing.first_photo?.file)" alt="Фото" class="w-full  object-cover rounded  h-48" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <button @click="toggleLike(listing)"
                                            class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition">
                                        <HeartIcon v-if="!listing.is_favorited" class="w-6 h-6"/>
                                        <HeartSolidIcon v-else class="w-6 h-6 text-red-500"/>
                                    </button>
                                    <Link :href="route('adverts.show', listing.id)"  class="text-blue-500 hover:underline">
                                        Детальніше
                                    </Link>
                                </div>
                            </div>
                        </section>

                        <section class="p-6 rounded mt-6">
                            <h2 class="text-xl font-semibold mb-4">Останні оголошення</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div v-for="listing in news" :key="listing.id" class="border p-2 rounded shadow">
                                    <img :src="getFullPathForImage(listing.first_photo?.file)" alt="Фото" class="w-full  object-cover rounded  h-48" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <button @click="toggleLike(listing)"
                                            class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition">
                                        <HeartIcon v-if="!listing.is_favorited" class="w-6 h-6"/>
                                        <HeartSolidIcon v-else class="w-6 h-6 text-red-500"/>
                                    </button>
                                    <Link :href="route('adverts.show', listing.id)" class="text-blue-500 hover:underline">
                                        Детальніше
                                    </Link>
                                </div>
                            </div>
                        </section>

                        <section class="p-6 rounded mt-6">
                            <h2 class="text-xl font-semibold mb-4">Ви переглядали</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div v-for="listing in news" :key="listing.id" class="border p-2 rounded shadow">
                                    <img :src="getFullPathForImage(listing.first_photo?.file)" alt="Фото" class="w-full  object-cover rounded h-48" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <button @click="toggleLike(listing)"
                                            class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition">
                                        <HeartIcon v-if="!listing.is_favorited" class="w-6 h-6"/>
                                        <HeartSolidIcon v-else class="w-6 h-6 text-red-500"/>
                                    </button>
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

        <section class="py-20 bg-white">
            <div class="flex flex-col px-8 mx-auto space-y-12 max-w-7xl xl:px-12">
                <div class="relative">
                    <h2 class="w-full text-3xl font-bold text-center sm:text-4xl md:text-5xl"> Level Up Your Designs</h2>
                    <p class="w-full py-8 mx-auto -mt-2 text-lg text-center text-gray-700 intro sm:max-w-3xl">Add some nice touches to your interface with our latest designs, components, and templates. We've crafted a beautiful user experience that your visitors will love. </p>
                </div>
                <div class="flex flex-col mb-8 animated fadeIn sm:flex-row">
                    <div class="flex items-center mb-8 sm:w-1/2 md:w-5/12 sm:order-last">
                        <img class="rounded-lg shadow-xl" src="https://cdn.devdojo.com/images/december2020/dashboard-011.png" alt="">
                    </div>
                    <div class="flex flex-col justify-center mt-5 mb-8 md:mt-0 sm:w-1/2 md:w-7/12 sm:pr-16">
                        <p class="mb-2 text-sm font-semibold leading-none text-left text-indigo-600 uppercase">Drag-n-drop design</p>
                        <h3 class="mt-2 text-2xl sm:text-left md:text-4xl">Design Made Easy</h3>
                        <p class="mt-5 text-lg text-gray-700 text md:text-left">Crafting your user experience has never been easier, with our intuitive drag'n drop interface you will be creating beatiful designs in no time.</p>
                    </div>
                </div>
                <div class="flex flex-col mb-8 animated fadeIn sm:flex-row">
                    <div class="flex items-center mb-8 sm:w-1/2 md:w-5/12">
                        <img class="rounded-lg shadow-xl" src="https://cdn.devdojo.com/images/december2020/dashboard-04.png" alt="">
                    </div>
                    <div class="flex flex-col justify-center mt-5 mb-8 md:mt-0 sm:w-1/2 md:w-7/12 sm:pl-16">
                        <p class="mb-2 text-sm font-semibold leading-none text-left text-indigo-600 uppercase">know your data</p>
                        <h3 class="mt-2 text-2xl sm:text-left md:text-4xl">Optimized For Conversions</h3>
                        <p class="mt-5 text-lg text-gray-700 text md:text-left">Backed by data, these templates have been crafted for ultimate optimization. Now, converting your visitors into customers is easier than ever before.</p>
                    </div>
                </div>
                <div class="flex flex-col mb-8 animated fadeIn sm:flex-row">
                    <div class="flex items-center mb-8 sm:w-1/2 md:w-5/12 sm:order-last">
                        <img class="rounded-lg shadow-xl" src="https://cdn.devdojo.com/images/december2020/dashboard-03.png" alt="">
                    </div>
                    <div class="flex flex-col justify-center mt-5 mb-8 md:mt-0 sm:w-1/2 md:w-7/12 sm:pr-16">
                        <p class="mb-2 text-sm font-semibold leading-none text-left text-indigo-600 uppercase">Easy to customize</p>
                        <h3 class="mt-2 text-2xl sm:text-left md:text-4xl">Make It Your Own</h3>
                        <p class="mt-5 text-lg text-gray-700 text md:text-left">All templates and components are fully customizable. You can use these templates to tell your personal story and convey your message.</p>
                    </div>
                </div>

            </div>
        </section>


        <CookieBanner ></CookieBanner>
    </AuthenticatedLayout>
</template>

