<script setup>
import { computed } from 'vue';

const props = defineProps({
    categories: Array,
    locations: Array,
});

const generateCategoryLink = (index) => {
    let path = props.categories.slice(0, index + 1).map(c => c.slug).join("/");
    return route('main') + '/adverts/' + path;
};

const generateLocationLink = (index, slug = null) => {
    let path = props.locations.slice(0, index + 1).map(l => l.slug).join("/");
    if (slug) { path = slug + '/' + path; }
    return route('main') + '/adverts/' + path;
};


</script>

<template>
    <div v-if="categories.length || locations.length" class="bg-white rounded-lg shadow p-3">
        <a class=" text-gray-400 underline hover:text-gray-700 mr-1" :href="route('main')">Головна</a>
        <span v-if="categories.length" v-for="(category, index) in categories" :key="category.id">
            <span v-if="index !== categories.length - 1">
                <span class="text-gray-400 mr-1">/</span>
            <a class=" text-gray-400 underline hover:text-gray-700 mr-1"  :href="generateCategoryLink(index)">{{ category.name }}</a>
            </span>
            <span class="text-gray-700" v-if="locations.length === 0 && index === categories.length - 1">
                <span class="text-gray-400 mr-1">/</span>
                <span >{{ category.name }}</span>
            </span>
        </span>

        <span v-if="locations.length" v-for="(location, index) in locations" :key="location.id">
            <span v-if="index !== locations.length - 1">
                <span class="text-gray-400 mr-1">/</span>
                <a v-if="categories.length" class=" text-gray-400 underline hover:text-gray-700 mr-1" :href="generateLocationLink(index, categories[categories.length -1].slug)">
                    {{ categories[categories.length -1].name}} - {{ location.name }}</a>
                <a v-if="!categories.length" class=" text-gray-400 underline hover:text-gray-700 mr-1" :href="generateLocationLink(index)">
                    {{ location.name }}</a>
            </span>

             <span v-if="categories.length && index === locations.length - 1">
                 <span class="text-gray-400 mr-1">/</span>
                 <span class="text-gray-700">{{ categories[categories.length -1].name}} - {{ location.name }} </span>
            </span>
             <span v-if="!categories.length && index === locations.length - 1">
                 <span class="text-gray-400 mr-1">/</span>
                 <span class="text-gray-700">{{ location.name }} </span>
            </span>
        </span>

    </div>
</template>
