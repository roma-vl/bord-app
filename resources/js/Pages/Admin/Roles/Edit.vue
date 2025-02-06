<script setup>
import { ref, defineProps, defineEmits } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});
const emit = defineEmits(["roleUpdated"]);

const form = useForm({
    name: props.data.role.name,
    is_enabled: Boolean(props.data.role.is_enabled),
    permissions: props.data.rolePermissions,
});

const submit = () => {
    form.put(route("admin.roles.update", props.data.role.id), {
        onSuccess: () => emit("roleUpdated"),
    });
};
</script>

<template>
    <div>
        <h2 class="text-lg font-semibold">Edit Role</h2>
        <form @submit.prevent="submit">
            <input v-model="form.name" placeholder="Role name" required class="border p-2 w-full my-2" />
            <label>
                <input type="checkbox" v-model="form.is_enabled" />
                Enabled
            </label>

            <h3 class="text-md font-semibold mt-4">Permissions</h3>
            <div v-for="permission in props.data.permissions" :key="permission.id">
                <label>
                    <input type="checkbox" v-model="form.permissions" :value="permission.id" />
                    {{ permission.key }}
                </label>
            </div>

            <button type="submit" class="bg-blue-500 px-4 py-2 text-white rounded">Update</button>
        </form>
    </div>
</template>
