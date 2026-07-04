-- 普通用户表
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(64) NOT NULL UNIQUE,
    email VARCHAR(255) NULL DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    douyin_cookie TEXT NULL,
    cron_token VARCHAR(64) NULL DEFAULT NULL,
    status TINYINT NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    UNIQUE INDEX idx_user_email (email),
    UNIQUE INDEX idx_cron_token (cron_token)
);

-- 解析记录表
CREATE TABLE IF NOT EXISTS parse_records (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL DEFAULT NULL,
    session_id VARCHAR(64) NULL DEFAULT NULL,
    douyin_url VARCHAR(500) NOT NULL,
    custom_filename VARCHAR(255) NOT NULL,
    work_title TEXT NULL,
    work_cover VARCHAR(1000) NULL DEFAULT NULL,
    work_author VARCHAR(255) NULL DEFAULT NULL,
    source_mode VARCHAR(32) NULL DEFAULT NULL,
    parse_type ENUM('1', '2') NOT NULL DEFAULT '2',
    video_count INT NOT NULL DEFAULT 0,
    file_path VARCHAR(500) NOT NULL,
    auto_update BOOLEAN NOT NULL DEFAULT FALSE,
    last_parse_time DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_session_id (session_id),
    INDEX idx_source_mode (source_mode),
    INDEX idx_douyin_url (douyin_url(255)),
    INDEX idx_auto_update (auto_update),
    INDEX idx_last_parse_time (last_parse_time)
);

-- 自动更新日志表
CREATE TABLE IF NOT EXISTS auto_update_logs (
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
CREATE TABLE IF NOT EXISTS email_configs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email_address VARCHAR(255) NOT NULL DEFAULT '',
    smtp_host VARCHAR(255) NOT NULL,
    smtp_port INT NOT NULL DEFAULT 465,
    smtp_username VARCHAR(255) NOT NULL,
    smtp_password VARCHAR(255) NOT NULL,
    smtp_encryption ENUM('ssl', 'tls', 'none') NOT NULL DEFAULT 'ssl',
    from_name VARCHAR(255) NOT NULL DEFAULT '抖音监控系统',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
