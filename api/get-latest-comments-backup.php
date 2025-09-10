<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\Database;
use App\YouTubeAPI;

try {
    $db = new Database();
    $youtube = new YouTubeAPI();
    
    // Son kontrol zamanını al
    $lastCheck = $_GET['last_check'] ?? date('Y-m-d H:i:s', strtotime('-1 minute'));
    $videoId = $_GET['video_id'] ?? null;
    
    $response = [
        'success' => true,
        'new_comments' => [],
        'updated_comments' => [],
        'deleted_comment_ids' => [],
        'total_changes' => 0,
        'last_check' => $lastCheck,
        'current_time' => date('Y-m-d H:i:s'),
        'debug' => [
            'video_id' => $videoId,
            'has_video_id' => !empty($videoId)
        ]
    ];
    
    if ($videoId) {
        // Video ID veya Channel ID olabilir
        $isChannelId = (strlen($videoId) > 11); // Channel ID'ler daha uzun
        
        if ($isChannelId) {
            // Kanal senkronizasyonu
            try {
                $channelVideos = $youtube->getChannelAllVideos($videoId, 10);
                
                foreach ($channelVideos as $video) {
                    // Her video için senkronizasyon yap
                    $currentComments = $youtube->getComments($video['id'], 50);
                    
                    foreach ($currentComments as $comment) {
                        $comment['video_id'] = $video['id'];
                        $comment['video_title'] = $video['title'];
                        $comment['video_url'] = 'https://www.youtube.com/watch?v=' . $video['id'];
                        $comment['video_view_count'] = $video['view_count'];
                        $comment['video_like_count'] = $video['like_count'];
                        $comment['video_published_at'] = $video['published_at'];
                        $comment['channel_id'] = $videoId;
                        $comment['channel_title'] = 'Channel'; // Bu bilgiyi cache'den alabilirsin
                        
                        $checkSql = "SELECT id, like_count, comment_text FROM comments WHERE comment_id = ?";
                        $checkStmt = $db->getConnection()->prepare($checkSql);
                        $checkStmt->execute([$comment['comment_id']]);
                        $existingComment = $checkStmt->fetch();
                        
                        if (!$existingComment) {
                            if ($db->saveComment($comment)) {
                                $response['new_comments'][] = $comment;
                            }
                        }
                    }
                }
            } catch (Exception $e) {
                error_log('Channel sync error: ' . $e->getMessage());
            }
        } else {
            // Video senkronizasyonu (mevcut kod)
            try {
                // Video bilgilerini al
                $videoInfo = $youtube->getVideoInfo($videoId);
                if (!$videoInfo) {
                    throw new Exception('Video bulunamadı');
                }
            
            // YouTube'dan güncel yorumları al (daha fazla yorum al)
            $currentComments = $youtube->getComments($videoId, 300);
            $currentCommentIds = array_column($currentComments, 'comment_id');
            
            $response['debug']['youtube_comments_count'] = count($currentComments);
            
            // Veritabanındaki bu videoya ait tüm yorumları al
            $dbComments = $db->getCommentsByVideoId($videoId, 1000, 0);
            $dbCommentIds = array_column($dbComments, 'comment_id');
            
            $response['debug']['db_comments_count'] = count($dbComments);
            
            // Silinen yorumları bul ve sil
            $deletedCommentIds = array_diff($dbCommentIds, $currentCommentIds);
            if (!empty($deletedCommentIds)) {
                $placeholders = str_repeat('?,', count($deletedCommentIds) - 1) . '?';
                $deleteSql = "DELETE FROM comments WHERE comment_id IN ($placeholders) AND video_id = ?";
                $deleteStmt = $db->getConnection()->prepare($deleteSql);
                $deleteParams = array_merge($deletedCommentIds, [$videoId]);
                $deleteStmt->execute($deleteParams);
                $response['deleted_comment_ids'] = $deletedCommentIds;
            }
            
            // Yeni ve güncellenmiş yorumları kontrol et
            foreach ($currentComments as $comment) {
                $comment['video_id'] = $videoId;
                $comment['video_title'] = $videoInfo['title'];
                
                $checkSql = "SELECT id, like_count, comment_text, published_at FROM comments WHERE comment_id = ?";
                $checkStmt = $db->getConnection()->prepare($checkSql);
                $checkStmt->execute([$comment['comment_id']]);
                $existingComment = $checkStmt->fetch();
                
                if ($existingComment) {
                    // Güncelleme kontrolü
                    if ($existingComment['like_count'] != $comment['like_count'] || 
                        $existingComment['comment_text'] != $comment['comment_text']) {
                        
                        $updateSql = "UPDATE comments SET comment_text = ?, like_count = ? WHERE comment_id = ?";
                        $updateStmt = $db->getConnection()->prepare($updateSql);
                        $updateStmt->execute([$comment['comment_text'], $comment['like_count'], $comment['comment_id']]);
                        
                        $response['updated_comments'][] = $comment;
                    }
                } else {
                    // Yeni yorum - veritabanına kaydet
                    if ($db->saveComment($comment)) {
                        $response['new_comments'][] = $comment;
                    }
                }
            }
            
            } catch (Exception $e) {
                error_log('Video sync error: ' . $e->getMessage());
            }
        }
    }
    
    // Genel yeni yorumlar (son kontrol zamanından sonra)
    if (empty($response['new_comments']) && !$videoId) {
        $sql = "SELECT * FROM comments 
                WHERE created_at > :last_check 
                ORDER BY created_at DESC 
                LIMIT 50";
        
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindValue(':last_check', $lastCheck);
        $stmt->execute();
        
        $response['new_comments'] = $stmt->fetchAll();
    }
    
    $response['total_changes'] = count($response['new_comments']) + 
                                count($response['updated_comments']) + 
                                count($response['deleted_comment_ids']);
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
