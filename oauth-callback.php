<?php
session_start();
require_once 'config/config.php';
require_once 'vendor/autoload.php';

use App\YouTubeAPI;

try {
    if (!isset($_GET['code'])) {
        throw new Exception('Authorization code bulunamadı');
    }
    
    $youtube = new YouTubeAPI();
    $accessToken = $youtube->getAccessTokenFromCode($_GET['code']);
    
    // Token'ı session'da sakla
    $_SESSION['youtube_access_token'] = $accessToken;
    $_SESSION['youtube_authenticated'] = true;
    
    // Ana sayfaya yönlendir
    header('Location: index.php?auth=success');
    exit;
    
} catch (Exception $e) {
    header('Location: index.php?auth=error&message=' . urlencode($e->getMessage()));
    exit;
}
?>
