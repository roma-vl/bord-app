<script setup>
import { defineProps } from "vue";
import AvatarIcon from "@/Components/Icon/AvatarIcon.vue";

defineProps({
    user: Object,
});
</script>

<template>
    <div class="bg-gray-100 font-sans leading-normal tracking-normal">
        <div class="flex justify-center pt-10 pb-10">
            <div class="bg-white rounded-lg profile-card w-[600px] p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <span class="ml-2 text-lg font-semibold text-gray-800">ID: {{ user.id }} | {{ user.name }} {{ user.role }}</span>
                    </div>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        {{ user.status ? "Active" : "Inactive" }}
                    </button>
                </div>
                <div class="flex justify-center mb-4">
                    <div class="border-b-2 border-gray-200 w-full">
                        <ul class="flex justify-around">
                            <li class="text-center">
                                <a href="#" class="text-blue-500 pb-2 border-b-2 border-blue-500 font-semibold">Overview</a>
                            </li>
                            <li class="text-center">
                                <a href="#" class="text-gray-600 pb-2 hover:text-blue-500">Goal</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex justify-center mb-6  " v-if="user.avatar_url">
                    <img :src="user.avatar_url" alt="Profile" class="rounded-full border-2 border-green-500 p-1 max-w-[300px]">
                </div>
                <div class="flex justify-center mb-6 "  v-else>
                    <AvatarIcon  class="max-w-[300px]"/>
                </div>

                <div class="text-center mb-4">
                    <h2 class="text-xl font-semibold">{{ user.name }}</h2>
                    <p class="text-gray-600">{{ user.email }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9l-5 5-5-5" />
                        </svg>
                        <p class="text-lg font-semibold mt-2"> Створено</p>
                        <p class="text-sm text-gray-600"> {{ user.created_at }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M12 6h.01M12 14h.01" />
                        </svg>
                        <p class="text-lg font-semibold mt-2">Остання зміна</p>
                        <p class="text-sm text-gray-600">{{ user.updated_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
