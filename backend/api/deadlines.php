<?php
/**
 * Deadlines API
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
            // Get all deadlines
            $stmt = $pdo->prepare("
                SELECT id, course, task, due_date as dueDate, priority 
                FROM deadlines 
                WHERE user_id = ?
                ORDER BY due_date ASC
            ");
            $stmt->execute([$userId]);
            $deadlines = $stmt->fetchAll();

            successResponse($deadlines);
            break;

        case 'POST':
            // Add new deadline
            $input = json_decode(file_get_contents('php://input'), true);

            $required = ['course', 'task', 'due_date'];
            foreach ($required as $field) {
                if (!isset($input[$field])) {
                    errorResponse("Field $field is required", 400);
                }
            }

            $stmt = $pdo->prepare("
                INSERT INTO deadlines (course, task, due_date, priority, user_id)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $input['course'],
                $input['task'],
                $input['due_date'],
                $input['priority'] ?? 'medium',
                $userId
            ]);

            $deadlineId = $pdo->lastInsertId();

            // Get the inserted deadline
            $stmt = $pdo->prepare("
                SELECT id, course, task, due_date as dueDate, priority 
                FROM deadlines WHERE id = ?
            ");
            $stmt->execute([$deadlineId]);
            $deadline = $stmt->fetch();

            successResponse($deadline, 201);
            break;

        case 'PUT':
            // Update deadline
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['id'])) {
                errorResponse('Deadline ID is required', 400);
            }

            $fields = [];
            $params = [];

            $updatable = ['course', 'task', 'due_date', 'priority'];
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

            $sql = "UPDATE deadlines SET " . implode(', ', $fields) . " WHERE id = ? AND user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Get updated deadline
            $stmt = $pdo->prepare("
                SELECT id, course, task, due_date as dueDate, priority 
                FROM deadlines WHERE id = ? AND user_id = ?
            ");
            $stmt->execute([$input['id'], $userId]);
            $deadline = $stmt->fetch();

            if ($deadline) {
                successResponse($deadline);
            } else {
                errorResponse('Deadline not found', 404);
            }
            break;

        case 'DELETE':
            // Delete deadline
            if (!isset($_GET['id'])) {
                errorResponse('Deadline ID is required', 400);
            }

            $stmt = $pdo->prepare("DELETE FROM deadlines WHERE id = ? AND user_id = ?");
            $stmt->execute([$_GET['id'], $userId]);

            successResponse(['message' => 'Deadline deleted successfully']);
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (Exception $e) {
    error_log("Deadlines API Error: " . $e->getMessage());
    errorResponse('Internal server error', 500);
}
