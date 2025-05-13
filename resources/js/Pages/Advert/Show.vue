<script setup>
import {computed, ref} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import HeartIcon from "@/Components/Icon/HeartIcon.vue";
import HeartSolidIcon from "@/Components/Icon/HeartSolidIcon.vue";
import Reject from "@/Pages/Admin/Advert/Actions/Reject.vue";
import Modal from "@/Components/Modal.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import {getDateFormatFromLocale, getFullPathForImage} from "@/helpers.js";
const props = defineProps({
    advert: Object,
    values: Array,
    photos: Array,
    category: Object,
    isFavorited: Boolean
});

const flash = computed(() => usePage().props.flash);
const isLiked = ref(false);
const userPhone = ref(false);
const isRejectModalOpen = ref(false);
const advertId = ref(null);
const toggleLike = () => {
    if (props.isFavorited === true) {
        router.delete(route("account.favorites.remove", {advert: props.advert.id}));
    } else {
        router.post(route("account.favorites.add", {advert: props.advert.id}));
    }
    isLiked.value = !isLiked.value;
};

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

const mainPhoto = ref(props.photos.length ? getFullPathForImage(props.photos[0].file) : "");

const setMainPhoto = (photo) => {
    mainPhoto.value = photo;
};

const getPhone = async (id) => {
    if (userPhone.value) return;
    try {
        const response = await axios.get(route('adverts.phone', id));
        userPhone.value = response.data;
    } catch (error) {
        console.error("Помилка при завантаженні номера користувача", error);
    }
};

const publish = async () => {
    router.post(route("account.adverts.actions.publish", { advert: props.advert.id }), {
        onSuccess: () => router.replace(route("admin.users.index")),
    });

};

const toDraft = async () => {
    router.post(route("account.adverts.actions.draft", { advert: props.advert.id }), {
        onSuccess: () => router.replace(route("admin.users.index")),
    });

};
const activate = async () => {
    router.post(route("admin.adverts.actions.moderation.active", { advert: props.advert.id }), {
        onSuccess: () => router.replace(route("admin.users.index")),
    });
    console.log(id, "id");
};

const rejectAdvert = () => {
    isRejectModalOpen.value = true;
    advertId.value = props.advert.id;
};
const deleteAdvert = () => {
    if (confirm("Ви впевнені, що хочете видалити оголошення?")) {
        router.delete(route("account.adverts.destroy", props.advert.id));
    }
};

</script>

<template>
    <AuthenticatedLayout>
        <div class="py-2">

            <div class=" mx-auto max-w-7xl sm:px-6 lg:px-8 p-6  ">
                <div class="flex justify-between gap-2 mb-6 bg-white p-3 ">
                    <div class="flex flex-row gap-2">
                        <a :href="route('account.adverts.edit', props.advert.id)"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">Редагувати</a>
                        <a :href="route('account.adverts.edit.photos', props.advert.id)"
                           class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-1 rounded">Фото</a>
                        <button v-if="isDraft" @click="publish" class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Публікувати</button>
                        <button v-if="isActive" @click="submitAction('adverts.adverts.close')"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Закрити</button>
                        <button v-if="isOnModeration || isActive" @click="toDraft"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded">Повернути в чорновик</button>
                        <button @click="deleteAdvert"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Видалити</button>
                    </div>

                    <div v-can="'admin'" class=" flex flex-row gap-2 items-center">
                        <button v-if="isOnModeration" @click="activate"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Опублікувати</button>
                        <button v-if="isOnModeration || isActive" @click="rejectAdvert"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Відхилити</button>
                    </div>
                </div>

                <div class="list-disc list-inside text-gray-800">
                    <span class="underline cursor-pointer"> Головна</span> /
                    <span v-for="ancestor in props.category.ancestors" :key="ancestor.id">
                        <span class="underline cursor-pointer">{{ ancestor.name }}</span> /
                    </span>
                    <span  class="underline cursor-pointer">{{ category.name }}</span> /
                    <span  class="underline cursor-pointer">{{ advert.region?.region }} </span>
                </div>
            </div>
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 p-6 bg-white-50 ">
                <FlashMessage :flash="flash" />
                <div v-if="isDraft" class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">Це чернетка.</div>
                <div v-if="isOnModeration" class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">На модерації.</div>
                <div v-if="advert.reject_reason" class="bg-red-100 text-red-800 p-3 rounded mb-4">
                   Причина відмови: {{ advert.reject_reason }}
                </div>

                <div class="flex gap-6 ">
                    <div class="w-2/3 ">
                        <div class="bg-white rounded-lg shadow p-3">
                            <div class="w-full h-[600px] flex justify-center items-center ">
                                <img :src="mainPhoto" class="w-full h-full object-contain " alt=""/>
                            </div>

                            <div class="flex gap-2 mt-3 overflow-x-auto">
                                <img v-for="photo in props.photos" :key="photo.id" :src="getFullPathForImage(photo.file)"
                                     class="w-24 h-24 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-blue-500 transition"
                                     @click="setMainPhoto(getFullPathForImage(photo.file))"/>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-3 mt-5">
                            <div class="flex flex-wrap gap-2">
                                <span v-for="item in values" :key="item.id"
                                      class="border border-gray-700 px-4 py-1 font-medium text-gray-700 rounded-md cursor-pointer">
                                    {{ item.attribute }} : {{ getValue(item.attribute) }}
                                </span>
                            </div>

                            <p class="mt-4 text-gray-900 text-lg font-bold">Опис</p>
                            <p class="mt-4 text-gray-800">{{ advert.content }}</p>
                            <div class="my-4 border border-b-1 mx-3"></div>
                            <div class="flex justify-end">
                                <button class=" hover:underline hover:text-red-600 text-red-400 px-4 py-2 rounded">
                                    Поскаржитися
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="w-1/3">
                        <div class="rounded-lg shadow p-3 bg-white">
                            <p class="mt-4 text-gray-800 text-sm">
                                Опубліковано {{getDateFormatFromLocale(advert.created_at) }}
                            </p>
                            <p class="mt-4 text-gray-800 text-sm">
                                Закінчення  {{getDateFormatFromLocale(advert.expires_at) }}
                            </p>
                            <button @click="toggleLike"
                                class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition">
                                <HeartIcon v-if="!props.isFavorited" class="w-6 h-6"/>
                                <HeartSolidIcon v-else class="w-6 h-6 text-red-500"/>
                            </button>

                            <h1 class="text-2xl font-bold text-gray-900">{{ advert.title }} </h1>


                            <div class="mt-4 flex flex-row items-center">
                                <h2 class="text-2xl font-bold text-green-600">{{ advert.price }} грн.</h2>
                                <span class="pt-2 text-gray-800 text-sm pl-2"> Договірна</span>
                            </div>

                            <button class=" h-14 rounded-md border-2 hover:border-[5px] hover:bg-white hover:text-blue-500
                            border-blue-500  bg-blue-500 w-full mt-5 mb-5 text-neutral-50 after:absolute after:left-0 after:top-0
                    after:-z-10 after:h-full after:w-full after:rounded-md">
                                <span class="text-lg font-bold">Повідомлення</span>
                            </button>

                            <button @click.prevent="getPhone(advert.id)" class="h-14 rounded-md border-2 hover:border-[5px]
                             border-blue-500 w-full mb-5 text-blue-500 after:absolute after:left-0 after:top-0
                    after:-z-10 after:h-full after:w-full after:rounded-md">
                                <span class="text-lg font-bold">{{userPhone ? userPhone : ' Показати телефон'}}</span>
                            </button>
                        </div>

                        <div class="rounded-lg shadow p-3 bg-white mt-5">
                            <p class="font-bold pb-3">Користувач</p>
                            <div class="flex flex-row">
                                <img class="w-20 h-20 rounded-full" :src="advert.user?.avatar_url" alt="">
                                <div class="mt-4 ">
                                    <p class="text-gray-600 mt-1 text-lg font-bold">
                                        {{ advert.user.name + ' ' + advert.user?.first_name }}</p>
                                    <p class="text-gray-600 mt-1">на сайті з {{getDateFormatFromLocale(advert.user?.created_at) }}</p>
                                </div>
                            </div>
                            <div class="my-4 border border-b-1 mx-3"></div>
                            <div class="flex items-center justify-center">
                                <a href="#" class="text-blue-500 hover:text-blue-600"> Всі оголошення автора > </a>
                            </div>
                        </div>

                        <div class="rounded-lg shadow p-3 bg-white mt-5">
                            <p class="font-bold pb-3">Місцезнаходження</p>
                            <p class="text-gray-600 mt-1">Адреса: {{ advert.region?.name }} {{ advert.address }}</p>
                            <div class="flex items-center justify-center">
                                <img src="https://inweb.ua/blog/wp-content/uploads/2020/09/vstavte-etot-kod-na-svoyu-html-stranitsu-ili-vidzhet.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Modal :show="isRejectModalOpen" maxWidth="2xl" @close="isRejectModalOpen = false">
                <Reject :advertId="advertId" @rejectCreated="isRejectModalOpen = false" />
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
