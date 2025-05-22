<script setup>
import ArrowUpDownIcon from '@/Components/Icon/ArrowUpDownIcon.vue';
import { computed } from 'vue';

const props = defineProps({
  items: Array,
  headings: Array,
  uniqueKey: { type: String, default: 'id' },
  searchQuery: String,
});

const emit = defineEmits(['sort']);
const sortField = JSON.parse(localStorage.getItem('sortField'));
const highlightText = (text, query) => {
  if (!query || !text) return text;
  const regex = new RegExp(`(${query})`, 'gi');
  return text.replace(regex, '<span class="bg-yellow-200">$1</span>');
};

const processedItems = computed(() => {
  return props.items.map((item) => {
    const newItem = { ...item };
    props.headings.forEach((heading) => {
      if (heading.highlight) {
        newItem[heading.key] = highlightText(item[heading.key], props.searchQuery);
      }
    });
    return newItem;
  });
});
</script>

<template>
  <table class="w-full border-collapse bg-white text-left text-sm text-gray-500 border-b mb-2">
    <thead class="bg-gray-50">
      <tr>
        <th
          v-for="heading in props.headings"
          :key="heading.key"
          @click="emit('sort', heading.key)"
          class="border-b px-3 py-3 text-gray-700 uppercase text-xs flex-col"
          :class="{
            'border-b bg-gray-100 border-gray-900 text-gray-900 font-bold ':
              sortField === heading.key && heading.sortable === true,
            'cursor-pointer hover:bg-gray-100': heading.sortable === true,
          }"
        >
          <div class="flex gap-2">
            <span class="inline-flex items-center gap-1">
              {{ heading.value }}
            </span>
            <span
              class="flex items-end gap-1 cursor-pointer text-gray-400 hover:text-gray-600"
              :class="{ 'text-gray-900 font-bold': sortField === heading.key }"
            >
              <ArrowUpDownIcon class="w-4 h-4" v-if="heading.sortable" />
            </span>
          </div>
        </th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 border-t border-gray-100">
      <tr v-for="item in processedItems" :key="item[uniqueKey]" class="hover:bg-gray-50">
        <td v-for="heading in headings" :key="heading.key" class="px-6 py-4 text-gray-600">
          <slot :name="`column-${heading.key}`" :row="item">
            <span v-if="heading.highlight" v-html="item[heading.key]"></span>
            <span v-else>{{ item[heading.key] }}</span>
          </slot>
        </td>
      </tr>
    </tbody>
  </table>
</template>
