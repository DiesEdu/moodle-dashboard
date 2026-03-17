<?php
/**
 * Activities API
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
            // Get recent activities
            $limit = $_GET['limit'] ?? 10;

            $stmt = $pdo->prepare("
                SELECT id, type, course, description, time, icon 
                FROM activities 
                WHERE user_id = ?
                ORDER BY id DESC
                LIMIT ?
            ");
            $stmt->execute([$userId, $limit]);
            $activities = $stmt->fetchAll();

            successResponse($activities);
            break;

        case 'POST':
            // Add new activity
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ['type', 'course', 'description', 'time'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    errorResponse("Field $field is required", 400);
                }
            }

            // Map type to icon
            $iconMap = [
                'assignment' => 'bi-file-code',
                'quiz' => 'bi-pencil-square',
                'forum' => 'bi-chat-dots',
                'grade' => 'bi-trophy',
                'resource' => 'bi-download'
            ];
            $icon = $iconMap[$input['type']] ?? 'bi-book';

            $stmt = $pdo->prepare("
                INSERT INTO activities (type, course, description, time, icon, user_id)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $input['type'],
                $input['course'],
                $input['description'],
                $input['time'],
                $icon,
                $userId
            ]);

            $activityId = $pdo->lastInsertId();

            // Get the inserted activity
            $stmt = $pdo->prepare("
                SELECT id, type, course, description, time, icon 
                FROM activities WHERE id = ?
            ");
            $stmt->execute([$activityId]);
            $activity = $stmt->fetch();

            successResponse($activity, 201);
            break;

        case 'DELETE':
            // Delete activity
            if (!isset($_GET['id'])) {
                errorResponse('Activity ID is required', 400);
            }

            $stmt = $pdo->prepare("DELETE FROM activities WHERE id = ? AND user_id = ?");
            $stmt->execute([$_GET['id'], $userId]);

            successResponse(['message' => 'Activity deleted successfully']);
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (Exception $e) {
    error_log("Activities API Error: " . $e->getMessage());
    errorResponse('Internal server error', 500);
}
