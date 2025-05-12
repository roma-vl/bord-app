<script setup>

const props = defineProps({
    activeTab: String,
});

const menuItems = [
    { name: "Оголошення", route: "account.adverts.index" },
    { name: "Чат", route: "account.chats.index" },
    { name: "Профіль", route: "account.profile.index" },
    { name: "Збережені", route: "account.favorites.index" },
    { name: "Налаштування", route: "account.profile.settings" },
    { name: "Реклама", route: "account.banners.index" },
];


</script>

<template>
    <nav class="flex border-b mb-4">
        <template v-for="item in menuItems" :key="item.route">
            <a
                :href="route(item.route)"
                class="px-4 py-2 text-gray-600 hover:text-gray-900 border-b-2"
                :class="{
                    'border-blue-500 font-bold ': props.activeTab === item.route,
                    'border-transparent': props.activeTab !== item.route
                }"
            >
                {{ item.name }}
            </a>
        </template>
    </nav>
</template>
