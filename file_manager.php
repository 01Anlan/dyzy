<?php
// file_manager.php - 文件管理功能
header('Content-Type: application/json; charset=utf-8');

// 定义存储文件夹
$storageFolder = 'downloads';

// 获取操作类型
$action = $_GET['action'] ?? '';

// 允许跨域请求（如果需要）
header('Access-Control-Allow-Origin: *');

switch ($action) {
    case 'list':
        listFiles();
        break;
    case 'delete':
        deleteFile();
        break;
    case 'download':
        downloadFile();
        break;
    case 'cleanup':
        cleanupFiles();
        break;
    default:
        echo json_encode([
            'code' => 0,
            'msg' => '未知操作',
            'data' => null
        ], JSON_UNESCAPED_UNICODE);
}

// 列出所有txt文件
function listFiles() {
    global $storageFolder;
    
    // 确保文件夹存在
    if (!is_dir($storageFolder)) {
        if (!mkdir($storageFolder, 0755, true)) {
            echo json_encode([
                'code' => 0,
                'msg' => '存储文件夹不存在且无法创建',
                'data' => null
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
    }
    
    $files = [];
    $fileList = glob($storageFolder . '/*.txt');
    
    foreach ($fileList as $file) {
        if (is_file($file)) {
            $fileName = basename($file);
            $files[] = [
                'name' => $fileName,
                'size' => formatFileSize(filesize($file)),
                'time' => date('Y-m-d H:i:s', filemtime($file)),
                'timestamp' => filemtime($file),
                'download_url' => getDownloadUrl($fileName),
                'full_path' => $file
            ];
        }
    }
    
    // 按时间倒序排列
    usort($files, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    
    echo json_encode([
        'code' => 1,
        'msg' => '成功获取文件列表',
        'data' => $files
    ], JSON_UNESCAPED_UNICODE);
}

// 删除文件
function deleteFile() {
    global $storageFolder;
    
    $fileName = $_GET['file'] ?? '';
    
    if (empty($fileName)) {
        echo json_encode([
            'code' => 0,
            'msg' => '文件名不能为空',
            'data' => null
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    
    // 安全检查 - 只允许删除txt文件
    $safeFileName = basename($fileName);
    if (!preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}_-]+\.txt$/u', $safeFileName)) {
        echo json_encode([
            'code' => 0,
            'msg' => '文件名不合法',
            'data' => null
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    
    $filePath = $storageFolder . '/' . $safeFileName;
    
    if (!file_exists($filePath)) {
        echo json_encode([
            'code' => 0,
            'msg' => '文件不存在: ' . $safeFileName,
            'data' => null
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    
    // 尝试删除文件
    if (unlink($filePath)) {
        echo json_encode([
            'code' => 1,
            'msg' => '文件删除成功: ' . $safeFileName,
            'data' => null
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $error = error_get_last();
        echo json_encode([
            'code' => 0,
            'msg' => '文件删除失败: ' . ($error['message'] ?? '未知错误'),
            'data' => null
        ], JSON_UNESCAPED_UNICODE);
    }
}

// 自动清理文件
function cleanupFiles() {
    global $storageFolder;
    
    $hours = isset($_GET['hours']) ? intval($_GET['hours']) : 24;
    
    if ($hours <= 0) {
        echo json_encode([
            'code' => 0,
            'msg' => '清理时间必须大于0',
            'data' => null
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
    
    $cutoffTime = time() - ($hours * 3600);
    $deletedFiles = [];
    $fileList = glob($storageFolder . '/*.txt');
    
    foreach ($fileList as $file) {
        if (is_file($file) && filemtime($file) < $cutoffTime) {
            if (unlink($file)) {
                $deletedFiles[] = basename($file);
            }
        }
    }
    
    echo json_encode([
        'code' => 1,
        'msg' => '自动清理完成',
        'data' => [
            'deleted_count' => count($deletedFiles),
            'deleted_files' => $deletedFiles,
            'cutoff_time' => date('Y-m-d H:i:s', $cutoffTime)
        ]
    ], JSON_UNESCAPED_UNICODE);
}

// 下载文件
function downloadFile() {
    global $storageFolder;
    
    $fileName = $_GET['file'] ?? '';
    
    if (empty($fileName)) {
        http_response_code(400);
        echo "错误: 未指定文件名";
        exit;
    }
    
    // 安全检查
    $safeFileName = basename($fileName);
    if (!preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}_-]+\.txt$/u', $safeFileName)) {
        http_response_code(400);
        echo "错误: 文件名不合法";
        exit;
    }
    
    $filePath = $storageFolder . '/' . $safeFileName;
    
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo "错误: 文件不存在";
        exit;
    }
    
    // 设置下载头信息
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $safeFileName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    
    // 清空输出缓冲区
    flush();
    readfile($filePath);
    exit;
}

// 格式化文件大小
function formatFileSize($bytes) {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' B';
    }
}

// 获取下载URL
function getDownloadUrl($fileName) {
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    
    if ($scriptPath === '/') {
        return $baseUrl . '/file_manager.php?action=download&file=' . urlencode($fileName);
    } else {
        return $baseUrl . $scriptPath . '/file_manager.php?action=download&file=' . urlencode($fileName);
    }
}
?>