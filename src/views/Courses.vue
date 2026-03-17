<!-- src/views/Courses.vue -->
<template>
  <div class="courses">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>My Courses</h4>
      <div class="d-flex gap-2">
        <div class="input-group" style="width: 300px">
          <span class="input-group-text bg-white">
            <i class="bi bi-search"></i>
          </span>
          <input
            type="text"
            class="form-control"
            v-model="searchQuery"
            placeholder="Search courses..."
          />
        </div>
        <select class="form-select" style="width: auto" v-model="selectedCategory">
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category">{{ category }}</option>
        </select>
      </div>
    </div>

    <!-- Course stats -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="bg-primary text-white p-3 rounded">
          <h6 class="mb-0">Total Courses</h6>
          <h3 class="mb-0">{{ filteredCourses.length }}</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-success text-white p-3 rounded">
          <h6 class="mb-0">Completed</h6>
          <h3 class="mb-0">{{ completedCourses }}</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-warning text-white p-3 rounded">
          <h6 class="mb-0">In Progress</h6>
          <h3 class="mb-0">{{ inProgressCourses }}</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-info text-white p-3 rounded">
          <h6 class="mb-0">Average Progress</h6>
          <h3 class="mb-0">{{ averageProgress }}%</h3>
        </div>
      </div>
    </div>

    <!-- Courses grid -->
    <div class="row g-4">
      <div v-for="course in filteredCourses" :key="course.id" class="col-md-4">
        <CourseCard :course="course" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useDashboardStore } from '../stores/dashboardStore'
import CourseCard from '../components/CourseCard.vue'

const store = useDashboardStore()
const searchQuery = ref('')
const selectedCategory = ref('')

const courses = computed(() => store.courses)

const categories = computed(() => {
  return [...new Set(courses.value.map((c) => c.category))]
})

const filteredCourses = computed(() => {
  return courses.value.filter((course) => {
    const matchesSearch =
      course.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      course.instructor.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !selectedCategory.value || course.category === selectedCategory.value
    return matchesSearch && matchesCategory
  })
})

const completedCourses = computed(() => {
  return filteredCourses.value.filter((c) => c.progress === 100).length
})

const inProgressCourses = computed(() => {
  return filteredCourses.value.filter((c) => c.progress > 0 && c.progress < 100).length
})

const averageProgress = computed(() => {
  const total = filteredCourses.value.reduce((sum, c) => sum + c.progress, 0)
  return filteredCourses.value.length ? Math.round(total / filteredCourses.value.length) : 0
})
</script>
