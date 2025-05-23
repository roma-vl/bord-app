<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  user: { type: Object, required: true },
  roles: { type: Array, required: true },
  userRoles: { type: Array, required: true },
});

const emit = defineEmits(['userUpdated']);

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  password: '',
  active: !!props.user.email_verified_at,
  locale: props.user.locale,
  roles: [...props.userRoles],
});

watch(
  () => props.user,
  (newUser) => {
    if (newUser) {
      form.name = newUser.name;
      form.email = newUser.email;
      form.active = !!newUser.email_verified_at;
      form.locale = newUser.locale;
    }
  }
);

const submit = () => {
  form.put(route('admin.users.update', props.user.id), {
    onSuccess: () => {
      emit('userUpdated');
    },
  });
};
</script>

<template>
  <div class="max-w-md mx-auto mt-8">
    <h2 class="text-2xl font-semibold text-gray-700 text-center">
      Edit User
    </h2>
    <form
      class="space-y-4 mt-4 mb-10"
      @submit.prevent="submit"
    >
      <div>
        <InputLabel
          for="name"
          value="Name"
        />
        <TextInput
          id="name"
          v-model="form.name"
          type="text"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          placeholder="Enter name"
        />
        <InputError :message="form.errors.name" />
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
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          placeholder="Enter email"
        />
        <InputError :message="form.errors.email" />
      </div>
      <div>
        <InputLabel
          for="password"
          value="New Password (optional)"
        />
        <TextInput
          id="password"
          v-model="form.password"
          type="password"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
          placeholder="Enter password"
        />
        <InputError :message="form.errors.password" />
      </div>
      <div>
        <InputLabel
          for="active"
          value="Status"
        />
        <select
          id="active"
          v-model="form.active"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
        >
          <option :value="true">
            Active
          </option>
          <option :value="false">
            Inactive
          </option>
        </select>
        <InputError :message="form.errors.active" />
      </div>
      <div>
        <InputLabel
          for="locale"
          value="Locale"
        />
        <select
          id="locale"
          v-model="form.locale"
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
        >
          <option value="en">
            English
          </option>
          <option value="uk">
            Українська
          </option>
        </select>
        <InputError :message="form.errors.locale" />
      </div>
      <div>
        <InputLabel
          for="roles"
          value="Roles"
        />
        <select
          id="roles"
          v-model="form.roles"
          multiple
          class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
        >
          <option
            v-for="role in props.roles"
            :key="role.id"
            :value="role.id"
          >
            {{ role.name }}
          </option>
        </select>
        <InputError :message="form.errors.roles" />
      </div>

      <div class="max-w-md mx-auto">
        <label
          for="select"
          class="font-semibold block py-2"
        >Select Input:</label>

        <div class="relative">
          <div class="h-10 bg-white flex border border-gray-200 rounded items-center">
            <input
              id="select"
              value="Javascript"
              name="select"
              class="px-4 appearance-none outline-none text-gray-800 w-full"
              checked
            >

            <button
              class="cursor-pointer outline-none focus:outline-none transition-all text-gray-300 hover:text-gray-600"
            >
              <svg
                class="w-4 h-4 mx-2 fill-current"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <line
                  x1="18"
                  y1="6"
                  x2="6"
                  y2="18"
                />
                <line
                  x1="6"
                  y1="6"
                  x2="18"
                  y2="18"
                />
              </svg>
            </button>
            <label
              for="show_more"
              class="cursor-pointer outline-none focus:outline-none border-l border-gray-200 transition-all text-gray-300 hover:text-gray-600"
            >
              <svg
                class="w-4 h-4 mx-2 fill-current"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <polyline points="18 15 12 9 6 15" />
              </svg>
            </label>
          </div>

          <input
            id="show_more"
            type="checkbox"
            name="show_more"
            class="hidden peer"
          >
          <div
            class="absolute rounded shadow bg-white overflow-hidden hidden peer-checked:flex flex-col w-full mt-1 border border-gray-200"
          >
            <div class="cursor-pointer group">
              <a
                class="block p-2 border-transparent border-l-4 group-hover:border-blue-600 group-hover:bg-gray-100"
              >Python</a>
            </div>
            <div class="cursor-pointer group border-t">
              <a
                class="block p-2 border-transparent border-l-4 group-hover:border-blue-600 border-blue-600 group-hover:bg-gray-100"
              >Javascript</a>
            </div>
            <div class="cursor-pointer group border-t">
              <a
                class="block p-2 border-transparent border-l-4 group-hover:border-blue-600 group-hover:bg-gray-100"
              >Node</a>
            </div>
            <div class="cursor-pointer group border-t">
              <a
                class="block p-2 border-transparent border-l-4 group-hover:border-blue-600 group-hover:bg-gray-100"
              >PHP</a>
            </div>
          </div>
        </div>
      </div>
      <button
        type="submit"
        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200"
      >
        Update User
      </button>
    </form>
  </div>
</template>
