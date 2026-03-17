<!-- src/components/ActivityChart.vue -->
<template>
  <div class="card">
    <div class="card-header bg-white">
      <h6 class="mb-0">Weekly Activity</h6>
    </div>
    <div class="card-body">
      <Bar :data="chartData" :options="chartOptions" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  activityData: {
    type: Object,
    required: true,
  },
})

const chartData = computed(() => ({
  labels: props.activityData.labels,
  datasets: [
    {
      label: 'Hours Studied',
      data: props.activityData.data,
      backgroundColor: '#0d6efd',
      borderRadius: 5,
    },
  ],
}))

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      display: false,
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        display: true,
        color: '#f0f0f0',
      },
    },
    x: {
      grid: {
        display: false,
      },
    },
  },
}
</script>
