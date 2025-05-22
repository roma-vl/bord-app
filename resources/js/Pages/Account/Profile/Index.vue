<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProfileMenu from '@/Pages/Account/Profile/Partials/ProfileMenu.vue';

const user = usePage().props.auth.user;
</script>

<template>
  <Head title="Profile" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3">
          <ProfileMenu :activeTab="'account.profile.index'" />

          <div class="flex min-h-[400px] p-3 px-6">
            <div
              class="w-1/3 bg-white flex items-center justify-center shadow-md rounded overflow-hidden"
            >
              <img
                v-if="user.avatar_url"
                class="w-60 h-60 bg-gray-400 rounded-lg"
                :src="user.avatar_url"
              />
              <div v-else class="w-48 h-48 bg-gray-400 rounded-full"></div>
            </div>

            <div
              class="w-2/3 p-6 bg-white shadow-md rounded overflow-hidden flex flex-col m-0 ml-4"
            >
              <div class="grid grid-cols-2 gap-4">
                <h2 class="text-2xl font-bold mb-4">Профіль користувача</h2>
                <a :href="route('account.profile.settings')" class="flex justify-end font-bold"
                  >ред.</a
                >
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-gray-600"><strong>ID:</strong> {{ user.id }}</p>
                  <p class="text-gray-600"><strong>Ім'я:</strong> {{ user.first_name }}</p>
                  <p class="text-gray-600"><strong>Прізвище:</strong> {{ user.last_name }}</p>
                  <p class="text-gray-600"><strong>Email:</strong> {{ user.email }}</p>
                  <p class="text-gray-600"><strong>Локаль:</strong> {{ user.locale }}</p>
                  <p class="text-gray-600"><strong>Телефон:</strong> {{ user.phone }}</p>
                  <PrimaryButton v-if="Number(user.phone_verified) === 0">
                    <a :href="route('account.profile.phone.request')">Підтвердити</a>
                  </PrimaryButton>
                </div>
                <div>
                  <p class="text-gray-600"><strong>Створено:</strong> {{ user.created_at }}</p>
                  <p class="text-gray-600"><strong>Оновлено:</strong> {{ user.updated_at }}</p>
                  <p class="text-gray-600">
                    <strong>Верифіковано:</strong>
                    {{ user.email_verified_at ? 'Так' : 'Ні' }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
