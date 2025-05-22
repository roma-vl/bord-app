<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  visible: Boolean,
});
const emit = defineEmits(['update:visible', 'submit']);
const message = ref('');
const rating = ref(0);
const hoverRating = ref(0);

const setRating = (value) => {
  rating.value = value;
};

const setHoverRating = (value) => {
  hoverRating.value = value;
};

const clearHoverRating = () => {
  hoverRating.value = 0;
};

const close = () => {
  emit('update:visible', false);
};

const handleClickOutside = (e) => {
  if (!e.target.closest('.custom-container')) {
    emit('update:visible', false);
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});
onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

const handleRateNow = () => {
  emit('submit', {
    message: message.value,
    rating: rating.value,
  });
  close();
};
</script>

<template>
  <div
    v-if="props.visible"
    class="w-full h-full bg-white absolute left-0 top-0 custom-bg flex items-center justify-center"
  >
    <div class="py-3 sm:max-w-xl sm:mx-auto z-50 custom-container">
      <div class="bg-white min-w-1xl flex flex-col rounded-xl shadow-lg">
        <div class="px-12 py-5">
          <h2 class="text-gray-800 text-3xl font-semibold">Your opinion matters to us!</h2>
        </div>
        <div class="bg-gray-200 w-full flex flex-col items-center">
          <div class="flex flex-col items-center py-6 space-y-3">
            <span class="text-lg text-gray-800">How was quality of the call?</span>
            <div class="flex space-x-3">
              <template v-for="i in 5" :key="i">
                <svg
                  @click="setRating(i)"
                  @mouseover="setHoverRating(i)"
                  @mouseleave="clearHoverRating"
                  :class="[
                    'w-12 h-12 transition-all duration-300 cursor-pointer',
                    hoverRating >= i || (!hoverRating && rating >= i)
                      ? 'text-yellow-500 scale-110'
                      : 'text-gray-400',
                  ]"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                  />
                </svg>
              </template>
            </div>
          </div>
          <div class="w-3/4 flex flex-col">
            <textarea
              v-model="message"
              rows="3"
              class="p-4 text-gray-500 rounded-xl resize-none"
              placeholder="Leave a message, if you want"
            ></textarea>
            <button
              @click="handleRateNow"
              class="py-3 my-8 text-lg bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl text-white"
            >
              Rate now
            </button>
          </div>
        </div>
        <div class="h-20 flex items-center justify-center">
          <a href="#" @click.prevent="close" class="text-gray-600">Maybe later</a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.custom-bg {
  background: rgba(0, 0, 0, 0.3);
  backdrop-filter: blur(3px);
}
</style>
