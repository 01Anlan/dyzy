<?php
// install.php - 一键安装脚本
header('Content-Type: text/html; charset=utf-8');

// 检查安装锁
if (file_exists('install.lock')) {
    die('
    <!DOCTYPE html>
    <html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <title>系统已安装</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); text-align: center; }
            .warning { font-size: 48px; margin-bottom: 20px; }
            h1 { color: #dc3545; margin-bottom: 20px; }
            p { margin-bottom: 20px; color: #666; }
            a { color: #007bff; text-decoration: none; }
            a:hover { text-decoration: underline; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="warning">⚠️</div>
            <h1>系统已安装</h1>
            <p>抖音监控系统已经安装完成，如需重新安装请先删除 install.lock 文件。</p>
            <p><a href="../index.html">进入系统</a></p>
        </div>
    </body>
    </html>
    ');
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>抖音监控系统 - 一键安装</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Microsoft YaHei', Arial, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .header p { opacity: 0.9; }
        .content { padding: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input, select { width: 100%; padding: 12px 15px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 16px; transition: border-color 0.3s; }
        input:focus, select:focus { border-color: #4CAF50; outline: none; }
        .btn { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; border: none; padding: 15px 30px; border-radius: 8px; font-size: 16px; cursor: pointer; width: 100%; transition: transform 0.2s; }
        .btn:hover { transform: translateY(-2px); }
        .btn:active { transform: translateY(0); }
        .message { padding: 15px; border-radius: 8px; margin-top: 20px; display: none; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .step { display: none; }
        .step.active { display: block; }
        .progress { display: flex; margin-bottom: 30px; }
        .progress-step { flex: 1; text-align: center; padding: 10px; position: relative; }
        .progress-step:not(:last-child):after { content: ''; position: absolute; top: 20px; right: -50%; width: 100%; height: 2px; background: #e1e5e9; }
        .progress-step.active { color: #4CAF50; font-weight: bold; }
        .progress-step.active:after { background: #4CAF50; }
        .progress-number { width: 40px; height: 40px; border-radius: 50%; background: #e1e5e9; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 5px; }
        .progress-step.active .progress-number { background: #4CAF50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 抖音监控系统</h1>
            <p>一键安装向导</p>
        </div>
        
        <div class="content">
            <!-- 进度条 -->
            <div class="progress">
                <div class="progress-step active" id="step1-progress">
                    <div class="progress-number">1</div>
                    <div>数据库配置</div>
                </div>
                <div class="progress-step" id="step2-progress">
                    <div class="progress-number">2</div>
                    <div>安装数据库</div>
                </div>
                <div class="progress-step" id="step3-progress">
                    <div class="progress-number">3</div>
                    <div>完成安装</div>
                </div>
            </div>

            <!-- 步骤1: 数据库配置 -->
            <div class="step active" id="step1">
                <h2>数据库配置</h2>
                <p style="margin-bottom: 20px; color: #666;">请填写您的MySQL数据库信息</p>
                
                <form id="dbForm">
                    <div class="form-group">
                        <label for="db_host">数据库主机</label>
                        <input type="text" id="db_host" name="db_host" value="localhost" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_name">数据库名称</label>
                        <input type="text" id="db_name" name="db_name" value="douyin_monitor" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_user">用户名</label>
                        <input type="text" id="db_user" name="db_user" value="root" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="db_pass">密码</label>
                        <input type="password" id="db_pass" name="db_pass" placeholder="输入数据库密码">
                    </div>
                    
                    <div class="form-group">
                        <label for="db_charset">字符集</label>
                        <select id="db_charset" name="db_charset">
                            <option value="utf8mb4" selected>utf8mb4 (推荐)</option>
                            <option value="utf8">utf8</option>
                            <option value="gbk">gbk</option>
                        </select>
                    </div>
                    
                    <button type="button" class="btn" onclick="testDatabase()">测试连接并继续</button>
                </form>
                
                <div id="message1" class="message"></div>
            </div>

            <!-- 步骤2: 安装数据库 -->
            <div class="step" id="step2">
                <h2>安装数据库</h2>
                <p style="margin-bottom: 20px; color: #666;">正在创建数据库表和初始数据...</p>
                
                <div id="installProgress" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <div id="progressText">准备安装...</div>
                </div>
                
                <button type="button" class="btn" onclick="installDatabase()" id="installBtn">开始安装</button>
                <button type="button" class="btn" onclick="showStep(1)" style="background: #6c757d; margin-top: 10px;">上一步</button>
                
                <div id="message2" class="message"></div>
            </div>

            <!-- 步骤3: 完成安装 -->
            <div class="step" id="step3">
                <h2>安装完成！</h2>
                <div style="text-align: center; padding: 30px 0;">
                    <div style="font-size: 48px; margin-bottom: 20px;">🎉</div>
                    <p style="margin-bottom: 20px; font-size: 18px;">抖音监控系统安装成功！</p>
                </div>
                
                <div class="message success">
                    <strong>安装信息：</strong>
                    <div id="installSummary"></div>
                </div>
                
                <div style="margin-top: 30px;">
                    <a href="../index.html" class="btn" style="text-decoration: none; display: block; text-align: center;">进入系统</a>
                    <button type="button" class="btn" onclick="showStep(2)" style="background: #6c757d; margin-top: 10px;">重新安装</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let dbConfig = {};
        
        function showStep(step) {
            // 隐藏所有步骤
            document.querySelectorAll('.step').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.progress-step').forEach(s => s.classList.remove('active'));
            
            // 显示指定步骤
            document.getElementById('step' + step).classList.add('active');
            document.getElementById('step' + step + '-progress').classList.add('active');
        }
        
        function showMessage(step, message, type) {
            const messageEl = document.getElementById('message' + step);
            messageEl.textContent = message;
            messageEl.className = 'message ' + type;
            messageEl.style.display = 'block';
        }
        
        function hideMessage(step) {
            document.getElementById('message' + step).style.display = 'none';
        }
        
        async function testDatabase() {
            hideMessage(1);
            
            // 获取表单数据
            const formData = new FormData(document.getElementById('dbForm'));
            dbConfig = Object.fromEntries(formData);
            
            try {
                const response = await fetch('install_ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'test_connection',
                        ...dbConfig
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showMessage(1, '✅ 数据库连接成功！', 'success');
                    setTimeout(() => showStep(2), 1000);
                } else {
                    showMessage(1, '❌ ' + data.message, 'error');
                }
            } catch (error) {
                showMessage(1, '❌ 网络错误: ' + error.message, 'error');
            }
        }
        
        async function installDatabase() {
            hideMessage(2);
            const installBtn = document.getElementById('installBtn');
            const progressText = document.getElementById('progressText');
            
            installBtn.disabled = true;
            installBtn.textContent = '安装中...';
            
            try {
                progressText.textContent = '正在创建数据库表...';
                
                const response = await fetch('install_ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'install',
                        ...dbConfig
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    progressText.textContent = '✅ 安装完成！';
                    showMessage(2, '🎉 数据库安装成功！', 'success');
                    
                    // 显示安装摘要
                    document.getElementById('installSummary').innerHTML = `
                        <div>数据库: ${dbConfig.db_name}</div>
                        <div>创建表: ${data.tables_created} 个</div>
                        <div>安装时间: ${new Date().toLocaleString()}</div>
                    `;
                    
                    setTimeout(() => showStep(3), 1500);
                } else {
                    progressText.textContent = '安装失败';
                    showMessage(2, '❌ ' + data.message, 'error');
                }
            } catch (error) {
                progressText.textContent = '安装失败';
                showMessage(2, '❌ 网络错误: ' + error.message, 'error');
            } finally {
                installBtn.disabled = false;
                installBtn.textContent = '重新安装';
            }
        }
    </script>
</body>
</html>