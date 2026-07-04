<?php
// 定义中文语言包
$languages['zh-cn'] = [
    'title' => 'Source Guardian Loader 安装助手',
];

// 环境信息数组
$env = [];

// 操作系统信息
$env['os'] = [];
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $env['os']['name'] = "windows";
    $env['os']['raw_name'] = php_uname();
} else {
    $env['os']['name'] = "unix";
    $env['os']['raw_name'] = php_uname();
}

// PHP信息
$env['php'] = [];
$env['php']['version'] = phpversion();

$sapi_type = php_sapi_name();
if ("cli" == $sapi_type) {
    $env['php']['run_mode'] = "cli";
} else {
    $env['php']['run_mode'] = "web";
}

if (PHP_INT_SIZE == 4) {
    $env['php']['bit'] = 32;
} else {
    $env['php']['bit'] = 64;
}

// 获取线程安全信息
$thread_safe = false;
if (defined('PHP_ZTS') && PHP_ZTS) {
    $thread_safe = true;
}

$env['php']['sapi'] = $sapi_type;
$env['php']['ini_loaded_file'] = php_ini_loaded_file();
$env['php']['ini_scanned_files'] = php_ini_scanned_files();
$env['php']['loaded_extensions'] = get_loaded_extensions();
$env['php']['incompatible_extensions'] = ['xdebug', 'ionCube', 'zend_loader'];
$env['php']['loaded_incompatible_extensions'] = [];
$env['php']['extension_dir'] = ini_get('extension_dir');

// 检查加载的扩展中是否包含不兼容的扩展
if (is_array($env['php']['loaded_extensions'])) {
    foreach ($env['php']['loaded_extensions'] as $loaded_extension) {
        foreach ($env['php']['incompatible_extensions'] as $incompatible_extension) {
            if (strpos(strtolower($loaded_extension), strtolower($incompatible_extension)) !== false) {
                $env['php']['loaded_incompatible_extensions'][] = $loaded_extension;
            }
        }
    }
}
$env['php']['loaded_incompatible_extensions'] = array_unique($env['php']['loaded_incompatible_extensions']);

// 判断Source Guardian是否已安装 - 添加自动跳转逻辑
if (!function_exists('sg_load')) {
    $status = '<span class="badge badge-danger">未安装</span>';
    $status_installed = false;
} else {
    $status = '<span class="badge badge-success">已安装</span>';
    $status_installed = true;
    
    // 如果已安装且是通过Web访问，自动跳转到主页面
    if ('web' == $env['php']['run_mode'] && !isset($_GET['no_redirect'])) {
        header('Location: ../index.php');
        exit;
    }
}

// 初始化错误变量
$html_error = '';

// 下载地址数组
$download_url = [
    'unix'=>[
        'nosafety'=>[
            '7.4'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.7.4ts.lin',
                'filename' => 'ixed.7.4ts.lin'
            ],
            '8.0'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.0ts.lin',
                'filename' => 'ixed.8.0ts.lin'
            ],
            '8.1'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.1ts.lin',
                'filename' => 'ixed.8.1ts.lin'
            ],
            '8.2'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.2ts.lin',
                'filename' => 'ixed.8.2ts.lin'
            ],
            '8.3'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.3ts.lin',
                'filename' => 'ixed.8.3ts.lin'
            ],
            '8.4'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/unix/ixed.8.4ts.lin',
                'filename' => 'ixed.8.4ts.lin'
            ],
            '8.5'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/unix/ixed.8.5ts.lin',
                'filename' => 'ixed.8.5ts.lin'
            ],
        ],
        'safety'=>[
            '7.4'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.7.4.lin',
                'filename' => 'ixed.7.4.lin'
            ],
            '8.0'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.0.lin',
                'filename' => 'ixed.8.0.lin'
            ],
            '8.1'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.1.lin',
                'filename' => 'ixed.8.1.lin'
            ],
            '8.2'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.2.lin',
                'filename' => 'ixed.8.2.lin'
            ],
            '8.3'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.3.lin',
                'filename' => 'ixed.8.3.lin'
            ],
            '8.4'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.4.lin',
                'filename' => 'ixed.8.4.lin'
            ],
            '8.5'=> [
                'url' => 'https://download.zhcnli.cn/download.php?file=unix/ixed.8.5.lin',
                'filename' => 'ixed.8.5.lin'
            ],
        ]
    ],
];

// 解析PHP操作系统、版本和安全性
$_php_os = $env['os']['name'];
$_php_v = substr($env['php']['version'], 0, 3);
$_is_safety = $thread_safe ? 'safety' : 'nosafety';

// 检查是否支持当前系统和PHP版本
if (!isset($download_url[$_php_os])) {
    $html_error = '<div class="alert alert-danger">当前引导页仅支持 Linux 服务器，Windows 安装引导已删除。</div>';
    $the_os_downurl = '';
    $down_name = ['', 'ixed.lin'];
} elseif (!isset($download_url[$_php_os][$_is_safety][$_php_v])) {
    $html_error = '<div class="alert alert-danger">不支持当前 PHP 版本 ' . $_php_v . '，仅支持 PHP 7.4 ~ 8.5。</div>';
    $the_os_downurl = '';
    $down_name = ['', 'ixed.lin'];
} else {
    $download_info = $download_url[$_php_os][$_is_safety][$_php_v];
    $the_os_downurl = $download_info['url'];
    $down_name[1] = $download_info['filename'];
}

// 构建Web页面
if ('web' == $env['php']['run_mode']) {
    $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 4) : 'zh-c';
    if (preg_match("/zh-c/i", $language)) {
        $env['lang'] = "zh-cn";
        $wizard_lang = $env['lang'];
    } else {
        $env['lang'] = "zh-cn";
        $wizard_lang = "zh-cn";
    }
    
    $html = '';
    
    // 构建HTML头部 - 修复了Font Awesome链接
    $html_header = '<!doctype html>
    <html lang="zh-cn">
      <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/vendor/fontawesome/css/all.min.css" rel="stylesheet">
        <title>' . htmlspecialchars($languages[$wizard_lang]['title']) . '</title>
        <link rel="stylesheet" href="../assets/css/source-guardian.css">
      </head>
      <body>';
    
    // 构建HTML主体
    $html_body = '<div class="container main-container">';
    
    // Hero Section
    $html_body_nav = '<div class="hero-section">';
    $html_body_nav .= '<h1><i class="fas fa-shield-alt"></i> Source Guardian 安装向导</h1>';
    $html_body_nav .= '<p class="lead">支持 PHP 7.4 ~ 8.5 版本，推荐使用 PHP 8.1</p>';
    $html_body_nav .= '</div>';
    
    // 显示错误信息
    if (!empty($html_error)) {
        $html_body_nav .= $html_error;
    }
    
    // 安装成功提示
    if ($status_installed) {
        $html_body_nav .= '<div class="alert alert-success-custom alert-custom" role="alert">';
        $html_body_nav .= '<h5><i class="fas fa-check-circle"></i> 安装成功！</h5>';
        $html_body_nav .= '<p class="mb-0">Source Guardian Loader 已成功安装并加载。</p>';
        $html_body_nav .= '</div>';
    }
    
    // 构建环境信息部分
    $html_body_environment = '<div class="card">';
    $html_body_environment .= '<div class="card-header">';
    $html_body_environment .= '<h4><i class="fas fa-info-circle"></i> 当前环境信息</h4>';
    $html_body_environment .= '</div>';
    $html_body_environment .= '<div class="card-body">';
    
    $html_body_environment .= '<div class="info-row">';
    $html_body_environment .= '<div class="info-label"><i class="fas fa-desktop"></i>操作系统</div>';
    $html_body_environment .= '<div class="info-value">' . htmlspecialchars($env['os']['raw_name']) . '</div>';
    $html_body_environment .= '</div>';
    
    $html_body_environment .= '<div class="info-row">';
    $html_body_environment .= '<div class="info-label"><i class="fab fa-php"></i>PHP 版本</div>';
    $html_body_environment .= '<div class="info-value">' . htmlspecialchars($env['php']['version']) . '</div>';
    $html_body_environment .= '</div>';
    
    $html_body_environment .= '<div class="info-row">';
    $html_body_environment .= '<div class="info-label"><i class="fas fa-cog"></i>PHP 运行环境</div>';
    $html_body_environment .= '<div class="info-value">' . htmlspecialchars($env['php']['sapi']) . '</div>';
    $html_body_environment .= '</div>';
    
    $html_body_environment .= '<div class="info-row">';
    $html_body_environment .= '<div class="info-label"><i class="fas fa-file-code"></i>PHP 配置文件</div>';
    $html_body_environment .= '<div class="info-value">' . htmlspecialchars($env['php']['ini_loaded_file']) . '</div>';
    $html_body_environment .= '</div>';
    
    $html_body_environment .= '<div class="info-row">';
    $html_body_environment .= '<div class="info-label"><i class="fas fa-folder"></i>扩展安装目录</div>';
    $html_body_environment .= '<div class="info-value">' . htmlspecialchars($env['php']['extension_dir']) . '</div>';
    $html_body_environment .= '</div>';
    
    $html_body_environment .= '<div class="info-row">';
    $html_body_environment .= '<div class="info-label"><i class="fas fa-shield-alt"></i>Source Guardian</div>';
    $html_body_environment .= '<div class="info-value">' . $status . '</div>';
    $html_body_environment .= '</div>';
    
    $html_body_environment .= '</div></div>';
    
    // 构建Loader安装部分
    $html_body_loader = '';
    
    if (!$status_installed && empty($html_error)) {
        $html_body_loader .= '<div class="card">';
        $html_body_loader .= '<div class="card-header">';
        $html_body_loader .= '<h4><i class="fas fa-download"></i> 安装步骤</h4>';
        $html_body_loader .= '</div>';
        $html_body_loader .= '<div class="card-body">';
        
        // 宝塔面板提示
        $html_body_loader .= '<div class="bt-tip">';
        $html_body_loader .= '<strong><i class="fas fa-lightbulb"></i> 快捷安装提示</strong>';
        $html_body_loader .= '<p class="mb-0">如果您使用的是宝塔面板，可以直接在 <strong>宝塔软件商店</strong> 中搜索并安装 <strong>PHP SG16 扩展，这是最简单快捷的安装方式！</p>';
        $html_body_loader .= '</div>';
        
        $html_body_loader .= '<div class="alert alert-info-custom alert-custom" role="alert">';
        $html_body_loader .= '<i class="fas fa-info-circle"></i> 请按照以下步骤完成 Source Guardian Loader 的安装和配置';
        $html_body_loader .= '</div>';
        
        // 步骤1
        $html_body_loader .= '<div class="step-card">';
        $html_body_loader .= '<div class="d-flex align-items-start">';
        $html_body_loader .= '<span class="step-number">1</span>';
        $html_body_loader .= '<div class="flex-grow-1">';
        $html_body_loader .= '<div class="step-title">下载扩展文件</div>';
        $html_body_loader .= '<p class="mb-2">下载适用于您当前环境的 Source Guardian Loader 扩展文件</p>';
        if (!empty($the_os_downurl)) {
            $html_body_loader .= '<a class="btn btn-primary-custom btn-custom" target="_blank" href="' . htmlspecialchars($the_os_downurl) . '">';
            $html_body_loader .= '<i class="fas fa-download"></i> 下载 ' . htmlspecialchars($_php_os) . ' PHP' . htmlspecialchars($_php_v) . ' 扩展</a>';
        }
        $html_body_loader .= '</div></div></div>';
        
        // 步骤2
        $html_body_loader .= '<div class="step-card">';
        $html_body_loader .= '<div class="d-flex align-items-start">';
        $html_body_loader .= '<span class="step-number">2</span>';
        $html_body_loader .= '<div class="flex-grow-1">';
        $html_body_loader .= '<div class="step-title">上传扩展文件</div>';
        $html_body_loader .= '<p class="mb-2">将下载的扩展文件（<code>' . htmlspecialchars($down_name[1]) . '</code>）上传到 PHP 扩展目录：</p>';
        $html_body_loader .= '<div class="code-block">' . htmlspecialchars($env['php']['extension_dir']) . '</div>';
        $html_body_loader .= '</div></div></div>';
        
        // 步骤3
        $html_body_loader .= '<div class="step-card">';
        $html_body_loader .= '<div class="d-flex align-items-start">';
        $html_body_loader .= '<span class="step-number">3</span>';
        $html_body_loader .= '<div class="flex-grow-1">';
        $html_body_loader .= '<div class="step-title">修改 PHP 配置</div>';
        $html_body_loader .= '<p class="mb-2">编辑 PHP 配置文件：<code>' . htmlspecialchars($env['php']['ini_loaded_file']) . '</code></p>';
        $html_body_loader .= '<p class="mb-2">在文件底部添加以下配置：</p>';
        $html_body_loader .= '<div class="code-block">extension=' . htmlspecialchars($down_name[1]) . '</div>';
        $html_body_loader .= '<small class="text-muted"><i class="fas fa-exclamation-triangle"></i> 注意：扩展名称必须与上传的文件名一致</small>';
        $html_body_loader .= '</div></div></div>';
        
        // 步骤4
        $html_body_loader .= '<div class="step-card">';
        $html_body_loader .= '<div class="d-flex align-items-start">';
        $html_body_loader .= '<span class="step-number">4</span>';
        $html_body_loader .= '<div class="flex-grow-1">';
        $html_body_loader .= '<div class="step-title">重启服务</div>';
        $html_body_loader .= '<p class="mb-0">重启 PHP 服务或 Web 服务器使配置生效</p>';
        $html_body_loader .= '</div></div></div>';
        
        // 步骤5
        $html_body_loader .= '<div class="step-card">';
        $html_body_loader .= '<div class="d-flex align-items-start">';
        $html_body_loader .= '<span class="step-number">5</span>';
        $html_body_loader .= '<div class="flex-grow-1">';
        $html_body_loader .= '<div class="step-title">验证安装</div>';
        $html_body_loader .= '<p class="mb-3">重启服务后，点击下方按钮刷新页面验证安装结果</p>';
        $html_body_loader .= '<a class="btn btn-success-custom btn-custom" href="javascript:location.reload()">';
        $html_body_loader .= '<i class="fas fa-sync-alt"></i> 刷新页面</a>';
        $html_body_loader .= '</div></div></div>';
        
        $html_body_loader .= '</div></div>';
    }

    $html_body .= $html_body_nav . $html_body_environment . $html_body_loader;
    $html_body .= '</div>';
    
    // 构建HTML尾部
    $html_footer = '
        <script src="../assets/vendor/jquery/jquery.min.js"></script>
        <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        </body>
    </html>';
    
    $html = $html_header . $html_body . $html_footer;
    
    echo $html;
}