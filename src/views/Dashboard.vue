<!-- src/views/Dashboard.vue -->
<template>
  <div class="dashboard">
    <!-- Welcome section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4>Welcome back, {{ user.name }}!</h4>
      <div>
        <button class="btn btn-outline-primary me-2"><i class="bi bi-download"></i> Reports</button>
        <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> New Course</button>
      </div>
    </div>

    <!-- Statistics cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-3" v-for="(stat, index) in stats" :key="index">
        <div class="stat-card p-3 rounded shadow-sm">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="text-muted mb-2">{{ stat.label }}</h6>
              <h3 class="mb-0">{{ stat.value }}</h3>
            </div>
            <div :class="`bg-${stat.color} bg-opacity-10 p-3 rounded`">
              <i :class="`bi ${stat.icon} fs-3 text-${stat.color}`"></i>
            </div>
          </div>
          <small :class="`text-${stat.trend > 0 ? 'success' : 'danger'}`">
            <i :class="`bi bi-arrow-${stat.trend > 0 ? 'up' : 'down'}`"></i>
            {{ Math.abs(stat.trend) }}% from last month
          </small>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <!-- Chart and Activity -->
      <div class="col-lg-8">
        <ActivityChart :activityData="weeklyActivity" class="mb-4" />

        <!-- Recent Activities -->
        <div class="card">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Recent Activities</h6>
            <a href="#" class="text-decoration-none">View All</a>
          </div>
          <div class="list-group list-group-flush">
            <div
              v-for="activity in recentActivities"
              :key="activity.id"
              class="list-group-item activity-item"
            >
              <div class="d-flex align-items-center">
                <div class="me-3">
                  <i :class="`bi ${activity.icon} fs-4 text-primary`"></i>
                </div>
                <div class="flex-grow-1">
                  <p class="mb-0 fw-bold">{{ activity.description }}</p>
                  <small class="text-muted">{{ activity.course }} • {{ activity.time }}</small>
                </div>
                <i class="bi bi-chevron-right text-muted"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Sidebar - Deadlines and Upcoming -->
      <div class="col-lg-4">
        <!-- Upcoming Deadlines -->
        <div class="card mb-4">
          <div class="card-header bg-white">
            <h6 class="mb-0">Upcoming Deadlines</h6>
          </div>
          <div class="card-body">
            <div v-for="deadline in deadlines" :key="deadline.id" class="mb-3">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h6 class="mb-0">{{ deadline.task }}</h6>
                  <small class="text-muted">{{ deadline.course }}</small>
                </div>
                <span :class="`badge bg-${getDeadlineBadge(deadline.priority)}`">
                  {{ getDaysLeft(deadline.dueDate) }} days
                </span>
              </div>
              <div class="progress mt-2" style="height: 3px">
                <div class="progress-bar bg-warning" style="width: 75%"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Continue Learning -->
        <div class="card">
          <div class="card-header bg-white">
            <h6 class="mb-0">Continue Learning</h6>
          </div>
          <div class="card-body">
            <div v-for="course in courses.slice(0, 3)" :key="course.id" class="mb-3">
              <div class="d-flex align-items-center">
                <img
                  :src="course.thumbnail"
                  :alt="course.title"
                  style="width: 50px; height: 50px; object-fit: cover"
                  class="rounded me-2"
                />
                <div class="flex-grow-1">
                  <h6 class="mb-0 small">{{ course.title }}</h6>
                  <small class="text-muted">{{ course.instructor }}</small>
                  <div class="progress mt-1" style="height: 3px">
                    <div class="progress-bar" :style="{ width: course.progress + '%' }"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- My Courses Section -->
    <div class="mt-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5>My Courses</h5>
        <router-link to="/courses" class="btn btn-link">View All Courses</router-link>
      </div>
      <div class="row g-4">
        <div v-for="course in courses.slice(0, 4)" :key="course.id" class="col-md-3">
          <CourseCard :course="course" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useDashboardStore } from '../stores/dashboardStore'
import ActivityChart from '../components/ActivityChart.vue'
import CourseCard from '../components/CourseCard.vue'

const store = useDashboardStore()
const user = computed(() => store.user)
const courses = computed(() => store.courses)
const recentActivities = computed(() => store.recentActivities)
const deadlines = computed(() => store.deadlines)
const weeklyActivity = computed(() => store.weeklyActivity)

// Statistics with icons and colors
const stats = computed(() => [
  {
    label: 'Total Courses',
    value: store.stats.totalCourses,
    icon: 'bi-book',
    color: 'primary',
    trend: 12,
  },
  {
    label: 'In Progress',
    value: store.stats.inProgressCourses,
    icon: 'bi-play-circle',
    color: 'warning',
    trend: 8,
  },
  {
    label: 'Average Grade',
    value: store.stats.averageGrade,
    icon: 'bi-trophy',
    color: 'success',
    trend: 5,
  },
  {
    label: 'Hours Spent',
    value: store.stats.totalHoursSpent,
    icon: 'bi-clock-history',
    color: 'info',
    trend: -3,
  },
])

const getDaysLeft = (dueDate) => {
  const today = new Date()
  const due = new Date(dueDate)
  const diffTime = due - today
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  return diffDays
}

const getDeadlineBadge = (priority) => {
  const badges = {
    urgent: 'danger',
    high: 'warning',
    medium: 'info',
    low: 'success',
  }
  return badges[priority] || 'secondary'
}
</script>
