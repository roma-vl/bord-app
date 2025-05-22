<script setup>
import AdvertListItem from '@/Components/Advert/Category/AdvertListItem.vue';

const props = defineProps({
  adverts: Array,
});
</script>

<template>
  <div class="space-y-4">
    <AdvertListItem v-for="advert in adverts" :key="advert.id" :advert="advert" />
  </div>
</template>
