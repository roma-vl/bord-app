<script setup>
import {computed, onBeforeUnmount, onMounted, ref} from "vue";
import {Head, Link, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const categories = usePage().props.categories;
const news = usePage().props.news;
const vip = usePage().props.vip;
const regions = usePage().props.regions;

const citiesData = {
    "Київ": [
        { name: "Центр" },
        { name: "Оболонь" },
        { name: "Троєщина" }
    ],
    "Львів": [
        { name: "Шевченківський район" },
        { name: "Галицький район" }
    ],
    "Одеса": [
        { name: "Приморський район" },
        { name: "Київський район" }
    ]
};

const villagesData = {
    "Центр": [
        { name: "Вулиця Центральна" },
        { name: "Вулиця Літературна" }
    ],
    "Оболонь": [
        { name: "Вулиця Південна" },
        { name: "Вулиця Сонячна" }
    ],
    "Шевченківський район": [
        { name: "Вулиця Шевченка" },
        { name: "Вулиця Миколайська" }
    ]
};

const selectedRegion = ref('all');
const selectedCity = ref(null);
const selectedVillage = ref(null);
const searchQuery = ref("");
const searchHistory = ref(["iPhone 13", "Ноутбук Dell", "Годинник Apple", "Квартира у Києві"]);
const searchRecommendations = ref(["iPhone 13", "Ноутбук Dell", "Годинник Apple", "Квартира у Києві"]);
const showSuggestions = ref(false);
const openCategory = ref(null);
const showLocationDropdown = ref(false);


const openSubmenu = ref({ region: null, city: null });

const locationQuery = computed(() => {
    if (selectedVillage) return selectedVillage;
    if (selectedCity) return selectedCity;
    if (selectedRegion === 'all') return 'Уся Україна';
    return selectedRegion;
});

const toggleLocationDropdown = () => {
    showLocationDropdown.value = !showLocationDropdown.value;
    if (showLocationDropdown.value) {
        openSubmenu.value = { region: null, city: null };
    }
};

const toggleSubmenu = (type, name) => {
    if (type === 'region') {
        openSubmenu.value.region = openSubmenu.value.region === name ? null : name;
    } else if (type === 'city') {
        openSubmenu.value.city = openSubmenu.value.city === name ? null : name;
    }
    console.log(openSubmenu.value, "openSubmenu.value");
};

const selectLocation = (location) => {
    if (location.type === 'region') {
        selectedRegion.value = location.name;
        selectedCity.value = null;
        selectedVillage.value = null;
        openSubmenu.value = { region: null, city: null };
    } else if (location.type === 'city') {
        selectedCity.value = location.name;
        selectedVillage.value = null;
        openSubmenu.value.city = null;
    } else if (location.type === 'village') {
        selectedVillage.value = location.name;
    }
    showLocationDropdown.value = false;
};


const selectSuggestion = (query) => {
    searchQuery.value = query;
    showSuggestions.value = false;
};

const removeSuggestion = (index) => {
    searchHistory.value.splice(index, 1);
};

const toggleCategory = (categoryId) => {
    if (openCategory.value === categoryId) {
        openCategory.value = null;
    } else {
        openCategory.value = categoryId;
    }
};

const selectedCategoryName = computed(() => {
    const category = categories.find((c) => c.id === openCategory.value);
    return category ? category.name : "";
});

const handleClickOutside = (event) => {
    if (!event.target.closest(".search-container")) {
        showSuggestions.value = false;
    }
};

const subCategories = computed(() => {
    const category = categories.find(c => c.id === openCategory.value);
    return category ? category.root_with_one_children : [];
});

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
                                    @focus="showSuggestions = true" @blur="hideSuggestions"/>
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
                                <div class="relative w-[180px]">
                                    <input v-model="locationQuery" @click="toggleLocationDropdown" type="text" placeholder="Уся Україна"
                                        class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600 transition duration-200" readonly/>
                                    <div v-if="showLocationDropdown" class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10">
                                        <ul class="py-2">
                                            {{console.log(!selectedRegion, 'sss')}}
                                            <li v-if="!selectedRegion" @click="selectLocation({ type: 'region', name: 'all' })"
                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                Уся Україна
                                            </li>
                                            <li v-for="(region, index) in regions" :key="index" class="relative">
                                                <div @click="toggleSubmenu('region', region.name)"
                                                    class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200 flex items-center justify-between">
                                                    {{ region.name }}
                                                    <span v-if="citiesData[region.name] && citiesData[region.name].length" class="text-gray-400 ml-2">›</span>
                                                </div>
                                                <ul v-if="openSubmenu.region === region.name && citiesData[region.name] && citiesData[region.name].length" class="absolute right-full top-0 w-60 bg-white border rounded-lg shadow-lg ml-1">
                                                    <li v-for="(city, cityIndex) in citiesData[region.name]" :key="cityIndex" class="relative">
                                                        <div @click="toggleSubmenu('city', city.name)"
                                                            class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200 flex items-center justify-between">
                                                            {{ city.name }}
                                                            <span v-if="villagesData[city.name] && villagesData[city.name].length" class="text-gray-400 ml-2">›</span>
                                                        </div>
                                                        <ul v-if="openSubmenu.city === city.name && villagesData[city.name] && villagesData[city.name].length" class="absolute right-full top-0 w-60 bg-white border rounded-lg shadow-lg ml-1">
                                                            <li v-for="(village, villageIndex) in villagesData[city.name]"
                                                                :key="villageIndex"
                                                                @click="selectLocation({ type: 'village', name: village.name })"
                                                                class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200">
                                                                {{ village.name }}
                                                            </li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <button class="bg-green-600 text-white px-8 py-3 rounded-lg text-lg focus:outline-none hover:bg-green-700 transition duration-300">
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
                                                <span class="text-sm hover:underline cursor-pointer">{{ selectedCategoryName }}</span>
                                            </p><hr>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3">
                                                <template v-for="subCategory in subCategories" :key="subCategory.id">
                                                    <p class="text-sm hover:underline cursor-pointer">
                                                        {{ subCategory.name }}
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

