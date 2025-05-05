<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link, usePage} from "@inertiajs/vue3";
import ProfileMenu from "@/Pages/Account/Profile/Partials/ProfileMenu.vue";
import {computed} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {getFullPathForImage} from "@/helpers.js";

const user = usePage().props.auth.user;

const adverts = usePage().props.adverts;
console.log(adverts,'adverts')
const flash = computed(() => usePage().props.flash);

console.log(adverts, "adverts");
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('uk-UA', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Оголошення" />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <FlashMessage :flash="flash" />
                    <ProfileMenu :activeTab="'account.adverts.index'" />
                    <div class=" px-4">
                        <div class="grid grid-cols-2 gap-4 items-start mb-3">
                            <h2 class="text-xl font-bold mb-4">Мої оголошення</h2>

                            <a :href="route('account.adverts.create')"
                               class="justify-self-end w-48 h-12 flex items-center justify-center text-md font-medium text-violet-500 hover:text-violet-600 transition-all duration-300 ease-in-out relative
                                before:absolute before:bottom-0 before:left-0 before:w-full before:h-[3px] before:bg-violet-600 before:origin-bottom-right before:scale-x-0
                                 before:transition-transform before:duration-300 before:ease-in-out after:absolute after:top-0 after:left-0 after:w-full after:h-[2px]
                                 after:bg-violet-600 after:origin-top-left after:scale-x-0 after:transition-transform after:duration-300 after:ease-in-out
                                  hover:before:scale-x-100 hover:before:origin-bottom-left hover:after:scale-x-100 hover:after:origin-top-right group">
                                + Додати Оголошення
                                <span class="absolute left-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-top"></span>
                                <span class="absolute right-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-bottom"></span>
                            </a>
                        </div>

                        <div class="bg-white rounded shadow overflow-hidden mb-4">
                            <div v-if="adverts.data.length" class="divide-y divide-gray-100">
                                <div v-for="advert in adverts.data" :key="advert.id"
                                     class="p-4 hover:bg-gray-100 transition duration-150 ease-in-out">

                                    <div class="flex justify-between items-start gap-8 min-h-36">
                                        <div class="w-40">
                                            <img :src="getFullPathForImage(advert.first_photo?.file)" :alt="advert.title">
                                        </div>
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
                                                <span class="flex items-center gap-2 text-gray-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                                    </svg>
                                                    2 в закладках
                                                </span>
                                                <p class="text-sm text-gray-500">Опубліковано: {{ formatDate(advert.created_at )}}</p>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-6">
                                            <span class="px-4 py-2 rounded-full text-sm font-medium"
                                                  :class="{
                                                      'bg-green-100 text-green-800': advert.status === 'active',
                                                      'bg-green-100 text-green-400': advert.status === 'moderation',
                                                      'bg-yellow-100 text-yellow-800': advert.status === 'pending',
                                                      'bg-red-100 text-red-800': advert.status === 'inactive'
                                                  }">
                                                {{advert.status}}
                                            </span>

                                            <div class="flex items-center gap-3">
                                                <a :href="route('account.adverts.edit', advert.id)"
                                                   class="p-1.5 text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition duration-150 group relative"
                                                   title="Редагувати">
                                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                        Редагувати
                                                    </span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                                <a :href="route('adverts.show', advert.id)"
                                                   class="p-1.5 text-violet-600 hover:text-violet-700 hover:bg-violet-50 rounded-lg transition duration-150 group relative"
                                                   title="Переглянути">
                                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                        Переглянути
                                                    </span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                                <button class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-150 group relative"
                                                        title="Видалити">
                                                    <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 text-sm text-white bg-gray-800 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap">
                                                        Видалити
                                                    </span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="py-12 text-center text-gray-500 text-lg">У вас немає оголошень.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
