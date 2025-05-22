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
  <Head title="Додати Тікет" />
  <div class="overflow-hidden bg-white shadow sm:rounded-lg p-6">
    <div class="px-4">
      <h1 class="text-2xl font-bold mb-6">Додати Тікет</h1>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2"> Тема </label>
          <input
            v-model="form.subject"
            type="text"
            class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <InputError class="mt-2" :message="form.errors.subject" />
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2"> Повідомлення </label>
          <textarea
            v-model="form.content"
            type="text"
            class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          />
        </div>
        <InputError class="mt-2" :message="form.errors.content" />
        <button
          type="submit"
          class="mt-6 bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Створити
        </button>
      </form>
    </div>
  </div>
</template>
