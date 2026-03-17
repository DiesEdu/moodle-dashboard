<?php
/**
 * Courses API
 * Moodle Dashboard Backend
 */

require_once __DIR__ . '/../config/database.php';

setJsonHeaders();

$method = $_SERVER['REQUEST_METHOD'];
$userId = 1; // Default user for demo

try {
    $pdo = getDBConnection();
    if (!$pdo) {
        errorResponse('Database connection failed', 500);
    }

    switch ($method) {
        case 'GET':
            // Get all courses with optional filters
            $category = $_GET['category'] ?? '';
            $search = $_GET['search'] ?? '';

            $sql = "SELECT id, title, instructor, progress, thumbnail, category, 
                           enrolled_date as enrolledDate, next_assignment as nextAssignment, grade 
                    FROM courses WHERE user_id = ?";
            $params = [$userId];

            if ($category) {
                $sql .= " AND category = ?";
                $params[] = $category;
            }

            if ($search) {
                $sql .= " AND (title LIKE ? OR instructor LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }

            $sql .= " ORDER BY enrolled_date DESC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $courses = $stmt->fetchAll();

            successResponse($courses);
            break;

        case 'POST':
            // Add new course
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ['title', 'instructor', 'category', 'enrolled_date'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    errorResponse("Field $field is required", 400);
                }
            }

            $stmt = $pdo->prepare("
                INSERT INTO courses (title, instructor, progress, thumbnail, category, enrolled_date, next_assignment, grade, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $input['title'],
                $input['instructor'],
                $input['progress'] ?? 0,
                $input['thumbnail'] ?? 'https://via.placeholder.com/300x200',
                $input['category'],
                $input['enrolled_date'],
                $input['next_assignment'] ?? '',
                $input['grade'] ?? 'Not started',
                $userId
            ]);

            $courseId = $pdo->lastInsertId();

            // Get the inserted course
            $stmt = $pdo->prepare("
                SELECT id, title, instructor, progress, thumbnail, category, 
                       enrolled_date as enrolledDate, next_assignment as nextAssignment, grade 
                FROM courses WHERE id = ?
            ");
            $stmt->execute([$courseId]);
            $course = $stmt->fetch();

            successResponse($course, 201);
            break;

        case 'PUT':
            // Update course
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['id'])) {
                errorResponse('Course ID is required', 400);
            }

            $fields = [];
            $params = [];

            $updatable = ['title', 'instructor', 'progress', 'thumbnail', 'category', 'enrolled_date', 'next_assignment', 'grade'];
            foreach ($updatable as $field) {
                if (isset($input[$field])) {
                    $dbField = str_replace('_', '_', $field);
                    $fields[] = "$dbField = ?";
                    $params[] = $input[$field];
                }
            }

            if (empty($fields)) {
                errorResponse('No fields to update', 400);
            }

            $params[] = $input['id'];
            $params[] = $userId;

            $sql = "UPDATE courses SET " . implode(', ', $fields) . " WHERE id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Get updated course
            $stmt = $pdo->prepare("
                SELECT id, title, instructor, progress, thumbnail, category, 
                       enrolled_date as enrolledDate, next_assignment as nextAssignment, grade 
                FROM courses WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$input['id'], $userId]);
            $course = $stmt->fetch();

            if ($course) {
                successResponse($course);
            } else {
                errorResponse('Course not found', 404);
            }
            break;

        case 'DELETE':
            // Delete course
            if (!isset($_GET['id'])) {
                errorResponse('Course ID is required', 400);
            }

            $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ? AND user_id = ?");
            $stmt->execute([$_GET['id'], $userId]);

            successResponse(['message' => 'Course deleted successfully']);
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (Exception $e) {
    error_log("Courses API Error: " . $e->getMessage());
    errorResponse('Internal server error', 500);
}
