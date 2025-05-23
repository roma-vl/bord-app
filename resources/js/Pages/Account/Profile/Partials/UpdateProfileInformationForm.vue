<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
  mustVerifyEmail: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
    default: '',
  },
});

const user = usePage().props.auth.user;

const isEmailVerified = computed(() => {
  return new Date(user.email_verified_at).getTime() > 0;
});

const form = useForm({
  first_name: user.first_name,
  name: user.name,
  last_name: user.last_name,
  email: user.email,
});
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">
        Profile Information
      </h2>

      <p class="mt-1 text-sm text-gray-600">
        Update your account's profile information and email address.
      </p>
    </header>

    <form
      class="mt-6 space-y-6"
      @submit.prevent="form.patch(route('account.profile.update'))"
    >
      <div>
        <InputLabel
          for="first_name"
          value="First Name"
        />

        <TextInput
          id="first_name"
          v-model="form.first_name"
          type="text"
          class="mt-1 block w-full"
          required
          autocomplete="first_name"
        />

        <InputError
          class="mt-2"
          :message="form.errors.first_name"
        />
      </div>
      <div>
        <InputLabel
          for="name"
          value="Name"
        />

        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="mt-1 block w-full"
          required
          autocomplete="name"
        />

        <InputError
          class="mt-2"
          :message="form.errors.name"
        />
      </div>
      <div>
        <InputLabel
          for="last_name"
          value="Last Name"
        />

        <TextInput
          id="last_name"
          v-model="form.last_name"
          type="text"
          class="mt-1 block w-full"
          autocomplete="last_name"
        />

        <InputError
          class="mt-2"
          :message="form.errors.last_name"
        />
      </div>

      <div>
        <InputLabel
          for="email"
          value="Email"
        />

        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          class="mt-1 block w-full"
          required
          autocomplete="username"
        />
        <div
          v-if="!isEmailVerified"
          class="text-gray-400 flex justify-end"
        >
          Not confirmed
        </div>
        <InputError
          class="mt-2"
          :message="form.errors.email"
        />
      </div>

      <div v-if="mustVerifyEmail && user.email_verified_at === null">
        <p class="mt-2 text-sm text-gray-800">
          Your email address is unverified.
          <Link
            :href="route('verification.send')"
            method="post"
            as="button"
            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
          >
            Click here to re-send the verification email.
          </Link>
        </p>

        <div
          v-show="status === 'verification-link-sent'"
          class="mt-2 text-sm font-medium text-green-600"
        >
          A new verification link has been sent to your email address.
        </div>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">
          Save
        </PrimaryButton>

        <Transition
          enter-active-class="transition ease-in-out"
          enter-from-class="opacity-0"
          leave-active-class="transition ease-in-out"
          leave-to-class="opacity-0"
        >
          <p
            v-if="form.recentlySuccessful"
            class="text-sm text-gray-600"
          >
            Saved.
          </p>
        </Transition>
      </div>
    </form>

    <div class="flex flex-col items-center">
      <div class="flex items-center gap-2">
        <input
          id="switch-link"
          type="checkbox"
          class="appearance-none relative inline-block rounded-full w-12 h-6 cursor-pointer before:inline-block before:absolute before:top-0 before:left-0 before:w-full before:h-full before:rounded-full before:bg-stone-200 before:transition-colors before:duration-200 before:ease-in after:absolute after:top-2/4 after:left-0 after:-translate-y-2/4 after:w-6 after:h-6 after:border after:border-stone-200 after:bg-white after:rounded-full checked:after:translate-x-full after:transition-all after:duration-200 after:ease-in disabled:opacity-50 disabled:cursor-not-allowed dark:after:bg-white checked:before:bg-stone-800 checked:after:border-stone-800"
        >
        <label
          for="switch-link"
          class="font-sans antialiased text-base cursor-pointer text-stone-600"
        >
          I agree with the
          <a
            href="#"
            class="font-sans antialiased text-base text-stone-500 inline"
          >
            terms and conditions</a>
        </label>
      </div>
    </div>
  </section>
</template>
