<script setup>
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
const props = defineProps({
  page: {
    type: Object,
    default: () => ({}),
  },
});
const menuOpen = ref(false);

// Функція для декодування HTML-entities
function decodeHtml(html) {
  const txt = document.createElement('textarea');
  txt.innerHTML = html;
  return txt.value;
}

const decodedContent = computed(() => decodeHtml(props.page.content));
</script>

<template>
  <Head :title="$t('home.page_title')" />
  <AuthenticatedLayout>
    <header class="bg-white border-b shadow-sm">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex-shrink-0">
            <a
              href="/"
              class="text-xl font-bold text-indigo-600"
            >{{ $t('home.logo') }}</a>
          </div>

          <!-- Desktop Menu -->
          <nav class="hidden md:flex space-x-6">
            <a
              href="/"
              class="text-gray-700 hover:text-indigo-600 font-medium"
            >{{
              $t('nav.home')
            }}</a>
            <a
              href="/ads"
              class="text-gray-700 hover:text-indigo-600 font-medium"
            >{{
              $t('nav.ads')
            }}</a>
            <a
              href="/about"
              class="text-gray-700 hover:text-indigo-600 font-medium"
            >{{
              $t('nav.about')
            }}</a>
            <a
              href="/contact"
              class="text-gray-700 hover:text-indigo-600 font-medium"
            >{{
              $t('nav.contact')
            }}</a>
          </nav>

          <!-- Mobile menu button -->
          <div class="md:hidden">
            <button
              class="text-gray-700 focus:outline-none"
              @click="menuOpen = !menuOpen"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  :d="menuOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div
        v-if="menuOpen"
        class="md:hidden px-4 pb-4 space-y-2"
      >
        <a
          href="/"
          class="block text-gray-700 hover:text-indigo-600 font-medium"
        >{{
          $t('nav.home')
        }}</a>
        <a
          href="/ads"
          class="block text-gray-700 hover:text-indigo-600 font-medium"
        >{{
          $t('nav.ads')
        }}</a>
        <a
          href="/about"
          class="block text-gray-700 hover:text-indigo-600 font-medium"
        >{{
          $t('nav.about')
        }}</a>
        <a
          href="/contact"
          class="block text-gray-700 hover:text-indigo-600 font-medium"
        >{{
          $t('nav.contact')
        }}</a>
      </div>
    </header>

    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- eslint-disable-next-line vue/no-v-html -->
        <div v-html="decodedContent" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
