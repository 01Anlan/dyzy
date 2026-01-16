<?php
// 检测 SG16 扩展是否安装
if (!function_exists('sg_load')) {
    // 未安装，跳转到扩展下载页面
    header('Location: source-guardian-loader-helper.php');
    exit;
}

// 检测是否已安装系统
$installLockFile = __DIR__ . '/install/install.lock';
$installDir = __DIR__ . '/install';

// 检查安装目录是否存在且包含安装文件
if (is_dir($installDir) && file_exists($installDir . '/install.php')) {
    // 检查是否已完成安装
    if (!file_exists($installLockFile)) {
        // 未安装，跳转到安装页面
        header('Location: install/install.php');
        exit;
    }
} else {
    // 安装目录不存在，可能是生产环境，直接显示主页面
    readfile('index.html');
    exit;
}

// 已安装且SG16扩展已安装，显示主页面
readfile('index.html');
?>