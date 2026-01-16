<?php
// file_preview.php - 文件预览
header('Content-Type: text/plain; charset=utf-8');

$file = $_GET['file'] ?? '';
if ($file) {
    $filePath = 'downloads/' . $file;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        // 限制预览内容长度
        if (strlen($content) > 10000) {
            $content = substr($content, 0, 10000) . "\n\n... (内容过长，已截断)";
        }
        echo $content;
    } else {
        echo '文件不存在';
    }
} else {
    echo '未指定文件';
}
?>