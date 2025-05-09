
<script setup>
import { ref } from 'vue'
import {Head, router, usePage} from '@inertiajs/vue3'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import Pagination from "@/Components/Pagination.vue";
import CategoryAdvert from "@/Components/Advert/Category/CategoryAdvert.vue";
const adverts = usePage().props.adverts;
// const adverts = [];
// // adverts.data = results;
// const pagination = usePage().props.pagination;
console.log(adverts, 'pagination')
const query = ref(usePage().props.query)

function submit() {
    router.get('/search', { q: query.value })
}
</script>

<template>
    <Head title="Категорії оголошень " />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white">

                        <div>
                            <form @submit.prevent="submit">
                                <input v-model="query" placeholder="Що шукаєш?" />
                                <button type="submit">Пошук</button>
                            </form>

                            Пошук за запитом : {{query}}
                            <div v-if="adverts.data.length">
                                <CategoryAdvert
                                    :adverts="adverts"
                                />

                            </div>
                            <div v-else>
                                Нічого не знайдено.
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
