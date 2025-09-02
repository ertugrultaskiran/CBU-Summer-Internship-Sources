<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\Database;

try {
    $db = new Database();
    
    $videoId = $_GET['video_id'] ?? null;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
    
    if ($videoId) {
        $comments = $db->getCommentsByVideoId($videoId, $limit, $offset);
    } else {
        $comments = $db->getAllComments($limit, $offset);
    }
    
    echo json_encode([
        'success' => true,
        'comments' => $comments,
        'count' => count($comments)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
