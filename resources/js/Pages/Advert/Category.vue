<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    category: Object,
    subCategory: Object,
    subCategories: Object,
    city: Object,
});
console.log(props, "props");

</script>

<template>
    <AuthenticatedLayout>
        <div class="py-2">
            <div class=" mx-auto max-w-7xl sm:px-6 lg:px-8 p-6  ">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50 ">
                    <div class="bg-white rounded-lg shadow p-3">
                        {{props.category?.name}}
                        <hr>
                        {{props.subCategory?.name}}
                        <div v-for="category in props.subCategories">
                            --{{category.name}}
                        </div>
                        <hr>
                        {{props.city?.name}}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
