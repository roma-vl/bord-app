<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Grid from '@/Components/Grid.vue';
import { useI18n } from 'vue-i18n';

const logs = computed(() => usePage().props.logs.data);
const { t } = useI18n();
const pagination = computed(() => usePage().props.logs);

const headings = [
  { key: 'id', value: t('ID'), sortable: true, disabled: true },
  { key: 'description', value: t('actions'), sortable: true, disabled: true },
  { key: 'causer', value: t('causer') },
  { key: 'subject', value: t('subject') },
  { key: 'changes', value: t('changes') },
  { key: 'created_at', value: t('Created At') },
];

const routes = [
  { key: 'index', value: 'admin.activity.logs' },
  { key: 'search', value: 'admin.activity.logs' },
];
</script>

<template>
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div
          class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-3 dark:bg-gray-900 dark:text-gray-200"
        >
          <div class="p-6 space-y-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
              {{ t('log.of.activities') }}
            </h1>

            <Grid
              :items="logs"
              :pagination="pagination"
              :headings="headings"
              :routes="routes"
            >
              <template #column-subject="{ row }">
                <span class="p-3">{{ row.subject_type }} #{{ row.subject_id }}</span>
              </template>
              <template #column-changes="{ row }">
                <td class="p-3 text-sm text-gray-600">
                  <div v-if="row.changes.attributes || row.changes.old">
                    <div v-if="row.changes.attributes">
                      <strong>Нові:</strong>
                      <ul class="list-disc ml-5">
                        <li
                          v-for="(value, key) in row.changes.attributes"
                          :key="key"
                        >
                          {{ key }}: <span class="text-green-700">{{ value }}</span>
                        </li>
                      </ul>
                    </div>
                    <div v-if="row.changes.old">
                      <strong>Старі:</strong>
                      <ul class="list-disc ml-5 text-red-700">
                        <li
                          v-for="(value, key) in row.changes.old"
                          :key="key"
                        >
                          {{ key }}: {{ value }}
                        </li>
                      </ul>
                    </div>
                  </div>
                  <span
                    v-else
                    class="italic text-gray-400"
                  >Без змін</span>
                </td>
              </template>
            </Grid>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
