<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router, usePage} from "@inertiajs/vue3";
import ProfileMenu from "@/Pages/Account/Profile/Partials/ProfileMenu.vue";
import {computed} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {getDateFormatFromLocale} from "@/helpers.js";

const favoriteAdverts =computed(() => usePage().props.favoriteAdverts);
const flash = computed(() => usePage().props.flash);
const removeFavoriteAdvert = (advertId) => {
    router.delete(route("account.favorites.remove", {advert: advertId}));
}
</script>

<template>
    <Head title="Оголошення" />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <FlashMessage :flash="flash" />
                    <ProfileMenu :activeTab="'account.favorites.index'" />
                    <div class=" px-4">
                        <div class="grid grid-cols-2 gap-4 items-start mb-3">
                            <h2 class="text-xl font-bold mb-4">Збережені оголошення</h2>
                        </div>

                        <div class="bg-white rounded shadow overflow-hidden mb-4">
                            <div v-if="favoriteAdverts.data.length" class="divide-y divide-gray-100">
                                <div v-for="advert in favoriteAdverts.data" :key="advert.id"
                                     class="p-8 hover:bg-gray-100 transition duration-150 ease-in-out">
                                    <div class="flex justify-between items-start gap-8">
                                        <div class="flex-grow space-y-3">
                                            <a :href="route('adverts.show', advert.id)" class="block group">
                                                <h3 class="text-2xl font-semibold text-gray-800 group-hover:text-violet-600 transition-colors duration-200">{{ advert.title }}</h3>
                                            </a>
                                            <p class="text-xl font-medium text-violet-600">{{ advert.price }} ₴</p>
                                            <div class="flex items-center gap-6 text-sm mt-4">
                                                <span class="flex items-center gap-2 text-gray-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    6 переглядів
                                                </span>
                                                <p class="text-sm text-gray-500">Опубліковано: {{ getDateFormatFromLocale(advert.created_at )}}</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-6">
                                            <div class="flex items-center gap-3">
                                                <button @click.prevent="removeFavoriteAdvert(advert.id)" class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-150 group relative"
                                                        title="Видалити з збережених">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="py-12 text-center text-gray-500 text-lg">У вас немає збережених оголошень.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
