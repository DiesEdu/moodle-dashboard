<?php
/**
 * Weekly Activity API
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
            // Get weekly activity data
            $stmt = $pdo->prepare("
                SELECT day, hours 
                FROM weekly_activity 
                WHERE user_id = ?
                ORDER BY FIELD(day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')
            ");
            $stmt->execute([$userId]);
            $activities = $stmt->fetchAll();

            // Format for frontend
            $labels = [];
            $data = [];

            foreach ($activities as $activity) {
                $labels[] = $activity['day'];
                $data[] = $activity['hours'];
            }

            successResponse([
                'labels' => $labels,
                'data' => $data
            ]);
            break;

        case 'PUT':
            // Update weekly activity
            $input = json_decode(file_get_contents('php://input'), true);

            if (!isset($input['data']) || !is_array($input['data'])) {
                errorResponse('Weekly data array is required', 400);
            }

            // Days order
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

            // Clear existing data and insert new
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("DELETE FROM weekly_activity WHERE user_id = ?");
            $stmt->execute([$userId]);

            $stmt = $pdo->prepare("INSERT INTO weekly_activity (user_id, day, hours) VALUES (?, ?, ?)");

            foreach ($input['data'] as $index => $hours) {
                $day = $days[$index] ?? "Day" . ($index + 1);
                $stmt->execute([$userId, $day, $hours]);
            }

            $pdo->commit();

            // Get updated data
            $stmt = $pdo->prepare("
                SELECT day, hours 
                FROM weekly_activity 
                WHERE user_id = ?
                ORDER BY FIELD(day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')
            ");
            $stmt->execute([$userId]);
            $activities = $stmt->fetchAll();

            $labels = [];
            $data = [];

            foreach ($activities as $activity) {
                $labels[] = $activity['day'];
                $data[] = $activity['hours'];
            }

            successResponse([
                'labels' => $labels,
                'data' => $data
            ]);
            break;

        default:
            errorResponse('Method not allowed', 405);
    }

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Weekly Activity API Error: " . $e->getMessage());
    errorResponse('Internal server error', 500);
}
