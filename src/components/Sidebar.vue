<!-- src/components/Sidebar.vue -->
<template>
  <div class="sidebar p-3">
    <div class="d-flex align-items-center mb-4">
      <i class="bi bi-mortarboard fs-2 text-primary me-2"></i>
      <h4 class="mb-0">MoodleLearn</h4>
    </div>

    <div class="user-info mb-4 p-3 bg-white rounded">
      <div class="d-flex align-items-center">
        <div class="avatar-circle me-2">
          {{ user.avatar }}
        </div>
        <div>
          <h6 class="mb-0">{{ user.name }}</h6>
          <small class="text-muted">{{ user.email }}</small>
        </div>
      </div>
    </div>

    <nav class="nav flex-column">
      <router-link to="/" class="nav-link" :class="{ active: $route.path === '/' }">
        <i class="bi bi-speedometer2"></i> Dashboard
      </router-link>
      <router-link to="/courses" class="nav-link" :class="{ active: $route.path === '/courses' }">
        <i class="bi bi-book"></i> My Courses
      </router-link>
      <router-link to="/profile" class="nav-link" :class="{ active: $route.path === '/profile' }">
        <i class="bi bi-person"></i> Profile
      </router-link>
      <a href="#" class="nav-link"> <i class="bi bi-calendar"></i> Calendar </a>
      <a href="#" class="nav-link">
        <i class="bi bi-envelope"></i> Messages
        <span class="badge bg-danger rounded-pill ms-2">3</span>
      </a>
      <a href="#" class="nav-link"> <i class="bi bi-gear"></i> Settings </a>
    </nav>

    <hr />

    <div class="upcoming-deadlines">
      <h6 class="mb-3">Upcoming Deadlines</h6>
      <div v-for="deadline in deadlines" :key="deadline.id" class="mb-2 p-2 bg-white rounded">
        <small class="text-muted">{{ deadline.course }}</small>
        <p class="mb-0 fw-bold">{{ deadline.task }}</p>
        <small :class="getPriorityClass(deadline.priority)">
          <i class="bi bi-clock"></i> Due: {{ formatDate(deadline.dueDate) }}
        </small>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useDashboardStore } from '../stores/dashboardStore'

const store = useDashboardStore()
const user = computed(() => store.user)
const deadlines = computed(() => store.deadlines)

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}

const getPriorityClass = (priority) => {
  const classes = {
    urgent: 'text-danger',
    high: 'text-warning',
    medium: 'text-info',
    low: 'text-success',
  }
  return classes[priority] || 'text-muted'
}
</script>
