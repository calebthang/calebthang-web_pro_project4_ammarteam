<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'web_pro_project4_ammarteam');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application configuration
define('SITE_URL', 'http://localhost/web_pro_project4_ammarteam');
define('SITE_NAME', 'Property Connect');

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');

// Session configuration
ini_set('session.cookie_lifetime', 60 * 60 * 24); // 24 hours
ini_set('session.gc_maxlifetime', 60 * 60 * 24); // 24 hours