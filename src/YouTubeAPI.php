<?php
namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

class YouTubeAPI {
    private $youtube;
    
    public function __construct($accessToken = null) {
        $client = new \Google_Client();
        $client->setApplicationName('YouTube Comments Fetcher');
        $client->setDeveloperKey(YOUTUBE_API_KEY);
        
        // OAuth2 için gerekli ayarlar
        if (defined('GOOGLE_CLIENT_ID') && defined('GOOGLE_CLIENT_SECRET')) {
            $client->setClientId(GOOGLE_CLIENT_ID);
            $client->setClientSecret(GOOGLE_CLIENT_SECRET);
            $client->setRedirectUri(BASE_URL . 'oauth-callback.php');
            $client->addScope('https://www.googleapis.com/auth/youtube.force-ssl');
        }
        
        // Access token varsa ayarla
        if ($accessToken) {
            $client->setAccessToken($accessToken);
        }
        
        $this->youtube = new \Google_Service_YouTube($client);
    }
    
    public function getVideoIdFromUrl($url) {
        // YouTube URL'den video ID'sini çıkar
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : false;
    }
    
    public function getChannelIdFromUrl($url) {
        // YouTube kanal URL'sinden kanal ID'sini çıkar
        // @username, /c/channel, /channel/UC... formatlarını destekler
        
        // URL'yi temizle
        $url = trim($url);
        
        // @username formatı (yeni YouTube format) - daha geniş destek
        if (preg_match('/youtube\.com\/@([a-zA-Z0-9_.-]+)/', $url, $matches)) {
            $channelId = $this->getUsernameToChannelId($matches[1]);
            if ($channelId) return $channelId;
        }
        
        // /c/channel formatı (eski custom URL)
        if (preg_match('/youtube\.com\/c\/([^\/\?\s&]+)/', $url, $matches)) {
            $channelId = $this->getCustomUrlToChannelId($matches[1]);
            if ($channelId) return $channelId;
        }
        
        // /user/ formatı (çok eski format)
        if (preg_match('/youtube\.com\/user\/([^\/\?\s&]+)/', $url, $matches)) {
            $channelId = $this->getUsernameToChannelId($matches[1]);
            if ($channelId) return $channelId;
        }
        
        // /channel/UC... formatı (channel ID)
        if (preg_match('/youtube\.com\/channel\/([^\/\?\s&]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Son çare: URL'den handle'ı çıkarıp direkt ara
        if (preg_match('/@([a-zA-Z0-9_.-]+)/', $url, $matches)) {
            $channelId = $this->searchChannelByName($matches[1]);
            if ($channelId) return $channelId;
        }
        
        // Debug için log
        error_log('Channel ID not found for URL: ' . $url);
        
        return false;
    }
    
    private function getUsernameToChannelId($username) {
        try {
            // Önce forUsername ile dene (eski sistem)
            $response = $this->youtube->channels->listChannels('id', [
                'forUsername' => $username
            ]);
            
            if (!empty($response->items)) {
                return $response->items[0]->id;
            }
        } catch (\Exception $e) {
            // forUsername başarısız oldu
        }
        
        // Yeni @username formatı için search API kullan
        return $this->searchChannelByName($username);
    }
    
    private function getCustomUrlToChannelId($customUrl) {
        return $this->searchChannelByName($customUrl);
    }
    
    private function searchChannelByName($name) {
        try {
            // Önce tam kanal adı ile ara
            $response = $this->youtube->search->listSearch('snippet', [
                'q' => $name,
                'type' => 'channel',
                'maxResults' => 10
            ]);
            
            if (!empty($response->items)) {
                // En iyi eşleşmeyi bul
                foreach ($response->items as $item) {
                    $channelTitle = strtolower($item->snippet->title);
                    $searchName = strtolower($name);
                    
                    // Tam eşleşme varsa onu döndür
                    if ($channelTitle === $searchName || 
                        strpos($channelTitle, $searchName) !== false ||
                        strpos($searchName, $channelTitle) !== false) {
                        return $item->snippet->channelId;
                    }
                }
                
                // Tam eşleşme yoksa ilk sonucu döndür
                return $response->items[0]->snippet->channelId;
            }
        } catch (\Exception $e) {
            // Search başarısız, debug için log
            error_log('Channel search error: ' . $e->getMessage());
        }
        
        return false;
    }
    
    public function getChannelVideos($channelId, $maxResults = 50) {
        try {
            // Son 1 ayın videolarını al (1 hafta yerine daha geniş aralık)
            $oneMonthAgo = date('c', strtotime('-1 month'));
            
            $response = $this->youtube->search->listSearch('snippet', [
                'channelId' => $channelId,
                'type' => 'video',
                'order' => 'date',
                'publishedAfter' => $oneMonthAgo,
                'maxResults' => $maxResults
            ]);
            
            $videos = [];
            foreach ($response->items as $item) {
                $videoId = $item->id->videoId;
                
                // Video istatistiklerini al
                $statsResponse = $this->youtube->videos->listVideos('statistics,snippet', [
                    'id' => $videoId
                ]);
                
                if (!empty($statsResponse->items)) {
                    $video = $statsResponse->items[0];
                    $videos[] = [
                        'id' => $videoId,
                        'title' => $video->snippet->title,
                        'published_at' => $video->snippet->publishedAt,
                        'view_count' => $video->statistics->viewCount ?? 0,
                        'like_count' => $video->statistics->likeCount ?? 0,
                        'comment_count' => $video->statistics->commentCount ?? 0,
                        'duration' => $this->getVideoDuration($videoId),
                        'thumbnail' => $video->snippet->thumbnails->medium->url ?? ''
                    ];
                }
            }
            
            return $videos;
            
        } catch (\Exception $e) {
            throw new \Exception('Kanal videoları alınırken hata: ' . $e->getMessage());
        }
    }
    
    private function getVideoDuration($videoId) {
        try {
            $response = $this->youtube->videos->listVideos('contentDetails', [
                'id' => $videoId
            ]);
            
            if (!empty($response->items)) {
                return $response->items[0]->contentDetails->duration;
            }
        } catch (\Exception $e) {
            // Duration alınamazsa
        }
        
        return 'PT0S';
    }
    
    public function getChannelAllVideos($channelId, $maxResults = 20) {
        try {
            // Tarih filtresi olmadan son videoları al
            $response = $this->youtube->search->listSearch('snippet', [
                'channelId' => $channelId,
                'type' => 'video',
                'order' => 'date',
                'maxResults' => $maxResults
            ]);
            
            $videos = [];
            foreach ($response->items as $item) {
                $videoId = $item->id->videoId;
                
                // Video istatistiklerini al
                $statsResponse = $this->youtube->videos->listVideos('statistics,snippet', [
                    'id' => $videoId
                ]);
                
                if (!empty($statsResponse->items)) {
                    $video = $statsResponse->items[0];
                    $videos[] = [
                        'id' => $videoId,
                        'title' => $video->snippet->title,
                        'published_at' => $video->snippet->publishedAt,
                        'view_count' => $video->statistics->viewCount ?? 0,
                        'like_count' => $video->statistics->likeCount ?? 0,
                        'comment_count' => $video->statistics->commentCount ?? 0,
                        'duration' => $this->getVideoDuration($videoId),
                        'thumbnail' => $video->snippet->thumbnails->medium->url ?? ''
                    ];
                }
            }
            
            return $videos;
            
        } catch (\Exception $e) {
            throw new \Exception('Kanal videoları alınırken hata: ' . $e->getMessage());
        }
    }
    
    public function getChannelInfo($channelId) {
        try {
            $response = $this->youtube->channels->listChannels('snippet,statistics', [
                'id' => $channelId
            ]);
            
            if (empty($response->items)) {
                return false;
            }
            
            $channel = $response->items[0];
            return [
                'id' => $channel->id,
                'title' => $channel->snippet->title,
                'description' => $channel->snippet->description,
                'subscriber_count' => $channel->statistics->subscriberCount ?? 0,
                'video_count' => $channel->statistics->videoCount ?? 0,
                'view_count' => $channel->statistics->viewCount ?? 0,
                'thumbnail' => $channel->snippet->thumbnails->medium->url ?? '',
                'published_at' => $channel->snippet->publishedAt
            ];
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getVideoInfo($videoId) {
        try {
            $response = $this->youtube->videos->listVideos('snippet', [
                'id' => $videoId
            ]);
            
            if (empty($response->items)) {
                return false;
            }
            
            $video = $response->items[0];
            return [
                'id' => $video->id,
                'title' => $video->snippet->title,
                'channel_title' => $video->snippet->channelTitle,
                'published_at' => $video->snippet->publishedAt
            ];
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getComments($videoId, $maxResults = 100) {
        try {
            $comments = [];
            $nextPageToken = '';
            
            do {
                $params = [
                    'videoId' => $videoId,
                    'maxResults' => min($maxResults, 100),
                    'order' => 'time'
                ];
                
                if ($nextPageToken) {
                    $params['pageToken'] = $nextPageToken;
                }
                
                $response = $this->youtube->commentThreads->listCommentThreads('snippet', $params);
                
                foreach ($response->items as $item) {
                    $comment = $item->snippet->topLevelComment->snippet;
                    $comments[] = [
                        'comment_id' => $item->snippet->topLevelComment->id,
                        'author_name' => $comment->authorDisplayName,
                        'author_channel_id' => $comment->authorChannelId->value ?? '',
                        'comment_text' => $comment->textDisplay,
                        'like_count' => $comment->likeCount,
                        'published_at' => date('Y-m-d H:i:s', strtotime($comment->publishedAt))
                    ];
                }
                
                $nextPageToken = $response->nextPageToken ?? '';
                $maxResults -= count($response->items);
                
            } while ($nextPageToken && $maxResults > 0);
            
            return $comments;
            
        } catch (\Exception $e) {
            throw new \Exception('YouTube API Error: ' . $e->getMessage());
        }
    }
    
    /**
     * YouTube yorumuna cevap gönder
     */
    public function replyToComment($parentCommentId, $replyText) {
        try {
            // Comment reply objesi oluştur
            $commentReply = new \Google_Service_YouTube_Comment();
            $snippet = new \Google_Service_YouTube_CommentSnippet();
            $commentReply->setSnippet($snippet);
            $commentReply->getSnippet()->setParentId($parentCommentId);
            $commentReply->getSnippet()->setTextOriginal($replyText);
            
            // API'ye gönder
            $response = $this->youtube->comments->insert('snippet', $commentReply);
            
            return [
                'success' => true,
                'comment_id' => $response->getId(),
                'text' => $response->getSnippet()->getTextOriginal(),
                'published_at' => $response->getSnippet()->getPublishedAt(),
                'author' => $response->getSnippet()->getAuthorDisplayName()
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * OAuth2 yetkilendirme URL'si oluştur
     */
    public function getAuthUrl() {
        $client = new \Google_Client();
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        $client->setRedirectUri(BASE_URL . 'oauth-callback.php');
        $client->addScope('https://www.googleapis.com/auth/youtube.force-ssl');
        $client->setAccessType('offline');
        $client->setPrompt('consent');
        
        return $client->createAuthUrl();
    }
    
    /**
     * Authorization code'u access token'a çevir
     */
    public function getAccessTokenFromCode($code) {
        try {
            $client = new \Google_Client();
            $client->setClientId(GOOGLE_CLIENT_ID);
            $client->setClientSecret(GOOGLE_CLIENT_SECRET);
            $client->setRedirectUri(BASE_URL . 'oauth-callback.php');
            
            $token = $client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                throw new \Exception($token['error_description']);
            }
            
            return $token;
            
        } catch (\Exception $e) {
            throw new \Exception('Token alma hatası: ' . $e->getMessage());
        }
    }
    
    /**
     * Access token'ın geçerli olup olmadığını kontrol et
     */
    public function isTokenValid($accessToken) {
        try {
            $client = new \Google_Client();
            $client->setAccessToken($accessToken);
            
            return !$client->isAccessTokenExpired();
            
        } catch (\Exception $e) {
            return false;
        }
    }
}
