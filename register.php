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
    <title>用户注册 - 抖音解析工具</title>
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
            <div class="logo-mark"><i class="fas fa-user-plus"></i></div>
            <div>
                <h1>用户注册</h1>
                <p>注册后拥有独立解析记录、文件记录和个人 Cookie</p>
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
                <i class="fas fa-user-plus"></i>
                用户注册
            </div>
            <div class="field auth-field">
                <label>用户名</label>
                <input type="text" id="username" autocomplete="username" placeholder="3-32 位中文、英文、数字或下划线">
            </div>
            <div class="field auth-field">
                <label>密码</label>
                <input type="password" id="password" autocomplete="new-password" placeholder="至少 6 位">
            </div>
            <div class="field auth-field" id="emailField" style="display:none">
                <label>邮箱</label>
                <div class="captcha-row">
                    <input type="email" id="email" autocomplete="email" placeholder="请输入邮箱地址">
                    <button class="btn btn-outline" type="button" id="sendEmailCodeBtn" data-scene="register">
                        <i class="fas fa-paper-plane"></i> 发送验证码
                    </button>
                </div>
            </div>
            <div class="field auth-field captcha-field">
                <label id="captchaLabel">注册验证码</label>
                <div class="captcha-row">
                    <input type="text" id="captcha" inputmode="numeric" placeholder="输入验证码">
                    <button class="btn btn-outline" type="button" id="refreshCaptchaBtn">
                        <i class="fas fa-rotate"></i> <span id="captchaQuestion">加载中...</span>
                    </button>
                </div>
                <div class="hint" id="captchaHint">验证码用于防止批量注册，请按后台配置完成验证。</div>
            </div>
            <input type="hidden" id="redirectUrl" value="<?= htmlspecialchars($redirect, ENT_QUOTES, 'UTF-8') ?>">
            <div class="btn-row">
                <button class="btn btn-primary" id="registerBtn">
                    <i class="fas fa-user-plus"></i> 注册
                </button>
                <a class="btn btn-outline" href="user.php?redirect=<?= urlencode($redirect) ?>">
                    <i class="fas fa-arrow-right-to-bracket"></i> 去登录
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
