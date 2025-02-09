<script setup>
import { defineProps, ref } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    categories: Array,
    listings: Array,
    news: Array,
});

// Регіони України
const regions = [
    "Вся Україна",
    "Київська область",
    "Львівська область",
    "Одеська область",
    "Харківська область",
    "Дніпропетровська область",
    "Запорізька область",
    "Інші регіони",
];

const selectedRegion = ref(regions[0]);
const searchQuery = ref("");

// Список історії пошуку (автопідказки)
const searchHistory = ref(["iPhone 13", "Ноутбук Dell", "Годинник Apple", "Квартира у Києві"]);

// Відображати список автопідказок чи ні
const showSuggestions = ref(false);

// Додаємо запит в історію
const selectSuggestion = (query) => {
    searchQuery.value = query;
    showSuggestions.value = false;
};

// Видалення запиту з історії
const removeSuggestion = (index) => {
    searchHistory.value.splice(index, 1);
};
</script>

<template>
    <Head title="Головна | Оголошення" />

    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <div class="container mx-auto p-4">
                        <!-- Пошук з автопідказками -->
                        <div class="relative flex items-center gap-4 bg-gray-100 p-4 rounded-lg shadow-md">
                            <div class="relative w-full">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Що шукаєте?"
                                    class="w-full px-4 py-2  rounded-lg"
                                    @focus="showSuggestions = true"
                                    @blur="setTimeout(() => showSuggestions = false, 200)"
                                />
                                <!-- Випадаючий список підказок -->
                                <ul v-if="showSuggestions && searchHistory.length" class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10">
                                    <li
                                        v-for="(suggestion, index) in searchHistory"
                                        :key="index"
                                        class="flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-200"
                                        @click="selectSuggestion(suggestion)"
                                    >
                                        {{ suggestion }}
                                        <button @click.stop="removeSuggestion(index)" class="text-red-500 text-lg">×</button>
                                    </li>
                                </ul>
                            </div>

                            <select v-model="selectedRegion" class="border rounded-lg px-3 py-2">
                                <option v-for="region in regions" :key="region" :value="region">
                                    {{ region }}
                                </option>
                            </select>

                            <button class="bg-green-600 text-white px-6 py-2 rounded-lg">
                                Пошук
                            </button>
                        </div>

                        <!-- Категорії -->
                        <section class="my-8">
                            <h2 class="text-xl font-semibold mb-4 text-center">Розділи на сервісі</h2>
                            <div class="grid grid-cols-3 md:grid-cols-6 gap-4">
                                <div v-for="category in categories" :key="category.id" class="text-center">
                                    <img :src="category.icon" alt="Іконка" class="w-12 h-12 mx-auto" />
                                    <p class="text-sm mt-2">{{ category.name }}</p>
                                </div>
                            </div>
                        </section>

                        <!-- VIP-оголошення -->
                        <section class="bg-gray-100 p-6 rounded">
                            <h2 class="text-xl font-semibold mb-4">VIP-оголошення</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div v-for="listing in listings" :key="listing.id" class="border p-4 rounded shadow">
                                    <img :src="listing.image" alt="Фото" class="w-full h-40 object-cover rounded" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <Link :href="`/listings/${listing.id}`" class="text-blue-500 hover:underline">
                                        Детальніше
                                    </Link>
                                </div>
                            </div>
                        </section>

                        <!-- NEW-оголошення -->
                        <section class="p-6 rounded mt-6">
                            <h2 class="text-xl font-semibold mb-4">Останні оголошення</h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div v-for="listing in news" :key="listing.id" class="border p-4 rounded shadow">
                                    <img :src="listing.image" alt="Фото" class="w-full h-40 object-cover rounded" />
                                    <h3 class="mt-2 text-lg font-semibold">{{ listing.title }}</h3>
                                    <p class="text-green-600 font-bold">{{ listing.price }}</p>
                                    <Link :href="`/listings/${listing.id}`" class="text-blue-500 hover:underline">
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
