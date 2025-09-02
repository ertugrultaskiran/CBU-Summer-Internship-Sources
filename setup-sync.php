<?php
// Video sync tablosunu oluştur
require_once 'config/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    
    $sql = "CREATE TABLE IF NOT EXISTS video_sync (
        video_id VARCHAR(20) PRIMARY KEY,
        last_sync DATETIME NOT NULL,
        comment_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);
    echo "✅ Video sync tablosu oluşturuldu!<br>";
    echo "✅ Anlık senkronizasyon sistemi hazır!<br>";
    echo "<br><strong>Yeni Özellikler:</strong><br>";
    echo "• 🔄 Silinen yorumlar otomatik panelden kalkacak<br>";
    echo "• 📝 Güncellenen yorumlar (beğeni, metin) yenilenecek<br>";
    echo "• ⚡ Yeni yorumlar 10 saniyede panele düşecek<br>";
    echo "• 🎯 YouTube ile tam senkronizasyon<br>";
    
} catch (Exception $e) {
    echo "❌ Hata: " . $e->getMessage();
}
?>
