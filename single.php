<?php
require_once __DIR__ . '/includes/auth.php';
dyzy_require_user(false);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>单作品解析 - 抖音解析工具</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/vendor/fonts/local-fonts.css">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/parser.css?v=2026070309">
    <link rel="stylesheet" href="assets/css/ui.css?v=2026070309">
    <style>
        @media (max-width: 900px) {
            .site-header {
                display: grid !important;
                grid-template-columns: 42px minmax(0, 1fr) !important;
                align-items: start !important;
                gap: 10px 12px !important;
            }
            .site-header > div:not(.logo-mark):not(.user-panel) {
                min-width: 0 !important;
            }
            .site-header .user-panel {
                grid-column: 1 / -1 !important;
                width: 100% !important;
                margin-left: 0 !important;
                display: grid !important;
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
                gap: 8px !important;
            }
            .site-header .user-panel span,
            .site-header .user-panel .btn {
                width: 100% !important;
                min-width: 0 !important;
                height: 36px !important;
                justify-content: center !important;
                padding: 0 8px !important;
                font-size: 12px !important;
                white-space: nowrap !important;
            }
        }
        @media (max-width: 420px) {
            .site-header .user-panel {
                grid-template-columns: 1fr 1fr !important;
            }
            .site-header .user-panel span {
                grid-column: 1 / -1 !important;
            }
        }
    </style>
</head>
<body>
<div class="page-wrap single-page">
    <div class="site-header">
        <div class="logo-mark">
            <i class="fab fa-tiktok"></i>
        </div>
        <div>
            <h1>单作品解析</h1>
            <p>解析单个抖音视频或图文作品</p>
        </div>
        <div class="user-panel">
            <span id="userStatus"><i class="fas fa-user"></i> 未登录</span>
            <a class="btn btn-ghost" href="account.php"><i class="fas fa-shield-halved"></i> 账号设置</a>
            <button class="btn btn-ghost" id="logoutUserBtn" type="button"><i class="fas fa-right-from-bracket"></i> 退出</button>
        </div>
    </div>

    <div class="browser-tabs">
        <a class="browser-tab active" href="single.php"><i class="fas fa-bolt"></i> 单作品解析</a>
        <a class="browser-tab" href="parser.html"><i class="fas fa-link"></i> 主页解析</a>
        <a class="browser-tab" href="parser.html#cookiePanel"><i class="fas fa-cookie-bite"></i> 点赞 / 收藏解析</a>
        <a class="browser-tab" href="downloads.php"><i class="fas fa-download"></i> 下载中心</a>
        <a class="browser-tab" href="settings.php"><i class="fas fa-gear"></i> 全局设置</a>
    </div>

    <div class="alert alert-error" id="errorAlert">
        <i class="fas fa-circle-exclamation"></i>
        <span id="errorMsg"></span>
    </div>
    <div class="alert alert-success" id="successAlert">
        <i class="fas fa-circle-check"></i>
        <span id="successMsg"></span>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-wand-magic-sparkles"></i>
                作品链接
            </div>
        </div>

        <div class="field">
            <label>抖音作品链接</label>
            <div class="input-wrap">
                <i class="fas fa-paste input-icon"></i>
                <input type="text" id="singleUrl" class="with-icon" placeholder="https://v.douyin.com/xxxxx/ 或 https://www.douyin.com/video/xxxxx">
            </div>
        </div>

        <div class="field-row">
            <div class="field">
                <label>自定义文件名</label>
                <div class="input-wrap">
                    <i class="fas fa-file input-icon"></i>
                    <input type="text" id="singleFileName" class="with-icon" placeholder="single_work">
                </div>
            </div>
            <div class="field">
                <label>解析类型</label>
                <select id="singleParseType">
                    <option value="1">视频作品</option>
                    <option value="2">图文作品</option>
                </select>
            </div>
        </div>

        <div class="info-bar tip" style="margin-bottom:14px">
            <i class="fas fa-circle-info"></i>
            <span>作品链接会保存到当前用户文件空间，可在下载中心继续管理。</span>
        </div>

        <button class="btn btn-primary" id="singleParseBtn" type="button">
            <i class="fas fa-bolt"></i> 解析作品
        </button>

        <div class="loading-state" id="singleLoading">
            <div class="spinner"></div>
            <span>正在解析作品，请稍候...</span>
        </div>
    </div>

    <div class="result-card" id="singleResultContainer">
        <div class="result-header">
            <i class="fas fa-circle-check"></i>
            <span>解析成功</span>
            <span class="result-count" id="singleCount">0 个链接</span>
        </div>
        <div class="single-work-info" id="singleWorkInfo" style="display:none"></div>
        <div class="result-body">
            <div>
                <div class="result-filename" id="singleFileNameDisplay">single_work.txt</div>
                <div class="result-meta" id="singleMeta">作品链接已保存</div>
            </div>
            <div class="result-actions">
                <button class="btn" id="singleDownloadBtn" type="button">
                    <i class="fas fa-download"></i> 下载
                </button>
                <button class="btn" id="singleCopyBtn" type="button">
                    <i class="fas fa-copy"></i> 复制链接
                </button>
                <button class="btn" id="singleWatchBtn" type="button">
                    <i class="fas fa-play"></i> 观看
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-database"></i>
                解析记录
                <span class="badge" id="singleRecordsCount">0</span>
            </div>
            <div class="card-actions">
                <button class="btn btn-ghost" id="singleRefreshRecordsBtn" type="button">
                    <i class="fas fa-rotate"></i> 刷新
                </button>
            </div>
        </div>
        <div class="list-container" id="singleRecordsList">
            <div class="empty-state">
                <i class="fas fa-database"></i>
                <p>暂无解析记录</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-folder-open"></i>
                文件管理
                <span class="badge" id="singleFileCount">0</span>
            </div>
            <div class="card-actions">
                <button class="btn btn-ghost" id="singleRefreshFilesBtn" type="button">
                    <i class="fas fa-rotate"></i> 刷新
                </button>
            </div>
        </div>
        <div class="list-container" id="singleFileList">
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>暂无文件，解析链接后将显示在这里</p>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/single.js?v=2026070303"></script>
</body>
</html>
