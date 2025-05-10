<script setup>
import AdvertGridItem from "@/Components/Advert/Category/AdvertGridItem.vue";

const props = defineProps({
    adverts: Array
});
</script>

<template>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <AdvertGridItem v-for="advert in adverts" :key="advert.id" :advert="advert"/>
    </div>
</template>

