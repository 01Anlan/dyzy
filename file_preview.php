<?php
// file_preview.php - 文件预览功能
header('Content-Type: text/plain; charset=utf-8');

// 定义存储文件夹
$storageFolder = 'downloads';

// 获取文件名
$fileName = $_GET['file'] ?? '';

if (empty($fileName)) {
    echo "错误: 未指定文件名";
    exit;
}

// 安全检查
$safeFileName = basename($fileName);
if (!preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}_-]+\.txt$/u', $safeFileName)) {
    echo "错误: 文件名不合法";
    exit;
}

$filePath = $storageFolder . '/' . $safeFileName;

if (!file_exists($filePath)) {
    echo "错误: 文件不存在";
    exit;
}

// 读取文件内容
$content = file_get_contents($filePath);
if ($content === false) {
    echo "错误: 无法读取文件内容";
    exit;
}

// 输出文件内容
echo $content;
?>