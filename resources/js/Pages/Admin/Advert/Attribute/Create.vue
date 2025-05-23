<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps, defineEmits } from 'vue';
import TagInput from '@/Components/TagInput.vue';

const props = defineProps({
  data: {
    type: Object,
    default: () => ({}),
  },
});
const emit = defineEmits(['attributeCreated']);

const form = useForm({
  name: '',
  type: null,
  is_required: false,
  variants: '',
  sort: 1,
});

const submit = () => {
  form.post(
    route('admin.adverts.category.attributes.store', {
      category: props.data.category.id,
    }),
    {
      onSuccess: () => {
        emit('attributeCreated');
        form.reset();
      },
    }
  );
};
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">
      Додати Атрибут
    </h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-gray-700">Назва</label>
        <input
          v-model="form.name"
          type="text"
          class="w-full p-2 border rounded"
          required
        >
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Тип</label>
        <select
          v-model="form.type"
          class="w-full p-2 border rounded"
        >
          <option :value="null">
            Виберіть тип
          </option>
          <option
            v-for="(label, key) in props.data.types"
            :key="key"
            :value="label"
          >
            {{ label }}
          </option>
        </select>
      </div>

      <div class="mb-4">
        <label class="flex items-center space-x-2">
          <input
            v-model="form.is_required"
            type="checkbox"
            class="rounded"
          >
          <span>Обов’язковий</span>
        </label>
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Варіанти (по одному на рядок)</label>
        <TagInput v-model="form.variants" />
      </div>

      <div class="mb-4">
        <label class="block text-gray-700">Сортування</label>
        <input
          v-model="form.sort"
          type="number"
          class="w-full p-2 border rounded"
          required
        >
      </div>

      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded"
      >
        Зберегти
      </button>
    </form>
  </div>
</template>
