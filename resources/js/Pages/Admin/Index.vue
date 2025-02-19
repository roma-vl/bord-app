<script setup>
import {Head, usePage} from "@inertiajs/vue3";
import FlashMessage from "@/Components/FlashMessage.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const flash = usePage().props.flash;

</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">

                    <FlashMessage  :flash="flash" />
                    <div class="container mx-auto p-4 min-h-[400px]">
                        Content</div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>

</style>
