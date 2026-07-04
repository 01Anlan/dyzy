<?php
require_once __DIR__ . '/includes/auth.php';

if (dyzy_current_user()) {
    $redirect = $_GET['redirect'] ?? 'parser.html';
    header('Location: ' . $redirect);
    exit;
}

$redirect = $_GET['redirect'] ?? 'parser.html';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户登录 - 抖音解析工具</title>
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
            <div class="logo-mark"><i class="fas fa-user"></i></div>
            <div>
                <h1>用户中心</h1>
                <p>登录后拥有独立解析记录、文件记录和个人 Cookie</p>
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
                <i class="fas fa-right-to-bracket"></i>
                用户登录
            </div>
            <div class="field auth-field">
                <label>用户名</label>
                <input type="text" id="username" autocomplete="username" placeholder="请输入用户名">
            </div>
            <div class="field auth-field">
                <label>密码</label>
                <input type="password" id="password" autocomplete="current-password" placeholder="请输入密码">
            </div>
            <input type="hidden" id="redirectUrl" value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>">
            <div class="btn-row">
                <button class="btn btn-primary" id="loginBtn">
                    <i class="fas fa-arrow-right-to-bracket"></i> 登录
                </button>
                <a class="btn btn-outline" href="register.php?redirect=<?= urlencode($redirect) ?>">
                    <i class="fas fa-user-plus"></i> 去注册
                </a>
                <a class="btn btn-outline" href="forgot.php?redirect=<?= urlencode($redirect) ?>">
                    <i class="fas fa-key"></i> 忘记密码
                </a>
            </div>
        </div>

        <section class="footer">
            <strong>说明：</strong>普通用户账号用于隔离个人解析记录、文件记录和个人 Cookie；后台管理员仍只用于全局配置和对象存储密钥管理。
        </section>
    </div>

    <script src="assets/js/user.js"></script>
</body>
</html>
