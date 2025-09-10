<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\Database;
use App\YouTubeAPI;

try {
    $db = new Database();
    $youtube = new YouTubeAPI();
    
    $videoId = $_GET['video_id'] ?? null;
    
    if (!$videoId) {
        throw new Exception('Video ID gerekli');
    }
    
    $response = [
        'success' => true,
        'video_id' => $videoId,
        'current_time' => date('Y-m-d H:i:s'),
        'debug' => []
    ];
    
    // 1. YouTube API'sinden yorumları çek
    $apiComments = $youtube->getComments($videoId, 50);
    $response['api_comments_count'] = count($apiComments);
    $response['api_comments_sample'] = array_slice($apiComments, 0, 3);
    
    // 2. Veritabanındaki yorumları çek
    $dbComments = $db->getCommentsByVideoId($videoId, 50, 0);
    $response['db_comments_count'] = count($dbComments);
    
    // 3. Karşılaştırma
    $apiCommentIds = array_column($apiComments, 'comment_id');
    $dbCommentIds = array_column($dbComments, 'comment_id');
    
    $newInApi = array_diff($apiCommentIds, $dbCommentIds);
    $deletedFromApi = array_diff($dbCommentIds, $apiCommentIds);
    
    $response['new_in_api'] = array_values($newInApi);
    $response['deleted_from_api'] = array_values($deletedFromApi);
    $response['new_count'] = count($newInApi);
    $response['deleted_count'] = count($deletedFromApi);
    
    // 4. En son yorumların detayları
    if (!empty($apiComments)) {
        $latestComment = $apiComments[0];
        $response['latest_comment'] = [
            'id' => $latestComment['comment_id'],
            'author' => $latestComment['author_name'],
            'text' => substr($latestComment['comment_text'], 0, 100),
            'published_at' => $latestComment['published_at'],
            'time_ago' => round((time() - strtotime($latestComment['published_at'])) / 60) . ' dakika önce'
        ];
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>

