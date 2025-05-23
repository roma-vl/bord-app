<script setup>
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { computed, watch } from 'vue';
import TooltipIcon from '@/Components/TooltipIcon.vue';

const props = defineProps({
  groups: {
    type: Object,
    default: () => ({}),
  },
  group: {
    type: String,
    default: '',
  },
  settings: {
    type: Object,
    default: () => ({}),
  },
});

const flash = computed(() => usePage().props.flash);
const form = useForm({ ...props.settings });

function submit() {
  form.put(route(`admin.settings.${props.group}`));
}

function getIcon(key) {
  switch (key) {
    case 'general':
      return `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            `;
    case 'user':
      return `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 017.9 16h8.2a4 4 0 012.779 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                </svg>
            `;
    default:
      return `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="2" />
                </svg>
            `;
  }
}
</script>

<template>
  <Head title="Settings" />
  <AdminLayout>
    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex bg-white shadow sm:rounded-lg overflow-hidden">
          <!-- Sidebar -->
          <aside class="w-1/4 bg-gray-50 border-r p-6">
            <h2 class="text-lg font-bold mb-4 text-gray-700">
              Групи налаштувань
            </h2>
            <ul class="space-y-1">
              <li
                v-for="(label, key) in groups"
                :key="key"
              >
                <Link
                  :href="route('admin.settings.edit', key)"
                  class="flex items-center gap-3 px-4 py-2 rounded transition duration-150"
                  :class="{
                    'bg-blue-500 text-white hover:bg-blue-600': props.group === key,
                    'text-gray-700 hover:bg-blue-100': props.group !== key,
                  }"
                >
                  <!-- eslint-disable vue/no-v-html -->
                  <span
                    class="w-5 h-5 shrink-0"
                    v-html="getIcon(key)"
                  />
                  <!-- eslint-enable vue/no-v-html -->
                  <span class="capitalize">{{ key.replace('_', ' ') }}</span>
                </Link>
              </li>
            </ul>
          </aside>

          <!-- Content -->
          <div class="w-3/4 p-6">
            <FlashMessage :flash="flash" />

            <div
              v-if="!props.group"
              class="text-gray-500 italic"
            >
              Виберіть групу для редагування
            </div>
            <form
              v-else
              class="space-y-6"
              @submit.prevent="submit"
            >
              <template v-if="props.group === 'general'">
                <h2 class="text-lg font-bold mb-4 text-gray-700">
                  Головні налаштування
                </h2>
                <div>
                  <label class="block font-semibold mb-1">Site Name</label>
                  <input
                    v-model="form.site_name"
                    class="border rounded p-2 w-full"
                    required
                  >
                </div>
                <div>
                  <label class="inline-flex items-center gap-2">
                    <input
                      v-model="form.maintenance_mode"
                      type="checkbox"
                      class="p-2.5 rounded shadow"
                    >
                    Maintenance Mode
                    <TooltipIcon message="Вручну потушити сайт" />
                  </label>
                </div>
              </template>

              <template v-else-if="props.group === 'user'">
                <h2 class="text-lg font-bold mb-4 text-gray-700">
                  Налаштування Користувачів
                </h2>
                <div>
                  <label class="inline-flex items-center gap-2 relative group">
                    <input
                      v-model="form.allow_registration"
                      type="checkbox"
                      class="p-2.5 rounded shadow"
                    >
                    Allow Registration
                    <TooltipIcon message="Дозволити новим користувачам реєструватися" />
                  </label>
                </div>
                <div>
                  <label class="inline-flex items-center gap-2">
                    <input
                      v-model="form.require_email_verification"
                      type="checkbox"
                      class="p-2.5 rounded shadow"
                    >
                    Require Email Verification
                    <TooltipIcon message="Нові користувачі мають обовязково підтверджувати пошту" />
                  </label>
                </div>
              </template>

              <button
                type="submit"
                class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded shadow"
              >
                Зберегти
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
