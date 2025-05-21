<script setup>
defineProps({
    message: {
        type: String,
        required: true
    }
})
</script>

<template>
    <div class="relative group inline-block cursor-help text-gray-500">
        <span>❓</span>
        <div class="absolute left-full top-2 -translate-y-1/2 ml-2 w-60 bg-gray-800 text-white text-sm rounded p-3
         opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-lg">
            {{ message }}
        </div>
    </div>
</template>
