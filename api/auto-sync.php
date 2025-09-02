<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\Database;
use App\YouTubeAPI;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $db = new Database();
    $youtube = new YouTubeAPI();
    
    // Aktif videoları al (son 24 saat içinde eklenen)
    $sql = "SELECT DISTINCT video_id, video_title, MAX(created_at) as last_sync 
            FROM comments 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
            GROUP BY video_id, video_title 
            ORDER BY last_sync DESC 
            LIMIT 5";
    
    $stmt = $db->getConnection()->prepare($sql);
    $stmt->execute();
    $activeVideos = $stmt->fetchAll();
    
    $totalNewComments = 0;
    $updatedVideos = [];
    
    foreach ($activeVideos as $video) {
        try {
            // Son senkronizasyondan sonraki yorumları al
            $newComments = $youtube->getComments($video['video_id'], 50);
            
            // Sadece yeni yorumları kaydet
            $newCount = 0;
            foreach ($newComments as $comment) {
                $comment['video_id'] = $video['video_id'];
                $comment['video_title'] = $video['video_title'];
                
                // Yorum zaten var mı kontrol et
                $checkSql = "SELECT COUNT(*) FROM comments WHERE comment_id = :comment_id";
                $checkStmt = $db->getConnection()->prepare($checkSql);
                $checkStmt->execute(['comment_id' => $comment['comment_id']]);
                
                if ($checkStmt->fetchColumn() == 0) {
                    if ($db->saveComment($comment)) {
                        $newCount++;
                    }
                }
            }
            
            if ($newCount > 0) {
                $updatedVideos[] = [
                    'video_id' => $video['video_id'],
                    'video_title' => $video['video_title'],
                    'new_comments' => $newCount
                ];
                $totalNewComments += $newCount;
            }
            
        } catch (Exception $e) {
            // Video için hata olursa devam et
            continue;
        }
    }
    
    echo json_encode([
        'success' => true,
        'total_new_comments' => $totalNewComments,
        'updated_videos' => $updatedVideos,
        'checked_videos' => count($activeVideos),
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
