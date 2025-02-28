<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    categories: Object,
    locations: Object,
    city: Object,
    subCategories: Array
});

const generateCategoryLink = (index) => {
    let path = props.categories.slice(0, index + 1).map(c => c.slug).join("/");
    return route('main') + '/adverts/' + path;
};

const generateLocationLink = (index) => {
    let path = props.locations.slice(0, index + 1).map(l => l.slug).join("/");
    return route('main') + '/adverts/' + path;
};
console.log(props.locations, "props");
console.log(props.categories, "props");

</script>

<template>
    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50">
                    <div class="bg-white rounded-lg shadow p-3">
                        <a :href="route('main')">Головна</a>
                        <span v-if="categories.length" v-for="(category, index) in categories" :key="category.id">
                            >
                            <a :href="generateCategoryLink(index)">{{ category.name }}</a>
                        </span>

                        <span v-if="locations" v-for="(location, index) in locations" :key="location.id">
                            >
                            <a :href="generateLocationLink(index)">{{ location.name }}</a>
                        </span>
                        <span v-if="city"> > {{ city.name }}</span>
                        <div v-for="sub in subCategories" :key="sub.id">
                            --{{ sub.name }}
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
