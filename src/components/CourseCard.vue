<!-- src/components/CourseCard.vue -->
<template>
  <div class="card course-card h-100">
    <img
      :src="course.thumbnail"
      class="card-img-top"
      :alt="course.title"
      style="height: 150px; object-fit: cover"
    />
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h5 class="card-title mb-0">{{ course.title }}</h5>
        <span class="badge bg-primary">{{ course.category }}</span>
      </div>
      <p class="text-muted small mb-2">
        <i class="bi bi-person-circle me-1"></i> {{ course.instructor }}
      </p>

      <!-- Progress bar -->
      <div class="mb-3">
        <div class="d-flex justify-content-between mb-1">
          <small>Progress</small>
          <small class="fw-bold">{{ course.progress }}%</small>
        </div>
        <div class="progress">
          <div class="progress-bar" :style="{ width: course.progress + '%' }"></div>
        </div>
      </div>

      <!-- Next assignment -->
      <div class="alert alert-info py-2 mb-0 small">
        <i class="bi bi-exclamation-circle me-1"></i>
        {{ course.nextAssignment }}
      </div>
    </div>
    <div class="card-footer bg-transparent">
      <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">
          <i class="bi bi-calendar me-1"></i>
          Enrolled: {{ formatDate(course.enrolledDate) }}
        </small>
        <span class="badge bg-light text-dark">Grade: {{ course.grade }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  course: {
    type: Object,
    required: true,
  },
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}
</script>
