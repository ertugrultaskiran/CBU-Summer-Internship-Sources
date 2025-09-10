<?php
namespace App;

class Database {
    private $pdo;
    
    public function __construct() {
        try {
            $this->pdo = new \PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch (\PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function saveComment($data) {
        // Eksik alanları varsayılan değerlerle doldur
        $defaults = [
            'video_url' => $data['video_url'] ?? ('https://www.youtube.com/watch?v=' . $data['video_id']),
            'video_view_count' => $data['video_view_count'] ?? 0,
            'video_like_count' => $data['video_like_count'] ?? 0,
            'video_published_at' => $data['video_published_at'] ?? date('Y-m-d H:i:s'),
            'channel_id' => $data['channel_id'] ?? '',
            'channel_title' => $data['channel_title'] ?? '',
            'author_channel_id' => $data['author_channel_id'] ?? ''
        ];
        
        $data = array_merge($defaults, $data);
        
        $sql = "INSERT INTO comments (
                    video_id, video_title, video_url, video_view_count, video_like_count, video_published_at, 
                    channel_id, channel_title, comment_id, author_name, author_channel_id, 
                    comment_text, like_count, published_at
                ) VALUES (
                    :video_id, :video_title, :video_url, :video_view_count, :video_like_count, :video_published_at,
                    :channel_id, :channel_title, :comment_id, :author_name, :author_channel_id,
                    :comment_text, :like_count, :published_at
                ) ON DUPLICATE KEY UPDATE 
                like_count = VALUES(like_count),
                comment_text = VALUES(comment_text),
                video_view_count = VALUES(video_view_count),
                video_like_count = VALUES(video_like_count)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function getCommentsByVideoId($videoId, $limit = 50, $offset = 0) {
        $sql = "SELECT * FROM comments WHERE video_id = :video_id 
                ORDER BY published_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':video_id', $videoId);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllComments($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM comments ORDER BY published_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getVideoStats() {
        $sql = "SELECT video_id, video_title, video_url, channel_title,
                video_view_count, video_like_count, video_published_at,
                COUNT(*) as comment_count, MAX(published_at) as latest_comment 
                FROM comments 
                GROUP BY video_id, video_title, video_url, channel_title,
                video_view_count, video_like_count, video_published_at
                ORDER BY latest_comment DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getCommentsByChannelId($channelId, $limit = 100, $offset = 0) {
        $sql = "SELECT * FROM comments WHERE channel_id = :channel_id 
                ORDER BY published_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':channel_id', $channelId);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
