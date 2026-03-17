// API Service for Moodle Dashboard Frontend
// Connects to PHP backend

// Use PHP built-in server URL or adjust for your setup
// For PHP built-in server: use '/api'
// For Apache with .htaccess: use '/moodle-dashboard/backend/api'
const API_BASE_URL = 'http://localhost:8000/api'

/**
 * Fetch wrapper with error handling
 * @param {string} url - API endpoint
 * @param {object} options - Fetch options
 * @returns {Promise<any>}
 */
async function fetchAPI(url, options = {}) {
  const defaultOptions = {
    headers: {
      'Content-Type': 'application/json',
    },
  }

  const config = { ...defaultOptions, ...options }

  try {
    const response = await fetch(`${API_BASE_URL}${url}`, config)
    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.error || 'Something went wrong')
    }

    return data
  } catch (error) {
    console.error('API Error:', error)
    throw error
  }
}

// API endpoints
export const api = {
  // Dashboard - Get all data at once
  getDashboard: () => fetchAPI('/dashboard'),

  // User endpoints
  getUser: () => fetchAPI('/user'),
  updateUser: (data) =>
    fetchAPI('/user', {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  changePassword: (data) =>
    fetchAPI('/user', {
      method: 'PUT',
      body: JSON.stringify(data),
    }),

  // Courses endpoints
  getCourses: (params = {}) => {
    const queryString = new URLSearchParams(params).toString()
    return fetchAPI(`/courses${queryString ? '?' + queryString : ''}`)
  },
  addCourse: (data) =>
    fetchAPI('/courses', {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  updateCourse: (data) =>
    fetchAPI('/courses', {
      method: 'PUT',
      body: JSON.stringify(data),
    }),
  deleteCourse: (id) =>
    fetchAPI(`/courses?id=${id}`, {
      method: 'DELETE',
    }),

  // Activities endpoints
  getActivities: (limit = 10) => fetchAPI(`/activities?limit=${limit}`),
  addActivity: (data) =>
    fetchAPI('/activities', {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  deleteActivity: (id) =>
    fetchAPI(`/activities?id=${id}`, {
      method: 'DELETE',
    }),

  // Deadlines endpoints
  getDeadlines: () => fetchAPI('/deadlines'),
  addDeadline: (data) =>
    fetchAPI('/deadlines', {
      method: 'POST',
      body: JSON.stringify(data),
    }),
  updateDeadline: (data) =>
    fetchAPI('/deadlines', {
      method: 'PUT',
      body: JSON.stringify(data),
    }),
  deleteDeadline: (id) =>
    fetchAPI(`/deadlines?id=${id}`, {
      method: 'DELETE',
    }),

  // Stats endpoints
  getStats: () => fetchAPI('/stats'),
  updateStats: (data) =>
    fetchAPI('/stats', {
      method: 'PUT',
      body: JSON.stringify(data),
    }),

  // Weekly Activity endpoints
  getWeeklyActivity: () => fetchAPI('/weekly-activity'),
  updateWeeklyActivity: (data) =>
    fetchAPI('/weekly-activity', {
      method: 'PUT',
      body: JSON.stringify(data),
    }),
}

export default api
