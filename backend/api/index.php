<?php
/**
 * Main API Router
 * Moodle Dashboard Backend
 * 
 * Usage: api/index.php?endpoint=user
 *        api/index.php?endpoint=courses
 *        api/index.php?endpoint=activities
 *        api/index.php?endpoint=deadlines
 *        api/index.php?endpoint=stats
 *        api/index.php?endpoint=weekly-activity
 */

require_once __DIR__ . '/../config/database.php';

setJsonHeaders();

// Get endpoint from query string or from URL path
$endpoint = $_GET['endpoint'] ?? '';

// If endpoint is empty, try to get from URL path (for PHP built-in server)
if (empty($endpoint)) {
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestUri = strtok($requestUri, '?'); // Remove query string
    // Remove /api/ prefix if present
    $endpoint = str_replace('api/', '', $requestUri);
    $endpoint = trim($endpoint, '/');
}

$method = $_SERVER['REQUEST_METHOD'];

// Route to appropriate API
switch ($endpoint) {
    case 'user':
        require_once __DIR__ . '/user.php';
        break;

    case 'courses':
        require_once __DIR__ . '/courses.php';
        break;

    case 'activities':
        require_once __DIR__ . '/activities.php';
        break;

    case 'deadlines':
        require_once __DIR__ . '/deadlines.php';
        break;

    case 'stats':
        require_once __DIR__ . '/stats.php';
        break;

    case 'weekly-activity':
        require_once __DIR__ . '/weekly-activity.php';
        break;

    case 'dashboard':
        // Get all dashboard data at once
        try {
            $pdo = getDBConnection();
            if (!$pdo) {
                errorResponse('Database connection failed', 500);
            }

            $userId = 1;

            // Get user
            $stmt = $pdo->prepare("SELECT id, name, email, avatar FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();

            // Get courses
            $stmt = $pdo->prepare("SELECT * FROM courses WHERE user_id = ? ORDER BY enrolled_date DESC");
            $stmt->execute([$userId]);
            $courses = $stmt->fetchAll();

            // Get activities
            $stmt = $pdo->prepare("SELECT * FROM activities WHERE user_id = ? ORDER BY id DESC LIMIT 10");
            $stmt->execute([$userId]);
            $activities = $stmt->fetchAll();

            // Get deadlines
            $stmt = $pdo->prepare("SELECT * FROM deadlines WHERE user_id = ? ORDER BY due_date ASC");
            $stmt->execute([$userId]);
            $deadlines = $stmt->fetchAll();

            // Get stats
            $stmt = $pdo->prepare("SELECT * FROM stats WHERE user_id = ?");
            $stmt->execute([$userId]);
            $stats = $stmt->fetch();

            // Get weekly activity
            $stmt = $pdo->prepare("SELECT day, hours FROM weekly_activity WHERE user_id = ? ORDER BY FIELD(day, 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun')");
            $stmt->execute([$userId]);
            $weeklyActivityRaw = $stmt->fetchAll();

            $weeklyActivity = [
                'labels' => [],
                'data' => []
            ];
            foreach ($weeklyActivityRaw as $row) {
                $weeklyActivity['labels'][] = $row['day'];
                $weeklyActivity['data'][] = $row['hours'];
            }

            successResponse([
                'user' => $user,
                'courses' => $courses,
                'recentActivities' => $activities,
                'deadlines' => $deadlines,
                'stats' => $stats,
                'weeklyActivity' => $weeklyActivity
            ]);

        } catch (Exception $e) {
            error_log("Dashboard API Error: " . $e->getMessage());
            errorResponse('Internal server error', 500);
        }
        break;

    default:
        errorResponse('Invalid endpoint. Use: user, courses, activities, deadlines, stats, weekly-activity, or dashboard', 404);
}
