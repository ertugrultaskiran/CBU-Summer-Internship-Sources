<?php
// YouTube API Configuration - Example File
// Copy this file to config.php and update with your values

define('YOUTUBE_API_KEY', 'YOUR_YOUTUBE_API_KEY_HERE');
define('DB_HOST', 'localhost');
define('DB_NAME', 'youtube_comments');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL
define('BASE_URL', 'http://localhost/youtube-yorumlar/');

// OAuth2 Configuration (Google Cloud Console'dan alınacak)
// YouTube'a yorum yazmak için gerekli
define('GOOGLE_CLIENT_ID', 'YOUR_GOOGLE_CLIENT_ID_HERE');
define('GOOGLE_CLIENT_SECRET', 'YOUR_GOOGLE_CLIENT_SECRET_HERE');