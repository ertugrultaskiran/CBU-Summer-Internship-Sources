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
    $stats = $db->getVideoStats();
    
    echo json_encode([
        'success' => true,
        'videos' => $stats
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
