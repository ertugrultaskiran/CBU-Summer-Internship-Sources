<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\YouTubeAPI;

try {
    $authenticated = false;
    $authUrl = '';
    $userInfo = null;
    
    // OAuth2 ayarları kontrol et
    if (!defined('GOOGLE_CLIENT_ID') || GOOGLE_CLIENT_ID === 'YOUR_GOOGLE_CLIENT_ID_HERE') {
        echo json_encode([
            'success' => false,
            'error' => 'OAuth2 ayarları yapılmamış. config.php dosyasında GOOGLE_CLIENT_ID ve GOOGLE_CLIENT_SECRET ayarlayın.'
        ]);
        exit;
    }
    
    if (isset($_SESSION['youtube_access_token']) && isset($_SESSION['youtube_authenticated'])) {
        $youtube = new YouTubeAPI();
        
        if ($youtube->isTokenValid($_SESSION['youtube_access_token'])) {
            $authenticated = true;
            
            // Kullanıcı bilgilerini al (isteğe bağlı)
            try {
                $youtube = new YouTubeAPI($_SESSION['youtube_access_token']);
                // Burada kullanıcı channel bilgilerini alabilirsiniz
                $userInfo = [
                    'authenticated_at' => $_SESSION['youtube_authenticated'] ?? 'Unknown'
                ];
            } catch (Exception $e) {
                // Kullanıcı bilgisi alınamazsa sorun değil
            }
        } else {
            // Token süresi dolmuş
            unset($_SESSION['youtube_access_token']);
            unset($_SESSION['youtube_authenticated']);
        }
    }
    
    if (!$authenticated) {
        $youtube = new YouTubeAPI();
        $authUrl = $youtube->getAuthUrl();
    }
    
    echo json_encode([
        'success' => true,
        'authenticated' => $authenticated,
        'auth_url' => $authUrl,
        'user_info' => $userInfo
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
