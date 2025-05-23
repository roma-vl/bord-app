<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';
import { computed } from 'vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import AdvertList from '@/Components/AdvertList.vue';

const adverts = usePage().props.adverts;
const flash = computed(() => usePage().props.flash);

const routes = {
  edit: 'account.adverts.edit',
  remove: 'account.adverts.destroy',
  show: 'adverts.show',
};
</script>

<template>
  <Head title="Оголошення" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
          <FlashMessage :flash="flash" />
          <ProfileMenu :active-tab="'account.adverts.index'" />
          <div class="px-4">
            <div class="grid grid-cols-2 gap-4 items-start mb-3">
              <h2 class="text-xl font-bold mb-4">
                Мої оголошення
              </h2>

              <a
                :href="route('account.adverts.create')"
                class="justify-self-end w-48 h-12 flex items-center justify-center text-md font-medium text-violet-500 hover:text-violet-600 transition-all duration-300 ease-in-out relative before:absolute before:bottom-0 before:left-0 before:w-full before:h-[3px] before:bg-violet-600 before:origin-bottom-right before:scale-x-0 before:transition-transform before:duration-300 before:ease-in-out after:absolute after:top-0 after:left-0 after:w-full after:h-[2px] after:bg-violet-600 after:origin-top-left after:scale-x-0 after:transition-transform after:duration-300 after:ease-in-out hover:before:scale-x-100 hover:before:origin-bottom-left hover:after:scale-x-100 hover:after:origin-top-right group"
              >
                + Додати Оголошення
                <span
                  class="absolute left-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-top"
                />
                <span
                  class="absolute right-0 top-0 h-full w-[3px] bg-violet-600 scale-y-0 transition-transform duration-300 ease-in-out group-hover:scale-y-100 origin-bottom"
                />
              </a>
            </div>
            <AdvertList
              :adverts="adverts"
              :routes="routes"
            />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
