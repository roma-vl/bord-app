<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {computed} from "vue";

defineProps({ groups: Object })

const flash = computed(() => usePage().props.flash);
</script>

<template>
    <Head title="Roles" />
    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <FlashMessage :flash="flash" />
                    <div class=" px-4">
                        <div class="grid grid-cols-2 gap-4 items-start mb-3">
                            <h2 class="text-xl font-bold mb-4">Налаштування</h2>
                        </div>
                    </div>
                    <div>
                        <ul>
                            <li v-for="(className, group) in groups" :key="group">
                                <Link :href="route('admin.settings.edit', group)">{{ group }}</Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
