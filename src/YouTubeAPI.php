<?php
namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

class YouTubeAPI {
    private $youtube;
    
    public function __construct() {
        $client = new \Google_Client();
        $client->setApplicationName('YouTube Comments Fetcher');
        $client->setDeveloperKey(YOUTUBE_API_KEY);
        
        $this->youtube = new \Google_Service_YouTube($client);
    }
    
    public function getVideoIdFromUrl($url) {
        // YouTube URL'den video ID'sini çıkar
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return isset($matches[1]) ? $matches[1] : false;
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
        } catch (Exception $e) {
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
            
        } catch (Exception $e) {
            throw new Exception('YouTube API Error: ' . $e->getMessage());
        }
    }
}
