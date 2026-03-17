<!-- src/views/Profile.vue -->
<template>
  <div class="profile">
    <h4 class="mb-4">Profile Settings</h4>

    <div class="row">
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <div
              class="avatar-circle mx-auto mb-3"
              style="width: 100px; height: 100px; font-size: 2rem"
            >
              {{ user.avatar }}
            </div>
            <h5>{{ user.name }}</h5>
            <p class="text-muted">{{ user.email }}</p>
            <button class="btn btn-outline-primary btn-sm">
              <i class="bi bi-camera"></i> Change Photo
            </button>
          </div>
        </div>

        <div class="card mt-3">
          <div class="card-body">
            <h6>Account Statistics</h6>
            <hr />
            <div class="d-flex justify-content-between mb-2">
              <span>Member since</span>
              <span class="fw-bold">January 2024</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Courses completed</span>
              <span class="fw-bold">{{ stats.completedCourses }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span>Certificates earned</span>
              <span class="fw-bold">{{ stats.certificatesEarned }}</span>
            </div>
            <div class="d-flex justify-content-between">
              <span>Total hours</span>
              <span class="fw-bold">{{ stats.totalHoursSpent }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-8">
        <div class="card">
          <div class="card-body">
            <h6>Personal Information</h6>
            <hr />
            <form @submit.prevent="saveProfile">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" v-model="profileForm.name" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" v-model="profileForm.email" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Phone</label>
                  <input type="tel" class="form-control" v-model="profileForm.phone" />
                </div>
                <div class="col-md-6">
                  <label class="form-label">Location</label>
                  <input type="text" class="form-control" v-model="profileForm.location" />
                </div>
                <div class="col-12">
                  <label class="form-label">Bio</label>
                  <textarea class="form-control" rows="3" v-model="profileForm.bio"></textarea>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="card mt-3">
          <div class="card-body">
            <h6>Change Password</h6>
            <hr />
            <form @submit.prevent="changePassword">
              <div class="row g-3">
                <div class="col-md-4">
                  <input type="password" class="form-control" placeholder="Current Password" />
                </div>
                <div class="col-md-4">
                  <input type="password" class="form-control" placeholder="New Password" />
                </div>
                <div class="col-md-4">
                  <input type="password" class="form-control" placeholder="Confirm Password" />
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-warning">Update Password</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useDashboardStore } from '../stores/dashboardStore'

const store = useDashboardStore()
const user = computed(() => store.user)
const stats = computed(() => store.stats)

const profileForm = ref({
  name: user.value.name,
  email: user.value.email,
  phone: '+1 234 567 8900',
  location: 'New York, USA',
  bio: 'Passionate learner interested in web development and data science.',
})

const saveProfile = () => {
  alert('Profile updated successfully!')
}

const changePassword = () => {
  alert('Password changed successfully!')
}
</script>
