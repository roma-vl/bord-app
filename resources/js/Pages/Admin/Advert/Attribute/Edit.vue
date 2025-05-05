<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { defineProps, defineEmits } from "vue";
import TagInput from "@/Components/TagInput.vue";

const props = defineProps({
    data: Object,
});

const emit = defineEmits(["attributeUpdated"]);

const form = useForm({
    name: props.data.attribute.name,
    type: props.data.attribute.type,
    is_required: Boolean(props.data.attribute.is_required),
    variants: [...props.data.attribute.variants], // масив
    sort: props.data.attribute.sort,
});

const submit = () => {
    form.post(route("admin.adverts.category.attributes.update", {
        category: props.data.category.id,
        attribute: props.data.attribute.id,
    }), {
        onSuccess: () => {
            emit("attributeUpdated");
            form.reset();
        }
    });
};
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Редагувати Атрибут</h1>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label class="block text-gray-700">Назва</label>
                <input v-model="form.name" type="text" class="w-full p-2 border rounded" required />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Тип</label>
                <select v-model="form.type" class="w-full p-2 border rounded">
                    <option v-for="t in props.data.types" :key="t" :value="form.type">{{ t }}</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="flex items-center space-x-2">
                    <input v-model="form.is_required" type="checkbox" class="rounded" />
                    <span>Обов’язковий</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Варіанти (по одному на рядок)</label>
                <textarea v-model="form.variants" class="w-full p-2 border rounded"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Варіанти</label>
                <TagInput v-model="form.variants" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Сортування</label>
                <input v-model="form.sort" type="number" class="w-full p-2 border rounded" required />
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Зберегти</button>
        </form>
    </div>
</template>
