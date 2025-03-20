<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration - Using SQLite
define('DB_PATH', __DIR__ . '/../database.sqlite');

// Application configuration
define('SITE_NAME', 'Music Streaming');
define('BASE_URL', 'http://localhost:8000');

// File upload paths
define('UPLOAD_PATH', __DIR__ . '/../uploads');
define('MUSIC_PATH', UPLOAD_PATH . '/music');
define('COVER_PATH', UPLOAD_PATH . '/covers');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Create upload directories if they don't exist
if (!file_exists(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
if (!file_exists(MUSIC_PATH)) mkdir(MUSIC_PATH, 0755, true);
if (!file_exists(COVER_PATH)) mkdir(COVER_PATH, 0755, true);