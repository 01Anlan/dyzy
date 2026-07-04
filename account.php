<?php
require_once __DIR__ . '/includes/auth.php';
dyzy_require_user(false);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>账号设置 - 抖音解析工具</title>
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
    <div class="wrap account-wrap">
        <div class="site-header">
            <div class="logo-mark"><i class="fas fa-shield-halved"></i></div>
            <div>
                <h1>账号设置</h1>
                <p>管理账号邮箱绑定，用于验证码验证和忘记密码找回</p>
            </div>
        </div>

        <nav class="breadcrumb">
            <a href="./"><i class="fas fa-home"></i> 首页</a>
            <i class="fas fa-chevron-right"></i>
            <a href="parser.html">解析工具</a>
            <i class="fas fa-chevron-right"></i>
            <span>账号设置</span>
        </nav>

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
                <i class="fas fa-envelope-circle-check"></i>
                邮箱绑定
            </div>
            <div class="info-bar tip" style="margin-bottom:16px">
                <i class="fas fa-circle-info"></i>
                <span id="boundEmailStatus">正在读取邮箱绑定状态...</span>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>邮箱地址</label>
                    <input type="email" id="bindEmail" placeholder="请输入要绑定的邮箱">
                </div>
                <div class="field">
                    <label>邮箱验证码</label>
                    <input type="text" id="bindEmailCode" inputmode="numeric" placeholder="请输入邮箱验证码">
                </div>
            </div>
            <div class="account-actions">
                <button class="btn btn-outline" id="sendEmailCodeBtn" type="button">
                    <i class="fas fa-paper-plane"></i> 发送验证码
                </button>
                <button class="btn btn-primary" id="bindEmailBtn" type="button">
                    <i class="fas fa-link"></i> 绑定邮箱
                </button>
                <button class="btn" id="unbindEmailBtn" type="button">
                    <i class="fas fa-link-slash"></i> 解绑邮箱
                </button>
            </div>
        </div>

        <section class="footer">
            <strong>说明：</strong>绑定邮箱后可用于邮箱验证码注册、账号验证和忘记密码找回；解绑邮箱需要向当前绑定邮箱发送验证码。
        </section>
    </div>

    <script src="assets/js/account.js?v=2026070101"></script>
</body>
</html>
