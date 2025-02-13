<script setup>
import { defineProps } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import Breadcrumb from "@/Components/Breadcrumb.vue";

defineProps({
    user: Object
});

const breadcrumbData = [
    { url: route("main"), icon: "svg" },
    { title: "Profile" },
];
</script>

<template>
    <Head title="Profile" />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <Breadcrumb :breadcrumbs="breadcrumbData" class="p-3 pl-6 pb-0"/>

                    <div class="flex min-h-[400px] p-3 px-6">
                        <div class="w-1/3 bg-white flex items-center justify-center shadow-md rounded overflow-hidden">
                            <div class="w-48 h-48 bg-gray-400 rounded-full"></div>
                        </div>

                        <!-- Деталі користувача -->
                        <div class="w-2/3 p-6 bg-white shadow-md rounded overflow-hidden flex flex-col m-0 ml-4">
                            <div class="grid grid-cols-2 gap-4">
                            <h2 class=" text-2xl font-bold mb-4">Профіль користувача</h2>
                            <a :href="route('account.profile.edit')" class="flex justify-end font-bold">ред.</a>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-gray-600"><strong>ID:</strong> {{ user.id }}</p>
                                    <p class="text-gray-600"><strong>Ім'я:</strong> {{ user.first_name }}</p>
                                    <p class="text-gray-600"><strong>Прізвище:</strong> {{ user.last_name }}</p>
                                    <p class="text-gray-600"><strong>Email:</strong> {{ user.email }}</p>
                                    <p class="text-gray-600"><strong>Локаль:</strong> {{ user.locale }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600"><strong>Створено:</strong> {{ user.created_at }}</p>
                                    <p class="text-gray-600"><strong>Оновлено:</strong> {{ user.updated_at }}</p>
                                    <p class="text-gray-600"><strong>Верифіковано:</strong>
                                        {{ user.email_verified_at ? 'Так' : 'Ні' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
