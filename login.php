<?php
require_once __DIR__ . '/includes/auth.php';

function dyzy_safe_redirect_url($url) {
    $url = trim((string)$url);
    if ($url === '' || preg_match('#^(?:[a-z][a-z0-9+.-]*:)?//#i', $url) || strpos($url, "\r") !== false || strpos($url, "\n") !== false) {
        return './';
    }
    return $url;
}

$redirect = dyzy_safe_redirect_url($_GET['redirect'] ?? './');

if (dyzy_is_logged_in()) {
    header('Location: ' . $redirect);
    exit;
}

$initialized = dyzy_admin_initialized();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $initialized ? '后台登录' : '初始化后台' ?> - 抖音解析工具</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/vendor/fonts/local-fonts.css">
    <link rel="stylesheet" href="assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/settings.css?v=2026070309">
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
    <div class="wrap auth-wrap">
        <div class="site-header">
            <div class="logo-mark"><i class="fas fa-shield-halved"></i></div>
            <div>
                <h1><?= $initialized ? '后台登录' : '初始化后台管理员' ?></h1>
                <p><?= $initialized ? '登录后管理 Cookie、对象存储和下载任务' : '首次使用需要创建管理员账号' ?></p>
            </div>
        </div>

        <div class="alert alert-success" id="successAlert">
            <i class="fas fa-circle-check"></i>
            <span id="successMsg"></span>
        </div>
        <div class="alert alert-error" id="errorAlert">
            <i class="fas fa-circle-exclamation"></i>
            <span id="errorMsg"></span>
        </div>

        <div class="card">
            <div class="card-title">
                <i class="fas fa-user-lock"></i>
                <?= $initialized ? '请输入管理员账号' : '创建管理员账号' ?>
            </div>
            <div class="field auth-field">
                <label>管理员账号</label>
                <input type="text" id="username" autocomplete="username" placeholder="admin">
            </div>
            <div class="field auth-field">
                <label>管理员密码</label>
                <input type="password" id="password" autocomplete="current-password" placeholder="至少 8 位">
            </div>
            <?php if (!$initialized): ?>
            <div class="field auth-field">
                <label>确认密码</label>
                <input type="password" id="confirmPassword" autocomplete="new-password" placeholder="再次输入密码">
            </div>
            <?php endif; ?>
            <input type="hidden" id="authMode" value="<?= $initialized ? 'login' : 'init' ?>">
            <input type="hidden" id="redirectUrl" value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>">
            <div class="btn-row">
                <button class="btn btn-primary" id="authBtn">
                    <i class="fas fa-arrow-right-to-bracket"></i>
                    <?= $initialized ? '登录后台' : '创建并登录' ?>
                </button>
            </div>
        </div>

        <section class="footer">
            <strong>安全说明：</strong>后台账号密码仅保存在服务器本地 <code>data/admin.php</code>，密码使用哈希存储；登录状态通过 HttpOnly Cookie 会话维护，不会把敏感密钥暴露给浏览器脚本。
        </section>
    </div>

    <script src="assets/js/auth.js"></script>
</body>
</html>
