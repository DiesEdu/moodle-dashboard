<?php
/**
 * Database Configuration
 * Moodle Dashboard Backend
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'moodle_dashboard');
define('DB_USER', 'root'); // Change this to your MySQL username
define('DB_PASS', 'tester');     // Change this to your MySQL password

/**
 * Get database connection
 * @return PDO|null
 */
function getDBConnection()
{
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database Connection Error: " . $e->getMessage());
        return null;
    }
}

/**
 * Set JSON response headers
 */
function setJsonHeaders()
{
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

/**
 * Send JSON response
 * @param mixed $data
 * @param int $statusCode
 */
function jsonResponse($data, $statusCode = 200)
{
    setJsonHeaders();
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * Send error response
 * @param string $message
 * @param int $statusCode
 */
function errorResponse($message, $statusCode = 400)
{
    jsonResponse([
        'success' => false,
        'error' => $message
    ], $statusCode);
}

/**
 * Send success response
 * @param mixed $data
 * @param int $statusCode
 */
function successResponse($data, $statusCode = 200)
{
    jsonResponse([
        'success' => true,
        'data' => $data
    ], $statusCode);
}
