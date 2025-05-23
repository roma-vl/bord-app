<script setup>
const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});

const imageUrl = '/storage/images/errors/403.svg';
</script>

<template>
  <div class="max-w-md mx-auto mt-8">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">
      {{ data.status }} - {{ data.statusText }}
    </h2>

    <img
      :src="imageUrl"
      alt="Logo"
    >
  </div>
</template>

<style scoped></style>
