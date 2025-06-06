<script setup>
import { ref, watch, onMounted } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import ViewColumnsIcon from '@/Components/Icon/ViewColumnsIcon.vue';
import ArrowDownIcon from '@/Components/Icon/ArrowDownIcon.vue';

const props = defineProps({
  headings: {
    type: Array,
    required: true,
  },
  searchQuery: {
    type: String,
    default: '',
  },
  perPage: {
    type: Number,
    default: 2,
  },
  visibleColumns: {
    type: Array,
    default: () => [{ key: 'id', value: 'User ID', sortable: true }],
  },
  perPageValues: {
    type: Array,
    default: () => [5, 10],
  },
});
const emit = defineEmits(['update:searchQuery', 'update:perPage', 'update:visibleColumns']);

const searchQuery = ref(props.searchQuery);

const perPage = ref(props.perPage);
const visibleColumns = ref(
  props.visibleColumns.length ? props.visibleColumns : props.headings.map((h) => h.key)
);

watch(searchQuery, (newVal) => {
  emit('update:searchQuery', newVal);
});
watch(perPage, (newVal) => {
  emit('update:perPage', newVal);
});
watch(
  visibleColumns,
  (newVal) => {
    emit('update:visibleColumns', newVal);
  },
  { deep: true }
);

const isDropdownOpen = ref(false);
const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const searchInputRef = ref(null);
onMounted(() => {
  const search = ref(new URLSearchParams(window.location.search).get('search') || '');
  emit('update:searchQuery', search.value);

  if (searchInputRef.value && search.value) {
    searchInputRef.value.focus();
  }
});
</script>

<template>
  <div class="mb-4 flex justify-between items-center">
    <div class="flex-1 pr-2">
      <input
        ref="searchInputRef"
        v-model="searchQuery"
        type="search"
        class="w-full pl-10 pr-4 py-2 rounded-lg shadow focus:outline-none text-gray-600 font-medium border-0 dark:bg-gray-900 dark:text-gray-200"
        :placeholder="$t('search.button') + '...'"
      >
    </div>
    <div class="relative mr-2">
      <button
        class="rounded-lg bg-white px-4 py-2 flex items-center shadow border-gray-300 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200"
        @click.prevent="toggleDropdown"
      >
        <ViewColumnsIcon />
        <ArrowDownIcon />
      </button>
      <div
        v-if="isDropdownOpen"
        class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 dark:border-gray-800 rounded-lg shadow-lg z-10 dark:bg-gray-900 dark:text-gray-200"
      >
        <label
          v-for="heading in headings"
          :key="heading.key"
          class="flex items-center px-4 py-2"
          :class="{
            'bg-gray-200 dark:bg-gray-600 cursor-not-allowed  dark:hover:bg-gray-600':
              heading.disabled,
            'hover:bg-gray-100 cursor-pointer dark:hover:bg-gray-800 dark:bg-gray-700':
              !heading.disabled,
          }"
        >
          <input
            v-model="visibleColumns"
            type="checkbox"
            class="mr-3"
            :class="{
              'bg-gray-900 dark:bg-gray-600 cursor-not-allowed  dark:hover:bg-gray-600':
                heading.disabled,
              'hover:bg-gray-100 cursor-pointer dark:hover:bg-gray-800 text-gray-700 rounded-sm ':
                !heading.disabled,
            }"
            :value="heading.key"
            :disabled="heading.disabled"
          >
          <span>{{ heading.value }}</span>
        </label>
      </div>
    </div>
    <div class="relative">
      <Dropdown
        :align="'right'"
        :width="'24'"
      >
        <template #trigger>
          <button
            class="rounded-lg bg-white px-4 py-2 flex items-center shadow hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-200"
          >
            {{ perPage }} <ArrowDownIcon />
          </button>
        </template>
        <template #content>
          <label
            v-for="count in perPageValues"
            :key="count"
            class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer dark:hover:bg-gray-800"
          >
            <input
              v-model="perPage"
              type="radio"
              :value="count"
              class="mr-3 cursor-pointer"
            >
            {{ count }}
          </label>
        </template>
      </Dropdown>
    </div>
  </div>
</template>
