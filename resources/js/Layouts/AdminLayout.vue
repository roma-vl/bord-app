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
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-gray-100 bg-white">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('main')">
                                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                                </Link>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('main')">
                                   <ArrowLeftIcon /> Повернутися на головну
                                </NavLink>
                                <NavLink v-if="can('user.view')" :href="route('admin.users.index')">
                                    Users
                                </NavLink>
                                <NavLink v-if="can('role')" :href="route('admin.roles.index')">
                                    Roles
                                </NavLink>
                                <NavLink v-if="can('permission')" :href="route('admin.permissions.index')">
                                    Permissions
                                </NavLink>
                                <NavLink v-if="can('location')" :href="route('admin.locations.index')">
                                    Location
                                </NavLink>
                                <NavLink v-if="can('adverts')" :href="route('admin.adverts.category.index')">
                                    Категорії
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

                            <div class="relative ms-3">
                                <Dropdown align="right" width="96">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="flex items-center justify-center  bg-gray-100">
                                            <div class="w-full max-w-sm rounded-lg bg-white p-3 drop-shadow-xl divide-y divide-gray-200">
                                                <div aria-label="header" class="flex space-x-4 items-center p-4">
                                                    <div aria-label="avatar" class="flex mr-auto items-center space-x-4">
                                                        <img :src="$page.props.auth.user.avatar_url" :alt="$page.props.auth.user.name" class="w-16 h-16 shrink-0 rounded-full"/>
                                                        <div class="space-y-2 flex flex-col flex-1 truncate">
                                                            <div class="font-medium relative text-xl leading-tight text-gray-900">
                                                                <span class="flex">
                                                                    <span class="truncate relative pr-8">
                                                                        {{ $page.props.auth.user.name }}
                                                                        <span aria-label="verified" class="absolute top-1/2 -translate-y-1/2 right-0 inline-block rounded-full">
                                                                            <VerifyIcon />
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                            <p class="font-normal text-base leading-tight text-gray-500 truncate">
                                                                {{ $page.props.auth.user.email }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                   <UpDownIcon />
                                                </div>
                                                <div aria-label="navigation" class="py-2">
                                                    <nav class="grid gap-1">
                                                        <a :href="route('account.profile.edit')" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <AccountIcon /> <span>Account Settings</span>
                                                        </a>
                                                        <a href="/" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <IntegrationIcon /> <span>Integrations</span>
                                                        </a>
                                                        <a href="/" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <SettingsIcon /> <span>Settings</span>
                                                        </a>
                                                        <a href="/" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <GuideIcon /> <span>Guide</span>
                                                        </a>
                                                        <a href="/" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <HelperCenterIcon /> <span>Helper Center</span>
                                                        </a>
                                                    </nav>
                                                </div>
                                                <div aria-label="account-upgrade" class="px-4 py-6">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="mr-auto space-y-2">
                                                            <p class="font-medium text-xl text-gray-900 leading-none">
                                                                Free Plan
                                                            </p>
                                                            <p class="font-normal text-lg text-gray-500 leading-none">
                                                                12,000 views
                                                            </p>
                                                        </div>
                                                        <button type="button" class="inline-flex px-6 leading-6 py-3 rounded-md bg-indigo-50 hover:bg-indigo-50/80 transition-colors duration-200 text-indigo-500 font-medium text-lg">
                                                            Upgrade
                                                        </button>
                                                    </div>
                                                </div>
                                                <div aria-label="footer" class="pt-2">
                                                    <Link :href="route('logout')" method="post" type="button"
                                                        class="flex items-center space-x-3 py-3 px-4 w-full leading-6 text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                        <LogoutIcon /> <span>Log Out</span>
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </Dropdown>
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

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-3 py-3 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <SideMenu />
            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <footer class=" bg-gray-100" aria-labelledby="footer-heading">
                <h2 >Footer</h2>
            </footer>
        </div>
</template>
