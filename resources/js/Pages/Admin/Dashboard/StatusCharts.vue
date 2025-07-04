<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, PieController, ArcElement, Tooltip, Legend } from 'chart.js';
import {
  getStatusText,
  orderStatusColors,
  advertStatusColors,
} from '@/Pages/Admin/Dashboard/helpers.js';

Chart.register(PieController, ArcElement, Tooltip, Legend);

const props = defineProps({
  charts: {
    type: Object,
    required: true,
  },
});

const ordersChart = ref(null);
const advertsChart = ref(null);

let ordersChartInstance = null;
let advertsChartInstance = null;

const createOrdersChart = () => {
  if (ordersChartInstance) ordersChartInstance.destroy();

  const ctx = ordersChart.value.getContext('2d');
  const dataEntries = Object.entries(props.charts.ordersByStatus || {});
  ordersChartInstance = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: dataEntries.map(([status]) => getStatusText(status)),
      datasets: [
        {
          data: dataEntries.map(([, count]) => count),
          backgroundColor: dataEntries.map(([status]) => orderStatusColors[status] || '#6B7280'),
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' },
      },
    },
  });
};

const createAdvertsChart = () => {
  if (advertsChartInstance) advertsChartInstance.destroy();

  const ctx = advertsChart.value.getContext('2d');
  const dataEntries = Object.entries(props.charts.advertsByStatus || {});
  advertsChartInstance = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: dataEntries.map(([status]) => status),
      datasets: [
        {
          data: dataEntries.map(([, count]) => count),
          backgroundColor: dataEntries.map(([status]) => advertStatusColors[status] || '#6B7280'),
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' },
      },
    },
  });
};

onMounted(() => {
  createOrdersChart();
  createAdvertsChart();
});

watch(
  () => props.charts,
  () => {
    createOrdersChart();
    createAdvertsChart();
  }
);
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">
          Замовлення по статусах
        </h3>
      </div>
      <div class="p-6 h-64">
        <canvas ref="ordersChart" />
      </div>
    </div>

    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">
          Оголошення по статусах
        </h3>
      </div>
      <div class="p-6 h-64">
        <canvas ref="advertsChart" />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
