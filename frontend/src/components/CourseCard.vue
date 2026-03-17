<!-- src/components/CourseCard.vue -->
<template>
  <div class="card course-card h-100 luxury-card">
    <div class="card-image-wrapper">
      <img
        :src="course.thumbnail"
        class="card-img-top"
        :alt="course.title"
        style="height: 150px; object-fit: cover"
      />
      <div class="card-image-overlay"></div>
      <span class="badge category-badge">{{ course.category }}</span>
    </div>
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h5 class="card-title mb-0">{{ course.title }}</h5>
      </div>
      <p class="text-muted small mb-2">
        <i class="bi bi-person-circle me-1 icon-bounce"></i> {{ course.instructor }}
      </p>

      <!-- Progress bar -->
      <div class="mb-3">
        <div class="d-flex justify-content-between mb-1">
          <small>Progress</small>
          <small class="fw-bold">{{ course.progress }}%</small>
        </div>
        <div class="progress progress-animated">
          <div class="progress-bar" :style="{ width: course.progress + '%' }"></div>
        </div>
      </div>

      <!-- Next assignment -->
      <div class="alert alert-info py-2 mb-0 small">
        <i class="bi bi-exclamation-circle me-1 gentle-bob"></i>
        {{ course.nextAssignment }}
      </div>
    </div>
    <div class="card-footer bg-transparent">
      <div class="d-flex justify-content-between align-items-center">
        <small class="text-muted">
          <i class="bi bi-calendar me-1"></i>
          Enrolled: {{ formatDate(course.enrolledDate) }}
        </small>
        <span class="badge bg-light text-dark golden-pulse">Grade: {{ course.grade }}</span>
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
