<?php
require_once __DIR__ . '/includes/auth.php';
dyzy_require_user(false);

if (!function_exists('sg_load')) {
    header('Location: system/source-guardian-loader-helper.php');
    exit;
}

$downloadsDir = dyzy_workspace_download_dir();
$files = [];

function isDisplayableMediaUrl($url) {
    $url = trim((string)$url);
    if ($url === '' || !preg_match('/^https?:\/\//i', $url)) return false;
    return !preg_match('/\.(mp3|m4a|aac|wav|flac)(\?|$)/i', $url);
}

function extractMediaUrlFromLine($line) {
    $line = trim((string)$line);
    if ($line === '') return '';
    if (preg_match('/^https?:\/\//i', $line)) return isDisplayableMediaUrl($line) ? $line : '';

    if (preg_match('/^(?:播放地址|图片地址|视频地址|媒体地址)[：:]\s*(https?:\/\/\S+)/u', $line, $matches)) {
        return isDisplayableMediaUrl($matches[1]) ? $matches[1] : '';
    }

    $record = json_decode($line, true);
    if (!is_array($record)) return '';

    foreach (['url', 'play_url', 'image_url', 'cover', 'origin_cover'] as $key) {
        $url = trim((string)($record[$key] ?? ''));
        if (isDisplayableMediaUrl($url)) {
            return $url;
        }
    }

    return '';
}

function extractMediaUrlsFromLines($lines) {
    return array_values(array_filter(array_map('extractMediaUrlFromLine', $lines)));
}

if (is_dir($downloadsDir)) {
    foreach (scandir($downloadsDir) as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $downloadsDir . '/' . $file;
        if (!is_file($path) || strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'txt') continue;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        $links = extractMediaUrlsFromLines($lines);
        if (!$links) continue;
        $files[] = [
            'name' => $file,
            'count' => count($links),
            'size' => filesize($path),
            'modified' => filemtime($path),
        ];
    }
    usort($files, fn($a, $b) => $b['modified'] - $a['modified']);
}

$viewFile = basename((string)($_GET['file'] ?? ''));
if ($viewFile === '' && $files) {
    $viewFile = $files[0]['name'];
}

$links = [];
$fileTitle = '';
if ($viewFile !== '') {
    $safePath = $downloadsDir . '/' . $viewFile;
    if (is_file($safePath) && strtolower(pathinfo($safePath, PATHINFO_EXTENSION)) === 'txt') {
        $fileTitle = pathinfo($viewFile, PATHINFO_FILENAME);
        $lines = file($safePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
        $links = extractMediaUrlsFromLines($lines);
    }
}

function formatSize($bytes) {
    if ($bytes < 1024) return $bytes . ' B';
    if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
    return round($bytes / 1048576, 1) . ' MB';
}

function isImageLink($url) {
    return preg_match('/\.(webp|jpg|jpeg|png|gif)(\?|$)/i', $url) || strpos($url, 'douyinpic.com') !== false;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>观看视频 - 抖音解析工具</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/vendor/fonts/local-fonts.css">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/watch.css?v=2026070309">
    <link rel="stylesheet" href="assets/css/ui.css?v=2026070309">
    <style>
        @media (max-width: 900px) {
            .site-header { display: grid !important; grid-template-columns: 42px minmax(0, 1fr) !important; align-items: start !important; gap: 10px 12px !important; }
            .site-header > div:not(.logo-mark):not(.user-panel) { min-width: 0 !important; }
            .site-header .user-panel { grid-column: 1 / -1 !important; width: 100% !important; margin-left: 0 !important; display: grid !important; grid-template-columns: repeat(3, minmax(0, 1fr)) !important; gap: 8px !important; }
            .site-header .user-panel span, .site-header .user-panel .btn { width: 100% !important; min-width: 0 !important; height: 36px !important; justify-content: center !important; padding: 0 8px !important; font-size: 12px !important; white-space: nowrap !important; }
        }
        @media (max-width: 420px) { .site-header .user-panel { grid-template-columns: 1fr 1fr !important; } .site-header .user-panel span { grid-column: 1 / -1 !important; } }
    </style>
</head>
<body>
<div class="wrap">
    <div class="site-header">
        <div class="logo-mark"><i class="fas fa-play"></i></div>
        <div>
            <h1>观看视频</h1>
            <p>从解析生成的 TXT 链接文件中直接播放视频或预览图片</p>
        </div>
    </div>

    <div class="breadcrumb">
        <a href="index.php"><i class="fas fa-house"></i> 首页</a><span>/</span>
        <a href="downloads.php">下载中心</a><span>/</span><span>观看视频</span>
    </div>

    <div class="layout">
        <aside class="card">
            <div class="card-head">
                <div class="card-title"><i class="fas fa-file-lines"></i> 结果文件</div>
                <span class="badge"><?= count($files) ?> 个</span>
            </div>
            <?php if (!$files): ?>
                <div class="empty"><i class="fas fa-folder-open"></i><p>暂无可观看的 TXT 结果文件</p></div>
            <?php else: ?>
                <div class="file-list">
                    <?php foreach ($files as $file): ?>
                        <a class="file-item <?= $file['name'] === $viewFile ? 'active' : '' ?>" href="watch.php?file=<?= urlencode($file['name']) ?>">
                            <div class="item-icon"><i class="fas fa-list"></i></div>
                            <div class="item-info">
                                <div class="item-name"><?= htmlspecialchars(pathinfo($file['name'], PATHINFO_FILENAME)) ?></div>
                                <div class="item-meta"><?= $file['count'] ?> 条 · <?= formatSize($file['size']) ?> · <?= date('Y-m-d H:i', $file['modified']) ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </aside>

        <main class="content-grid">
            <section class="card player-card">
                <div class="player-title">
                    <span><?= $fileTitle ? htmlspecialchars($fileTitle) : '未选择文件' ?></span>
                    <span id="counter"><?= $links ? '1 / ' . count($links) : '0 / 0' ?></span>
                </div>
                <?php if (!$links): ?>
                    <div class="empty"><i class="fas fa-video-slash"></i><p>当前文件没有可播放链接</p></div>
                <?php else: ?>
                    <div class="player-wrap" id="playerWrap"></div>
                    <div class="toolbar">
                        <div>
                            <button class="btn" id="prevBtn"><i class="fas fa-chevron-left"></i> 上一条</button>
                            <button class="btn btn-primary" id="nextBtn">下一条 <i class="fas fa-chevron-right"></i></button>
                        </div>
                        <div>
                            <label class="switch-label" for="autoNextToggle"><input type="checkbox" id="autoNextToggle"> 播完自动下一条</label>
                            <button class="btn" id="copyBtn"><i class="fas fa-copy"></i> 复制当前链接</button>
                            <a class="btn" id="openBtn" target="_blank" rel="noopener"><i class="fas fa-arrow-up-right-from-square"></i> 新窗口打开</a>
                        </div>
                    </div>
                    <div class="current-url" id="currentUrl"></div>
                <?php endif; ?>
            </section>

            <aside class="card">
                <div class="card-head">
                    <div class="card-title"><i class="fas fa-list-ol"></i> 播放列表</div>
                    <span class="badge"><?= count($links) ?> 条</span>
                </div>
                <?php if ($links): ?>
                    <div class="media-list" id="mediaList">
                        <?php foreach ($links as $index => $url): ?>
                            <div class="media-item <?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
                                <div class="item-icon"><i class="fas <?= isImageLink($url) ? 'fa-image' : 'fa-video' ?>"></i></div>
                                <div class="item-info">
                                    <div class="item-name">第 <?= $index + 1 ?> 条<?= isImageLink($url) ? '图片' : '视频' ?></div>
                                    <div class="item-meta"><?= htmlspecialchars(parse_url($url, PHP_URL_HOST) ?: $url) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty"><i class="fas fa-list"></i><p>暂无播放列表</p></div>
                <?php endif; ?>
            </aside>
        </main>
    </div>
</div>

<script>
    window.__WATCH_PAGE__ = {
        links: <?= json_encode($links, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
        viewFile: <?= json_encode($viewFile, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    };
</script>
<script src="assets/js/app-console.js"></script>
<script src="assets/js/watch.js"></script>
</body>
</html>
