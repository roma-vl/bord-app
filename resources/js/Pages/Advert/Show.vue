<script setup>
import { computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    advert: Object,
    values: Array,
    categoryAttributes: Array,
    photos: Array,
    category: Object

});

console.log(props.advert, 'ss')

const form = useForm({});
const isDraft = computed(() => props.advert.status === "draft");
const isOnModeration = computed(() => props.advert.status === "moderation");
const isActive = computed(() => props.advert.status === "active");

const submitAction = (routeName) => {
    form.post(route(routeName, props.advert.id));
};

const getValue = (attributeName) => {
    const valueObj = props.values.find(v => v.attribute === attributeName);
    return valueObj ? valueObj.value : "-";
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg">
            <div v-if="isDraft" class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">Це чернетка.</div>
            <div v-if="advert.reject_reason" class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ advert.reject_reason }}</div>

            <div class="flex gap-2 mb-6">
                <button @click="submitAction('admin.adverts.adverts.edit')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Редагувати</button>
                <button @click="submitAction('admin.adverts.adverts.photos')" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">Фото</button>
                <button v-if="isOnModeration" @click="submitAction('admin.adverts.adverts.moderate')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Модерувати</button>
                <button v-if="isOnModeration || isActive" @click="submitAction('admin.adverts.adverts.reject')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Відхилити</button>
                <button @click="submitAction('admin.adverts.adverts.destroy')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Видалити</button>
            </div>

            <h1 class="text-3xl font-bold text-gray-900">{{ advert.title }}</h1>
            <p class="text-gray-600 mt-1">Адреса: {{ advert.region?.region }} {{ advert.address }}</p>
            <hr>
            <p class="text-gray-600 mt-1">Автор: {{advert.user?.first_name + ' '+   advert.user.name }}</p>

            <div v-if="photos.length" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                <img v-for="photo in photos" :key="photo.id" :src="photo.file" class="w-full h-32 object-cover rounded-lg shadow" />
            </div>

            <p class="mt-4 text-gray-800">{{ advert.content }}</p>

            <div class="mt-6">
                <h2 class="text-xl font-semibold text-gray-900">Категорія:</h2>
                <ul class="list-disc list-inside text-gray-800">
                    <li v-for="ancestor in props.category.ancestors" :key="ancestor.id">{{ ancestor.name }}</li>
                    <li >{{ category.name }}</li>
                </ul>
            </div>


            <table class="w-full mt-6 border-collapse border border-gray-200">
                <tbody>
                <tr v-for="attribute in categoryAttributes" :key="attribute.id" class="border-b">
                    <td class="border px-4 py-2 font-medium text-gray-700">{{ attribute.name }}</td>
                    <td class="border px-4 py-2 text-gray-800">{{ getValue(attribute.name) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>
