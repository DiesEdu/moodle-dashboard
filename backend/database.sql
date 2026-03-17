-- Moodle Dashboard Database Schema
-- Run this SQL to create the database and tables

-- Create database
CREATE DATABASE IF NOT EXISTS moodle_dashboard;
USE moodle_dashboard;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(10) DEFAULT 'JD',
    phone VARCHAR(50) DEFAULT '',
    location VARCHAR(255) DEFAULT '',
    bio TEXT DEFAULT,
    member_since DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    instructor VARCHAR(255) NOT NULL,
    progress INT DEFAULT 0,
    thumbnail VARCHAR(500) DEFAULT 'https://via.placeholder.com/300x200',
    category VARCHAR(100) NOT NULL,
    enrolled_date DATE NOT NULL,
    next_assignment VARCHAR(500) DEFAULT '',
    grade VARCHAR(20) DEFAULT 'Not started',
    user_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Activities table
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    course VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    time VARCHAR(100) NOT NULL,
    icon VARCHAR(100) DEFAULT 'bi-book',
    user_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Deadlines table
CREATE TABLE IF NOT EXISTS deadlines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course VARCHAR(255) NOT NULL,
    task VARCHAR(500) NOT NULL,
    due_date DATE NOT NULL,
    priority ENUM('urgent', 'high', 'medium', 'low') DEFAULT 'medium',
    user_id INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Stats table
CREATE TABLE IF NOT EXISTS stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT 1,
    total_courses INT DEFAULT 0,
    completed_courses INT DEFAULT 0,
    in_progress_courses INT DEFAULT 0,
    average_grade VARCHAR(20) DEFAULT '0%',
    total_hours_spent INT DEFAULT 0,
    certificates_earned INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Weekly Activity table
CREATE TABLE IF NOT EXISTS weekly_activity (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT 1,
    day VARCHAR(20) NOT NULL,
    hours INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data for user
INSERT INTO users (id, name, email, password, avatar, phone, location, bio, member_since) 
VALUES (1, 'John Doe', 'john.doe@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'JD', '+1 234 567 8900', 'New York, USA', 'Passionate learner interested in web development and data science.', '2024-01-01')
ON DUPLICATE KEY UPDATE name=name;

-- Insert sample courses
INSERT INTO courses (title, instructor, progress, thumbnail, category, enrolled_date, next_assignment, grade, user_id) VALUES
('Introduction to Web Development', 'Dr. Sarah Johnson', 75, 'https://via.placeholder.com/300x200', 'Programming', '2024-01-15', 'Project Submission - Due in 5 days', 'A-', 1),
('Data Science Fundamentals', 'Prof. Michael Chen', 45, 'https://via.placeholder.com/300x200', 'Data Science', '2024-02-01', 'Quiz 3 - Due in 2 days', 'B+', 1),
('UI/UX Design Principles', 'Emma Wilson', 90, 'https://via.placeholder.com/300x200', 'Design', '2024-01-10', 'Final Project - Due next week', 'A', 1),
('Cloud Computing with AWS', 'David Brown', 30, 'https://via.placeholder.com/300x200', 'Cloud', '2024-03-01', 'Lab 2 - Due tomorrow', 'B', 1),
('Mobile App Development', 'Lisa Anderson', 60, 'https://via.placeholder.com/300x200', 'Programming', '2024-02-15', 'Assignment 3 - Due in 3 days', 'A-', 1),
('Cybersecurity Basics', 'Robert Taylor', 15, 'https://via.placeholder.com/300x200', 'Security', '2024-03-10', 'Week 1 Quiz - Due today', 'Not started', 1)
ON DUPLICATE KEY UPDATE title=title;

-- Insert sample activities
INSERT INTO activities (type, course, description, time, icon, user_id) VALUES
('assignment', 'Web Development', 'Submitted HTML/CSS project', '2 hours ago', 'bi-file-code', 1),
('quiz', 'Data Science', 'Completed Quiz 2 - Score: 85%', 'Yesterday', 'bi-pencil-square', 1),
('forum', 'UI/UX Design', 'Posted in Discussion Forum', '2 days ago', 'bi-chat-dots', 1),
('grade', 'Cloud Computing', 'Received grade for Lab 1: 92%', '3 days ago', 'bi-trophy', 1),
('resource', 'Mobile Development', 'Downloaded course materials', '4 days ago', 'bi-download', 1)
ON DUPLICATE KEY UPDATE description=description;

-- Insert sample deadlines
INSERT INTO deadlines (course, task, due_date, priority, user_id) VALUES
('Web Development', 'Final Project Submission', '2024-04-20', 'high', 1),
('Data Science', 'Quiz 3', '2024-04-15', 'medium', 1),
('Mobile Development', 'Assignment 3', '2024-04-18', 'high', 1),
('Cloud Computing', 'Lab 2', '2024-04-14', 'urgent', 1)
ON DUPLICATE KEY UPDATE task=task;

-- Insert stats
INSERT INTO stats (user_id, total_courses, completed_courses, in_progress_courses, average_grade, total_hours_spent, certificates_earned) 
VALUES (1, 6, 1, 5, '85%', 128, 2)
ON DUPLICATE KEY UPDATE total_courses=total_courses;

-- Insert weekly activity
INSERT INTO weekly_activity (user_id, day, hours) VALUES
(1, 'Mon', 4),
(1, 'Tue', 6),
(1, 'Wed', 8),
(1, 'Thu', 7),
(1, 'Fri', 10),
(1, 'Sat', 3),
(1, 'Sun', 2)
ON DUPLICATE KEY UPDATE hours=hours;
