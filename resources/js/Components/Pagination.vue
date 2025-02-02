<script setup>
import { defineProps } from "vue";
import { router } from "@inertiajs/vue3";
import ArrowLeftIcon from "@/Components/Icon/ArrowLeftIcon.vue";
import ArrowRightIcon from "@/Components/Icon/ArrowRightIcon.vue";

const props = defineProps({
    pagination: Object,
    searchQuery: String,
    sortField: String,
    sortOrder: String,
});

const searchQuery = props.searchQuery ? '&search=' + props.searchQuery: '';
const sortField = props.sortField ? '&sort_by=' + props.sortField: '';
const sortOrder = props.sortOrder ? '&sort_order=' + props.sortOrder: '';

const changePage = (url) => {
    if (url) {
        router.get(url + searchQuery + sortField + sortOrder);
    }
};

</script>

<template>
    <div class="pr-2 flex flex-row mb-2">
        <p class="p-2 text-gray-600 font-medium "> Всього: {{ pagination.total }}</p>
    </div>
    <div class="flex flex-col items-center mb-2">
        <div v-if="pagination.total > pagination.per_page" class="flex items-center gap-1">
            <button @click="changePage(pagination.links[0].url)" :disabled="!pagination.links[0].url"
                class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed focus:shadow-none text-sm rounded-md py-2 px-4 bg-transparent border-transparent text-stone-800 hover:bg-stone-800/5 hover:border-stone-800/5 shadow-none hover:shadow-none">
                <ArrowLeftIcon />
                {{ pagination.links[0].label }}
            </button>

            <button
                v-for="(link, index) in pagination.links.slice(1, -1)"
                :key="index"
                @click="changePage(link.url)"
                :disabled="!link.url"
                class="inline-grid place-items-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-sm min-w-[38px] min-h-[38px] rounded-md"
                :class="{'bg-stone-800 border-stone-800 text-stone-50 hover:bg-stone-700 hover:border-stone-700 shadow-sm hover:shadow-md': link.active,
                      'bg-transparent border-transparent text-stone-800 hover:bg-stone-800/5 hover:border-stone-800/5 shadow-none hover:shadow-none': !link.active
                    }"
                v-html="link.label"
            ></button>

            <button
                @click="changePage(pagination.links[pagination.links.length - 1].url)"
                :disabled="!pagination.links[pagination.links.length - 1].url"
                class="inline-flex items-center justify-center border align-middle select-none font-sans font-medium text-center transition-all duration-300 ease-in disabled:opacity-50 disabled:shadow-none disabled:cursor-not-allowed focus:shadow-none text-sm rounded-md py-2 px-4 bg-transparent border-transparent text-stone-800 hover:bg-stone-800/5 hover:border-stone-800/5 shadow-none hover:shadow-none"
            >
                {{ pagination.links[pagination.links.length - 1].label }}
              <ArrowRightIcon />
            </button>
        </div>
    </div>
</template>
