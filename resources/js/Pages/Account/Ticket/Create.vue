<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

const form = useForm({
  subject: '',
  content: '',
});

const submit = () => {
  form.post(route('account.tickets.store'), {
    forceFormData: true,
    onSuccess: () => {
      console.log('Оголошення створено');
      form.reset();
    },
  });
};
</script>

<template>
  <Head :title="$t('add.ticket')" />
  <div class="overflow-hidden bg-white shadow sm:rounded-lg p-6 dark:bg-gray-700">
    <div class="px-4">
      <h1 class="text-2xl font-bold mb-6 dark:text-gray-200">
        {{ $t('add.ticket') }}
      </h1>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2 dark:text-gray-200">
            {{ $t('ticket.subject') }}
          </label>
          <input
            v-model="form.subject"
            type="text"
            class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
          >
        </div>
        <InputError
          class="mt-2"
          :message="form.errors.subject"
        />
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2 dark:text-gray-200">
            {{ $t('ticket.message') }}
          </label>
          <textarea
            v-model="form.content"
            class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-800 dark:border-gray-900"
          />
        </div>
        <InputError
          class="mt-2"
          :message="form.errors.content"
        />
        <button
          type="submit"
          class="mt-6 bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          {{ $t('ticket.create') }}
        </button>
      </form>
    </div>
  </div>
</template>
