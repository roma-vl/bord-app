<script setup>
import { computed } from 'vue';

const props = defineProps({
    flash: {
        type: Object,
        required: true
    }
});

const messages = computed(() => {
    return Object.entries(props.flash)
        .map(([type, message]) => ({ type, message }))
        .filter(msg => msg.message); // Фільтруємо, щоб не було null або undefined
});

const getClasses = (type) => {
    switch (type) {
        case 'success':
            return 'bg-green-200 text-green-800 border-green-400';
        case 'error':
            return 'bg-red-200 text-red-800 border-red-400';
        case 'info':
        default:
            return 'bg-blue-200 text-blue-800 border-blue-400';
    }
};
</script>

<template>
    <div v-if="messages.length">
        <div v-for="msg in messages" :key="msg.type" :class="`mb-4 p-2 rounded border ${getClasses(msg.type)}`">
            {{ msg.message }}
        </div>
    </div>
</template>
