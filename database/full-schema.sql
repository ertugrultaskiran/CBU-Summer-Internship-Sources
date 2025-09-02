-- YouTube Comments Panel - Complete Database Schema
-- Run this file to create all required tables

-- Create database
CREATE DATABASE IF NOT EXISTS youtube_comments;
USE youtube_comments;

-- Main comments table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    video_id VARCHAR(20) NOT NULL,
    video_title VARCHAR(500),
    comment_id VARCHAR(50) NOT NULL UNIQUE,
    author_name VARCHAR(100) NOT NULL,
    author_channel_id VARCHAR(50),
    comment_text TEXT NOT NULL,
    like_count INT DEFAULT 0,
    published_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_video_id (video_id),
    INDEX idx_published_at (published_at),
    INDEX idx_created_at (created_at)
);

-- Video synchronization tracking table
CREATE TABLE IF NOT EXISTS video_sync (
    video_id VARCHAR(20) PRIMARY KEY,
    last_sync DATETIME NOT NULL,
    comment_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Sample data (optional - remove if not needed)
-- INSERT INTO comments (video_id, video_title, comment_id, author_name, comment_text, like_count, published_at) VALUES
-- ('dQw4w9WgXcQ', 'Sample Video', 'sample_comment_1', 'Sample User', 'This is a sample comment', 5, NOW());

-- Show table status
SHOW TABLES;
SELECT 'Database schema created successfully!' as Status;
