<script setup>
import {defineEmits, defineProps} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    role: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(["roleUpdated"]);

const form = useForm({
    name: props.role.name,
    is_enabled: Boolean(props.role.is_enabled)
});

const submit = () => {
    form.put(route("admin.roles.update", props.role.id), {
        onSuccess: () => {
            emit("roleUpdated");
        },
    });
};
</script>

<template>
    <div>
        <h2 class="text-lg font-semibold">Edit Role</h2>
        <form @submit.prevent="submit">
            <input v-model="form.name" placeholder="Role name" required class="border p-2 w-full my-2" />
            <InputError class="mt-2" :message="form.errors.name"/>

            <label>
                <input type="checkbox" v-model="form.is_enabled" />
                Enabled
            </label>

            <button type="submit" class="bg-blue-500 px-4 py-2 text-white rounded">Update</button>
        </form>
    </div>
</template>
