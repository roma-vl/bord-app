<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import ProfileMenu from "@/Pages/Account/Profile/Partials/ProfileMenu.vue";

const user = usePage().props.auth.user;

// Заглушка для списку чатів
const chats = [
    { id: 1, title: "Продам велосипед", last_message: "Привіт! Як вам оголошення?", last_message_time: "2025-02-10 12:34" },
    { id: 2, title: "Оренда квартири", last_message: "Добрий день! Де можна побачити більше фото?", last_message_time: "2025-02-05 13:10" },
    { id: 3, title: "iPhone 13 Pro Max", last_message: "Чи є ще в наявності?", last_message_time: "2025-01-28 14:00" },
];

// Заглушка для повідомлень чату
const messages = [
    { id: 1, sender: 'Продавець', content: "Привіт! Ваше оголошення цікаве.", timestamp: "2025-02-10 12:34" },
    { id: 2, sender: 'Клієнт', content: "Доброго дня! Я зацікавлений у покупці.", timestamp: "2025-02-10 13:00" },
    { id: 3, sender: 'Продавець', content: "Які питання у вас виникли?", timestamp: "2025-02-10 13:10" },
];
</script>

<template>
    <Head title="Чат" />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">

                    <ProfileMenu :activeTab="'account.chats.index'" />
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Список чатів зліва -->
                        <div class="col-span-1 p-4 border-r border-gray-200">
                            <h2 class="text-xl font-bold mb-4">Список чатів</h2>
                            <div v-for="chat in chats" :key="chat.id" class="mb-3">
                                <a :href="`#chat-${chat.id}`" class="block p-3 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-100">
                                    <p class="font-medium text-md">{{ chat.title }}</p>
                                    <p class="text-sm text-gray-600">{{ chat.last_message }}</p>
                                    <p class="text-xs text-gray-400">{{ chat.last_message_time }}</p>
                                </a>
                            </div>
                        </div>

                        <!-- Повідомлення вибраного чату справа -->
                        <div class="col-span-2 p-4">
                            <h2 class="text-xl font-bold mb-4">Чат з продавцем</h2>

                            <div class="space-y-4 mb-4">
                                <div v-for="message in messages" :key="message.id" class="flex items-start space-x-2">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span>{{ message.sender[0] }}</span>
                                    </div>
                                    <div class="flex-1 p-3 bg-gray-50 rounded-lg shadow-sm">
                                        <p class="font-medium">{{ message.sender }}</p>
                                        <p class="text-sm text-gray-600">{{ message.content }}</p>
                                        <p class="text-xs text-gray-400">{{ message.timestamp }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Форма для нового повідомлення -->
                            <div class="mt-4">
                                <input
                                    type="text"
                                    placeholder="Напишіть повідомлення..."
                                    class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-violet-600"
                                />
                                <button class="mt-2 w-full bg-violet-600 text-white py-2 rounded-lg hover:bg-violet-700">
                                    Відправити
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
