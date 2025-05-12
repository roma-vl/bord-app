<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, usePage} from "@inertiajs/vue3";
import ProfileMenu from "@/Pages/Account/Profile/Partials/ProfileMenu.vue";
import {computed} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import AdvertList from "@/Components/Advert/Banner/AdvertList.vue";

const banners = usePage().props.banners;
console.log(banners, 'banners')

const routes = {
        edit: "account.banners.edit",
        remove: "account.banners.destroy",
        show: "account.banners.show",
};

const flash = computed(() => usePage().props.flash);

</script>

<template>
    <Head title="Оголошення" />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <FlashMessage :flash="flash" />
                    <ProfileMenu :activeTab="'account.banners.index'" />
                    <div class=" px-4">
                        <div class="grid grid-cols-2 gap-4 items-start mb-3">
                            <h2 class="text-xl font-bold mb-4">Мої оголошення</h2>

                            <a :href="route('account.banners.create')"
                               class="justify-self-end w-48 h-12 flex items-center justify-center text-md font-medium text-violet-400 hover:text-violet-700 ">
                                + Додати Оголошення
                            </a>
                        </div>

                        <AdvertList :banners="banners" :routes="routes"></AdvertList>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
