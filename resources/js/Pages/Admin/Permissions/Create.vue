<script setup>
import {useForm} from "@inertiajs/vue3";


const emit = defineEmits(["roleCreated"]);

const form = useForm({
    key: '',
    description: '',
});

const submit = () => {
    form.post(route("admin.permissions.store"), {
        onSuccess: () => emit("permissionCreated"),
    });
};

</script>

<template>
    <form @submit.prevent="submit">
        <input v-model="form.key" placeholder="Permission Key" class="border p-2 w-full mb-2" />
        <input v-model="form.description" placeholder="Description" class="border p-2 w-full mb-2" />
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
    </form>
</template>
