<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    childCategories: {
        type: Array,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
});

const generateChildCategoriesLink = (slug) => {
    const path = props.categories.map(c => c.slug).join("/");
    return `/adverts/${path}/${slug}`;
};
</script>

<template>
    <div v-if="childCategories.length" class="space-y-2 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            Підкатегорії
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <Link
                v-for="category in childCategories"
                :key="category.id"
                :href="generateChildCategoriesLink(category.slug)"
                class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200"
            >
                <span class="text-blue-600 hover:text-blue-800">
                    {{ category.name }}
                </span>
            </Link>
        </div>
    </div>
</template>
