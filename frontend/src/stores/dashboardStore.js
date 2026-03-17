// src/stores/dashboardStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/api'

export const useDashboardStore = defineStore('dashboard', () => {
  // Loading state
  const isLoading = ref(false)
  const error = ref(null)

  // User data
  const user = ref({
    name: 'John Doe',
    email: 'john.doe@example.com',
    avatar: 'JD',
  })

  // Courses data
  const courses = ref([])

  // Recent activities
  const recentActivities = ref([])

  // Upcoming deadlines
  const deadlines = ref([])

  // Dashboard statistics
  const stats = ref({
    totalCourses: 0,
    completedCourses: 0,
    inProgressCourses: 0,
    averageGrade: '0%',
    totalHoursSpent: 0,
    certificatesEarned: 0,
  })

  // Weekly activity data
  const weeklyActivity = ref({
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    data: [0, 0, 0, 0, 0, 0, 0],
  })

  /**
   * Fetch all dashboard data from API
   */
  async function fetchDashboardData() {
    isLoading.value = true
    error.value = null

    try {
      const response = await api.getDashboard()

      if (response.success && response.data) {
        // Set user data
        if (response.data.user) {
          user.value = {
            name: response.data.user.name || 'User',
            email: response.data.user.email || '',
            avatar: response.data.user.avatar || response.data.user.name?.charAt(0) || 'U',
          }
        }

        // Set courses
        if (response.data.courses) {
          courses.value = response.data.courses.map((course) => ({
            id: course.id,
            title: course.title,
            instructor: course.instructor,
            progress: course.progress,
            thumbnail: course.thumbnail,
            category: course.category,
            enrolledDate: course.enrolled_date,
            nextAssignment: course.next_assignment,
            grade: course.grade,
          }))
        }

        // Set recent activities
        if (response.data.recentActivities) {
          recentActivities.value = response.data.recentActivities.map((activity) => ({
            id: activity.id,
            type: activity.type,
            course: activity.course,
            description: activity.description,
            time: activity.time,
            icon: activity.icon,
          }))
        }

        // Set deadlines
        if (response.data.deadlines) {
          deadlines.value = response.data.deadlines.map((deadline) => ({
            id: deadline.id,
            course: deadline.course,
            task: deadline.task,
            dueDate: deadline.due_date,
            priority: deadline.priority,
          }))
        }

        // Set stats
        if (response.data.stats) {
          stats.value = {
            totalCourses: response.data.stats.total_courses || 0,
            completedCourses: response.data.stats.completed_courses || 0,
            inProgressCourses: response.data.stats.in_progress_courses || 0,
            averageGrade: response.data.stats.average_grade || '0%',
            totalHoursSpent: response.data.stats.total_hours_spent || 0,
            certificatesEarned: response.data.stats.certificates_earned || 0,
          }
        }

        // Set weekly activity
        if (response.data.weeklyActivity) {
          weeklyActivity.value = {
            labels: response.data.weeklyActivity.labels || [
              'Mon',
              'Tue',
              'Wed',
              'Thu',
              'Fri',
              'Sat',
              'Sun',
            ],
            data: response.data.weeklyActivity.data || [0, 0, 0, 0, 0, 0, 0],
          }
        }
      }
    } catch (err) {
      console.error('Failed to fetch dashboard data:', err)
      error.value = err.message || 'Failed to load dashboard data'
      // Keep using default/empty values on error
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Update user profile
   */
  async function updateUserProfile(data) {
    try {
      const response = await api.updateUser(data)
      if (response.success && response.data) {
        user.value = {
          name: response.data.name,
          email: response.data.email,
          avatar: response.data.avatar,
        }
      }
      return response
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  /**
   * Fetch courses
   */
  async function fetchCourses(params = {}) {
    try {
      const response = await api.getCourses(params)
      if (response.success && response.data) {
        courses.value = response.data.map((course) => ({
          id: course.id,
          title: course.title,
          instructor: course.instructor,
          progress: course.progress,
          thumbnail: course.thumbnail,
          category: course.category,
          enrolledDate: course.enrolledDate,
          nextAssignment: course.nextAssignment,
          grade: course.grade,
        }))
      }
      return response
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  /**
   * Add new course
   */
  async function addCourse(data) {
    try {
      const response = await api.addCourse(data)
      if (response.success && response.data) {
        courses.value.unshift({
          id: response.data.id,
          title: response.data.title,
          instructor: response.data.instructor,
          progress: response.data.progress,
          thumbnail: response.data.thumbnail,
          category: response.data.category,
          enrolledDate: response.data.enrolledDate,
          nextAssignment: response.data.nextAssignment,
          grade: response.data.grade,
        })
      }
      return response
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  /**
   * Fetch activities
   */
  async function fetchActivities(limit = 10) {
    try {
      const response = await api.getActivities(limit)
      if (response.success && response.data) {
        recentActivities.value = response.data.map((activity) => ({
          id: activity.id,
          type: activity.type,
          course: activity.course,
          description: activity.description,
          time: activity.time,
          icon: activity.icon,
        }))
      }
      return response
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  /**
   * Fetch deadlines
   */
  async function fetchDeadlines() {
    try {
      const response = await api.getDeadlines()
      if (response.success && response.data) {
        deadlines.value = response.data.map((deadline) => ({
          id: deadline.id,
          course: deadline.course,
          task: deadline.task,
          dueDate: deadline.dueDate,
          priority: deadline.priority,
        }))
      }
      return response
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  return {
    // State
    isLoading,
    error,
    user,
    courses,
    recentActivities,
    deadlines,
    stats,
    weeklyActivity,

    // Actions
    fetchDashboardData,
    updateUserProfile,
    fetchCourses,
    addCourse,
    fetchActivities,
    fetchDeadlines,
  }
})
