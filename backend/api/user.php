<?php
/**
 * User API
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
            // Get user profile
            $stmt = $pdo->prepare("
                SELECT id, name, email, avatar, phone, location, bio, member_since 
                FROM users WHERE id = ?
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if ($user) {
                // Format member_since
                $user['member_since'] = date('F Y', strtotime($user['member_since']));
                successResponse($user);
            } else {
                errorResponse('User not found', 404);
            }
            break;

        case 'POST':
            // Update user profile
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['name']) || !isset($input['email'])) {
                errorResponse('Name and email are required', 400);
            }

            $stmt = $pdo->prepare("
                UPDATE users 
                SET name = ?, email = ?, phone = ?, location = ?, bio = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $input['name'],
                $input['email'],
                $input['phone'] ?? '',
                $input['location'] ?? '',
                $input['bio'] ?? '',
                $userId
            ]);

            // Get updated user
            $stmt = $pdo->prepare("
                SELECT id, name, email, avatar, phone, location, bio, member_since 
                FROM users WHERE id = ?
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            successResponse($user);
            break;

        case 'PUT':
            // Change password
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['current_password']) || !isset($input['new_password'])) {
                errorResponse('Current and new password are required', 400);
            }

            // Verify current password (in production, use proper password hashing)
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            if (!password_verify($input['current_password'], $user['password'])) {
                errorResponse('Current password is incorrect', 401);
            }

            // Update password
            $newHash = password_hash($input['new_password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$newHash, $userId]);

            successResponse(['message' => 'Password changed successfully']);
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (Exception $e) {
    error_log("User API Error: " . $e->getMessage());
    errorResponse('Internal server error', 500);
}
