<?php
$rootDir = dirname(__DIR__);
$lockFile = __DIR__ . '/install.lock';
$configFile = $rootDir . '/config.php';
$isInstalled = file_exists($lockFile) && file_exists($configFile);
$checks = [
    ['label' => 'PHP 版本', 'value' => PHP_VERSION, 'ok' => version_compare(PHP_VERSION, '7.4.0', '>=')],
    ['label' => 'PDO MySQL', 'value' => extension_loaded('pdo_mysql') ? '已启用' : '未启用', 'ok' => extension_loaded('pdo_mysql')],
    ['label' => '安装目录可写', 'value' => is_writable(__DIR__) ? '可写' : '不可写', 'ok' => is_writable(__DIR__)],
    ['label' => '根目录可写', 'value' => is_writable($rootDir) ? '可写' : '不可写', 'ok' => is_writable($rootDir)],
    ['label' => '数据库脚本', 'value' => file_exists(__DIR__ . '/database.sql') ? '已找到' : '缺失', 'ok' => file_exists(__DIR__ . '/database.sql')],
];
$canInstall = !$isInstalled && count(array_filter($checks, fn($item) => !$item['ok'])) === 0;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系统安装 - 抖音解析工具</title>
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../assets/vendor/fonts/local-fonts.css">
    <link rel="stylesheet" href="../assets/vendor/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/install.css">
</head>
<body>
<div class="wrap">
    <section class="hero">
        <div class="panel hero-main">
            <div class="eyebrow"><i class="fas fa-wand-magic-sparkles"></i> 一键安装向导</div>
            <h1>抖音解析工具安装</h1>
            <p>配置数据库、导入数据表、生成配置文件和安装锁。安装完成后将自动回到首页，后续页面会使用统一的浅色卡片风格。</p>
        </div>
        <aside class="panel status-card">
            <div>
                <div class="status-pill <?= $isInstalled ? 'ok' : ($canInstall ? 'ok' : 'warn') ?>">
                    <i class="fas <?= $isInstalled ? 'fa-circle-check' : ($canInstall ? 'fa-circle-check' : 'fa-triangle-exclamation') ?>"></i>
                    <?= $isInstalled ? '系统已安装' : ($canInstall ? '环境可安装' : '环境需处理') ?>
                </div>
                <div class="notice">
                    <?= $isInstalled ? '检测到配置文件和安装锁。如需重装，请先删除 config.php 与 install/install.lock。' : '安装前请确认数据库账号有创建数据库和建表权限。' ?>
                </div>
            </div>
            <a class="btn btn-ghost" href="../index.php"><i class="fas fa-house"></i> 返回首页</a>
        </aside>
    </section>

    <section class="grid">
        <div class="panel card">
            <h2 class="card-title"><i class="fas fa-list-check"></i> 环境检查</h2>
            <div class="check-list">
                <?php foreach ($checks as $check): ?>
                    <div class="check-item">
                        <div>
                            <strong><?= htmlspecialchars($check['label']) ?></strong><br>
                            <span><?= htmlspecialchars($check['value']) ?></span>
                        </div>
                        <span class="badge <?= $check['ok'] ? 'ok' : 'err' ?>">
                            <i class="fas <?= $check['ok'] ? 'fa-check' : 'fa-xmark' ?>"></i>
                            <?= $check['ok'] ? '通过' : '异常' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="steps">
                <div class="step"><i class="fas fa-database"></i><span>自动创建数据库并导入 install/database.sql。</span></div>
                <div class="step"><i class="fas fa-file-code"></i><span>生成根目录 config.php，写入数据库连接信息。</span></div>
                <div class="step"><i class="fas fa-lock"></i><span>生成 install/install.lock，防止重复安装。</span></div>
            </div>
        </div>

        <div class="panel card">
            <h2 class="card-title"><i class="fas fa-server"></i> 数据库配置</h2>
            <form id="installForm">
                <div class="form-grid">
                    <div class="field">
                        <label for="db_host">数据库地址</label>
                        <input id="db_host" name="db_host" value="localhost" required>
                    </div>
                    <div class="field">
                        <label for="db_port">端口</label>
                        <input id="db_port" name="db_port" value="3306" inputmode="numeric">
                    </div>
                    <div class="field">
                        <label for="db_name">数据库名</label>
                        <input id="db_name" name="db_name" value="douyin_monitor" required>
                    </div>
                    <div class="field">
                        <label for="db_charset">字符集</label>
                        <select id="db_charset" name="db_charset">
                            <option value="utf8mb4">utf8mb4</option>
                            <option value="utf8">utf8</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="db_user">用户名</label>
                        <input id="db_user" name="db_user" value="root" required>
                    </div>
                    <div class="field">
                        <label for="db_pass">密码</label>
                        <input id="db_pass" name="db_pass" type="password" autocomplete="new-password">
                    </div>
                </div>
                <div class="actions">
                    <button class="btn btn-ghost" type="button" id="testBtn" <?= $isInstalled || !$canInstall ? 'disabled' : '' ?>><i class="fas fa-plug"></i> 测试连接</button>
                    <button class="btn btn-primary" type="submit" id="installBtn" <?= $isInstalled || !$canInstall ? 'disabled' : '' ?>><i class="fas fa-bolt"></i> 一键安装</button>
                </div>
                <div class="notice" id="messageBox">填写数据库信息后，先测试连接，再执行安装。</div>
            </form>
        </div>
    </section>
</div>
<script src="../assets/js/app-console.js"></script>
<script src="../assets/js/install.js"></script>
</body>
</html>
