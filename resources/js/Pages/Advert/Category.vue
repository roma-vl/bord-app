<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    categories: Object,
    locations: Object,
    childCategories: Object
});

const generateCategoryLink = (index) => {
    let path = props.categories.slice(0, index + 1).map(c => c.slug).join("/");
    return route('main') + '/adverts/' + path;
};

const generateLocationLink = (index, slug = null) => {
    let path = props.locations.slice(0, index + 1).map(l => l.slug).join("/");
    return route('main') + '/adverts/'+ slug +'/' + path;
};

const generateChildCategoriesLink = (index, slug = null) => {
    let path = props.childCategories.slice(0, index + 1).map(c => c.slug).join("/");
    return route('main') + '/adverts/'+ slug +'/' + path;
};
console.log(props.locations, "locations");
console.log(props.categories, "categories");
console.log(props.childCategories, "childCategories");

</script>

<template>
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50">
                    <div class="bg-white rounded-lg shadow p-3">
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

                        {{console.log(locations, "locations.length")}}
                        <span v-if="locations.length" v-for="(location, index) in locations" :key="location.id">
                            <span  v-if="index !== locations.length - 1">
                                <span class="text-gray-400 mr-1">/</span>
                                <a class=" text-gray-400 underline hover:text-gray-700" :href="generateLocationLink(index, categories[categories.length -1].slug)">
                                    {{ categories[categories.length -1].name}} - {{ location.name }}</a>
                            </span>
                             <span v-if="index === locations.length - 1">
                                     <span class="text-gray-400 mr-1">/</span>
                                <span class="text-gray-700">{{ categories[categories.length -1].name}} - {{ location.name }} </span>
                            </span>
                        </span>

                        <hr class="my-4">
                        <div class=" pl-2" v-if="childCategories.length" v-for="(category, index) in childCategories" :key="category.id">
                            <a :href="generateChildCategoriesLink(index)">{{ category.name }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
