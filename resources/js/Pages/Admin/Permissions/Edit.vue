<script setup>
import {  defineProps, defineEmits } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});
const emit = defineEmits(["permissionUpdated"]);

const form = useForm({
    key: props.data.permission.key,
    description: props.data.permission.description,
});

const submit = () => {
    form.put(route("admin.permissions.update", props.data.permission.id), {
        onSuccess: () => emit("permissionUpdated"),
    });
};

</script>

<template>
    <div class="p-4">
        <h2 class="text-lg font-bold mb-4">Edit Permission</h2>
        <form @submit.prevent="submit">
            <input v-model="form.key" placeholder="Permission Key" class="border p-2 w-full mb-2" />
            <input v-model="form.description" placeholder="Description" class="border p-2 w-full mb-2" />
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</template>

