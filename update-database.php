<?php
// Database tablosunu güncelle
require_once 'config/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    
    // Yeni kolonları ekle
    $alterQueries = [
        "ALTER TABLE comments ADD COLUMN video_url VARCHAR(200) AFTER video_title",
        "ALTER TABLE comments ADD COLUMN video_view_count BIGINT DEFAULT 0 AFTER video_url",
        "ALTER TABLE comments ADD COLUMN video_like_count INT DEFAULT 0 AFTER video_view_count",
        "ALTER TABLE comments ADD COLUMN video_published_at DATETIME AFTER video_like_count",
        "ALTER TABLE comments ADD COLUMN channel_id VARCHAR(50) AFTER video_published_at",
        "ALTER TABLE comments ADD COLUMN channel_title VARCHAR(200) AFTER channel_id",
        "ALTER TABLE comments ADD INDEX idx_channel_id (channel_id)"
    ];
    
    foreach ($alterQueries as $query) {
        try {
            $pdo->exec($query);
            echo "✅ Executed: " . substr($query, 0, 50) . "...<br>";
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false || 
                strpos($e->getMessage(), 'Duplicate key name') !== false) {
                echo "⚠️ Already exists: " . substr($query, 0, 50) . "...<br>";
            } else {
                echo "❌ Error: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    echo "<br>✅ Database updated successfully!<br>";
    echo "✅ Kanal analizi için yeni kolonlar eklendi!<br>";
    
} catch (Exception $e) {
    echo "❌ Database connection error: " . $e->getMessage();
}
?>
