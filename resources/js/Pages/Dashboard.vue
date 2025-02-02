<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, usePage} from '@inertiajs/vue3';
import Breadcrumb from "@/Components/Breadcrumb.vue";
import FileUpload from "@/Components/FileUpload.vue";
import Modal from "@/Components/Modal.vue";
import {ref} from "vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {useI18n} from "vue-i18n";

const { t } = useI18n()
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

const isDialogVisible = ref(false);
const isCheck = ref(false);

const check = () => {
    isCheck.value = !isCheck.value;
};
const openDialog = () => {
    isDialogVisible.value = true;
};

const closeDialog = () => {
    isDialogVisible.value = false;
};

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ t('welcome', { name: $page.props.auth.user.name }) }}
            </h2>
        </template>

        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <Breadcrumb :breadcrumbs="breadcrumbData" />
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
                    <div class="p-4 text-gray-900">
                        You're logged in!
                    </div>

                    <FlashMessage v-if="flash" :flash="flash" />


                    <div>
                        <button @click="openDialog" class="bg-blue-500 text-white px-4 py-2 rounded">
                            Open Dialog
                        </button>

                        <Modal
                            :show="isDialogVisible"
                            maxWidth="5xl"
                            closeable
                            @close="closeDialog"
                        >
                            <template #default>
                                <div class="p-6">
                                    <FileUpload uploadUrl="https://example.com/upload" />
                                </div>
                            </template>
                        </Modal>
                    </div>

                    <div>
                        <div class="bg-gray-200 text-sm text-gray-500 leading-none border-2 border-gray-200 rounded-full inline-flex">
                            <button @click="check" class="inline-flex items-center transition-colors duration-300 ease-in focus:outline-none
                             hover:text-blue-400 focus:text-blue-400 rounded-full px-4 py-2  " :class="!isCheck?  'active': ''" id="grid">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="fill-current w-4 h-4 mr-2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                                <span>Grid</span>
                            </button>
                            <button @click="check" class="inline-flex items-center transition-colors duration-300 ease-in focus:outline-none
                             hover:text-blue-400 focus:text-blue-400 rounded-full px-4 py-2" :class="isCheck?  'active': ''" id="list">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="fill-current w-4 h-4 mr-2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                                <span>List</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="flex items-center gap-2">
                            <input id="switch-link" type="checkbox"
                                class="appearance-none relative inline-block rounded-full w-12 h-6 cursor-pointer before:inline-block
                                 before:absolute before:top-0 before:left-0 before:w-full before:h-full before:rounded-full
                                 before:bg-stone-200 before:transition-colors before:duration-200 before:ease-in after:absolute
                                 after:top-2/4 after:left-0 after:-translate-y-2/4 after:w-6 after:h-6 after:border after:border-stone-200
                                  after:bg-white after:rounded-full checked:after:translate-x-full after:transition-all after:duration-200
                                  after:ease-in disabled:opacity-50 disabled:cursor-not-allowed dark:after:bg-white
                                  checked:before:bg-stone-800 checked:after:border-stone-800"
                            />
                            <label for="switch-link" class="font-sans antialiased text-base cursor-pointer text-stone-600">
                                I agree with the
                                <a href="#" class="font-sans antialiased text-base text-stone-500 inline"> terms and conditions</a>
                            </label>
                        </div>
                    </div>

                    <div>
                        <p>Lorem ipsum dolor sit amet. </p>
                        <p>Lorem ipsum dolor sit amet. </p>
                        <p>Lorem ipsum dolor sit amet. </p>
                        <p>Lorem ipsum dolor sit amet. </p>
                        <p>Lorem ipsum dolor sit amet. </p>
                        <p>Lorem ipsum dolor sit amet. </p>
                        <p>Lorem ipsum dolor sit amet. </p>
                        <p>Lorem ipsum dolor sit amet. </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<style>
/*@apply bg-white text-blue-400 rounded-full;*/
.active {background: white; border-radius: 9999px; color: #63b3ed;}
</style>
