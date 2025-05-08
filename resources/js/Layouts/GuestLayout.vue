<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import Locale from "@/Layouts/Partials/Locale.vue";
</script>

<template>
    <div class=" min-h-screen bg-gray-100 pt-4 sm:justify-center sm:pt-0">
        <div class="flex items-end flex-col p-2">
            <Locale />
        </div>
        <div class="flex flex-col items-center">
            <div>
                <Link href="/">
                    <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />
                </Link>
            </div>
            {{$page.props.locale}}
            <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
                <slot />
            </div>
        </div>
    </div>
</template>
