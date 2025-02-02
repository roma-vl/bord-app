<script setup>
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits, watch } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(["userUpdated"]);

// Створюємо форму з початковими даними користувача
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: "",
    // Якщо email_verified_at існує – користувач активований
    active: !!props.user.email_verified_at,
    locale: props.user.locale,
});

// Якщо дані користувача змінюються, оновлюємо форму
watch(() => props.user, (newUser) => {
    if (newUser) {
        form.name = newUser.name;
        form.email = newUser.email;
        form.active = !!newUser.email_verified_at;
        form.locale = newUser.locale;
    }
});

// Метод відправки форми
const submit = () => {
    // Опціонально: можна перетворити значення active на дату або null перед відправкою,
    // або обробити це на сервері.
    form.put(route("admin.users.update", props.user.id), {
        onSuccess: () => {
            emit("userUpdated");
        },
    });
};
</script>

<template>
    <div class="max-w-md mx-auto mt-8">
        <h2 class="text-2xl font-semibold text-gray-700 text-center">Edit User</h2>
        <form @submit.prevent="submit" class="space-y-4 mt-4 mb-10">
            <!-- Name -->
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    type="text"
                    v-model="form.name"
                    class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
                    placeholder="Enter name"
                />
                <InputError :message="form.errors.name" />
            </div>
            <!-- Email -->
            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
                    placeholder="Enter email"
                />
                <InputError :message="form.errors.email" />
            </div>
            <!-- Password -->
            <div>
                <InputLabel for="password" value="New Password (optional)" />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
                    placeholder="Enter password"
                />
                <InputError :message="form.errors.password" />
            </div>
            <!-- Статус (активований/неактивований) -->
            <div>
                <InputLabel for="active" value="Status" />
                <select
                    id="active"
                    v-model="form.active"
                    class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
                >
                    <option :value="true">Active</option>
                    <option :value="false">Inactive</option>
                </select>
                <InputError :message="form.errors.active" />
            </div>
            <!-- Локаль -->
            <div>
                <InputLabel for="locale" value="Locale" />
                <select
                    id="locale"
                    v-model="form.locale"
                    class="w-full mt-1 p-2 border rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200"
                >
                    <option value="en">English</option>
                    <option value="uk">Українська</option>
                </select>
                <InputError :message="form.errors.locale" />
            </div>
            <button
                type="submit"
                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200"
            >
                Update User
            </button>
        </form>
    </div>
</template>
