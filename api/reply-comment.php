<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\YouTubeAPI;

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Kullanıcının authenticated olup olmadığını kontrol et
    if (!isset($_SESSION['youtube_access_token']) || !isset($_SESSION['youtube_authenticated'])) {
        throw new Exception('YouTube ile giriş yapmanız gerekiyor');
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['parent_comment_id']) || !isset($input['reply_text'])) {
        throw new Exception('Parent comment ID ve cevap metni gerekli');
    }
    
    $parentCommentId = $input['parent_comment_id'];
    $replyText = trim($input['reply_text']);
    
    if (empty($replyText)) {
        throw new Exception('Cevap metni boş olamaz');
    }
    
    if (strlen($replyText) > 10000) {
        throw new Exception('Cevap metni çok uzun (maksimum 10.000 karakter)');
    }
    
    // YouTube API ile cevap gönder
    $youtube = new YouTubeAPI($_SESSION['youtube_access_token']);
    
    // Token'ın geçerli olup olmadığını kontrol et
    if (!$youtube->isTokenValid($_SESSION['youtube_access_token'])) {
        unset($_SESSION['youtube_access_token']);
        unset($_SESSION['youtube_authenticated']);
        throw new Exception('Oturum süresi dolmuş. Lütfen tekrar giriş yapın.');
    }
    
    $result = $youtube->replyToComment($parentCommentId, $replyText);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'message' => 'Cevabınız başarıyla gönderildi!',
            'reply' => $result
        ]);
    } else {
        throw new Exception($result['error']);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
