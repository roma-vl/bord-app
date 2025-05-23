<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
  status: {
    type: String,
    default: '',
  },
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <GuestLayout>
    <Head :title="$t('forgot.password')" />

    <div class="mb-4 text-sm text-gray-600">
      {{ $t('text.forgot.password') }}
    </div>

    <div
      v-if="status"
      class="mb-4 text-sm font-medium text-green-600"
    >
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel
          for="email"
          :value="$t('email')"
        />

        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full"
          required
          autofocus
          autocomplete="username"
        />

        <InputError
          class="mt-2"
          :message="form.errors.email"
        />
      </div>

      <div class="mt-4 flex items-center justify-end">
        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          {{ $t('email.password.reset') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
