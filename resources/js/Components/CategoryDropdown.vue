<script setup>
import { ref } from 'vue'

const props = defineProps({
    categoryFilters: Array,
})
const emit = defineEmits(['update:modelValue'])

const isOpen = ref(false)
const selectedCategory = ref(null)

function toggleDropdown() {
    isOpen.value = !isOpen.value
}

function selectCategory(category) {
    selectedCategory.value = category
    emit('update:modelValue', category.id)
    isOpen.value = false
}
</script>

<template>
    <div class="relative inline-block w-full">
        <div @click="toggleDropdown" class="cursor-pointer border px-4 py-2 rounded bg-white shadow">
            {{ selectedCategory?.name || 'Обрати категорію' }}
        </div>

        <div v-if="isOpen" class="absolute left-0 top-full mt-2 w-full z-50 bg-white border shadow-lg rounded-lg max-h-96 overflow-y-auto">
            <div v-for="category in categoryFilters" :key="category.id" class="group">
                <div class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    @click="selectCategory(category)">
                    {{ category.name }}
                    <span v-if="category.children?.length">▶</span>
                </div>

                <div v-if="category.children?.length" class="ml-2 border-l group-hover:block hidden">
                    <div v-for="child in category.children" :key="child.id">
                        <div class="flex justify-between items-center px-2 py-2 hover:bg-gray-100 cursor-pointer"
                             @click.stop="selectCategory(child)">
                            {{ child.name }}
                            <span v-if="child.children?.length">▶</span>
                        </div>

                        <div v-if="child.children?.length" class="ml-2 border-l group-hover:block hidden">
                            <div v-for="child2 in child.children" :key="child2.id"
                                @click.stop="selectCategory(child2)" class="px-2 py-2 hover:bg-gray-100 cursor-pointer">
                                {{ child2.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

