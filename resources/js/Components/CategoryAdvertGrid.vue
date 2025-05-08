<script setup>
import CategoryAdvertGridItem from "@/Components/CategoryAdvertGridItem.vue";

const props = defineProps({
    adverts: Array
});
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <CategoryAdvertGridItem
            v-for="advert in adverts"
            :key="advert.id"
            :advert="advert"
        />
    </div>
</template>

