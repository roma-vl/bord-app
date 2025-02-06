<script setup>
import {defineEmits, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

const form = useForm({
    name: "",
    is_enabled: true,
});

const emit = defineEmits(["roleCreated"]);

const submit = () => {
    form.post(route("admin.roles.store"), {
        onSuccess: () => {
            emit("roleCreated");
        },
    });
};

</script>

<template>
    <div>
        <h2 class="text-lg font-semibold">Create Role</h2>

        <form @submit.prevent="submit" class="space-y-4 mt-4 mb-10">
            <div>
                <InputLabel for="name" class="block text-sm font-medium text-gray-700" value="Name"/>
                <TextInput
                    id="name"
                    type="text"
                    class="w-full mt-1 p-2 border-0 rounded-md  focus:border-blue-500 focus:ring focus:ring-blue-200 "
                    v-model="form.name"
                    autocomplete="name"
                    placeholder="Enter name"
                />
            </div>
            <InputError class="mt-2" :message="form.errors.name"/>

            <label>
                <input type="checkbox" v-model="form.is_enabled" />
                Enabled
            </label>

            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200">
                Create Account
            </button>
        </form>
    </div>
</template>
