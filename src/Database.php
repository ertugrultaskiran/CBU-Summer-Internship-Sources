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
        $sql = "INSERT INTO comments (video_id, video_title, comment_id, author_name, author_channel_id, comment_text, like_count, published_at) 
                VALUES (:video_id, :video_title, :comment_id, :author_name, :author_channel_id, :comment_text, :like_count, :published_at)
                ON DUPLICATE KEY UPDATE 
                like_count = VALUES(like_count),
                comment_text = VALUES(comment_text)";
        
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
        $sql = "SELECT video_id, video_title, COUNT(*) as comment_count, 
                MAX(published_at) as latest_comment 
                FROM comments 
                GROUP BY video_id, video_title 
                ORDER BY latest_comment DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
