<?php
// file_preview.php - 文件预览
require_once __DIR__ . '/../includes/auth.php';
dyzy_require_user(false);

header('Content-Type: text/plain; charset=utf-8');

function isPreviewMediaUrl($url, $fileType) {
    $url = trim((string)$url);
    if ($url === '' || !preg_match('/^https?:\/\//i', $url)) {
        return false;
    }

    if ($fileType === 'video') {
        if (preg_match('/\.(mp3|m4a|aac|wav|flac)(\?|$)/i', $url)) {
            return false;
        }
        return strpos($url, '/aweme/v1/play/') !== false
            || strpos($url, 'video_id=') !== false
            || strpos($url, 'douyinvod.com') !== false
            || strpos($url, '/video/tos/') !== false;
    }

    if ($fileType === 'image') {
        if (preg_match('/\.(mp3|m4a|aac|wav|flac|mp4|mov|m3u8)(\?|$)/i', $url)) {
            return false;
        }
        return strpos($url, 'douyinpic.com') !== false
            || strpos($url, 'tplv-dy-aweme-images') !== false
            || preg_match('/\.(jpg|jpeg|png|webp)(\?|$)/i', $url);
    }

    return !preg_match('/\.(mp3|m4a|aac|wav|flac)(\?|$)/i', $url);
}

function detectPreviewFileType($filePath) {
    $fileName = basename($filePath);
    if (strpos($fileName, '_images.txt') !== false) {
        return 'image';
    }
    if (strpos($fileName, '_videos.txt') !== false) {
        return 'video';
    }
    return 'unknown';
}

function extractMediaUrlFromLine($line, $fileType = 'unknown') {
    $line = trim((string)$line);
    if ($line === '') {
        return '';
    }

    if (preg_match('/^https?:\/\//i', $line)) {
        return isPreviewMediaUrl($line, $fileType) ? $line : '';
    }

    if (preg_match('/^(?:播放地址|图片地址|视频地址|媒体地址)[：:]\s*(https?:\/\/\S+)/u', $line, $matches)) {
        return isPreviewMediaUrl($matches[1], $fileType) ? $matches[1] : '';
    }

    $record = json_decode($line, true);
    if (!is_array($record)) {
        return '';
    }

    foreach (['url', 'play_url', 'image_url', 'cover', 'origin_cover'] as $key) {
        $url = trim((string)($record[$key] ?? ''));
        if (isPreviewMediaUrl($url, $fileType)) {
            return $url;
        }
    }

    return '';
}

function extractPreviewContent($filePath) {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return '';
    }

    $fileType = detectPreviewFileType($filePath);
    $mediaUrls = array_values(array_filter(array_map(function ($line) use ($fileType) {
        return extractMediaUrlFromLine($line, $fileType);
    }, $lines)));
    if (empty($mediaUrls)) {
        return '';
    }

    $content = implode("\n", $mediaUrls);
    if (strlen($content) > 10000) {
        $content = substr($content, 0, 10000) . "\n\n... (内容过长，已截断)";
    }

    return $content;
}

$file = basename($_GET['file'] ?? '');
if ($file) {
    $filePath = dyzy_workspace_download_dir() . '/' . $file;
    if (is_file($filePath)) {
        $content = extractPreviewContent($filePath);
        echo $content !== '' ? $content : '未找到可预览的播放地址';
    } else {
        echo '文件不存在';
    }
} else {
    echo '未指定文件';
}
?>