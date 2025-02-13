<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import {Head, usePage} from '@inertiajs/vue3';
import Breadcrumb from "@/Components/Breadcrumb.vue";
import UpdateProfilePhoneForm from "@/Pages/Account/Profile/Partials/UpdateProfilePhoneForm.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {computed} from "vue";

const breadcrumbData = [
    { url: route("main"), icon: "svg" },
    { title: "Profile", url: route("account.profile.index")},
    { title: "Edit" },
];
const flash = computed(() => usePage().props.flash);
const user = usePage().props.auth.user;
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <FlashMessage :flash="flash" />
                    <Breadcrumb :breadcrumbs="breadcrumbData" class="p-3 pl-8 "/>

                    <div class="mx-auto max-w-7xl">
                        <div class="bg-white p-4  sm:rounded-lg sm:p-8">
                            <UpdateProfileInformationForm class="max-w-xl"/>
                        </div>

                        <div class="bg-white p-4  sm:rounded-lg sm:p-8">
                            <UpdateProfilePhoneForm class="max-w-xl"/>
                        </div>

                        <div class="bg-white p-4 sm:rounded-lg sm:p-8">
                            <UpdatePasswordForm class="max-w-xl" />
                        </div>

                        <div class="bg-white p-4 sm:rounded-lg sm:p-8">
                            <DeleteUserForm class="max-w-xl" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
