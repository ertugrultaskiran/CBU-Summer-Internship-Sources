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
    $timeFilter = $_GET['time_filter'] ?? 'all';
    $sortOrder = $_GET['sort'] ?? 'newest';
    
    // Zaman filtresi SQL'i
    $timeCondition = '';
    switch ($timeFilter) {
        case '1hour':
            $timeCondition = ' AND published_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)';
            break;
        case '6hours':
            $timeCondition = ' AND published_at >= DATE_SUB(NOW(), INTERVAL 6 HOUR)';
            break;
        case '24hours':
            $timeCondition = ' AND published_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)';
            break;
        case '7days':
            $timeCondition = ' AND published_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)';
            break;
        case '30days':
            $timeCondition = ' AND published_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)';
            break;
        default:
            $timeCondition = '';
    }
    
    // Sıralama SQL'i
    $orderBy = '';
    switch ($sortOrder) {
        case 'newest':
            $orderBy = 'ORDER BY published_at DESC';
            break;
        case 'oldest':
            $orderBy = 'ORDER BY published_at ASC';
            break;
        case 'most_liked':
            $orderBy = 'ORDER BY like_count DESC, published_at DESC';
            break;
        default:
            $orderBy = 'ORDER BY published_at DESC';
    }
    
    if ($videoId) {
        // Video ID veya Channel ID olabilir - uzunluğuna göre karar ver
        $isChannelId = (strlen($videoId) > 11); // Channel ID'ler daha uzun
        
        if ($isChannelId) {
            // Kanal ID'sine göre filtrele
            $sql = "SELECT * FROM comments WHERE channel_id = :channel_id" . $timeCondition . " " . $orderBy . " LIMIT :limit OFFSET :offset";
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->bindValue(':channel_id', $videoId);
        } else {
            // Video ID'sine göre filtrele
            $sql = "SELECT * FROM comments WHERE video_id = :video_id" . $timeCondition . " " . $orderBy . " LIMIT :limit OFFSET :offset";
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->bindValue(':video_id', $videoId);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll();
    } else {
        $sql = "SELECT * FROM comments WHERE 1=1" . $timeCondition . " " . $orderBy . " LIMIT :limit OFFSET :offset";
        $stmt = $db->getConnection()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll();
    }
    
    echo json_encode([
        'success' => true,
        'comments' => $comments,
        'count' => count($comments),
        'debug' => [
            'video_id' => $videoId,
            'time_filter' => $timeFilter,
            'sort_order' => $sortOrder,
            'limit' => $limit,
            'offset' => $offset
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
