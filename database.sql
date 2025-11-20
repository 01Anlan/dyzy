CREATE DATABASE IF NOT EXISTS douyin_parser DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE douyin_parser;

-- 解析记录表
CREATE TABLE parse_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    douyin_url VARCHAR(500) NOT NULL,
    custom_filename VARCHAR(255) NOT NULL,
    parse_type ENUM('1', '2') NOT NULL DEFAULT '2',
    video_count INT NOT NULL DEFAULT 0,
    file_path VARCHAR(500) NOT NULL,
    auto_update BOOLEAN NOT NULL DEFAULT FALSE,
    last_parse_time DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_douyin_url (douyin_url(255)),
    INDEX idx_auto_update (auto_update),
    INDEX idx_last_parse_time (last_parse_time)
);

-- 自动更新日志表
CREATE TABLE auto_update_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    record_id INT NOT NULL,
    status ENUM('success', 'error') NOT NULL,
    message TEXT NOT NULL,
    new_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (record_id) REFERENCES parse_records(id) ON DELETE CASCADE,
    INDEX idx_record_id (record_id),
    INDEX idx_created_at (created_at)
);

-- 邮件通知配置表
CREATE TABLE email_configs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    smtp_host VARCHAR(255) NOT NULL,
    smtp_port INT NOT NULL DEFAULT 465,
    smtp_username VARCHAR(255) NOT NULL,
    smtp_password VARCHAR(255) NOT NULL,
    smtp_encryption ENUM('ssl', 'tls', 'none') NOT NULL DEFAULT 'ssl',
    from_name VARCHAR(255) NOT NULL DEFAULT '抖音监控系统',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);