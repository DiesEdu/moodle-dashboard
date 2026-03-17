<!-- src/components/Header.vue -->
<template>
  <nav
    class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 animate-fade-in-down"
  >
    <div class="container-fluid">
      <button
        class="btn btn-outline-secondary d-lg-none btn-luxury"
        @click="$emit('toggle-sidebar')"
      >
        <i class="bi bi-list"></i>
      </button>

      <div class="d-flex align-items-center ms-auto">
        <!-- Search -->
        <div class="input-group me-3 input-focus-animate" style="width: 300px">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search"></i>
          </span>
          <input
            type="text"
            class="form-control input-luxury border-start-0"
            placeholder="Search courses..."
          />
        </div>

        <!-- Notifications -->
        <div class="dropdown me-3">
          <button
            class="btn btn-light position-relative btn-luxury golden-glow"
            data-bs-toggle="dropdown"
          >
            <i class="bi bi-bell icon-bounce"></i>
            <span
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge"
            >
              3
            </span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><h6 class="dropdown-header">Notifications</h6></li>
            <li><a class="dropdown-item" href="#">New assignment in Web Development</a></li>
            <li><a class="dropdown-item" href="#">Quiz graded: Data Science</a></li>
            <li><a class="dropdown-item" href="#">Forum reply on your post</a></li>
          </ul>
        </div>

        <!-- User menu -->
        <div class="dropdown">
          <button
            class="btn btn-light d-flex align-items-center btn-luxury"
            data-bs-toggle="dropdown"
          >
            <div class="avatar-circle me-2 avatar-ring" style="width: 32px; height: 32px">
              {{ user.avatar }}
            </div>
            <span>{{ user.name }}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><router-link to="/profile" class="dropdown-item">Profile</router-link></li>
            <li><hr class="dropdown-divider" /></li>
            <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useDashboardStore } from '../stores/dashboardStore'

const store = useDashboardStore()
const user = computed(() => store.user)

defineEmits(['toggle-sidebar'])
</script>
