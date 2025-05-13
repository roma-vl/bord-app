<script setup>
import { ref } from 'vue'

const props = defineProps({
    category: Object
})
const emit = defineEmits(['select'])

const isOpen = ref(false)

function toggleChildren() {
    isOpen.value = !isOpen.value
}

function select(category) {
    emit('select', category)
}
</script>

<template>
    <div>
        <div
            class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 cursor-pointer"
            @click="toggleChildren"
        >
            <span @click.stop="select(category)">{{ category.name }}</span>
            <span v-if="category.children?.length">
        <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path :class="{'rotate-90': isOpen}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5l7 7-7 7"/>
        </svg>
      </span>
        </div>

        <div v-if="isOpen && category.children?.length" class="ml-4 border-l">
            <CategoryDropdownItem
                v-for="child in category.children"
                :key="child.id"
                :category="child"
                @select="select"
            />
        </div>
    </div>
</template>

<style scoped>
.rotate-90 {
    transform: rotate(90deg);
}
</style>
