<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

const props = defineProps({
    categories: Object,
    locations: Object,
    childCategories: Object
});
const generateChildCategoriesLink = (slug) => {
    let path = props.categories.slice(0, props.categories.length).map(c => c.slug).join("/");
    return route('main') + '/adverts/' + path + '/' + slug;
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50">
                    <div v-if="categories || locations" class="bg-white rounded-lg shadow p-3">
                        <Breadcrumbs :categories="categories" :locations="locations" />

                        <hr class="my-4">

                        <div class=" pl-2" v-if="childCategories.length" v-for="(category, index) in childCategories" :key="category.id">
                            <a :href="generateChildCategoriesLink(category.slug)">{{ category.name }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
