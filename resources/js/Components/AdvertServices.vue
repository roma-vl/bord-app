<script setup>
import { computed, ref } from 'vue';
import axios from 'axios';
import TooltipIcon from '@/Components/TooltipIcon.vue';
import { route } from 'ziggy-js';
import { router } from '@inertiajs/vue3';
import { getFullPathForStaticImage } from '@/helpers.js';

const props = defineProps({
  advert: Object,
});

const services = ref([
  {
    key: 'highlight',
    title: 'Виокремлення',
    description: 'Виділення оголошення на 7 днів',
    tooltip:
      'Ваше оголошення буде виділено жовтим фоном на 7 днів. Вартість послуги – 25 грн. \n' +
      'Виділення оголошення - чудовий спосіб зробити Ваше оголошення яскравішим та помітнішим на тлі інших оголошень',
    price: 25,
  },
  {
    key: 'pin',
    title: 'Закріплення',
    description: 'Закріплення на початку списку',
    tooltip:
      'Ваше оголошення на вибрану кількість днів підніметься на початок списку оголошень схожої тематики і ' +
      'не опускатиметься вниз списку при додаванні нових безкоштовних оголошень, а протягом усього періоду дії послуги ' +
      'перебуватиме нагорі списку розділу, потрапляючи в поле зору відвідувачів сайту. Вартість послуги 15 грн на день',
    price: 15,
  },
  {
    key: 'urgent',
    title: 'Терміново',
    description: 'Позначка “Терміново” на 7 днів',
    tooltip:
      'Ваше оголошення буде позначено написом «Терміново» на період 7 днів. Вартість послуги – 25 грн \n' +
      '- Ви привертаєте більше уваги, а значить отримуєте більше відгуків \n' +
      '- Ваше оголошення стає більш помітним серед інших пропозицій',
    price: 25,
  },
  {
    key: 'premium',
    title: 'Преміум',
    description: 'На головній у блоці "Преміум"',
    tooltip:
      'Ваше оголошення на день закріплюється в блоці Преміум оголошення; на головній сторінці сайту. Вартість послуги 25 грн',
    price: 25,
  },
]);

const packages = ref([
  {
    key: 'turbo7',
    title: 'Пакет "Турбо 7"',
    description:
      'Це найбільш зручний варіант виділення оголошення на сайті на цілий тиждень. Вартість такого пакету послуг складає 49 грн. Він включає всі види просування на сайті:\n' +
      '- Закріплення оголошення на 7 днів\n' +
      '- Виділення оголошення на 7 днів\n' +
      '- Позначка "Терміново" на 7 днів\n' +
      '- Закріплення оголошення у блоці "Преміум оголошення" на головній сторінці сайту на 7 днів',
    price: 49,
    includes: ['highlight', 'pin', 'urgent', 'premium'],
  },
  {
    key: 'turbo30',
    title: 'Пакет "Турбо 30"',
    description:
      'Це найбільш зручний варіант виділення оголошення на сайті на цілий тиждень. Вартість такого пакету послуг складає 49 грн. Він включає всі види просування на сайті:\n' +
      '- Закріплення оголошення на 30 днів\n' +
      '- Виділення оголошення на 30 днів\n' +
      '- Позначка "Терміново" на 30 днів\n' +
      '- Закріплення оголошення у блоці "Преміум оголошення" на головній сторінці сайту на 30 днів',
    price: 149,
    includes: ['highlight', 'pin', 'urgent', 'premium'],
  },
  {
    key: 'maximal',
    title: 'Пакет "Максимальний" *"',
    description:
      'Це найбільш зручний варіант виділення оголошення на сайті цілий місяць. Вартість такого пакету послуг складає 149 грн. Він включає всі види просування на сайті:\n' +
      '- Закріплення оголошення на 30 днів\n' +
      '- Виділення оголошення на 30 днів\n' +
      '- Позначка "Терміново" на 30 днів\n' +
      '- Закріплення оголошення у блоці "Преміум оголошення" на головній сторінці сайту на 30 днів\n' +
      '- Розміщення посилання на оголошення в групах БАЗАРу у соціальних мережах Facebook, Viber, Instagram, Telegram, Pinterest, VK, OK (за наявності якісного зображення в оголошенні)',
    price: 199,
    includes: ['highlight', 'pin', 'urgent', 'premium'],
  },
]);

const selectedPackages = ref([]);
const selectedServices = ref([]);
const couponCode = ref('');

const serviceMap = computed(() => Object.fromEntries(services.value.map((s) => [s.key, s])));

const totalPrice = computed(() => {
  const serviceKeysInPackages = new Set(
    packages.value.filter((p) => selectedPackages.value.includes(p.key)).flatMap((p) => p.includes)
  );

  const uniqueServices = selectedServices.value.filter((key) => !serviceKeysInPackages.has(key));

  const serviceSum = uniqueServices.reduce((sum, key) => {
    const service = serviceMap.value[key];
    return sum + (service?.price || 0);
  }, 0);

  const packageSum = selectedPackages.value.reduce((sum, key) => {
    const pack = packages.value.find((p) => p.key === key);
    return sum + (pack?.price || 0);
  }, 0);

  return serviceSum + packageSum;
});

const canSubmit = computed(
  () => selectedPackages.value.length > 0 || selectedServices.value.length > 0
);

const submit = async () => {
  try {
    await axios.post(route('account.adverts.purchase', props.advert.id), {
      types: [...selectedPackages.value, ...selectedServices.value],
      couponCode: couponCode.value,
    });
    alert('Послуги активовано!');
    selectedPackages.value = [];
    selectedServices.value = [];
  } catch (e) {
    alert('Помилка покупки');
  }
};
const repeatPurchase = (type) => {
  router.post(route('account.adverts.extend', advert.id), { type });
};
</script>
<template>
  <div class="max-w-2xl mx-auto space-y-6 pb-4">
    <h2 class="text-2xl font-bold">
      Послуги просування
    </h2>

    <!-- 🧳 Пакети -->
    <div>
      <h3 class="text-lg font-semibold mb-2">
        Пакети
      </h3>
      <div class="flex flex-col gap-4">
        <div
          v-for="pack in packages"
          :key="pack.key"
          class="border-l-4 border-yellow-500 bg-yellow-100/70 hover:bg-yellow-50 transition-all duration-200 p-5 rounded-xl shadow-md"
        >
          <label class="flex flex-col sm:flex-row sm:items-start gap-3 cursor-pointer">
            <input
              v-model="selectedPackages"
              type="checkbox"
              class="form-checkbox h-6 w-6 mt-1 accent-yellow-600"
              :value="pack.key"
            >
            <div>
              <h4 class="font-bold text-xl text-yellow-900">
                {{ pack.title }} — {{ pack.price }} грн
              </h4>
              <p class="text-sm text-gray-700 whitespace-pre-line mt-1">{{ pack.description }}</p>
              <ul class="list-disc ml-4 text-sm mt-2 text-gray-600">
                <li
                  v-for="service in pack.includes"
                  :key="service"
                >
                  {{ serviceMap[service]?.title || service }}
                </li>
              </ul>
            </div>
          </label>
        </div>
      </div>
    </div>

    <!-- 🧩 Окремі послуги -->
    <div>
      <h3 class="text-lg font-semibold mb-2">
        Окремі послуги
      </h3>
      <div class="flex flex-col gap-4">
        <div
          v-for="service in services"
          :key="service.key"
          class="border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 p-5 rounded-xl shadow-sm"
        >
          <label class="flex flex-col sm:flex-row sm:items-start gap-3 cursor-pointer">
            <input
              v-model="selectedServices"
              type="checkbox"
              class="form-checkbox mt-1 h-6 w-6 accent-blue-600"
              :value="service.key"
            >
            <div class="w-full">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <h4 class="font-semibold text-base text-gray-900">{{ service.title }}</h4>
                  <TooltipIcon :message="service.tooltip" />
                </div>
                <span class="text-sm font-bold text-green-700">💰 {{ service.price }} грн</span>
              </div>
              <p class="text-sm text-gray-600 mt-1">{{ service.description }}</p>
            </div>
          </label>

          <div class="mt-3 text-right">
            <button
              class="text-sm text-blue-600 hover:underline"
              @click="repeatPurchase(service.type)"
            >
              🔁 Продовжити
            </button>
          </div>
        </div>
      </div>
    </div>
    <input
      v-model="couponCode"
      type="text"
      placeholder="Промокод"
      class="input w-full mt-4 px-4 py-3 text-base rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500"
    >

    <div v-if="canSubmit">
      <h3 class="text-lg font-semibold mb-3">
        Метод оплати
      </h3>
      <div class="mt-6 border rounded-lg p-5 shadow bg-gray-50 dark:bg-gray-800 dark:text-white">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
          <label class="flex items-center gap-3">
            <input
              type="radio"
              name="payment_method"
              value="liqpay"
              checked
              disabled
            >
            <img
              :src="getFullPathForStaticImage('images/pays/liqpay_logo.png')"
              class="w-20"
              alt="LiqPay"
            >
            <span class="text-base font-medium">LiqPay</span>
          </label>
          <span class="text-sm text-gray-500">(доступний тільки LiqPay)</span>
        </div>
      </div>
    </div>

    <div
      v-if="canSubmit"
      class="fixed bottom-0 left-0 w-full bg-white dark:bg-gray-900 border-t px-4 sm:px-6 py-3 sm:py-4 shadow-xl flex flex-col sm:flex-row sm:justify-between sm:items-center z-50 gap-2"
    >
      <div
        class="text-base sm:text-lg font-semibold text-gray-800 dark:text-white text-center sm:text-left"
      >
        Обрано: {{ selectedPackages.length + selectedServices.length }} | Сума: {{ totalPrice }} грн
      </div>
      <button
        :disabled="!canSubmit"
        class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-5 py-3 rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all duration-200 disabled:opacity-50 w-full sm:w-auto"
        @click="submit"
      >
        ✅ Купити вибране
      </button>
    </div>
  </div>
</template>
