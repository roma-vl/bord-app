<script setup>

import Breadcrumb from "@/Components/Breadcrumb.vue";
import {Head, usePage} from "@inertiajs/vue3";
import FlashMessage from "@/Components/FlashMessage.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const flash = usePage().props.flash;
const breadcrumbData = [
    {

        url: "http://localhost/",
        icon: "svg",
    },
    {
        title: "Profile",
        url: "http://localhost/dashboard/profile",
        current: true,
    },
    {
        title: "Breadcrumb without URL",
    },
];

</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Адмінка
            </h2>
        </template>

        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Breadcrumb :breadcrumbs="breadcrumbData" />
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">

                    <FlashMessage  :flash="flash" />
                    Content
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>
