<script setup>
import { getFullPathForImage } from '@/helpers.js';

const props = defineProps({
  banner: {
    type: Object,
    default: () => ({}),
  },
});
</script>

<template>
  <a
    v-if="banner"
    :href="route('banner.click', banner.id)"
    target="_blank"
  >
    <img
      :width="banner.width"
      :height="banner.height"
      :src="getFullPathForImage(banner.file)"
      alt=""
    >
  </a>
</template>

<style scoped></style>
