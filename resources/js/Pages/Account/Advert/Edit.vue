<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import AdvertFileUpload from '@/Pages/Account/Advert/Partials/AdvertFileUpload.vue';

const props = defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
  attributes: {
    type: Array,
    default: () => [],
  },
  activeAttributes: {
    type: Array,
    default: () => [],
  },
  regions: {
    type: Array,
    default: () => [],
  },
  advert: {
    type: Object,
    default: () => ({}),
  },
});
const showLocationDropdown = ref(false);
const loadingCities = ref(false);
const citySearchQuery = ref(props.advert.region[0].name);
const filteredCities = ref([]);
const form = useForm({
  category_id: props.advert.category_id,
  country_id: props.advert.country_id,
  region_id: props.advert.region_id,
  area_id: props.advert.area_id,
  village_id: props.advert.village_id,
  title: props.advert.title,
  price: props.advert.price,
  address: props.advert.address,
  content: props.advert.content,
  attributes: {},
  images: props.advert.images.map((img) => ({ type: 'existing', file: img.file, id: img.id })),
});

watch(
  () => form.category_id,
  async (newCategoryId) => {
    if (!newCategoryId) return;

    try {
      const response = await axios.get(
        route('account.adverts.attributes', { categoryId: newCategoryId })
      );
      attributes.value = response.data ?? [];

      form.attributes = {};
      for (const attr of attributes.value) {
        form.attributes[attr.id] = '';
      }
    } catch (error) {
      console.error('Помилка завантаження атрибутів', error);
    }
  }
);

const attributes = ref(props.attributes || []);

for (const attr of attributes.value) {
  form.attributes[attr.id] = props.activeAttributes?.[attr.id] ?? '';
}

const submit = () => {
  const payload = {};
  Object.keys(form).forEach((key) => {
    if (key !== 'images' && key !== 'attributes') {
      payload[key] = form[key];
    }
  });

  Object.entries(form.attributes).forEach(([key, value]) => {
    payload[`attributes${key}`] = value;
  });

  form.images.forEach((image, index) => {
    payload[`images[${index}]`] = image;
  });

  form.post(route('account.adverts.update', props.advert.id), {
    data: payload,
    forceFormData: true,
    onSuccess: () => {
      console.log('Оголошення оновлено');
    },
    onError: (errors) => {
      console.error('Помилка при відправці форми', errors);
    },
  });
};

const getCategoryOptions = (categories, prefix = '') => {
  let options = [];
  categories.forEach((category) => {
    options.push({ id: category.id, name: prefix + category.name });

    if (category.children_recursive && category.children_recursive.length) {
      options = options.concat(getCategoryOptions(category.children_recursive, prefix + '- '));
    }
  });
  return options;
};
const formattedCategories = computed(() => getCategoryOptions(props.categories));

const selectCity = (city) => {
  citySearchQuery.value = city.name;
  form.region_id = city.id;
  showLocationDropdown.value = false;
};
const searchCities = async () => {
  if (citySearchQuery.value.length < 2) {
    filteredCities.value = [];
    return;
  }

  loadingCities.value = true;
  try {
    const response = await axios.get(
      route('adverts.regions.search', { region: citySearchQuery.value })
    );
    filteredCities.value = response.data.regions;
    if (response.data.regions.length) {
      showLocationDropdown.value = true;
    }
  } finally {
    loadingCities.value = false;
  }
};
watch(citySearchQuery, searchCities);
const handleClickOutside = (event) => {
  if (!event.target.closest('.search-container')) {
    showLocationDropdown.value = false;
  }
};
onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <Head title="Оголошення" />
  <AuthenticatedLayout>
    <div class="py-6">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow sm:rounded-lg p-6">
          <div class="px-4">
            Редагувати оголошення
            <form @submit.prevent="submit">
              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Назва</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
              </div>
              <InputError
                class="mt-2"
                :message="form.errors.title"
              />

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Ціна</label>
                <input
                  v-model="form.price"
                  type="number"
                  required
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
              </div>
              <InputError
                class="mt-2"
                :message="form.errors.price"
              />

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Категорія</label>
                <select
                  v-model="form.category_id"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
                  <option
                    v-for="category in formattedCategories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.name }}
                  </option>
                </select>
              </div>
              <InputError
                class="mt-2"
                :message="form.errors.category_id"
              />

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Фото</label>
                <AdvertFileUpload v-model="form.images" />
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Місцезнаходження</label>
                <div class="relative search-container">
                  <input
                    v-model="citySearchQuery"
                    type="text"
                    class="w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-2 focus:ring-green-600 transition duration-200"
                    placeholder="Почніть вводити адресу"
                  >

                  <div
                    v-if="showLocationDropdown"
                    class="absolute left-0 w-full bg-white border mt-1 rounded-lg shadow-lg z-10 h-[400px] overflow-y-auto"
                  >
                    <ul v-if="filteredCities.length">
                      <li
                        v-for="city in filteredCities"
                        :key="city.id"
                        class="px-4 py-2 cursor-pointer hover:bg-gray-200 transition duration-200"
                        @click="selectCity(city)"
                      >
                        {{ city.name }}
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Адреса</label>
                <input
                  v-model="form.address"
                  type="text"
                  required
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                >
              </div>

              <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Опис</label>
                <textarea
                  v-model="form.content"
                  class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                />
              </div>
              <InputError
                class="mt-2"
                :message="form.errors.content"
              />

              <div v-if="attributes && attributes.length > 0">
                <h3 class="text-lg font-medium">
                  Атрибути
                </h3>
                <div
                  v-for="attribute in attributes"
                  :key="attribute"
                  class="mb-4"
                >
                  <label
                    :for="'attributes.' + attribute.id"
                    class="block text-sm font-medium mb-2"
                  >
                    {{ attribute.name }}
                  </label>

                  <template v-if="attribute.variants && attribute.variants.length">
                    <select
                      :id="'attributes.' + attribute.id"
                      v-model="form.attributes[attribute.id]"
                      class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                      <option value="" />
                      <option
                        v-for="variant in attribute.variants"
                        :key="variant"
                        :value="variant"
                      >
                        {{ variant }}
                      </option>
                    </select>
                  </template>

                  <template v-else-if="attribute.type === 'integer' || attribute.type === 'float'">
                    <input
                      :id="'attributes.' + attribute.id"
                      v-model="form.attributes[attribute.id]"
                      type="number"
                      class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                  </template>

                  <template v-else>
                    <input
                      :id="'attributes.' + attribute.id"
                      v-model="form.attributes[attribute.id]"
                      type="text"
                      class="w-full border-gray-300 p-2 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                  </template>
                </div>
              </div>
              <button
                type="submit"
                class="mt-6 bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                Створити
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
