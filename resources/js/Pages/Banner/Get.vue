<script setup>

import {usePage} from "@inertiajs/vue3";
import {getFullPathForImage} from "@/helpers.js";

const props = defineProps({
    banner: Object
});
console.log(props.banner, 'banner')
</script>

<template>
    <a v-if="banner" :href="route('banner.click', banner.id)" target="_blank">
        <img :width="banner.width" :height="banner.height" :src="getFullPathForImage(banner.file)" alt="">
    </a>
</template>

<style scoped>

</style>
