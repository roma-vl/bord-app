<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import FlashMessage from '@/Components/FlashMessage.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const flash = usePage().props.flash;
const countries = ref([]);
const regions = ref({});
const areas = ref({});
const villages = ref({});

const expanded = ref({
  region: null,
});

const modalOpen = ref(false);
const modalData = ref(null);

const openModal = async (located, parentId = null) => {
  form.type = located;
  form.parent_id = parentId;
  modalOpen.value = true;
};

const form = useForm({
  name: '',
  parent_id: null,
});

const closeModal = () => {
  modalOpen.value = false;
  form.name = '';
  form.type = '';
};

const submitForm = () => {
  router.post(route('admin.locations.store'), form, {
    onSuccess: () => {
      closeModal();
      loadCountries();
    },
  });
};

const loadCountries = async () => {
  try {
    const response = await axios.get(route('admin.locations.countries'));
    countries.value = response.data;
  } catch (error) {
    console.error('Помилка завантаження країн', error);
  }
};

const loadRegions = async (countryId) => {
  if (regions.value[countryId]) {
    expanded.value.country = expanded.value.country === countryId ? null : countryId;
    return;
  }
  try {
    const response = await axios.get(route('admin.locations.regions', { countryId }));
    regions.value[countryId] = response.data;
    expanded.value.country = countryId;
  } catch (error) {
    console.error('Помилка завантаження регіонів', error);
  }
};

const loadAreas = async (regionId) => {
  if (areas.value[regionId]) {
    expanded.value.region = expanded.value.region === regionId ? null : regionId;
    return;
  }
  try {
    const response = await axios.get(route('admin.locations.areas', { regionId }));
    areas.value[regionId] = response.data;
    expanded.value.region = regionId;
  } catch (error) {
    console.error('Помилка завантаження районів', error);
  }
};

const loadVillages = async (areaId) => {
  if (villages.value[areaId]) {
    expanded.value.area = expanded.value.area === areaId ? null : areaId;
    return;
  }
  try {
    const response = await axios.get(route('admin.locations.villages', { areaId }));
    villages.value[areaId] = response.data;
    expanded.value.area = areaId;
  } catch (error) {
    console.error('Помилка завантаження сіл', error);
  }
};

const deleteLocation = (id, type) => {
  if (confirm(`Ви впевнені, що хочете видалити ${type}?`)) {
    router.delete(
      route('admin.locations.destroy', {
        type: type,
        id: id,
      }),
      {
        preserveScroll: true,
        onSuccess: () => router.replace(route('admin.locations.index')),
      }
    );
  }
};

const modalInfo = computed(() => {
  if (form.type === 'village') {
    return `Додати село`;
  } else if (form.type === 'area') {
    return `Додати район`;
  } else if (form.type === 'region') {
    return 'Додати область';
  } else {
    return 'Додати країну';
  }
});

loadCountries();
</script>

<template>
  <Head title="Локації" />
  <AdminLayout>
    <div class="py-2">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <FlashMessage v-if="flash" :flash="flash" />
        <div class="mb-4 flex justify-end">
          <button
            @click="openModal('country', null)"
            type="submit"
            class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
          >
            + Додати Країну
          </button>
        </div>
        <div class="min-w-full bg-white rounded-lg shadow p-6 min-h-[700px]">
          <ul class="space-y-2">
            <li v-for="country in countries" :key="country.id">
              <div
                class="flex justify-between items-center bg-white-100 hover:bg-gray-100 p-4 mb-2 rounded cursor-pointer shadow-md"
                @click="loadRegions(country.id)"
              >
                <span class="font-semibold ml-3">
                  {{ country.name }}
                </span>
                <div class="flex items-right">
                  <button @click.stop="openModal('region', country.id)" class="text-green-500 pr-2">
                    Додати область
                  </button>
                  <button
                    v-if="country.id !== 1"
                    @click.stop="deleteLocation(country.id, 'country')"
                    class="text-red-500 hover:underline"
                  >
                    Видалити
                  </button>
                </div>
              </div>

              <ul v-if="expanded.country === country.id" class="ml-6">
                <li v-for="region in regions[country.id] || []" :key="region.id" class="pt-1">
                  <div
                    class="flex justify-between items-center bg-white-100 hover:bg-gray-100 p-3 mb-2 rounded cursor-pointer shadow-md"
                    @click="loadAreas(region.id)"
                  >
                    <span class="ml-3"> {{ region.name }}</span>
                    <div class="flex items-right">
                      <button
                        @click.stop="openModal('area', region.id)"
                        class="text-green-500 pr-2"
                      >
                        Додати район
                      </button>
                      <button
                        @click.stop="deleteLocation(region.id, 'region')"
                        class="text-red-500 hover:underline"
                      >
                        Видалити
                      </button>
                    </div>
                  </div>

                  <ul v-if="expanded.region === region.id" class="ml-6">
                    <li v-for="area in areas[region.id] || []" :key="area.id" class="pt-1">
                      <div
                        class="flex justify-between items-center bg-white-100 hover:bg-gray-100 p-2 ml-3 mb-2 rounded cursor-pointer shadow-md"
                        @click="loadVillages(area.id)"
                      >
                        <span class="ml-3"> {{ area.name }}</span>
                        <div class="flex items-right">
                          <button
                            @click.stop="openModal('village', area.id)"
                            class="text-green-500 pr-2"
                          >
                            Додати село
                          </button>
                          <button
                            @click.stop="deleteLocation(area.id, 'area')"
                            class="text-red-500 hover:underline"
                          >
                            Видалити
                          </button>
                        </div>
                      </div>

                      <ul v-if="expanded.area === area.id" class="ml-6">
                        <li
                          v-for="village in villages[area.id] || []"
                          :key="village.id"
                          class="pt-1"
                        >
                          <div
                            class="flex justify-between items-center bg-white-100 hover:bg-gray-100 p-2 pl-3 mb-2 rounded cursor-pointer shadow-md"
                          >
                            <span class="ml-3"> {{ village.name }}</span>
                            <button
                              @click.stop="deleteLocation(village.id, 'village')"
                              class="text-red-500 hover:underline"
                            >
                              Видалити
                            </button>
                          </div>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
          </ul>

          <div
            v-if="modalOpen"
            class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
          >
            <div class="bg-white p-6 rounded shadow-lg">
              <h2 class="text-xl font-bold mb-4">{{ modalInfo }}</h2>
              <input v-model="form.name" placeholder="Назва" class="border p-2 w-full mb-4" />
              <div class="flex gap-2">
                <button @click="submitForm" class="bg-blue-500 text-white px-4 py-2 rounded">
                  Зберегти
                </button>
                <button @click="closeModal" class="bg-gray-500 text-white px-4 py-2 rounded">
                  Скасувати
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
