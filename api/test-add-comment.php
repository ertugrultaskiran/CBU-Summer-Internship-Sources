<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\Database;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $db = new Database();
    
    // Test yorumu oluştur
    $testComment = [
        'video_id' => $input['video_id'] ?? 'test_video_123',
        'video_title' => $input['video_title'] ?? 'Test Video',
        'video_url' => $input['video_url'] ?? 'https://www.youtube.com/watch?v=test_video_123',
        'video_view_count' => 1000,
        'video_like_count' => 50,
        'video_published_at' => date('Y-m-d H:i:s'),
        'channel_id' => $input['channel_id'] ?? '',
        'channel_title' => $input['channel_title'] ?? '',
        'comment_id' => 'test_comment_' . time() . '_' . rand(1000, 9999),
        'author_name' => $input['author_name'] ?? 'Test User',
        'author_channel_id' => 'test_channel_123',
        'comment_text' => $input['comment_text'] ?? 'Bu bir test yorumudur - ' . date('H:i:s'),
        'like_count' => rand(0, 10),
        'published_at' => date('Y-m-d H:i:s')
    ];
    
    if ($db->saveComment($testComment)) {
        echo json_encode([
            'success' => true,
            'message' => 'Test yorumu başarıyla eklendi',
            'comment' => $testComment
        ]);
    } else {
        throw new Exception('Yorum kaydedilemedi');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
