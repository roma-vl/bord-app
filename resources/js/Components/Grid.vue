<!-- src/Components/Grid.vue -->
<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import DataTableHeader from '@/Components/DataTableHeader.vue';
import DataTable from '@/Components/DataTable.vue';
import Pagination from '@/Components/Pagination.vue';
import { useLocalStorageFn } from '@/helpers.js';

const props = defineProps({
  items: { type: Array, required: true },
  pagination: { type: Object, required: true },
  headings: { type: Array, required: true },
  routes: { type: Array, required: true }, // Назва роута для запиту
});

const MIN_SEARCH_LENGTH = 1;
const PER_PAGE_DEFAULT = 10;

const searchQuery = useLocalStorageFn('searchQuery', '');
const perPage = useLocalStorageFn('perPage', PER_PAGE_DEFAULT);
const sortField = useLocalStorageFn('sortField', 'id');
const sortOrder = useLocalStorageFn('sortOrder', 'asc');
const visibleColumns = useLocalStorageFn(
  'visibleColumns',
  props.headings.map((h) => h.key)
);

const emit = defineEmits(['update:searchQuery']);

watch(searchQuery, (newValue) => {
  emit('update:searchQuery', newValue);
});

const queryParams = computed(() => ({
  search: searchQuery.value,
  per_page: perPage.value,
  sort_by: sortField.value,
  sort_order: sortOrder.value,
}));

const updateItemsList = async () => {
  const routesMap = props.routes.reduce((acc, route) => {
    acc[route.key] = route.value;
    return acc;
  }, {});

  const url = queryParams.value.search ? routesMap.search : routesMap.index;

  await router.get(route(url), queryParams.value, { preserveScroll: true, replace: true });
};

watch(queryParams, updateItemsList, { deep: true });

const updateSorting = (field) => {
  const heading = props.headings.find((h) => h.key === field);
  if (!heading || !heading.sortable) return;

  if (sortField.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortOrder.value = 'asc';
  }
  updateItemsList();
};
</script>

<template>
  <div class="grid-container shadow p-2 bg-white rounded-lg">
    <DataTableHeader
      :headings="headings"
      v-model:searchQuery="searchQuery"
      v-model:perPage="perPage"
      v-model:visibleColumns="visibleColumns"
      :perPageValues="[5, 10, 20, 50]"
    />
    <DataTable
      v-if="items"
      :items="items"
      :headings="headings.filter((h) => visibleColumns.includes(h.key))"
      uniqueKey="id"
      :searchQuery="searchQuery"
      @sort="updateSorting"
    >
      <template v-for="(_, slotName) in $slots" v-slot:[slotName]="scope">
        <slot :name="slotName" v-bind="scope"></slot>
      </template>
    </DataTable>
    <div
      v-else
      class="w-full border-collapse bg-white text-center text text-gray-800 border-b mb-2"
    >
      No data found.
    </div>

    <Pagination
      :pagination="pagination"
      :searchQuery="searchQuery"
      :sortField="sortField"
      :sortOrder="sortOrder"
    />
  </div>
</template>
