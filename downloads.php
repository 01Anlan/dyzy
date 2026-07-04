<?php
require_once __DIR__ . '/includes/auth.php';
dyzy_require_user(false);

if (!function_exists('sg_load')) {
    header('Location: system/source-guardian-loader-helper.php');
    exit;
}

$installLockFile = __DIR__ . '/install/install.lock';
$installDir = __DIR__ . '/install';
if (is_dir($installDir) && file_exists($installDir . '/install.php') && !file_exists($installLockFile)) {
    header('Location: install/install.php');
    exit;
}

$downloadsDir = dyzy_workspace_download_dir();
$files = [];

if (is_dir($downloadsDir)) {
    $allFiles = scandir($downloadsDir);
    foreach ($allFiles as $file) {
        if ($file === '.' || $file === '..') continue;
        $filePath = $downloadsDir . '/' . $file;
        if (!is_file($filePath)) continue;
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $mediaUrls = extractMediaUrlsFromLines($lines);
        $linkCount = !empty($mediaUrls) ? count($mediaUrls) : count($lines);
        $files[] = [
            'name'       => $file,
            'path'       => $filePath,
            'size'       => filesize($filePath),
            'modified'   => filemtime($filePath),
            'linkCount'  => $linkCount,
            'extension'  => pathinfo($file, PATHINFO_EXTENSION),
        ];
    }
    usort($files, fn($a, $b) => $b['modified'] - $a['modified']);
}

$viewFile = $_GET['file'] ?? null;
$viewContent = [];
$viewFileName = '';

if ($viewFile) {
    $safeName = basename($viewFile);
    $safePath = $downloadsDir . '/' . $safeName;
    if (file_exists($safePath) && is_file($safePath)) {
        $viewFileName = $safeName;
        $viewContent = file($safePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    } else {
        $viewFile = null;
    }
}

function formatSize($bytes) {
    if ($bytes < 1024) return $bytes . ' B';
    if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
    return round($bytes / 1048576, 1) . ' MB';
}

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

function detectLinkType($lines) {
    if (empty($lines)) return 'empty';
    foreach ($lines as $line) {
        $url = extractMediaUrlFromLine($line);
        if ($url === '') continue;
        if (strpos($url, '/play/') !== false || strpos($url, 'video_id') !== false) {
            return 'video';
        }
        if (strpos($url, 'douyinpic.com') !== false || strpos($url, '.webp') !== false || strpos($url, '.jpeg') !== false || strpos($url, '.png') !== false) {
            return 'image';
        }
        return 'link';
    }
    return 'link';
}

function getMediaFilename($url, $index, $type) {
    $ext = $type === 'image' ? '.webp' : '.mp4';
    $queryParams = [];
    $parsed = parse_url($url);
    parse_str($parsed['query'] ?? '', $queryParams);
    if (!empty($queryParams['video_id'])) {
        return $queryParams['video_id'] . $ext;
    }
    return 'media_' . ($index + 1) . $ext;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>下载中心 - 抖音解析工具</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/vendor/fonts/local-fonts.css">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/downloads.css?v=2026070309">
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
    <div class="download-progress" id="downloadProgress">
        <div class="download-progress-bar" id="downloadProgressBar" style="width:0%"></div>
    </div>

    <div class="wrap">
        <div class="site-header">
            <div class="logo-mark"><i class="fas fa-download"></i></div>
            <div>
                <h1>下载中心</h1>
                <p>视频 / 图片资源批量下载</p>
            </div>
        </div>

        <?php if ($viewFile && !empty($viewContent)): ?>
            <!-- 文件详情视图 -->
            <nav class="breadcrumb">
                <a href="./"><i class="fas fa-home"></i> 首页</a>
                <i class="fas fa-chevron-right"></i>
                <a href="downloads.php">下载中心</a>
                <i class="fas fa-chevron-right"></i>
                <span><?= htmlspecialchars($viewFileName) ?></span>
            </nav>

            <?php
                $linkType = detectLinkType($viewContent);
                $typeLabel = $linkType === 'video' ? '视频' : ($linkType === 'image' ? '图片' : '链接');
                $typeIcon = $linkType === 'video' ? 'fa-video' : ($linkType === 'image' ? 'fa-image' : 'fa-link');
                $mediaLinks = extractMediaUrlsFromLines($viewContent);
                $totalLinks = count($mediaLinks);

                $perPage = 50;
                $currentPage = max(1, intval($_GET['page'] ?? 1));
                $search = trim($_GET['q'] ?? '');
                $filteredLinks = $mediaLinks;

                if ($search !== '') {
                    $filteredLinks = array_filter($mediaLinks, function($link) use ($search) {
                        return stripos($link, $search) !== false;
                    });
                }

                $totalFiltered = count($filteredLinks);
                $totalPages = max(1, ceil($totalFiltered / $perPage));
                $currentPage = min($currentPage, $totalPages);
                $offset = ($currentPage - 1) * $perPage;
                $pagedLinks = array_slice(array_values($filteredLinks), $offset, $perPage);
            ?>

            <section class="hero">
                <div class="badge"><i class="fas <?= $typeIcon ?>"></i> <?= $typeLabel ?>资源文件</div>
                <h2><?= htmlspecialchars(pathinfo($viewFileName, PATHINFO_FILENAME)) ?></h2>
                <p>共 <strong><?= $totalLinks ?></strong> 条<?= $typeLabel ?>链接，可逐条或批量下载<?= $typeLabel ?>资源到本地。</p>
                <div class="stats-bar">
                    <span class="stat-chip"><i class="fas fa-link"></i> <strong><?= $totalLinks ?></strong> 条链接</span>
                    <span class="stat-chip"><i class="fas <?= $typeIcon ?>"></i> <?= $typeLabel ?>类型</span>
                    <span class="stat-chip"><i class="fas fa-file"></i> <?= formatSize(filesize($downloadsDir . '/' . $viewFileName)) ?></span>
                </div>
            </section>

            <form class="search-bar" method="get" action="downloads.php">
                <input type="hidden" name="file" value="<?= htmlspecialchars($viewFileName) ?>">
                <i class="fas fa-search"></i>
                <input type="text" name="q" placeholder="搜索链接内容..." value="<?= htmlspecialchars($search) ?>">
            </form>

            <div class="content-header">
                <h2 style="font-size:16px;">
                    <?php if ($search !== ''): ?>
                        搜索结果：<?= $totalFiltered ?> 条匹配
                    <?php else: ?>
                        链接列表
                    <?php endif; ?>
                </h2>
                <div class="content-tools">
                    <div class="mode-selector" id="modeSelector">
                        <button class="mode-option active" data-mode="local" onclick="setDownloadMode('local')"><i class="fas fa-laptop"></i> 下载到本地</button>
                        <button class="mode-option" data-mode="server" onclick="setDownloadMode('server')"><i class="fas fa-server"></i> 下载到服务器</button>
                    </div>
                    <span class="batch-status" id="batchStatus">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span id="batchStatusText">准备下载...</span>
                    </span>
                    <div class="batch-controls" id="batchControls">
                        <a class="btn btn-sm btn-outline" href="watch.php?file=<?= urlencode($viewFileName) ?>"><i class="fas fa-play"></i> 观看视频</a>
                        <button class="btn btn-sm btn-outline" onclick="copyAllLinks()"><i class="fas fa-copy"></i> 复制全部</button>
                        <button class="btn btn-sm btn-success" id="btnBatchStart" onclick="batchDownload()"><i class="fas fa-play"></i> 开始下载</button>
                        <button class="btn btn-sm btn-pause" id="btnBatchPause" onclick="batchPause()" style="display:none"><i class="fas fa-pause"></i> 暂停</button>
                        <button class="btn btn-sm btn-resume" id="btnBatchResume" onclick="batchResume()" style="display:none"><i class="fas fa-play"></i> 继续</button>
                        <button class="btn btn-sm btn-stop" id="btnBatchStop" onclick="batchStop()" style="display:none"><i class="fas fa-stop"></i> 停止</button>
                    </div>
                </div>
            </div>

            <?php if (empty($pagedLinks)): ?>
                <div class="empty-state">
                    <i class="fas fa-search"></i>
                    <p>没有找到匹配的链接</p>
                </div>
            <?php else: ?>
                <div class="link-table">
                    <div class="link-table-header">
                        <span>显示 <?= $offset + 1 ?>-<?= min($offset + $perPage, $totalFiltered) ?> / 共 <?= $totalFiltered ?> 条</span>
                        <span>类型：<?= $typeLabel ?></span>
                    </div>
                    <ul class="link-list" id="linkList">
                        <?php foreach ($pagedLinks as $i => $link): ?>
                        <?php
                            $mediaUrl = $link;
                            $mediaFilename = getMediaFilename($mediaUrl, $offset + $i, $linkType);
                        ?>
                        <li class="link-item" data-url="<?= htmlspecialchars($mediaUrl) ?>" data-filename="<?= htmlspecialchars($mediaFilename) ?>">
                            <span class="link-num"><?= $offset + $i + 1 ?></span>
                            <span class="link-url"><a href="<?= htmlspecialchars($mediaUrl) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($mediaUrl) ?></a></span>
                            <div class="link-actions">
                                <button class="link-btn" onclick="copySingle(this, '<?= htmlspecialchars($mediaUrl) ?>')" title="复制链接">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button class="link-btn" onclick="downloadSingle(this)" title="下载<?= $typeLabel ?>">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php
                        $baseUrl = "downloads.php?file=" . urlencode($viewFileName);
                        if ($search !== '') $baseUrl .= '&q=' . urlencode($search);
                    ?>
                    <a class="page-btn" href="<?= $baseUrl ?>&page=<?= max(1, $currentPage - 1) ?>" <?= $currentPage <= 1 ? 'disabled' : '' ?>>
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php
                        $startPage = max(1, $currentPage - 3);
                        $endPage = min($totalPages, $currentPage + 3);
                        if ($startPage > 1): ?>
                            <a class="page-btn" href="<?= $baseUrl ?>&page=1">1</a>
                            <?php if ($startPage > 2): ?><span style="color:var(--text-3)">…</span><?php endif; ?>
                    <?php endif;
                        for ($p = $startPage; $p <= $endPage; $p++): ?>
                            <a class="page-btn <?= $p === $currentPage ? 'active' : '' ?>" href="<?= $baseUrl ?>&page=<?= $p ?>"><?= $p ?></a>
                    <?php endfor;
                        if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?><span style="color:var(--text-3)">…</span><?php endif; ?>
                            <a class="page-btn" href="<?= $baseUrl ?>&page=<?= $totalPages ?>"><?= $totalPages ?></a>
                    <?php endif; ?>
                    <a class="page-btn" href="<?= $baseUrl ?>&page=<?= min($totalPages, $currentPage + 1) ?>" <?= $currentPage >= $totalPages ? 'disabled' : '' ?>>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <div style="margin-top:16px;">
                <a class="btn-back" href="downloads.php"><i class="fas fa-arrow-left"></i> 返回下载中心</a>
            </div>

        <?php else: ?>
            <!-- 文件列表视图 -->
            <nav class="breadcrumb">
                <a href="./"><i class="fas fa-home"></i> 首页</a>
                <i class="fas fa-chevron-right"></i>
                <span>下载中心</span>
            </nav>

            <section class="hero">
                <div class="badge"><i class="fas fa-folder-open"></i> 下载中心</div>
                <h2>资源下载管理</h2>
                <p>浏览已存储的解析结果，点击进入后可逐条或批量下载视频/图片资源到本地。</p>
                <div class="stats-bar">
                    <span class="stat-chip"><i class="fas fa-file"></i> <strong><?= count($files) ?></strong> 个文件</span>
                    <span class="stat-chip"><i class="fas fa-link"></i> <strong><?= array_sum(array_column($files, 'linkCount')) ?></strong> 条链接</span>
                </div>
            </section>

            <?php if (empty($files)): ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>暂无下载文件，请先进行解析操作</p>
                </div>
            <?php else: ?>
                <div class="file-list">
                    <?php foreach ($files as $f): ?>
                        <?php
                            $lines = file($downloadsDir . '/' . $f['name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                            $type = detectLinkType($lines);
                            $typeIcon = $type === 'video' ? 'fa-video' : ($type === 'image' ? 'fa-image' : 'fa-link');
                            $typeClass = $type === 'video' ? 'video' : ($type === 'image' ? 'image' : 'link');
                            $typeLabel = $type === 'video' ? '视频' : ($type === 'image' ? '图片' : '链接');
                        ?>
                        <a class="file-card" href="downloads.php?file=<?= urlencode($f['name']) ?>">
                            <div class="file-info">
                                <div class="file-icon <?= $typeClass ?>">
                                    <i class="fas <?= $typeIcon ?>"></i>
                                </div>
                                <div class="file-details">
                                    <div class="file-name"><?= htmlspecialchars(pathinfo($f['name'], PATHINFO_FILENAME)) ?></div>
                                    <div class="file-meta">
                                        <span><i class="fas fa-link"></i> <?= $f['linkCount'] ?> 条<?= $typeLabel ?>链接</span>
                                        <span><i class="fas fa-file"></i> <?= formatSize($f['size']) ?></span>
                                        <span><i class="fas fa-clock"></i> <?= date('Y-m-d H:i', $f['modified']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="file-actions">
                                <span class="btn btn-sm"><i class="fas fa-play"></i> 可观看</span>
                                <span class="btn btn-sm btn-primary"><i class="fas fa-download"></i> 进入下载</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <section class="footer">
                <strong>使用说明：</strong> 点击文件卡片进入详情页，可逐条下载视频/图片资源，也可进入观看页在线播放。下载通过服务器代理，无需担心跨域问题。
            </section>
        <?php endif; ?>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        window.__DOWNLOADS_PAGE__ = {
            allLinks: <?= json_encode($viewContent ? extractMediaUrlsFromLines($viewContent) : [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>,
            linkType: <?= json_encode($viewContent ? detectLinkType($viewContent) : '', JSON_UNESCAPED_UNICODE) ?>,
            fileFolder: <?= json_encode($viewContent ? htmlspecialchars(pathinfo($viewFileName, PATHINFO_FILENAME)) : '', JSON_UNESCAPED_UNICODE) ?>,
            csrfToken: <?= json_encode(dyzy_csrf_token(), JSON_UNESCAPED_UNICODE) ?>
        };
    </script>
    <script src="assets/js/app-console.js"></script>
    <script src="assets/js/downloads.js"></script>
</body>
</html>
