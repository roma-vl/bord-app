<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';

const advert = usePage().props.advert;

const photosAdvert = [
  {
    id: 1,
    file: 'https://img.freepik.com/free-photo/funny-duck-3d-illustration_183364-80316.jpg?t=st=1739995450~exp=1739999050~hmac=ff47a4a1fc83795e58a4f89455cb048ca2ec288e1daef9ce6dfdabc6d3d83c17&w=740',
  },
  {
    id: 2,
    file: 'https://img.freepik.com/premium-vector/happy-turkey-cartoon-running_49499-219.jpg?w=996',
  },
  {
    id: 3,
    file: 'https://img.freepik.com/premium-vector/cute-frog-cartoon-character-cowboy-style_1639-50300.jpg?w=826',
  },
  {
    id: 4,
    file: 'https://img.freepik.com/free-vector/cartoon-fish-with-big-fangs-cartoon-sticker_1308-78081.jpg?t=st=1739995609~exp=1739999209~hmac=9bfdcb21ac2064741ee81dcc13c969de3587d638580f54cde8988feccefe46dd&w=1380',
  },
  {
    id: 5,
    file: 'https://img.freepik.com/free-photo/fun-horse_183364-80091.jpg?t=st=1739995631~exp=1739999231~hmac=7802289d2705e7a371830f773f35677a0902b774c5a7ea8214ec4a1d0e3dad59&w=996',
  },
];

const photos = ref(photosAdvert || []);
const mainPhoto = ref(advert.main_photo || null);

const addPhoto = (event) => {
  const files = event.target.files;
  for (let file of files) {
    const reader = new FileReader();
    reader.onload = (e) => {
      photos.value.push({
        id: Date.now(),
        file: e.target.result,
        isNew: true,
      });
    };
    reader.readAsDataURL(file);
  }
};

const removePhoto = (id) => {
  photos.value = photos.value.filter((photo) => photo.id !== id);
};

const setMainPhoto = (photo) => {
  mainPhoto.value = photo.file;
};
</script>

<template>
  <Head title="Редагувати Фото Оголошення" />
  <AuthenticatedLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-5">
          <h2 class="text-xl font-semibold mb-4">Редагувати Фото Оголошення</h2>

          <div v-if="mainPhoto" class="mb-4">
            <h3 class="text-lg font-medium">Головне фото</h3>
            <img :src="mainPhoto" alt="Головне фото" class="w-full max-w-sm rounded-lg shadow-md" />
          </div>

          <div class="grid grid-cols-3 gap-3 mb-4">
            <div v-for="photo in photos" :key="photo.id" class="relative group">
              <img
                :src="photo.file"
                alt="Фото"
                class="w-full h-40 object-cover rounded-md shadow-md"
              />
              <div
                class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center space-x-2 transition"
              >
                <button
                  @click="setMainPhoto(photo)"
                  class="bg-green-500 text-white px-2 py-1 rounded-md z-50"
                >
                  Головне
                </button>
                <button
                  @click="removePhoto(photo.id)"
                  class="bg-red-500 text-white px-2 py-1 rounded-md"
                >
                  Видалити
                </button>
              </div>
            </div>
          </div>

          <div
            class="p-4 border-dashed border-2 border-gray-300 rounded-md text-center cursor-pointer hover:bg-gray-100"
          >
            <input type="file" multiple @change="addPhoto" class="hidden" id="photoUpload" />
            <label for="photoUpload" class="text-blue-500 cursor-pointer">Додати фото</label>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
