<?php
/**
 * 服务器端保存 - 将视频/图片下载保存到服务器本地目录
 * 用法: POST 请求，JSON body: { "url": "...", "filename": "...", "folder": "..." }
 * 返回: JSON { "success": true/false, "message": "...", "path": "..." }
 */

if (!function_exists('sg_load')) {
    header('Location: ../system/source-guardian-loader-helper.php');
    exit;
}

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/storage.php';
dyzy_require_user(true);

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => '仅支持 POST 请求']);
    exit;
}
dyzy_require_csrf(true);

$input = json_decode(file_get_contents('php://input'), true);
$url = $input['url'] ?? '';
$filename = $input['filename'] ?? '';
$folder = $input['folder'] ?? 'media_downloads';

if (empty($url)) {
    echo json_encode(['success' => false, 'message' => '缺少 url 参数']);
    exit;
}

if (empty($filename)) {
    $filename = 'download_' . time() . '.mp4';
}

// 安全域名白名单
$allowedDomains = [
    'douyin.com', 'douyinpic.com', 'douyinvod.com',
    'snssdk.com', 'bytecdn.cn', 'bytedance.com',
    'byteimg.com', 'pstatp.com', 'tiktokcdn.com',
];

$parsedUrl = parse_url($url);
$host = $parsedUrl['host'] ?? '';
$isAllowed = false;

foreach ($allowedDomains as $domain) {
    if ($host === $domain || substr($host, -strlen('.' . $domain)) === '.' . $domain) {
        $isAllowed = true;
        break;
    }
}

if (!$isAllowed) {
    echo json_encode(['success' => false, 'message' => '不允许下载该域名的资源']);
    exit;
}

// 清理文件名中的非法字符
$filename = preg_replace('/[\/\\\\:*?"<>|]/', '_', $filename);
$folder = preg_replace('/[\/\\\\:*?"<>|]/', '_', $folder);

// 创建当前会话保存目录
$workspaceDir = dyzy_workspace_download_dir();
$saveDir = $workspaceDir . '/' . $folder;
if (!is_dir($saveDir)) {
    mkdir($saveDir, 0755, true);
}

$savePath = $saveDir . '/' . $filename;

// 如果文件已存在，跳过
if (file_exists($savePath)) {
    echo json_encode([
        'success' => true,
        'message' => '文件已存在，跳过',
        'path' => dyzy_workspace_relative_path($folder . '/' . $filename),
        'skipped' => true,
    ]);
    exit;
}

// 使用 cURL 下载并保存
$tmpFile = $savePath . '.tmp';
$fp = fopen($tmpFile, 'wb');
if (!$fp) {
    echo json_encode(['success' => false, 'message' => '无法创建文件']);
    exit;
}

$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $url,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 300,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_FILE           => $fp,
    CURLOPT_HTTPHEADER     => [
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Referer: https://www.douyin.com/',
    ],
    CURLOPT_SSL_VERIFYPEER => false,
]);

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$fileSize = curl_getinfo($ch, CURLINFO_SIZE_DOWNLOAD);
$curlError = curl_error($ch);
curl_close($ch);
fclose($fp);

if (!$result || $httpCode < 200 || $httpCode >= 400) {
    @unlink($tmpFile);
    echo json_encode([
        'success' => false,
        'message' => '下载失败: HTTP ' . $httpCode . ($curlError ? ' - ' . $curlError : ''),
    ]);
    exit;
}

// 重命名临时文件
rename($tmpFile, $savePath);

$storageConfig = dyzy_storage_load();
$uploadResult = null;
$localDeleted = false;
if (!empty($storageConfig['enabled'])) {
    $objectKey = dyzy_storage_object_key($savePath, $folder);
    $uploadResult = dyzy_storage_upload_file($savePath, $objectKey);
    if (!empty($uploadResult['success']) && !empty($storageConfig['delete_local_after_upload'])) {
        $localDeleted = @unlink($savePath);
    }
}

$response = [
    'success' => true,
    'message' => $uploadResult && empty($uploadResult['success']) ? '保存成功，对象存储上传失败' : '保存成功',
    'path' => dyzy_workspace_relative_path($folder . '/' . $filename),
    'size' => $fileSize,
    'storage' => $uploadResult,
    'local_deleted' => $localDeleted,
];

if ($uploadResult && !empty($uploadResult['success'])) {
    $response['cloud_url'] = $uploadResult['url'] ?? '';
    $response['message'] = '保存并上传对象存储成功';
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
