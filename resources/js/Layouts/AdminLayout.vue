<script setup>

import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import Locale from "@/Layouts/Partials/Locale.vue";
import Notifications from "@/Layouts/Partials/Notifications.vue";
import AdminResponsiveNavigationMenu from "@/Layouts/Partials/AdminResponsiveNavigationMenu.vue";
import SideMenu from "@/Layouts/Partials/SideMenu.vue";
import ArrowLeftIcon from "@/Components/Icon/ArrowLeftIcon.vue";
import { onMounted, ref } from 'vue';

const theme = ref('light');

onMounted(() => {
    // Витягуємо з localStorage або виставляємо за замовчуванням
    theme.value = localStorage.getItem('theme') || 'light';
    applyTheme();
});

function toggleTheme() {
    theme.value = theme.value === 'light' ? 'dark' : 'light';
    localStorage.setItem('theme', theme.value);
    applyTheme();
}

function applyTheme() {
    const html = document.documentElement;
    if (theme.value === 'dark') {
        html.classList.add('dark');
    } else {
        html.classList.remove('dark');
    }
}
const showingNavigationDropdown = ref(false);
const toggleFullscreen = () => {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    } else {
        document.documentElement.requestFullscreen();
    }
};
</script>

<template>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-800 flex flex-col">
            <nav class="border-b border-gray-100 dark:border-gray-600 bg-white dark:bg-gray-800">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 ">
                    <div class="flex h-16 justify-between ">
                        <div class="flex">
                            <div class="flex shrink-0 items-center ">
                                <Link :href="route('admin.index')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex ">
                                <NavLink :href="route('main')" class="dark:text-white">
                                   <ArrowLeftIcon /> Повернутися на головну
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">


                            <div class="relative ms-3 px-2 py-[7px]  dark:bg-gray-700 hover:bg-stone-200 rounded ">
                                <button @click="toggleFullscreen">
                                    <svg class="w-5 h-5 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H4m0 0v4m0-4 5 5m7-5h4m0 0v4m0-4-5 5M8 20H4m0 0v-4m0 4 5-5m7 5h4m0 0v-4m0 4-5-5"/>
                                    </svg>

                                </button>
                            </div>
                            <div class="relative ms-3 dark:bg-gray-700  rounded">
                                <Locale/>
                            </div>
                            <div class="relative ms-3">
                                <button @click="toggleTheme" class="px-4 py-2  hover:bg-stone-200 dark:bg-gray-700  rounded">
                                    {{ theme === 'dark' ? '🌙' : '☀️' }}
                                </button>
                            </div>

                            <div class="relative ms-3">
                                <Notifications/>
                            </div>
                        </div>

                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

               <AdminResponsiveNavigationMenu :showingNavigationDropdown="showingNavigationDropdown" />
            </nav>

            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-3 py-3 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <SideMenu />
            <main class="flex-grow">
                <slot />
            </main>

            <footer class=" bg-gray-100" aria-labelledby="footer-heading">
            </footer>
        </div>
</template>
