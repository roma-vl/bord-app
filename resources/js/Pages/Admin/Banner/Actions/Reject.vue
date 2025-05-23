<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { defineEmits } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  bannerId: {
    type: Number,
    default: 0,
  },
});

const form = useForm({
  reject_reason: '',
});

const emit = defineEmits(['rejectCreated']);

const submit = () => {
  form.post(route('admin.banners.reject', { banner: props.bannerId }), {
    onSuccess: () => {
      emit('rejectCreated');
      form.reset();
    },
  });
};
</script>

<template>
  <Head title="Оголошення" />
  <div class="py-6">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
      <div class="overflow-hidden">
        <div class="px-4">
          <form @submit.prevent="submit">
            <div class="mb-4">
              <label class="block text-sm font-medium mb-2">Причина відмови публікації</label>
              <input
                v-model="form.reject_reason"
                type="text"
                class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              >
            </div>
            <InputError
              class="mt-2"
              :message="form.errors.reject_reason"
            />

            <button
              type="submit"
              class="mt-6 bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              Створити
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
