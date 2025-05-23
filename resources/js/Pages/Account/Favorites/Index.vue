<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';
import { computed } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import AdvertList from '@/Components/AdvertList.vue';

const favoriteAdverts = computed(() => usePage().props.favoriteAdverts);
const flash = computed(() => usePage().props.flash);

const routes = {
  remove: 'account.favorites.remove',
};
</script>

<template>
  <Head title="Оголошення" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
          <FlashMessage :flash="flash" />
          <ProfileMenu :active-tab="'account.favorites.index'" />
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4">
                Збережені оголошення
              </h2>
            </div>

            <AdvertList
              :adverts="favoriteAdverts"
              :routes="routes"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
