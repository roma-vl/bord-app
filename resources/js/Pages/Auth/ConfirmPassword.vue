<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  password: '',
});

const submit = () => {
  form.post(route('password.confirm'), {
    onFinish: () => form.reset(),
  });
};
</script>

<template>
  <GuestLayout>
    <Head :title="$t('confirm.password')" />

    <div class="mb-4 text-sm text-gray-600">
      {{ $t('text.confirm.password') }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel
          for="password"
          :value="$t('password')"
        />
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="mt-1 block w-full"
          required
          autocomplete="current-password"
          autofocus
        />
        <InputError
          class="mt-2"
          :message="form.errors.password"
        />
      </div>

      <div class="mt-4 flex justify-end">
        <PrimaryButton
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          {{ $t('confirm.password') }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
