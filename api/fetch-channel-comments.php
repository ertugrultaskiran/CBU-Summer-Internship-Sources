<?php
header('Content-Type: application/json; charset=utf-8');
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
    
    if (!isset($input['channel_url']) || empty($input['channel_url'])) {
        throw new Exception('Kanal URL gerekli');
    }
    
    $youtube = new YouTubeAPI();
    $db = new Database();
    
    // URL'den kanal ID'sini çıkar
    $channelId = $youtube->getChannelIdFromUrl($input['channel_url']);
    if (!$channelId) {
        throw new Exception('Kanal bulunamadı. Lütfen geçerli bir YouTube kanal URL\'si girin. Örnek: https://youtube.com/@kanaladi veya YouTube API kotası aşılmış olabilir.');
    }
    
    // Kanal bilgilerini al
    $channelInfo = $youtube->getChannelInfo($channelId);
    if (!$channelInfo) {
        throw new Exception('Kanal bulunamadı');
    }
    
    // Son 1 ayın videolarını al (daha geniş aralık)
    $maxVideos = isset($input['max_videos']) ? (int)$input['max_videos'] : 50;
    $videos = $youtube->getChannelVideos($channelId, $maxVideos);
    
    if (empty($videos)) {
        // Eğer son 1 ayda video yoksa, tüm videoları al
        $videos = $youtube->getChannelAllVideos($channelId, 20);
    }
    
    if (empty($videos)) {
        throw new Exception('Bu kanalda video bulunamadı');
    }
    
    $totalComments = 0;
    $totalVideos = 0;
    $processedVideos = [];
    $maxCommentsPerVideo = isset($input['max_comments']) ? (int)$input['max_comments'] : 100;
    
    foreach ($videos as $video) {
        try {
            // Her video için yorumları çek
            $comments = $youtube->getComments($video['id'], $maxCommentsPerVideo);
            $savedComments = 0;
            
            foreach ($comments as $comment) {
                $comment['video_id'] = $video['id'];
                $comment['video_title'] = $video['title'];
                $comment['video_url'] = 'https://www.youtube.com/watch?v=' . $video['id'];
                $comment['video_view_count'] = $video['view_count'];
                $comment['video_like_count'] = $video['like_count'];
                $comment['video_published_at'] = $video['published_at'];
                $comment['channel_id'] = $channelId;
                $comment['channel_title'] = $channelInfo['title'];
                
                if ($db->saveComment($comment)) {
                    $savedComments++;
                }
            }
            
            $processedVideos[] = [
                'video_id' => $video['id'],
                'title' => $video['title'],
                'url' => 'https://www.youtube.com/watch?v=' . $video['id'],
                'view_count' => $video['view_count'],
                'like_count' => $video['like_count'],
                'comment_count' => $video['comment_count'],
                'published_at' => $video['published_at'],
                'total_comments' => count($comments),
                'saved_comments' => $savedComments
            ];
            
            $totalComments += $savedComments;
            $totalVideos++;
            
        } catch (Exception $e) {
            // Video için hata olursa devam et
            continue;
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => "{$totalComments} yorum {$totalVideos} videodan başarıyla kaydedildi",
        'channel_info' => $channelInfo,
        'total_videos' => $totalVideos,
        'total_comments' => $totalComments,
        'processed_videos' => $processedVideos
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
