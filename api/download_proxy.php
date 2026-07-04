<?php
/**
 * 下载代理 - 用于代理下载抖音视频/图片资源
 * 用法: download_proxy.php?url=ENCODED_URL&filename=OPTIONAL_FILENAME
 */

require_once __DIR__ . '/../includes/auth.php';
dyzy_require_user(false);

if (!function_exists('sg_load')) {
    header('Location: ../system/source-guardian-loader-helper.php');
    exit;
}

$url = $_GET['url'] ?? '';
$filename = $_GET['filename'] ?? '';
$inline = isset($_GET['inline']) && $_GET['inline'] === '1';

if (empty($url)) {
    http_response_code(400);
    die('缺少 url 参数');
}

// 安全检查：只允许下载抖音相关域名的资源
$allowedDomains = [
    'douyin.com',
    'douyinpic.com',
    'douyinvod.com',
    'snssdk.com',
    'bytecdn.cn',
    'bytedance.com',
    'byteimg.com',
    'pstatp.com',
    'tiktokcdn.com',
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
    http_response_code(403);
    die('不允许下载该域名的资源');
}

// 生成文件名
if (empty($filename)) {
    // 从 URL 中提取 video_id 或使用随机名
    $queryParams = [];
    parse_str($parsedUrl['query'] ?? '', $queryParams);
    
    if (!empty($queryParams['video_id'])) {
        $filename = $queryParams['video_id'];
    } else {
        $filename = 'download_' . time();
    }
    
    // 根据 URL 判断类型
    if (strpos($url, 'douyinpic.com') !== false || strpos($url, 'aweme-images') !== false) {
        $filename .= '.webp';
    } else {
        $filename .= '.mp4';
    }
}

$requestHeaders = [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Referer: https://www.douyin.com/',
    'Origin: https://www.douyin.com',
    'Accept: */*',
];

if (!empty($_SERVER['HTTP_RANGE'])) {
    $requestHeaders[] = 'Range: ' . $_SERVER['HTTP_RANGE'];
}

if ($inline) {
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Cache-Control: public, max-age=3600');
} else {
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
}

// 先解析最终媒体地址，避免把 302 中间响应头转发给浏览器造成网关 502
$resolveCh = curl_init();
curl_setopt_array($resolveCh, [
    CURLOPT_URL            => $url,
    CURLOPT_NOBODY         => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 10,
    CURLOPT_TIMEOUT        => 30,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => $requestHeaders,
    CURLOPT_SSL_VERIFYPEER => false,
]);
curl_exec($resolveCh);
$resolveError = curl_error($resolveCh);
$finalUrl = curl_getinfo($resolveCh, CURLINFO_EFFECTIVE_URL) ?: $url;
curl_close($resolveCh);

if ($resolveError !== '') {
    http_response_code(502);
    echo '解析媒体地址失败: ' . $resolveError;
    exit;
}

$finalHost = parse_url($finalUrl, PHP_URL_HOST) ?: '';
$finalAllowed = false;
foreach ($allowedDomains as $domain) {
    if ($finalHost === $domain || substr($finalHost, -strlen('.' . $domain)) === '.' . $domain) {
        $finalAllowed = true;
        break;
    }
}

if (!$finalAllowed) {
    http_response_code(403);
    die('不允许代理跳转后的资源域名');
}

// 使用 cURL 代理最终媒体资源下载/播放
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => $finalUrl,
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_TIMEOUT        => 300,
    CURLOPT_CONNECTTIMEOUT => 30,
    CURLOPT_RETURNTRANSFER => false,
    CURLOPT_HEADERFUNCTION => function ($curl, $header) {
        $len = strlen($header);
        $trimmed = trim($header);
        if ($trimmed === '') return $len;

        if (preg_match('#^HTTP/\S+\s+(\d+)#i', $trimmed, $matches)) {
            $code = (int)$matches[1];
            if ($code >= 100 && $code < 600) {
                http_response_code($code);
            }
            return $len;
        }

        $parts = explode(':', $trimmed, 2);
        if (count($parts) < 2) return $len;

        $name = strtolower(trim($parts[0]));
        if (in_array($name, ['content-type', 'content-length', 'content-range', 'accept-ranges'])) {
            header($trimmed, true);
        }

        return $len;
    },
    CURLOPT_HTTPHEADER     => $requestHeaders,
    CURLOPT_SSL_VERIFYPEER => false,
]);

$result = curl_exec($ch);

if (curl_errno($ch)) {
    if (!headers_sent()) http_response_code(502);
    echo '下载失败: ' . curl_error($ch);
}

curl_close($ch);
