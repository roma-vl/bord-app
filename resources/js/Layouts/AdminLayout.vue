<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import Locale from "@/Layouts/Partials/Locale.vue";
import Notifications from "@/Layouts/Partials/Notifications.vue";
import AdminResponsiveNavigationMenu from "@/Layouts/Partials/AdminResponsiveNavigationMenu.vue";
import SideMenu from "@/Layouts/Partials/SideMenu.vue";
import AccountIcon from "@/Components/Icon/AccountIcon.vue";
import VerifyIcon from "@/Components/Icon/VerifyIcon.vue";
import UpDownIcon from "@/Components/Icon/UpDownIcon.vue";
import IntegrationIcon from "@/Components/Icon/IntegrationIcon.vue";
import SettingsIcon from "@/Components/Icon/SettingsIcon.vue";
import GuideIcon from "@/Components/Icon/GuideIcon.vue";
import HelperCenterIcon from "@/Components/Icon/HelperCenterIcon.vue";
import LogoutIcon from "@/Components/Icon/LogoutIcon.vue";
import {useAcl} from "@/composables/useAcl.js";
import ArrowLeftIcon from "@/Components/Icon/ArrowLeftIcon.vue";

const showingNavigationDropdown = ref(false);
const { can } = useAcl();
const toggleFullscreen = () => {
    if (document.fullscreenElement) {
        document.exitFullscreen();
    } else {
        document.documentElement.requestFullscreen();
    }
};
</script>

<template>
        <div class="min-h-screen bg-gray-100 flex flex-col">
            <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('main')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('main')">
                                   <ArrowLeftIcon /> Повернутися на головну
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">


                            <div class="relative ms-3">
                                <button @click="toggleFullscreen">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="relative ms-3">
                                <Locale/>
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

            <header class="bg-white shadow" v-if="$slots.header">
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
