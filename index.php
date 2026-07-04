<?php
$__app_copyright = 'Anlan Net';
$__app_version = 'v3';
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
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>抖音解析工具导航</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/vendor/fonts/local-fonts.css">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/index.css?v=2026070309">
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
    <script src="assets/js/app-console.js"></script>
</head>
<body>
    <div class="wrap">
        <div class="site-header">
            <div class="logo-mark">
                <i class="fab fa-tiktok"></i>
            </div>
            <div>
                <h1>抖音解析工具</h1>
                <p>抖音链接解析与账号内容导出服务平台</p>
            </div>
        </div>

        <section class="hero">
            <div class="badge"><i class="fas fa-sparkles"></i> 用户独立空间</div>
            <h1>登录用户中心后开始解析</h1>
            <p>普通用户登录后拥有独立的解析记录、文件记录和个人 Douyin Cookie；后台入口仅用于管理员配置对象存储、邮件通知和用户状态。</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="./user.php?redirect=parser.html"><i class="fas fa-user"></i> 登录 / 进入用户中心</a>
                <a class="btn btn-outline" href="./single.php"><i class="fas fa-bolt"></i> 单作品解析</a>
                <a class="btn btn-outline" href="./parser.html"><i class="fas fa-arrow-right"></i> 主页解析</a>
                <a class="btn btn-outline" href="./settings.php"><i class="fas fa-lock"></i> 后台管理</a>
            </div>
            <div class="grid compact-grid">
                <article class="card compact-card">
                    <div class="icon"><i class="fas fa-link"></i></div>
                    <h2>解析工具</h2>
                    <p>支持单作品、主页链接、喜欢作品、收藏作品解析，未登录访问会自动跳转用户登录。</p>
                    <div class="actions">
                        <a class="btn btn-primary" href="./single.php"><i class="fas fa-bolt"></i> 单作品</a>
                        <a class="btn btn-outline" href="./parser.html"><i class="fas fa-arrow-right"></i> 主页解析</a>
                    </div>
                </article>
                <article class="card compact-card">
                    <div class="icon"><i class="fas fa-folder-open"></i></div>
                    <h2>我的文件</h2>
                    <p>下载中心和观看页只展示当前用户自己的解析文件，避免不同用户数据混用。</p>
                    <div class="actions">
                        <a class="btn btn-primary" href="./downloads.php"><i class="fas fa-download"></i> 下载中心</a>
                        <a class="btn btn-outline" href="./watch.php"><i class="fas fa-play"></i> 观看</a>
                    </div>
                </article>
            </div>
        </section>
        <section class="footer">
            <strong>服务说明：</strong> 普通用户请从用户中心登录；后台管理仅面向管理员，用于配置邮件 SMTP、对象存储密钥和用户状态。
        </section>
    </div>
</body>
</html>
