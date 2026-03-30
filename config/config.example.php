<?php
/**
 * config.example.php — Copy this file to config.php and fill in real values.
 * config.php is listed in .gitignore and must NEVER be committed.
 */

// --- Database ---
define('DB_HOST',    'localhost');
define('DB_NAME',    'your_database_name');
define('DB_USER',    'your_database_user');
define('DB_PASS',    'your_database_password');
define('DB_CHARSET', 'utf8mb4');

// --- Application ---
define('APP_ENV', 'production');          // 'development' | 'production'
define('APP_URL', 'https://autotech.net.vn');

// --- Session ---
define('SESSION_NAME',     'autotech_sess');
define('SESSION_LIFETIME', 7200);         // seconds (2 hours)
define('SESSION_SECURE',   true);         // set false if not using HTTPS locally
define('SESSION_HTTPONLY',  true);
