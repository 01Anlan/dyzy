<?php
require_once __DIR__ . '/includes/auth.php';
dyzy_require_login(false);

if (!function_exists('sg_load')) {
    header('Location: system/source-guardian-loader-helper.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>后台管理 - 抖音解析工具</title>
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
    <div class="wrap">
        <div class="site-header">
            <div class="logo-mark"><i class="fas fa-gear"></i></div>
            <div>
                <h1>后台管理</h1>
                <p>统一配置对象存储、邮件通知、后台安全和用户状态</p>
            </div>
        </div>

        <nav class="breadcrumb">
            <a href="./"><i class="fas fa-home"></i> 首页</a>
            <i class="fas fa-chevron-right"></i>
            <a href="parser.html">解析工具</a>
            <i class="fas fa-chevron-right"></i>
            <span>全局设置</span>
        </nav>

        <nav class="section-nav" aria-label="后台配置快捷导航">
            <a href="#cookieSettings"><i class="fas fa-key"></i> Cookie</a>
            <a href="#storageSettings"><i class="fas fa-cloud-arrow-up"></i> 对象存储</a>
            <a href="#emailSettings"><i class="fas fa-envelope"></i> 邮件通知</a>
            <a href="#userManagement"><i class="fas fa-users"></i> 用户管理</a>
        </nav>

        <div class="alert alert-success" id="successAlert">
            <i class="fas fa-circle-check"></i>
            <span id="successMsg"></span>
        </div>
        <div class="alert alert-error" id="errorAlert">
            <i class="fas fa-circle-exclamation"></i>
            <span id="errorMsg"></span>
        </div>

        <div class="card" id="cookieSettings">
            <div class="card-title">
                <i class="fas fa-key"></i>
                抖音 Cookie 配置
            </div>
            <div class="field">
                <label>Cookie</label>
                <textarea id="globalCookie" placeholder="粘贴抖音登录 Cookie（包含 odin_tt 字段）..."></textarea>
                <div class="hint">
                    获取方式：浏览器无痕模式登录抖音 → F12 打开开发者工具 → Network → 刷新页面 → 找到 <code>feed</code> 开头的请求 → 复制完整 <code>Cookie</code> 请求头内容，确保包含 <code>odin_tt</code> 字段。<br>
                    普通用户解析优先使用个人 Cookie；此处仅作为后台全局备用配置。
                </div>
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" id="saveBtn">
                    <i class="fas fa-floppy-disk"></i> 保存
                </button>
                <button class="btn btn-outline" id="checkCookieBtn">
                    <i class="fas fa-hourglass-half"></i> 检测过期时间
                </button>
                <button class="btn btn-outline" id="clearBtn">
                    <i class="fas fa-trash-can"></i> 清空
                </button>
            </div>
        </div>

        <div class="card" id="storageSettings">
            <div class="card-title">
                <i class="fas fa-cloud-arrow-up"></i>
                对象存储配置
            </div>
            <div class="field-row">
                <div class="field">
                    <label>启用对象存储</label>
                    <select id="storageEnabled">
                        <option value="0">关闭</option>
                        <option value="1">开启</option>
                    </select>
                </div>
                <div class="field">
                    <label>服务商类型</label>
                    <select id="storageProvider">
                        <option value="s3">S3 兼容协议</option>
                        <option value="cos">腾讯云 COS（官方 SDK）</option>
                        <option value="tos">火山引擎 TOS（官方 SDK）</option>
                        <option value="qiniu">七牛云 Kodo（S3 兼容）</option>
                        <option value="oss">阿里云 OSS（官方 SDK）</option>
                    </select>
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>Endpoint</label>
                    <input id="storageEndpoint" placeholder="https://oss-cn-hangzhou.aliyuncs.com 或自定义域名">
                </div>
                <div class="field">
                    <label>Region</label>
                    <input id="storageRegion" placeholder="cn-hangzhou / ap-guangzhou / cn-beijing">
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>Bucket</label>
                    <input id="storageBucket" placeholder="bucket-name">
                </div>
                <div class="field">
                    <label>上传路径前缀</label>
                    <input id="storagePathPrefix" placeholder="dyzy/{date}/">
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>AccessKey</label>
                    <input id="storageAccessKey" autocomplete="off" placeholder="保存后仅显示脱敏内容">
                </div>
                <div class="field">
                    <label>SecretKey</label>
                    <input id="storageSecretKey" type="password" autocomplete="new-password" placeholder="留空则保留原密钥">
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>公开访问域名/CDN</label>
                    <input id="storagePublicBaseUrl" placeholder="https://cdn.example.com">
                </div>
                <div class="field">
                    <label>上传成功后删除本地文件</label>
                    <select id="storageDeleteLocal">
                        <option value="0">保留本地文件</option>
                        <option value="1">删除本地文件</option>
                    </select>
                </div>
            </div>
            <div class="field">
                <div class="hint">密钥只保存在服务器本地 <code>data/storage.php</code>，前端不会读取明文。开启后，下载中心选择“服务器下载”时会先保存到服务器，再自动上传到对象存储。</div>
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" id="saveStorageBtn">
                    <i class="fas fa-floppy-disk"></i> 保存存储配置
                </button>
                <button class="btn btn-outline" id="testStorageBtn">
                    <i class="fas fa-plug-circle-check"></i> 测试上传
                </button>
            </div>
        </div>

        <div class="card" id="emailSettings">
            <div class="card-title">
                <i class="fas fa-envelope"></i>
                邮件通知 SMTP 配置
            </div>
            <div class="field-row">
                <div class="field">
                    <label>SMTP 服务器</label>
                    <input type="text" id="smtpHost" placeholder="smtp.qq.com">
                </div>
                <div class="field">
                    <label>端口</label>
                    <input type="number" id="smtpPort" placeholder="465" value="465">
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>邮箱账号</label>
                    <input type="text" id="smtpUsername" placeholder="your@qq.com">
                </div>
                <div class="field">
                    <label>授权码 / 密码</label>
                    <input type="password" id="smtpPassword" autocomplete="new-password" placeholder="留空则保留原密钥">
                </div>
            </div>
            <div class="field-row">
                <div class="field">
                    <label>加密方式</label>
                    <select id="smtpEncryption">
                        <option value="ssl">SSL</option>
                        <option value="tls">TLS</option>
                        <option value="none">无加密</option>
                    </select>
                </div>
                <div class="field">
                    <label>发件人名称</label>
                    <input type="text" id="fromName" placeholder="抖音监控系统" value="抖音监控系统">
                </div>
            </div>
            <div class="field">
                <label>测试接收邮箱</label>
                <input type="email" id="testEmailAddress" placeholder="your@email.com">
                <div class="hint">前台主页解析只填写接收邮箱，SMTP 服务器、账号和授权码统一由后台配置。</div>
            </div>
            <div class="field">
                <label>注册验证码方式</label>
                <select id="registerCaptchaMode">
                    <option value="math">计算验证码</option>
                    <option value="email">邮箱验证码</option>
                </select>
                <div class="hint">选择邮箱验证码后，用户注册需要填写邮箱并接收验证码；忘记密码和邮箱绑定也会复用此 SMTP 配置。</div>
            </div>
            <div class="btn-row">
                <button class="btn btn-primary" id="saveEmailBtn">
                    <i class="fas fa-floppy-disk"></i> 保存邮件配置
                </button>
                <button class="btn btn-outline" id="testEmailBtn">
                    <i class="fas fa-paper-plane"></i> 测试发送
                </button>
            </div>
        </div>

        <div class="card" id="userManagement">
            <div class="card-title">
                <i class="fas fa-users"></i>
                用户管理
            </div>
            <div class="table-wrap">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>用户名</th>
                            <th>状态</th>
                            <th>注册时间</th>
                            <th>最后登录</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr><td colspan="6">加载中...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="status-bar" id="statusBar">
            <i class="fas fa-clock"></i>
            <span id="statusText">加载中...</span>
        </div>

        <div class="status-bar" id="cookieExpiryBar">
            <i class="fas fa-hourglass-half"></i>
            <span id="cookieExpiryText">尚未检测 Cookie 过期时间</span>
        </div>

        <div class="btn-row">
            <button class="btn btn-outline" id="logoutBtn">
                <i class="fas fa-right-from-bracket"></i> 退出后台登录
            </button>
        </div>

        <section class="footer">
            <strong>说明：</strong> 后台配置仅管理员可访问；普通用户的解析记录、文件记录和个人 Cookie 按用户账号隔离。邮件 SMTP 密钥只在后台保存，前台只填写接收邮箱。
        </section>
    </div>

    <script src="assets/js/app-console.js"></script>
    <script src="assets/js/settings.js"></script>
</body>
</html>
