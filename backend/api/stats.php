<?php
/**
 * Stats API
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
            // Get user stats
            $stmt = $pdo->prepare("
                SELECT total_courses as totalCourses,
                       completed_courses as completedCourses,
                       in_progress_courses as inProgressCourses,
                       average_grade as averageGrade,
                       total_hours_spent as totalHoursSpent,
                       certificates_earned as certificatesEarned
                FROM stats 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            $stats = $stmt->fetch();

            if ($stats) {
                successResponse($stats);
            } else {
                // Return default stats if none exist
                successResponse([
                    'totalCourses' => 0,
                    'completedCourses' => 0,
                    'inProgressCourses' => 0,
                    'averageGrade' => '0%',
                    'totalHoursSpent' => 0,
                    'certificatesEarned' => 0
                ]);
            }
            break;

        case 'PUT':
            // Update stats
            $input = json_decode(file_get_contents('php://input'), true);

            $fields = [];
            $params = [];

            $updatable = ['total_courses', 'completed_courses', 'in_progress_courses', 'average_grade', 'total_hours_spent', 'certificates_earned'];
            foreach ($updatable as $field) {
                $shortField = str_replace('total_', '', str_replace('certificates_', 'certificates', str_replace('in_progress_', 'inProgress', str_replace('average_', 'average', $field))));

                // Check both formats (snake_case and camelCase)
                $inputKey = isset($input[$shortField]) ? $shortField : $field;
                if (isset($input[$inputKey])) {
                    $fields[] = "$field = ?";
                    $params[] = $input[$inputKey];
                }
            }

            if (empty($fields)) {
                errorResponse('No fields to update', 400);
            }

            $params[] = $userId;

            // Check if stats exist
            $stmt = $pdo->prepare("SELECT id FROM stats WHERE user_id = ?");
            $stmt->execute([$userId]);
            $exists = $stmt->fetch();

            if ($exists) {
                $sql = "UPDATE stats SET " . implode(', ', $fields) . " WHERE user_id = ?";
            } else {
                $sql = "INSERT INTO stats SET user_id = ?, " . implode(', ', $fields);
            }

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            // Get updated stats
            $stmt = $pdo->prepare("
                SELECT total_courses as totalCourses,
                       completed_courses as completedCourses,
                       in_progress_courses as inProgressCourses,
                       average_grade as averageGrade,
                       total_hours_spent as totalHoursSpent,
                       certificates_earned as certificatesEarned
                FROM stats 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            $stats = $stmt->fetch();

            successResponse($stats);
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (Exception $e) {
    error_log("Stats API Error: " . $e->getMessage());
    errorResponse('Internal server error', 500);
}
