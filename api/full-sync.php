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
    $input = json_decode(file_get_contents('php://input'), true);
    $videoId = $input['video_id'] ?? null;
    
    if (!$videoId) {
        throw new Exception('Video ID gerekli');
    }
    
    $db = new Database();
    $youtube = new YouTubeAPI();
    
    // Video bilgilerini kontrol et
    $videoInfo = $youtube->getVideoInfo($videoId);
    if (!$videoInfo) {
        throw new Exception('Video bulunamadı veya erişilemiyor');
    }
    
    // YouTube'dan güncel yorumları al (maksimum 500)
    $currentComments = $youtube->getComments($videoId, 500);
    $currentCommentIds = array_column($currentComments, 'comment_id');
    
    // Veritabanındaki bu videoya ait yorumları al
    $dbComments = $db->getCommentsByVideoId($videoId, 1000, 0);
    $dbCommentIds = array_column($dbComments, 'comment_id');
    
    $stats = [
        'new_comments' => 0,
        'updated_comments' => 0,
        'deleted_comments' => 0,
        'total_youtube_comments' => count($currentComments),
        'total_db_comments' => count($dbComments)
    ];
    
    // Silinen yorumları bul ve veritabanından kaldır
    $deletedCommentIds = array_diff($dbCommentIds, $currentCommentIds);
    if (!empty($deletedCommentIds)) {
        $placeholders = str_repeat('?,', count($deletedCommentIds) - 1) . '?';
        $deleteSql = "DELETE FROM comments WHERE comment_id IN ($placeholders) AND video_id = ?";
        $deleteStmt = $db->getConnection()->prepare($deleteSql);
        $deleteParams = array_merge($deletedCommentIds, [$videoId]);
        $deleteStmt->execute($deleteParams);
        $stats['deleted_comments'] = count($deletedCommentIds);
    }
    
    // Yeni ve güncellenmiş yorumları işle
    foreach ($currentComments as $comment) {
        $comment['video_id'] = $videoId;
        $comment['video_title'] = $videoInfo['title'];
        
        // Yorum zaten var mı kontrol et
        $checkSql = "SELECT id, like_count, comment_text FROM comments WHERE comment_id = ?";
        $checkStmt = $db->getConnection()->prepare($checkSql);
        $checkStmt->execute([$comment['comment_id']]);
        $existingComment = $checkStmt->fetch();
        
        if ($existingComment) {
            // Güncelleme gerekli mi kontrol et
            if ($existingComment['like_count'] != $comment['like_count'] || 
                $existingComment['comment_text'] != $comment['comment_text']) {
                
                $updateSql = "UPDATE comments SET 
                             comment_text = ?, 
                             like_count = ?, 
                             created_at = NOW() 
                             WHERE comment_id = ?";
                $updateStmt = $db->getConnection()->prepare($updateSql);
                $updateStmt->execute([
                    $comment['comment_text'],
                    $comment['like_count'],
                    $comment['comment_id']
                ]);
                $stats['updated_comments']++;
            }
        } else {
            // Yeni yorum ekle
            if ($db->saveComment($comment)) {
                $stats['new_comments']++;
            }
        }
    }
    
    // Son senkronizasyon zamanını kaydet
    $syncSql = "INSERT INTO video_sync (video_id, last_sync, comment_count) 
                VALUES (?, NOW(), ?) 
                ON DUPLICATE KEY UPDATE 
                last_sync = NOW(), comment_count = ?";
    $syncStmt = $db->getConnection()->prepare($syncSql);
    $syncStmt->execute([$videoId, count($currentComments), count($currentComments)]);
    
    echo json_encode([
        'success' => true,
        'video_id' => $videoId,
        'video_title' => $videoInfo['title'],
        'stats' => $stats,
        'sync_time' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
