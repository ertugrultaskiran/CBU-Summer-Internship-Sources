-- YouTube Comments Database Schema
CREATE DATABASE IF NOT EXISTS youtube_comments;
USE youtube_comments;

CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    video_id VARCHAR(20) NOT NULL,
    video_title VARCHAR(500),
    video_url VARCHAR(200),
    video_view_count BIGINT DEFAULT 0,
    video_like_count INT DEFAULT 0,
    video_published_at DATETIME,
    channel_id VARCHAR(50),
    channel_title VARCHAR(200),
    comment_id VARCHAR(50) NOT NULL UNIQUE,
    author_name VARCHAR(100) NOT NULL,
    author_channel_id VARCHAR(50),
    comment_text TEXT NOT NULL,
    like_count INT DEFAULT 0,
    published_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_video_id (video_id),
    INDEX idx_channel_id (channel_id),
    INDEX idx_published_at (published_at)
);
