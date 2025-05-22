<script setup>
import {computed, ref} from "vue";
import {Head, Link, router, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {fullPath, getFullPathForImage} from "@/helpers.js";
import HeartIcon from "@/Components/Icon/HeartIcon.vue";
import HeartSolidIcon from "@/Components/Icon/HeartSolidIcon.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import CookieBanner from "@/Components/CookieBanner.vue";

const categories = usePage().props.categories;
const news = usePage().props.news;
const vip = usePage().props.vip;

import { useSearch } from '@/composables/useSearch.js';
import SearchInput from '@/Components/Search/SearchInput.vue';
import LocationSelector from '@/Components/Search/LocationSelector.vue';

const { searchQuery, cityIdSearchQuery, search } = useSearch();
const handleCitySelect = (slug) => {
    cityIdSearchQuery.value = slug;
};
const handleSearch = (text) => {
    searchQuery.value = text;
};

const openCategory = ref(null);
const flash = computed(() => usePage().props.flash);
const toggleLike = (advert) => {
    if (advert.is_favorited === true) {
        router.delete(route("account.favorites.remove", {advert: advert.id}));
    } else {
        router.post(route("account.favorites.add", {advert: advert.id}));
    }
    advert.is_favorited = !advert.is_favorited;
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
    return category ? category.children : [];
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
                            <SearchInput v-model="searchQuery" @select-suggestion="handleSearch"/>
                            <div class="flex items-center gap-4">
                                <LocationSelector  v-model="cityIdSearchQuery" @select-city="handleCitySelect" />
                                <button @click="search" class="px-8 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl hover:shadow-2xl transition">
                                    Пошук
                                </button>
                            </div>
                        </div>

                        <section class="my-8">
                            <h2 class="text-xl font-semibold mb-4 text-center">
                                Розділи на сервісі
                            </h2>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-4 items-start">
                                <template v-for="category in categories" :key="category.id">
                                    <div class="text-center">
                                        <button class="p-3 shadow rounded bg-gray-100 hover:bg-gray-200 w-full min-h-[120px]"
                                            @click="toggleCategory(category.id)">
                                            <img src="https://categories.olxcdn.com/assets/categories/olxua/arenda-prokat-3428-1x.png" alt="Іконка" class="w-12 h-12 mx-auto" />
                                            <span class="text-sm mt-2 hover:underline">
                                                {{ category.name }}
                                            </span>
                                        </button>
                                    </div>
                                </template>

                                <div v-if="openCategory && subCategories.length > 0" class="col-span-full">
                                    <transition name="fade">
                                        <div class="bg-white shadow-md rounded p-4 mt-2">
                                            <p class="pb-3">
                                                <span class="font-bold text-sm"> > Переглянути всі оголошення в </span>
                                                <a :href="fullPath() + '/' + selectedCategory.slug" class="text-sm hover:underline cursor-pointer">
                                                    {{ selectedCategory.name }}
                                                </a>
                                            </p><hr>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-3">
                                                <template v-for="subCategory in subCategories" :key="subCategory.id">
                                                    <p class="text-sm hover:underline cursor-pointer">
                                                        <a :href="fullPath() + '/' + selectedCategory.slug + '/' + subCategory.slug">
                                                            {{ subCategory.name }}
                                                        </a>
                                                    </p>
                                                </template>
                                            </div>
                                        </div>
                                    </transition>
                                </div>
                            </div>
                        </section>

                        <section class="bg-gray-100 p-6 rounded">
                            <h2 class="text-xl font-semibold mb-4">
                                VIP-оголошення
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div v-for="listing in vip" :key="listing.id" class="border p-2 rounded shadow">
                                    <img :src="getFullPathForImage(listing.first_photo?.file)" alt="Фото" class="w-full  object-cover rounded  h-48" />
                                    <h3 class="mt-2 text-lg font-semibold">
                                        {{ listing.title }}
                                    </h3>
                                    <p class="text-green-600 font-bold">
                                        {{ listing.price }}
                                    </p>
                                    <button @click="toggleLike(listing)" class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition">
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
                            <h2 class="text-xl font-semibold mb-4">
                                Останні оголошення
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div v-for="listing in news" :key="listing.id" class="border p-2 rounded shadow">
                                    <img :src="getFullPathForImage(listing.first_photo?.file)" alt="Фото" class="w-full  object-cover rounded  h-48" />
                                    <h3 class="mt-2 text-lg font-semibold">
                                        {{ listing.title }}
                                    </h3>
                                    <p class="text-green-600 font-bold">
                                        {{ listing.price }}
                                    </p>
                                    <button @click="toggleLike(listing)" class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition">
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
                            <h2 class="text-xl font-semibold mb-4">
                                Ви переглядали
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div v-for="listing in news" :key="listing.id" class="border p-2 rounded shadow">
                                    <img :src="getFullPathForImage(listing.first_photo?.file)" alt="Фото" class="w-full  object-cover rounded h-48" />
                                    <h3 class="mt-2 text-lg font-semibold">
                                        {{ listing.title }}
                                    </h3>
                                    <p class="text-green-600 font-bold">
                                        {{ listing.price }}
                                    </p>
                                    <button @click="toggleLike(listing)" class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition">
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
        <CookieBanner ></CookieBanner>
    </AuthenticatedLayout>
</template>

