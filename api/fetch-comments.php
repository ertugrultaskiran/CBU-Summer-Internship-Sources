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
    
    if (!isset($input['video_url']) || empty($input['video_url'])) {
        throw new Exception('Video URL gerekli');
    }
    
    $youtube = new YouTubeAPI();
    $db = new Database();
    
    // URL'den video ID'sini çıkar
    $videoId = $youtube->getVideoIdFromUrl($input['video_url']);
    if (!$videoId) {
        throw new Exception('Geçersiz YouTube URL');
    }
    
    // Video bilgilerini al
    $videoInfo = $youtube->getVideoInfo($videoId);
    if (!$videoInfo) {
        throw new Exception('Video bulunamadı');
    }
    
    // Yorumları çek
    $maxResults = isset($input['max_results']) ? (int)$input['max_results'] : 100;
    $comments = $youtube->getComments($videoId, $maxResults);
    
    // Veritabanına kaydet
    $savedCount = 0;
    foreach ($comments as $comment) {
        $comment['video_id'] = $videoId;
        $comment['video_title'] = $videoInfo['title'];
        
        if ($db->saveComment($comment)) {
            $savedCount++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => "{$savedCount} yorum başarıyla kaydedildi",
        'video_info' => $videoInfo,
        'total_comments' => count($comments),
        'saved_comments' => $savedCount
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
