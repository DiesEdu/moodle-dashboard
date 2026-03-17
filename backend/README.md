# Moodle Dashboard Backend

A PHP-based REST API backend for the Moodle Dashboard frontend.

## Requirements

- PHP 7.4+ or 8.0+
- MySQL 5.7+ or MariaDB 10.3+
- Apache/Nginx web server
- PDO PHP extension

## Installation

### 1. Database Setup

1. Create a new MySQL database:

```sql
CREATE DATABASE moodle_dashboard;
```

2. Import the database schema:

```bash
mysql -u root -p moodle_dashboard < database.sql
```

Or import via phpMyAdmin or any MySQL client.

### 2. Configure Database Connection

Edit `backend/config/database.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'moodle_dashboard');
define('DB_USER', 'root');     // Your MySQL username
define('DB_PASS', '');          // Your MySQL password
```

### 3. Web Server Configuration

#### Option A: Apache (.htaccess)

The `.htaccess` file is already configured. Make sure:

- Apache `mod_rewrite` is enabled
- AllowOverride is set to All in your Apache config

#### Option B: Nginx

Add this to your Nginx server block:

```nginx
location /moodle-dashboard/backend/ {
    index index.php;
    try_files $uri $uri/ /moodle-dashboard/backend/api/index.php?endpoint=$1;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### 4. Test the API

Start your PHP built-in server:

```bash
cd backend
php -S localhost:8000
```

Or use your web server.

## API Endpoints

### Get All Dashboard Data

```
GET /api/index.php?endpoint=dashboard
```

### User

```
GET    /api/user.php         - Get user profile
POST   /api/user.php         - Update user profile
PUT    /api/user.php         - Change password
```

### Courses

```
GET    /api/courses.php              - Get all courses
GET    /api/courses.php?category=xxx - Filter by category
POST   /api/courses.php              - Add new course
PUT    /api/courses.php              - Update course
DELETE /api/courses.php?id=1         - Delete course
```

### Activities

```
GET    /api/activities.php             - Get recent activities
POST   /api/activities.php            - Add new activity
DELETE /api/activities.php?id=1       - Delete activity
```

### Deadlines

```
GET    /api/deadlines.php              - Get all deadlines
POST   /api/deadlines.php              - Add new deadline
PUT    /api/deadlines.php              - Update deadline
DELETE /api/deadlines.php?id=1        - Delete deadline
```

### Stats

```
GET    /api/stats.php         - Get user statistics
PUT    /api/stats.php         - Update statistics
```

### Weekly Activity

```
GET    /api/weekly-activity.php   - Get weekly activity data
PUT    /api/weekly-activity.php   - Update weekly activity
```

## Response Format

All responses follow this format:

```json
{
  "success": true,
  "data": { ... }
}
```

Error responses:

```json
{
  "success": false,
  "error": "Error message"
}
```

## Default User

A default user is created with ID 1 for testing:

- Email: john.doe@example.com
- Password: password (hashed)

## CORS Configuration

The API is configured to accept requests from any origin. For production, update the CORS headers in `config/database.php` to restrict access.

## Security Notes

1. Update the database credentials
2. In production, use HTTPS
3. Implement proper authentication (JWT, sessions, etc.)
4. Add input validation and sanitization
5. Rate limiting for API endpoints

## Project Structure

```
backend/
├── api/
│   ├── index.php          # Main API router
│   ├── user.php           # User endpoints
│   ├── courses.php        # Courses endpoints
│   ├── activities.php     # Activities endpoints
│   ├── deadlines.php      # Deadlines endpoints
│   ├── stats.php          # Stats endpoints
│   └── weekly-activity.php# Weekly activity endpoints
├── config/
│   └── database.php       # Database configuration
├── .htaccess              # Apache routing rules
├── database.sql           # Database schema
└── README.md              # This file
```
