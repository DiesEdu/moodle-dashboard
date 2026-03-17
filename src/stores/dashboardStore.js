// src/stores/dashboardStore.js
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useDashboardStore = defineStore('dashboard', () => {
  // User data
  const user = ref({
    name: 'John Doe',
    email: 'john.doe@example.com',
    avatar: 'JD',
  })

  // Courses data
  const courses = ref([
    {
      id: 1,
      title: 'Introduction to Web Development',
      instructor: 'Dr. Sarah Johnson',
      progress: 75,
      thumbnail: 'https://via.placeholder.com/300x200',
      category: 'Programming',
      enrolledDate: '2024-01-15',
      nextAssignment: 'Project Submission - Due in 5 days',
      grade: 'A-',
    },
    {
      id: 2,
      title: 'Data Science Fundamentals',
      instructor: 'Prof. Michael Chen',
      progress: 45,
      thumbnail: 'https://via.placeholder.com/300x200',
      category: 'Data Science',
      enrolledDate: '2024-02-01',
      nextAssignment: 'Quiz 3 - Due in 2 days',
      grade: 'B+',
    },
    {
      id: 3,
      title: 'UI/UX Design Principles',
      instructor: 'Emma Wilson',
      progress: 90,
      thumbnail: 'https://via.placeholder.com/300x200',
      category: 'Design',
      enrolledDate: '2024-01-10',
      nextAssignment: 'Final Project - Due next week',
      grade: 'A',
    },
    {
      id: 4,
      title: 'Cloud Computing with AWS',
      instructor: 'David Brown',
      progress: 30,
      thumbnail: 'https://via.placeholder.com/300x200',
      category: 'Cloud',
      enrolledDate: '2024-03-01',
      nextAssignment: 'Lab 2 - Due tomorrow',
      grade: 'B',
    },
    {
      id: 5,
      title: 'Mobile App Development',
      instructor: 'Lisa Anderson',
      progress: 60,
      thumbnail: 'https://via.placeholder.com/300x200',
      category: 'Programming',
      enrolledDate: '2024-02-15',
      nextAssignment: 'Assignment 3 - Due in 3 days',
      grade: 'A-',
    },
    {
      id: 6,
      title: 'Cybersecurity Basics',
      instructor: 'Robert Taylor',
      progress: 15,
      thumbnail: 'https://via.placeholder.com/300x200',
      category: 'Security',
      enrolledDate: '2024-03-10',
      nextAssignment: 'Week 1 Quiz - Due today',
      grade: 'Not started',
    },
  ])

  // Recent activities
  const recentActivities = ref([
    {
      id: 1,
      type: 'assignment',
      course: 'Web Development',
      description: 'Submitted HTML/CSS project',
      time: '2 hours ago',
      icon: 'bi-file-code',
    },
    {
      id: 2,
      type: 'quiz',
      course: 'Data Science',
      description: 'Completed Quiz 2 - Score: 85%',
      time: 'Yesterday',
      icon: 'bi-pencil-square',
    },
    {
      id: 3,
      type: 'forum',
      course: 'UI/UX Design',
      description: 'Posted in Discussion Forum',
      time: '2 days ago',
      icon: 'bi-chat-dots',
    },
    {
      id: 4,
      type: 'grade',
      course: 'Cloud Computing',
      description: 'Received grade for Lab 1: 92%',
      time: '3 days ago',
      icon: 'bi-trophy',
    },
    {
      id: 5,
      type: 'resource',
      course: 'Mobile Development',
      description: 'Downloaded course materials',
      time: '4 days ago',
      icon: 'bi-download',
    },
  ])

  // Upcoming deadlines
  const deadlines = ref([
    {
      id: 1,
      course: 'Web Development',
      task: 'Final Project Submission',
      dueDate: '2024-04-20',
      priority: 'high',
    },
    {
      id: 2,
      course: 'Data Science',
      task: 'Quiz 3',
      dueDate: '2024-04-15',
      priority: 'medium',
    },
    {
      id: 3,
      course: 'Mobile Development',
      task: 'Assignment 3',
      dueDate: '2024-04-18',
      priority: 'high',
    },
    {
      id: 4,
      course: 'Cloud Computing',
      task: 'Lab 2',
      dueDate: '2024-04-14',
      priority: 'urgent',
    },
  ])

  // Dashboard statistics
  const stats = ref({
    totalCourses: 6,
    completedCourses: 1,
    inProgressCourses: 5,
    averageGrade: '85%',
    totalHoursSpent: 128,
    certificatesEarned: 2,
  })

  // Weekly activity data
  const weeklyActivity = ref({
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    data: [4, 6, 8, 7, 10, 3, 2],
  })

  return {
    user,
    courses,
    recentActivities,
    deadlines,
    stats,
    weeklyActivity,
  }
})
