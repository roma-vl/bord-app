<script setup>
import { ref, computed, defineProps, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FlashMessage from '@/Components/FlashMessage.vue';

defineProps({ user: Object });

const flash = computed(() => usePage().props.flash);

const token = ref(['', '', '', '', '']);
const inputs = ref([]);

const form = useForm({
  token: '',
});

const submitForm = () => {
  form.token = token.value.join('');
  form.put(route('account.profile.phone.verify'));
};

const handleInput = (index, event) => {
  const value = event.target.value;

  if (!/^\d?$/.test(value)) {
    token.value[index] = '';
    return;
  }

  token.value[index] = value;

  if (value && index < token.value.length - 1) {
    inputs.value[index + 1]?.focus();
  }
};

const handleKeydown = (index, event) => {
  if (event.key === 'Backspace' && !token.value[index] && index > 0) {
    inputs.value[index - 1]?.focus();
  }
};

onMounted(() => {
  inputs.value[0]?.focus();
});
</script>

<template>
  <Head title="Profile" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3 min-h-[400px]">
          <FlashMessage :flash="flash" />
          <div class="flex items-center p-3">
            <h2 class="text-2xl font-bold">Verify Phone Number</h2>
          </div>
          <form @submit.prevent="submitForm" class="mt-6 space-y-6">
            <div>
              <div class="flex justify-center mt-6">
                <input
                  v-for="(digit, index) in token"
                  :key="index"
                  v-model="token[index]"
                  ref="inputs"
                  class="w-14 h-16 text-center text-xl border border-gray-300 rounded-md mx-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  maxlength="1"
                  @input="handleInput(index, $event)"
                  @keydown="handleKeydown(index, $event)"
                />
              </div>
            </div>
            <a :href="route('account.profile.phone.request')">Не прийшов код Спробуйте ще раз</a>

            <div class="flex justify-center gap-4">
              <PrimaryButton :disabled="form.processing">Save Phone Number</PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
