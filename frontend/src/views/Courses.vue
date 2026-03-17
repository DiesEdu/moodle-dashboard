<!-- src/views/Courses.vue -->
<template>
  <div class="courses">
    <div class="d-flex justify-content-between align-items-center mb-4 animate-fade-in-up">
      <div>
        <h4><i class="bi bi-book me-2"></i>My Courses</h4>
        <p class="text-muted mb-0">Explore and continue your learning journey</p>
      </div>
      <div class="d-flex gap-2">
        <div class="input-group input-focus-animate" style="width: 300px">
          <span class="input-group-text bg-white">
            <i class="bi bi-search"></i>
          </span>
          <input
            type="text"
            class="form-control input-luxury"
            v-model="searchQuery"
            placeholder="Search courses..."
          />
        </div>
        <select class="form-select input-luxury" style="width: auto" v-model="selectedCategory">
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category">{{ category }}</option>
        </select>
      </div>
    </div>

    <!-- Course stats -->
    <div class="row g-3 mb-4 animate-stagger">
      <div class="col-md-3">
        <div class="bg-primary text-white p-3 rounded luxury-card">
          <h6 class="mb-0"><i class="bi bi-collection me-2"></i>Total Courses</h6>
          <h3 class="mb-0 stat-number">{{ filteredCourses.length }}</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-success text-white p-3 rounded luxury-card">
          <h6 class="mb-0"><i class="bi bi-check-circle me-2"></i>Completed</h6>
          <h3 class="mb-0 stat-number">{{ completedCourses }}</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-warning text-white p-3 rounded luxury-card">
          <h6 class="mb-0"><i class="bi bi-play-circle me-2"></i>In Progress</h6>
          <h3 class="mb-0 stat-number">{{ inProgressCourses }}</h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="bg-info text-white p-3 rounded luxury-card">
          <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Average Progress</h6>
          <h3 class="mb-0 stat-number">{{ averageProgress }}%</h3>
        </div>
      </div>
    </div>

    <!-- Courses grid -->
    <div class="row g-4 stagger-reveal">
      <div v-for="course in filteredCourses" :key="course.id" class="col-md-4">
        <CourseCard :course="course" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useDashboardStore } from '../stores/dashboardStore'
import CourseCard from '../components/CourseCard.vue'

const store = useDashboardStore()
const searchQuery = ref('')
const selectedCategory = ref('')

// Fetch courses on mount
onMounted(() => {
  store.fetchCourses()
})

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
