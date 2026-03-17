<!-- src/App.vue -->
<template>
  <div class="app-wrapper">
    <div class="container-fluid p-0">
      <div class="row g-0">
        <!-- Sidebar for large screens -->
        <div class="col-lg-2 d-none d-lg-block">
          <Sidebar />
        </div>

        <!-- Main content -->
        <div class="col-12 col-lg-10">
          <Header @toggle-sidebar="toggleSidebar" />

          <!-- Mobile sidebar -->
          <div
            v-if="showMobileSidebar"
            class="mobile-sidebar-overlay"
            @click="showMobileSidebar = false"
          >
            <div class="mobile-sidebar" @click.stop>
              <Sidebar />
            </div>
          </div>

          <main class="p-4">
            <router-view />
          </main>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import Sidebar from './components/Sidebar.vue'
import Header from './components/Header.vue'

const showMobileSidebar = ref(false)

const toggleSidebar = () => {
  showMobileSidebar.value = !showMobileSidebar.value
}
</script>

<style>
.mobile-sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1000;
}

.mobile-sidebar {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  width: 280px;
  background-color: white;
  overflow-y: auto;
  z-index: 1001;
}
</style>
