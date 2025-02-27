<script setup>
import {computed, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import HeartIcon from "@/Components/Icon/HeartIcon.vue";
import HeartSolidIcon from "@/Components/Icon/HeartSolidIcon.vue";

const props = defineProps({
    advert: Object,
    values: Array,
    categoryAttributes: Array,
    photos: Array,
    category: Object
});
console.log(props.advert, "props");
const isLiked = ref(false);
const userPhone = ref(false);

const toggleLike = () => {
    isLiked.value = !isLiked.value;
};

const photos = [
    {
        id: 1,
        file: "https://img.freepik.com/free-photo/funny-duck-3d-illustration_183364-80316.jpg?t=st=1739995450~exp=1739999050~hmac=ff47a4a1fc83795e58a4f89455cb048ca2ec288e1daef9ce6dfdabc6d3d83c17&w=740"
    },
    {
        id: 2,
        file: "https://img.freepik.com/premium-vector/happy-turkey-cartoon-running_49499-219.jpg?w=996"},
    {
        id: 3,
        file: "https://img.freepik.com/premium-vector/cute-frog-cartoon-character-cowboy-style_1639-50300.jpg?w=826"
    },
    {
        id: 4,
        file: "https://img.freepik.com/free-vector/cartoon-fish-with-big-fangs-cartoon-sticker_1308-78081.jpg?t=st=1739995609~exp=1739999209~hmac=9bfdcb21ac2064741ee81dcc13c969de3587d638580f54cde8988feccefe46dd&w=1380"
    },
    {
        id: 5,
        file: "https://img.freepik.com/free-photo/fun-horse_183364-80091.jpg?t=st=1739995631~exp=1739999231~hmac=7802289d2705e7a371830f773f35677a0902b774c5a7ea8214ec4a1d0e3dad59&w=996"
    }
];

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

const mainPhoto = ref(photos.length ? photos[0].file : "");

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
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-2">

            <div class=" mx-auto max-w-7xl sm:px-6 lg:px-8 p-6  ">
                <div class="flex justify-between gap-2 mb-6 bg-white p-3 ">
                    <div class="flex flex-row gap-2">
                        <button @click="submitAction('adverts.adverts.edit')"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">Редагувати
                        </button>
                        <button @click="submitAction('adverts.adverts.photos')"
                                class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-1 rounded">Фото
                        </button>
                        <button v-if="isDraft" @click="publish"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Публікувати
                        </button>
                        <button v-if="isActive" @click="submitAction('adverts.adverts.close')"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">Закрити
                        </button>
                        <button v-if="isOnModeration || isActive" @click="toDraft"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded">Повернути в чорновик
                        </button>
                        <button @click="submitAction('adverts.adverts.destroy')"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Видалити
                        </button>
                    </div>

                    <div class=" flex flex-row gap-2 items-center">
                        <button @click="submitAction('admin.adverts.adverts.edit')"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">Редагувати
                        </button>
                        <button @click="submitAction('admin.adverts.adverts.photos')"
                                class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-1 rounded">Фото
                        </button>
                        <button v-if="isOnModeration" @click="submitAction('admin.adverts.adverts.moderate')"
                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">На модерації
                        </button>
                        <button v-if="isOnModeration || isActive" @click="submitAction('admin.adverts.adverts.reject')"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded">Відхилити
                        </button>
                        <button @click="submitAction('admin.adverts.adverts.destroy')"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded">Видалити
                        </button>
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
                <div v-if="isDraft" class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">Це чернетка.</div>
                <div v-if="isOnModeration" class="bg-yellow-100 text-yellow-800 p-3 rounded mb-4">На модерації.</div>
                <div v-if="advert.reject_reason" class="bg-red-100 text-red-800 p-3 rounded mb-4">
                    {{ advert.reject_reason }}
                </div>

                <div class="flex gap-6 ">
                    <!-- Галерея -->
                    <div class="w-2/3 ">
                        <div class="bg-white rounded-lg shadow p-3">
                            <div class="w-full h-[600px] flex justify-center items-center ">
                                <img :src="mainPhoto" class="w-full h-full object-contain " alt=""/>
                            </div>

                            <div class="flex gap-2 mt-3 overflow-x-auto">
                                <img v-for="photo in photos" :key="photo.id" :src="photo.file"
                                     class="w-24 h-24 object-cover rounded-lg cursor-pointer border-2 border-transparent hover:border-blue-500 transition"
                                     @click="setMainPhoto(photo.file)"/>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-3 mt-5">
                            <div class="flex flex-wrap gap-2">
                                <span v-for="attribute in categoryAttributes" :key="attribute.id"
                                      class="border border-gray-700 px-4 py-1 font-medium text-gray-700 rounded-md cursor-pointer">
                                    {{ attribute.name }} : {{ getValue(attribute.name) }}
                                </span>
                            </div>


                            <p class="mt-4 text-gray-900 text-lg font-bold">Опис</p>
                            <p class="mt-4 text-gray-800">{{ advert.content }}</p>
                            <div class="my-4 border border-b-1 mx-3"></div>
                            <div class="flex justify-end">
                                <button class=" hover:underline hover:text-red-600 text-red-400 px-4 py-2 rounded">Поскаржитися
                                </button>
                            </div>
                        </div>

                    </div>


                    <!-- Інформація -->
                    <div class="w-1/3">
                        <div class="rounded-lg shadow p-3 bg-white">
                            <p class="mt-4 text-gray-800 text-sm">
                                Опубліковано {{
                                    new Date(advert.published_at).toLocaleDateString("uk-UA", {
                                        year: "numeric",
                                        month: "long",
                                        day: "numeric",
                                        hour: "2-digit",
                                        minute: "2-digit",
                                    })
                                }}
                            </p>
                            <p class="mt-4 text-gray-800 text-sm">
                                Закінчення {{
                                    new Date(advert.expires_at).toLocaleDateString("uk-UA", {
                                        year: "numeric",
                                        month: "long",
                                        day: "numeric",
                                        hour: "2-digit",
                                        minute: "2-digit",
                                    })
                                }}
                            </p>
                            <button
                                @click="toggleLike"
                                class="px-4 py-2 rounded text-gray-500 hover:text-red-500 transition"
                            >
                                <HeartIcon v-if="!isLiked" class="w-6 h-6"/>
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
                                    <p class="text-gray-600 mt-1">на сайті з {{
                                            new Date(advert.user?.created_at).toLocaleDateString("uk-UA", {
                                                year: "numeric",
                                                month: "long",
                                                day: "numeric"
                                            })
                                        }}</p>
                                </div>
                            </div>
                            <div class="my-4 border border-b-1 mx-3"></div>
                            <div class="flex items-center justify-center">
                                <a href="#" class="text-blue-500 hover:text-blue-600"> Всі оголошення автора > </a>
                            </div>
                        </div>

                        <div class="rounded-lg shadow p-3 bg-white mt-5">
                            <p class="font-bold pb-3">Місцезнаходження</p>
                            <p class="text-gray-600 mt-1">Адреса: {{ advert.region?.region }} {{ advert.address }}</p>
                            <div class="flex items-center justify-center">
                                <img src="https://inweb.ua/blog/wp-content/uploads/2020/09/vstavte-etot-kod-na-svoyu-html-stranitsu-ili-vidzhet.jpg" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
