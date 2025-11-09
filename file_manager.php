<?php
// file_manager.php - 文件管理功能
header('Content-Type: application/json; charset=utf-8');

// 定义存储文件夹
$storageFolder = 'downloads';

// 获取操作类型
$action = $_GET['action'] ?? '';

// 允许跨域请求（如果需要）
header('Access-Control-Allow-Origin: *');

// 统一的JSON响应函数
function jsonResponse($code, $msg, $data = null) {
    $response = [
        'code' => $code,
        'msg' => $msg
    ];
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    ob_clean();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 检查存储文件夹
function checkStorageFolder() {
    global $storageFolder;
    
    if (!is_dir($storageFolder)) {
        if (!mkdir($storageFolder, 0755, true)) {
            jsonResponse(0, '存储文件夹不存在且无法创建');
        }
    }
    
    if (!is_writable($storageFolder)) {
        jsonResponse(0, '存储文件夹不可写');
    }
}

// 安全检查文件名
function sanitizeFileName($fileName) {
    // 只允许中文、英文、数字、下划线和连字符，且必须是txt文件
    if (!preg_match('/^[a-zA-Z0-9\x{4e00}-\x{9fa5}_-]+\.txt$/u', $fileName)) {
        return false;
    }
    return basename($fileName);
}

// 获取文件下载URL
function getDownloadUrl($fileName) {
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']);
    
    if ($scriptPath === '/') {
        return $baseUrl . '/file_manager.php?action=download&file=' . urlencode($fileName);
    } else {
        return $baseUrl . $scriptPath . '/file_manager.php?action=download&file=' . urlencode($fileName);
    }
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

// 列出所有txt文件
function listFiles() {
    global $storageFolder;
    
    checkStorageFolder();
    
    $files = [];
    $fileList = glob($storageFolder . '/*.txt');
    
    foreach ($fileList as $file) {
        if (is_file($file)) {
            $fileName = basename($file);
            $fileSize = filesize($file);
            $fileTime = filemtime($file);
            
            $files[] = [
                'name' => $fileName,
                'size' => formatFileSize($fileSize),
                'size_bytes' => $fileSize,
                'time' => date('Y-m-d H:i:s', $fileTime),
                'timestamp' => $fileTime,
                'download_url' => getDownloadUrl($fileName),
                'full_path' => $file
            ];
        }
    }
    
    // 按时间倒序排列
    usort($files, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    
    jsonResponse(1, '成功获取文件列表', $files);
}

// 删除文件
function deleteFile() {
    global $storageFolder;
    
    $fileName = $_GET['file'] ?? '';
    
    if (empty($fileName)) {
        jsonResponse(0, '文件名不能为空');
    }
    
    $safeFileName = sanitizeFileName($fileName);
    if (!$safeFileName) {
        jsonResponse(0, '文件名不合法');
    }
    
    $filePath = $storageFolder . '/' . $safeFileName;
    
    if (!file_exists($filePath)) {
        jsonResponse(0, '文件不存在: ' . $safeFileName);
    }
    
    // 尝试删除文件
    if (unlink($filePath)) {
        // 记录删除日志
        error_log("文件删除成功: " . $safeFileName . " - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        jsonResponse(1, '文件删除成功: ' . $safeFileName);
    } else {
        $error = error_get_last();
        error_log("文件删除失败: " . $safeFileName . " - 错误: " . ($error['message'] ?? '未知错误'));
        jsonResponse(0, '文件删除失败: ' . ($error['message'] ?? '未知错误'));
    }
}

// 自动清理文件
function cleanupFiles() {
    global $storageFolder;
    
    $hours = isset($_GET['hours']) ? intval($_GET['hours']) : 24;
    
    if ($hours <= 0) {
        jsonResponse(0, '清理时间必须大于0');
    }
    
    checkStorageFolder();
    
    $cutoffTime = time() - ($hours * 3600);
    $deletedFiles = [];
    $fileList = glob($storageFolder . '/*.txt');
    
    foreach ($fileList as $file) {
        if (is_file($file) && filemtime($file) < $cutoffTime) {
            $fileName = basename($file);
            if (unlink($file)) {
                $deletedFiles[] = $fileName;
                // 记录删除日志
                error_log("自动清理删除文件: " . $fileName . " - 创建时间: " . date('Y-m-d H:i:s', filemtime($file)));
            }
        }
    }
    
    $deletedCount = count($deletedFiles);
    
    // 记录清理操作日志
    error_log("自动清理完成: 删除了 " . $deletedCount . " 个文件 - 清理时间点: " . date('Y-m-d H:i:s', $cutoffTime));
    
    jsonResponse(1, '自动清理完成', [
        'deleted_count' => $deletedCount,
        'deleted_files' => $deletedFiles,
        'cutoff_time' => date('Y-m-d H:i:s', $cutoffTime),
        'current_time' => date('Y-m-d H:i:s')
    ]);
}

// 下载文件
function downloadFile() {
    global $storageFolder;
    
    $fileName = $_GET['file'] ?? '';
    
    if (empty($fileName)) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=utf-8');
        echo "错误: 未指定文件名";
        exit;
    }
    
    $safeFileName = sanitizeFileName($fileName);
    if (!$safeFileName) {
        http_response_code(400);
        header('Content-Type: text/plain; charset=utf-8');
        echo "错误: 文件名不合法";
        exit;
    }
    
    $filePath = $storageFolder . '/' . $safeFileName;
    
    if (!file_exists($filePath)) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo "错误: 文件不存在";
        exit;
    }
    
    // 记录下载日志
    error_log("文件下载: " . $safeFileName . " - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . " - 时间: " . date('Y-m-d H:i:s'));
    
    // 设置下载头信息
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $safeFileName . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    
    // 清空输出缓冲区
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    readfile($filePath);
    exit;
}

// 获取文件统计信息
function getFileStats() {
    global $storageFolder;
    
    checkStorageFolder();
    
    $fileList = glob($storageFolder . '/*.txt');
    $totalFiles = count($fileList);
    $totalSize = 0;
    $oldestFile = null;
    $newestFile = null;
    
    foreach ($fileList as $file) {
        if (is_file($file)) {
            $fileSize = filesize($file);
            $fileTime = filemtime($file);
            $totalSize += $fileSize;
            
            if ($oldestFile === null || $fileTime < $oldestFile['time']) {
                $oldestFile = [
                    'name' => basename($file),
                    'time' => $fileTime,
                    'size' => $fileSize
                ];
            }
            
            if ($newestFile === null || $fileTime > $newestFile['time']) {
                $newestFile = [
                    'name' => basename($file),
                    'time' => $fileTime,
                    'size' => $fileSize
                ];
            }
        }
    }
    
    return [
        'total_files' => $totalFiles,
        'total_size' => formatFileSize($totalSize),
        'total_size_bytes' => $totalSize,
        'oldest_file' => $oldestFile ? [
            'name' => $oldestFile['name'],
            'time' => date('Y-m-d H:i:s', $oldestFile['time']),
            'size' => formatFileSize($oldestFile['size'])
        ] : null,
        'newest_file' => $newestFile ? [
            'name' => $newestFile['name'],
            'time' => date('Y-m-d H:i:s', $newestFile['time']),
            'size' => formatFileSize($newestFile['size'])
        ] : null
    ];
}

// 获取文件统计信息
function fileStats() {
    $stats = getFileStats();
    jsonResponse(1, '文件统计信息获取成功', $stats);
}

// 批量删除文件
function batchDeleteFiles() {
    global $storageFolder;
    
    $fileNames = $_POST['files'] ?? [];
    
    if (empty($fileNames) || !is_array($fileNames)) {
        jsonResponse(0, '请选择要删除的文件');
    }
    
    checkStorageFolder();
    
    $deletedFiles = [];
    $failedFiles = [];
    
    foreach ($fileNames as $fileName) {
        $safeFileName = sanitizeFileName($fileName);
        if (!$safeFileName) {
            $failedFiles[] = ['file' => $fileName, 'reason' => '文件名不合法'];
            continue;
        }
        
        $filePath = $storageFolder . '/' . $safeFileName;
        
        if (!file_exists($filePath)) {
            $failedFiles[] = ['file' => $fileName, 'reason' => '文件不存在'];
            continue;
        }
        
        if (unlink($filePath)) {
            $deletedFiles[] = $safeFileName;
            error_log("批量删除文件: " . $safeFileName);
        } else {
            $failedFiles[] = ['file' => $fileName, 'reason' => '删除失败'];
        }
    }
    
    jsonResponse(1, '批量删除完成', [
        'deleted_files' => $deletedFiles,
        'failed_files' => $failedFiles,
        'deleted_count' => count($deletedFiles),
        'failed_count' => count($failedFiles)
    ]);
}

// 搜索文件
function searchFiles() {
    global $storageFolder;
    
    $keyword = $_GET['keyword'] ?? '';
    
    if (empty($keyword)) {
        jsonResponse(0, '请输入搜索关键词');
    }
    
    checkStorageFolder();
    
    $files = [];
    $fileList = glob($storageFolder . '/*.txt');
    
    foreach ($fileList as $file) {
        if (is_file($file)) {
            $fileName = basename($file);
            
            // 搜索文件名中包含关键词的文件
            if (stripos($fileName, $keyword) !== false) {
                $fileSize = filesize($file);
                $fileTime = filemtime($file);
                
                $files[] = [
                    'name' => $fileName,
                    'size' => formatFileSize($fileSize),
                    'size_bytes' => $fileSize,
                    'time' => date('Y-m-d H:i:s', $fileTime),
                    'timestamp' => $fileTime,
                    'download_url' => getDownloadUrl($fileName),
                    'full_path' => $file
                ];
            }
        }
    }
    
    // 按时间倒序排列
    usort($files, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    
    jsonResponse(1, '搜索完成', [
        'keyword' => $keyword,
        'results' => $files,
        'count' => count($files)
    ]);
}

// 处理不同动作
try {
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
        case 'stats':
            fileStats();
            break;
        case 'batch_delete':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                batchDeleteFiles();
            } else {
                jsonResponse(0, '只支持POST请求');
            }
            break;
        case 'search':
            searchFiles();
            break;
        default:
            jsonResponse(0, '未知操作: ' . $action);
    }
} catch (Exception $e) {
    error_log("文件管理器错误: " . $e->getMessage());
    jsonResponse(0, '服务器错误: ' . $e->getMessage());
}
?>