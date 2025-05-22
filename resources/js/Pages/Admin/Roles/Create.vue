<script setup>
import { ref, defineEmits, defineProps } from 'vue';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['roleCreated']);

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
});
const form = useForm({
  name: '',
  is_enabled: true,
  permissions: [],
});

const permissions = ref(props.data.permissions);

const submit = () => {
  form.post(route('admin.roles.store'), {
    onSuccess: () => emit('roleCreated'),
  });
};
</script>

<template>
  <form @submit.prevent="submit">
    <input v-model="form.name" placeholder="Role name" required class="border p-2 w-full my-2" />

    <label><input type="checkbox" v-model="form.is_enabled" />Enabled</label>

    <h3>Permissions</h3>
    <div v-for="permission in permissions" :key="permission.id">
      <label>
        <input type="checkbox" v-model="form.permissions" :value="permission.id" />
        {{ permission.key }}
      </label>
    </div>

    <button type="submit" class="bg-blue-500 px-4 py-2 text-white rounded">Create</button>
  </form>
</template>
