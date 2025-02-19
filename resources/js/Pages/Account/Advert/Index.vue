<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import ProfileMenu from "@/Pages/Account/Profile/Partials/ProfileMenu.vue";

const user = usePage().props.auth.user;

const adverts = usePage().props.adverts;

console.log(adverts, "adverts");

</script>

<template>
    <Head title="Оголошення" />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">

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
                                Додати Оголошення
                                <span class="absolute left-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-top"></span>
                                <span class="absolute right-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-bottom"></span>
                            </a>
                        </div>




                        <div class="p-6 bg-white shadow rounded overflow-hidden">
                            <div v-if="adverts.data.length" class="divide-y">
                                <div v-for="advert in adverts.data" :key="advert.id" class="py-4 flex justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ advert.title }}</h3>
                                        <p class="text-gray-600">{{ advert.price }}</p>
                                        <p class="text-sm text-gray-400">Опубліковано: {{ advert.created_at }}</p>
                                    </div>
                                    <div class="flex flex-col ">
                                        <div class="flex items-start justify-end">
                                             <span class="text-gray-500"> Статус: {{advert.status}}</span>
                                             <span class="text-gray-500"> Переглядів: (6)</span>
                                             <span class="text-gray-500"> Закладки: (2)</span>
                                        </div>
                                        <div class="flex items-end space-x-2 justify-end">
                                            <a :href="route('account.adverts.edit', advert.id)" class="text-blue-600 hover:underline">Редагувати</a>
                                            <a :href="route('adverts.show', advert.id)" class="text-blue-600 hover:underline">Переглянути</a>
                                            <a href="#" class="text-red-600 hover:underline">Видалити</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p v-else class="text-center text-gray-500">У вас немає оголошень.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
