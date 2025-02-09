<script setup>
import {computed, watchEffect} from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";

const props = defineProps(["category"]);
const emit = defineEmits(["categoryUpdated"]);
const categories = usePage().props.categories;
const form = useForm({
    name: "",
    slug: "",
    parent_id: null,
});

watchEffect(() => {
    form.name = props.category?.name || "";
    form.slug = props.category?.slug || "";
    form.parent_id = props.category?.parent_id || null;
});

const submit = () => {
    form.put(route("admin.adverts.category.update", props.category.id), {
        onSuccess: () => emit("categoryUpdated"),
    });
};

const getCategoryOptions = (categories, prefix = "") => {
    let options = [];
    categories.forEach((category) => {
        options.push({ id: category.id, name: prefix + category.name });

        if (category.children && category.children.length) {
            options = options.concat(getCategoryOptions(category.children, prefix + "- "));
        }
    });
    return options;
};

const formattedCategories = computed(() => getCategoryOptions(categories));
</script>

<template>
    <Head title="Редагувати категорію" />

    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Редагувати категорію</h1>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-gray-700">Назва</label>
                <input v-model="form.name" type="text" class="w-full p-2 border rounded" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Slug</label>
                <input v-model="form.slug" type="text" class="w-full p-2 border rounded" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Батьківська категорія</label>
                <select v-model="form.parent_id" class="w-full p-2 border rounded">
                    <option :value="null">Без батьківської категорії</option>
                    <option v-for="cat in formattedCategories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Оновити</button>
        </form>
    </div>
</template>
