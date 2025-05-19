<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {getDateFormatFromLocale} from "@/helpers.js";
import {ref} from "vue";
const props = defineProps({
    ticket: Object,
    messages: Object,
    statuses: Object
});
const newComment = ref('');
function submitComment() {
    if (!newComment.value.trim()) return;
    console.log('Коментар надіслано:', newComment.value);
    newComment.value = '';
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-2">
            <div class=" mx-auto max-w-7xl sm:px-6 lg:px-8 p-6  ">
                <div class="list-disc list-inside text-gray-800">
                    <span class="underline cursor-pointer">
                        Головна
                    </span>
                </div>
            </div>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50 ">
                <div class="flex gap-6 ">
                    <div class="w-2/3 ">
                        <div class="bg-white rounded-xl shadow p-6 space-y-4">
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ ticket.subject }}
                            </h1>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ ticket.content }}
                            </p>
                        </div>
                        <div class="bg-white rounded-xl shadow p-6 mt-6">
                            <p class="text-gray-900 text-lg font-bold mb-2">
                                Коментарі
                            </p>
                            <div class="border-b border-gray-200 mb-4"></div>
                            <div v-if="messages.length" class="space-y-6">
                                <div v-for="(msg, index) in messages" :key="index" class="flex items-start gap-4">
                                    <img :src="msg.user.avatar_url || 'https://ui-avatars.com/api/?name=' + msg.user.name"
                                        class="w-10 h-10 rounded-full object-cover border border-gray-300"
                                        :alt="msg.user.name"/>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center">
                                            <span class="font-semibold text-sm text-gray-800">
                                                {{ msg.user.name }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                {{ getDateFormatFromLocale(msg.created_at) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">
                                            {{ msg.message }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 text-sm italic">
                                Коментарів ще немає.
                            </div>

                            <form @submit.prevent="submitComment" class="mt-6">
                                <p class="text-gray-900 text-lg font-bold mb-2">
                                    Залишити коментар
                                </p>
                                <textarea v-model="newComment" rows="4"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none resize-y"
                                    placeholder="Введіть свій коментар..." ></textarea>
                                <div class="flex justify-end mt-3">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow transition">
                                        Надіслати
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="w-1/3">
                        <div class="rounded-lg shadow p-3 bg-white">
                            <p class="mt-4 text-gray-800 text-sm">
                                Опубліковано {{getDateFormatFromLocale(ticket.created_at) }}
                            </p>
                            <div class="rounded-lg shadow p-3 bg-white mt-4">
                                <p class="text-gray-800 font-bold text-sm mb-4"> Статуси </p>
                                <ul class="space-y-2">
                                    <li v-for="(status, index) in statuses" :key="index" class="flex justify-between items-center text-sm">
                                        <span class="inline-block px-1 py-0.5 rounded-full text-s font-semibold"
                                            :class="{
                                                'bg-green-100 text-green-800': status.status === 'open',
                                                'bg-green-200 text-green-700': status.status === 'processing',
                                                'bg-yellow-100 text-yellow-800': status.status === 'pending',
                                                'bg-red-100 text-red-800': status.status === 'closed'
                                              }">
                                              {{ status.status }}
                                            </span>
                                        <span class="text-gray-500 text-xs">
                                            {{ getDateFormatFromLocale(status.created_at) }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
