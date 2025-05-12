<script setup>

import {usePage} from "@inertiajs/vue3";
import {getFullPathForImage} from "@/helpers.js";

const banner = usePage().props.banner;
console.log(banner, 'banner')
</script>

<template>
    <a :href="route('banner.click', banner.id)" target="_blank">
        <img :width="banner.width" :height="banner.height" :src="getFullPathForImage(banner.file)" alt="">
    </a>
</template>

<style scoped>

</style>
