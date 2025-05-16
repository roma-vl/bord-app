<script setup>
import {computed, defineProps, ref} from "vue";
import {Head, usePage, router, Link} from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import Modal from "@/Components/Modal.vue";
import Create from "@/Pages/Admin/Advert/Attribute/Create.vue";
import Edit from "@/Pages/Admin/Advert/Attribute/Edit.vue";

const props = defineProps({
    page: Object,
});
const flash = usePage().props.flash;
function decodeHtml(html) {
    const txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}


const decodedContent = computed(() => decodeHtml(props.page.content))
</script>

<template>
    <Head title="Категорія - Атрибути" />
    <AdminLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <FlashMessage :flash="flash"/>
                <div class="mb-2 flex justify-end">
                    <Link :href="route('admin.pages.index')" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"> < Pages </Link>
                </div>

                <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px]">
                    <h1 class="text-2xl font-bold">Категорія: {{ props.page.title }}</h1>
                    <p class="text-gray-600">Slug: {{ props.page.slug }}</p>

                    <div v-html="decodedContent" ></div>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>
