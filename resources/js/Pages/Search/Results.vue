
<script setup>
import {ref} from 'vue'
import {Head, router, usePage} from '@inertiajs/vue3'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CategoryAdvert from "@/Components/Advert/Category/CategoryAdvert.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import ChildCategories from "@/Components/Advert/Category/ChildCategories.vue";
const adverts = usePage().props.adverts;
const queryFilter = ref(usePage().props.query || {});
const props = defineProps({
    categories: Object,
    locations: Object,
    childCategories: Object,
    regionsCounts: Object,
    categoriesCounts:Object,
    attributes: Object
});



function submit() {
    const filteredQuery = { ...queryFilter.value };

    Object.keys(filteredQuery).forEach((key) => {
        const val = filteredQuery[key];
        if (val === null || val === '' || typeof val === 'undefined') {
            delete filteredQuery[key];
        }
    });

    router.get('/search', filteredQuery);
}

</script>

<template>
    <Head title="Категорії оголошень " />
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white">
                        <form @submit.prevent="submit" class="mb-6">
                            <div class="flex gap-4 items-center mb-4">
                                <input v-model="queryFilter.query" placeholder="Що шукаєш?"
                                    class="flex-1 px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                                    Пошук
                                </button>
                            </div>
                        </form>

                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Фільтри</h2>

                        <div v-if="props.attributes.length"
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-4">
                            <div v-for="(attribute, index) in props.attributes" :key="index"
                                class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ attribute.name }}
                                </label>

                                <select v-if="attribute.variants && attribute.variants.length"
                                    v-model="queryFilter[attribute.id]"
                                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Обрати</option>
                                    <option v-for="(variant, i) in attribute.variants" :key="i" :value="variant">
                                        {{ variant }}
                                    </option>
                                </select>

                                <div v-else-if="attribute.type === 'integer'" class="flex gap-2">
                                    <input type="number" :placeholder="`Від`" v-model="queryFilter[`${attribute.id}_from`]"
                                        class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                    <input type="number" :placeholder="`До`" v-model="queryFilter[`${attribute.id}_to`]"
                                        class="w-1/2 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                                </div>

                                <input v-else-if="attribute.type === 'string'" type="text" v-model="queryFilter[attribute.id]"
                                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                            </div>
                        </div>


                        <Breadcrumbs class="mb-6" :categories="props.categories" :locations="props.locations"/>

                        <ChildCategories
                            :childCategories="props.childCategories"
                            :categoriesCounts="props.categoriesCounts"
                            :categories="props.categories"/>

                        Пошук за запитом : {{query}}
                        <div v-if="adverts.data.length">
                            <CategoryAdvert :adverts="adverts"/>
                        </div>
                        <div v-else> Нічого не знайдено. </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
