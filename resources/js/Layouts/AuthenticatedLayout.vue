<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import Locale from "@/Layouts/Partials/Locale.vue";
import GuideIcon from "@/Components/Icon/GuideIcon.vue";
import IntegrationIcon from "@/Components/Icon/IntegrationIcon.vue";
import VerifyIcon from "@/Components/Icon/VerifyIcon.vue";
import AccountIcon from "@/Components/Icon/AccountIcon.vue";
import LogoutIcon from "@/Components/Icon/LogoutIcon.vue";
import SettingsIcon from "@/Components/Icon/SettingsIcon.vue";
import HelperCenterIcon from "@/Components/Icon/HelperCenterIcon.vue";
import UpDownIcon from "@/Components/Icon/UpDownIcon.vue";

const showingNavigationDropdown = ref(false);
const isMessagesVisible = ref(false);

const messages = () => {
    isMessagesVisible.value = !isMessagesVisible.value;
};


</script>

<template>
    <div>
        <div class="flex flex-col min-h-screen bg-gray-100" > <!-- style='background: url("/storage/images/background/1.jpg") center top no-repeat;' -->
                <nav class="border-b border-gray-100 bg-white/80 backdrop-blur-md">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('main')">
                                    <ApplicationLogo class="block fill-current  w-24" />
                                </Link>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <div class="relative ms-3 ">
                                <a :href="route('account.adverts.create')"
                                   class="justify-self-end w-48 h-12 flex items-center justify-center text-md font-medium text-violet-600 transition-all duration-300 ease-in-out relative
                                before:absolute before:bottom-0 before:left-0 before:w-full before:h-[3px] before:bg-violet-600 before:origin-bottom-right before:scale-x-0
                                 before:transition-transform before:duration-300 before:ease-in-out after:absolute after:top-0 after:left-0 after:w-full after:h-[2px]
                                 after:bg-violet-600 after:origin-top-left after:scale-x-0 after:transition-transform after:duration-300 after:ease-in-out
                                  hover:before:scale-x-100 hover:before:origin-bottom-left hover:after:scale-x-100 hover:after:origin-top-right group">
                                    Додати Оголошення
                                    <span class="absolute left-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-top"></span>
                                    <span class="absolute right-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-bottom"></span>
                                </a>
                            </div>
                            <div class="relative ms-3">
                                <Locale/>
                            </div>
                            <div class="relative ms-3">
                                <Dropdown align="right" width="72">
                                    <template #trigger>
                                        <div class="flex items-center justify-center rounded-lg p-2 font-sans text-xs cursor-pointer font-bold uppercase text-pink-500 transition-all hover:bg-pink-500/10 active:bg-pink-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                            </svg>
                                        </div>
                                    </template>

                                    <template #content>
                                        <ul class="  flex-col gap-2 overflow-auto  bg-white p-3  font-sans text-sm font-normal text-blue-gray-500 shadow-blue-gray-500/10 focus:outline-none">
                                            <div role="menuitem" class=" flex w-full cursor-pointer select-none items-center gap-4 rounded-md px-3 py-2 pr-8 pl-2 text-start leading-tight outline-none transition-all hover:bg-gray-500/10 hover:border-gray-300  active:bg-gray-300 active:border-gray-800">
                                                <img alt="tania andrew"
                                                     src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1480&amp;q=80"
                                                     class="relative inline-block h-12 w-12 !rounded-full object-cover object-center"/>
                                                <div class="flex flex-col gap-1">
                                                    <p class="block font-sans text-sm font-normal leading-normal text-gray-700 antialiased">
                                                        <span class="font-medium text-blue-gray-900">Tania</span> send you a message
                                                    </p>
                                                    <p class="flex items-center gap-1 font-sans text-xs font-light text-gray-600 antialiased">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            aria-hidden="true"
                                                            class="h-3 w-3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        13 minutes ago
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex w-full cursor-pointer select-none items-center gap-4 rounded-md px-3 py-2 pr-8 pl-2 text-start leading-tight outline-none transition-all hover:bg-gray-500/10 hover:border-gray-300  active:bg-gray-300 active:border-gray-800">
                                                <img
                                                    alt="natali craig"
                                                    src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-1.2.1&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1061&amp;q=80"
                                                    class="relative inline-block h-12 w-12 !rounded-full object-cover object-center"/>
                                                <div class="flex flex-col gap-1">
                                                    <p class="block font-sans text-sm font-normal leading-normal text-gray-700 antialiased">
                                                        <span class="font-medium text-blue-gray-900">Natali</span> reply to your email
                                                    </p>
                                                    <p class="flex items-center gap-1 font-sans text-xs font-light text-gray-600 antialiased">
                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            fill="none"
                                                            viewBox="0 0 24 24"
                                                            stroke-width="1.5"
                                                            stroke="currentColor"
                                                            aria-hidden="true"
                                                            class="h-3 w-3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        a hour ago
                                                    </p>
                                                </div>
                                            </div>

                                        </ul>
                                    </template>
                                </Dropdown>
                            </div>
                            <div class="relative ms-3  z-20">
                                <Dropdown align="right" width="96" >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth?.user?.name ?? 'Ваш профіль' }}

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
                                                        <img :src="$page.props.auth?.user?.avatar_url" :alt="$page.props.auth?.user?.name" class="w-16 h-16 shrink-0 rounded-full"/>
                                                        <div class="space-y-2 flex flex-col flex-1 truncate">
                                                            <div class="font-medium relative text-xl leading-tight text-gray-900">
                                                                <span class="flex">
                                                                    <span class="truncate relative pr-8">
                                                                        {{ $page.props.auth?.user?.name }}
                                                                        <span aria-label="verified" class="absolute top-1/2 -translate-y-1/2 right-0 inline-block rounded-full">
                                                                            <VerifyIcon />
                                                                        </span>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                            <p class="font-normal text-base leading-tight text-gray-500 truncate">
                                                                {{ $page.props.auth?.user?.email }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <UpDownIcon />
                                                </div>
                                                <div aria-label="navigation" class="py-2">
                                                    <nav class="grid gap-1">
                                                        <a :href="route('account.adverts.index')" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <GuideIcon /> <span>Adverts</span>
                                                        </a>
                                                        <a :href="route('account.chats.index')" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <IntegrationIcon /> <span>Chat</span>
                                                        </a>
                                                        <a :href="route('account.profile.index')"class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <AccountIcon /> <span>Profile</span>
                                                        </a>
                                                        <a :href="route('account.profile.settings')" class="flex items-center leading-6 space-x-3 py-3 px-4 w-full text-lg text-gray-600 focus:outline-none hover:bg-gray-100 rounded-md">
                                                            <SettingsIcon /> <span>Account Settings</span>
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

                        <!-- Hamburger -->
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

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden">

                    <div class="border-t border-gray-200 pb-1 pt-4">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800">
                                {{ $page.props.auth?.user?.name ?? 'Ваш профіль' }}
                            </div>
                            <div class="text-sm font-medium text-gray-500">
                                {{ $page.props.auth?.user?.email ?? '' }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('account.profile.settings')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-grow">
                <slot />
            </main>

            <footer class=" bg-gray-100" aria-labelledby="footer-heading">
                <div>
                    <button @click="messages" class="z-20 text-white flex flex-col shrink-0 grow-0 justify-around fixed bottom-0  right-1 rounded-lgmr-3 mb-3">
                        <div class="p-3 rounded-full border-4 border-white bg-green-600">
                            <svg class="w-6 h-6 lg:w-8 lg:h-8 xl:w-10 xl:h-10" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </button>
                    <div v-if="isMessagesVisible" class="fixed flex-col space-y-4 mb-20 right-4  md:bottom-8 lg:mb-16  z-5 flex">
                        <div class="w-full h-auto relative">
                            <div class="bg-white  px-5 py-3.5 rounded-lg shadow hover:shadow-md max-w-sm mx-auto transform hover:-translate-y-[0.125rem] transition duration-100 ease-linear">
                                <div class="w-full flex items-center justify-between">
                                    <span class="font-medium text-sm text-slate-400">New Notification</span>
                                    <button class="-mr-1 bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-600 h-5 w-5 rounded-full flex justify-center items-center">
                                        <svg class="h-2 w-2 fill-current items-center" viewBox="0 0 20 20"><path d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"/></svg>
                                    </button>
                                </div>
                                <div class="flex items-center mt-2 rounded-lg px-1 py-1 cursor-pointer">
                                    <div class="relative flex flex-shrink-0 items-end">
                                        <img class="h-16 w-16 rounded-full" src="https://i.pravatar.cc/300" alt="">
                                        <span class="absolute h-4 w-4 bg-green-400 rounded-full bottom-0 right-0 border-2 border-white"></span>
                                    </div>
                                    <div class="ml-3">
                                        <span class="font-semibold tracking-tight text-xs">John Doe</span>
                                        <span class="text-xs leading-none opacity-50"> reacted to your comment:</span>
                                        <p class="text-xs leading-4 pt-2 italic opacity-70">"This is the comment..."</p>
                                        <span class="text-[10px] text-blue-500 font-medium leading-4 opacity-75">a few seconds ago</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mx-auto max-w-7xl px-6 pt-6 ">
                        <div class="xl:grid xl:grid-cols-3 xl:gap-8">
                            <div>
                                <p class="text-sm leading-6 p-2 text-gray-600">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cumque, fugit.</p>

                                <div class="flex space-x-6 items-center">
                                    <Link :href="route('main')">
                                        <ApplicationLogo class="block fill-current  w-24" />
                                    </Link>
                                    <a href="#" class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">Facebook</span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">GitHub</span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="#" class="text-gray-400 hover:text-gray-500">
                                        <span class="sr-only">YouTube</span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
                                <div class="md:grid md:grid-cols-2 md:gap-8">
                                    <div>
                                        <h3 class="text-sm font-semibold leading-6 text-gray-900">Solutions</h3>
                                        <ul role="list" class="mt-6 space-y-4">
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Marketing</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Analytics</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Commerce</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="mt-10 md:mt-0">
                                        <h3 class="text-sm font-semibold leading-6 text-gray-900">Support</h3>
                                        <ul role="list" class="mt-6 space-y-4">
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Pricing</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Documentation</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Guides</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="md:grid md:grid-cols-2 md:gap-8">
                                    <div>
                                        <h3 class="text-sm font-semibold leading-6 text-gray-900">Company</h3>
                                        <ul role="list" class="mt-6 space-y-4">
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">About</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Blog</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Jobs</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="mt-10 md:mt-0">
                                        <h3 class="text-sm font-semibold leading-6 text-gray-900">Legal</h3>
                                        <ul role="list" class="mt-6 space-y-4">
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Claim</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Privacy</a>
                                            </li>
                                            <li>
                                                <a href="#" class="text-sm leading-6 text-gray-600 hover:text-gray-900">Terms</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 border-t border-gray-900/10 pt-8">
                            <p class="text-xs leading-5 text-gray-500">&copy; 2025 Зозулька, Inc. All rights reserved.</p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>
