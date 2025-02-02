<template>
    <div>
        <!-- Кнопка відкриття меню -->
        <button @click="toggleSidebar" class="fixed top-4 left-4 z-50 p-2 bg-gray-800 text-white rounded-md">
            <i class="ri-menu-line text-lg"></i>
        </button>

        <!-- Темний оверлей -->
        <div v-if="isOpen" @click="closeSidebar" class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden"></div>

        <!-- Бокове меню -->
        <div
            class="fixed left-0 top-0 w-64 h-full bg-[#f8f4f3] p-4 z-50 transition-transform"
            :class="{ '-translate-x-full': !isOpen }"
        >
            <a href="#" class="flex items-center pb-4 border-b border-b-gray-800">
                <h2 class="font-bold text-2xl">LOREM <span class="bg-[#f84525] text-white px-2 rounded-md">IPSUM</span></h2>
            </a>
            <ul class="mt-4">
                <span class="text-gray-400 font-bold">ADMIN</span>
                <li v-for="item in menuItems" :key="item.text" class="mb-1 group">
                    <a href="#" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md">
                        <i :class="item.icon" class="mr-3 text-lg"></i>
                        <span class="text-sm">{{ item.text }}</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const isOpen = ref(false)

const toggleSidebar = () => {
    isOpen.value = !isOpen.value
}

const closeSidebar = () => {
    isOpen.value = false
}

const menuItems = [
    { text: 'Dashboard', icon: 'ri-home-2-line' },
    { text: 'Users', icon: 'bx bx-user' },
    { text: 'Activities', icon: 'bx bx-list-ul' },
    { text: 'Post', icon: 'bxl-blogger' },
    { text: 'Archive', icon: 'bx bx-archive' },
    { text: 'Notifications', icon: 'bx bx-bell' },
    { text: 'Messages', icon: 'bx bx-envelope' },
]
</script>

<style scoped>
/* Анімація появи меню */
.sidebar-menu {
    transition: transform 0.3s ease-in-out;
}

.-translate-x-full {
    transform: translateX(-100%);
}
</style>
