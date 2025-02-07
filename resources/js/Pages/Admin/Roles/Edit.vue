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

        <div class="max-w-screen-xl mx-auto px-5 bg-white min-h-sceen">
            <div class="flex flex-col items-center">
                <h2 class="font-bold text-5xl mt-5 tracking-tight">
                    FAQ
                </h2>
                <p class="text-neutral-500 text-xl mt-3">
                    Frequenty asked questions
                </p>
            </div>
            <div class="grid divide-y divide-neutral-200 max-w-xl mx-auto mt-8">
                <div class="py-5">
                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                            <span> What is a SAAS platform?</span>
                            <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path>
</svg>
              </span>
                        </summary>
                        <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                            SAAS platform is a cloud-based software service that allows users to access
                            and use a variety of tools and functionality.
                        </p>
                    </details>
                </div>
                <div class="py-5">
                    <details class="group">
                        <summary class="flex justify-between items-center font-medium cursor-pointer list-none">
                            <span> How does  billing work?</span>
                            <span class="transition group-open:rotate-180">
                <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path>
</svg>
              </span>
                        </summary>
                        <p class="text-neutral-600 mt-3 group-open:animate-fadeIn">
                            We offers a variety of billing options, including monthly and annual subscription plans,
                            as well as pay-as-you-go pricing for certain services. Payment is typically made through a credit
                            card or other secure online payment method.
                        </p>
                    </details>
                </div>

            </div>
        </div>

    </div>
</template>
