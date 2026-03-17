<?php
/**
 * Moodle Dashboard Backend
 * Main Entry Point
 * 
 * This file serves as the main entry point for the Moodle Dashboard backend.
 * It provides API information and health check functionality.
 */

require_once __DIR__ . '/config/database.php';

// Set JSON headers
setJsonHeaders();

// Get the requested path
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

// Remove query string from URI
$requestUri = strtok($requestUri, '?');

// Get the path relative to the script
$path = str_replace('/index.php', '', $requestUri);
$path = trim($path, '/');

// If path is empty, try getting from query string (for PHP built-in server)
if (empty($path) && isset($_GET['endpoint'])) {
    $path = $_GET['endpoint'];
}

// Parse query string
parse_str($_SERVER['QUERY_STRING'] ?? '', $query);

// Route based on path
switch ($path) {
    case '':
    case 'index.php':
        // API Information / Health Check
        successResponse([
            'name' => 'Moodle Dashboard API',
            'version' => '1.0.0',
            'status' => 'running',
            'timestamp' => date('c'),
            'endpoints' => [
                'api' => 'api/',
                'user' => 'api/user',
                'courses' => 'api/courses',
                'activities' => 'api/activities',
                'deadlines' => 'api/deadlines',
                'stats' => 'api/stats',
                'weekly-activity' => 'api/weekly-activity',
                'dashboard' => 'api/dashboard'
            ],
            'database' => [
                'connected' => getDBConnection() !== null
            ]
        ]);
        break;

    case 'api':
        // Redirect to API router
        require_once __DIR__ . '/api/index.php';
        break;

    case 'health':
        // Health check endpoint
        $dbConnected = getDBConnection() !== null;
        $status = $dbConnected ? 'healthy' : 'degraded';
        $statusCode = $dbConnected ? 200 : 503;

        successResponse([
            'status' => $status,
            'timestamp' => date('c'),
            'checks' => [
                'database' => $dbConnected ? 'connected' : 'disconnected',
                'php' => 'running'
            ]
        ], $statusCode);
        break;

    case 'info':
        // Detailed API information
        successResponse([
            'name' => 'Moodle Dashboard API',
            'version' => '1.0.0',
            'description' => 'RESTful API for Moodle Dashboard frontend',
            'endpoints' => [
                [
                    'path' => '/api',
                    'method' => 'GET',
                    'description' => 'API router - route to specific endpoints'
                ],
                [
                    'path' => '/api/user',
                    'method' => 'GET',
                    'description' => 'Get user information'
                ],
                [
                    'path' => '/api/courses',
                    'method' => 'GET',
                    'description' => 'Get enrolled courses'
                ],
                [
                    'path' => '/api/activities',
                    'method' => 'GET',
                    'description' => 'Get recent activities'
                ],
                [
                    'path' => '/api/deadlines',
                    'method' => 'GET',
                    'description' => 'Get upcoming deadlines'
                ],
                [
                    'path' => '/api/stats',
                    'method' => 'GET',
                    'description' => 'Get user statistics'
                ],
                [
                    'path' => '/api/weekly-activity',
                    'method' => 'GET',
                    'description' => 'Get weekly activity data'
                ],
                [
                    'path' => '/api/dashboard',
                    'method' => 'GET',
                    'description' => 'Get all dashboard data at once'
                ],
                [
                    'path' => '/health',
                    'method' => 'GET',
                    'description' => 'Health check endpoint'
                ]
            ]
        ]);
        break;

    default:
        // Check if it's an API endpoint
        if (strpos($path, 'api/') === 0) {
            require_once __DIR__ . '/api/index.php';
        } else {
            errorResponse('Endpoint not found. Available endpoints: /, /api, /health, /info', 404);
        }
}
