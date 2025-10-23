document.addEventListener('DOMContentLoaded', function() {
    // 获取DOM元素
    const parseBtn = document.getElementById('parseBtn');
    const douyinUrlInput = document.getElementById('douyinUrl');
    const fileNameInput = document.getElementById('fileName');
    const parseTypeSelect = document.getElementById('parseType');
    const loading = document.getElementById('loading');
    const resultContainer = document.getElementById('resultContainer');
    const videoCount = document.getElementById('videoCount');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const linkCount = document.getElementById('linkCount');
    const downloadBtn = document.getElementById('downloadBtn');
    const copyBtn = document.getElementById('copyBtn');
    const errorAlert = document.getElementById('errorAlert');
    const successAlert = document.getElementById('successAlert');
    const refreshBtn = document.getElementById('refreshBtn');
    const fileList = document.getElementById('fileList');
    const fileCount = document.getElementById('fileCount');
    const cleanupBtn = document.getElementById('cleanupBtn');
    const cleanupHours = document.getElementById('cleanupHours');
    
    // 弹窗相关元素
    const helpModal = document.getElementById('helpModal');
    const helpTrigger = document.getElementById('helpTrigger');
    const showHelp = document.getElementById('showHelp');
    const closeModal = document.getElementById('closeModal');
    const understandBtn = document.getElementById('understandBtn');
    
    // 预览弹窗相关元素
    const previewModal = document.getElementById('previewModal');
    const closePreviewModal = document.getElementById('closePreviewModal');
    const closePreviewBtn = document.getElementById('closePreviewBtn');
    const filePreviewContent = document.getElementById('filePreviewContent');
    const previewFileName = document.getElementById('previewFileName');
    
    // 自动更新相关元素
    const autoUpdateHeader = document.getElementById('autoUpdateHeader');
    const autoUpdateToggle = document.getElementById('autoUpdateToggle');
    const autoUpdateControls = document.getElementById('autoUpdateControls');
    const autoUpdateUrl = document.getElementById('autoUpdateUrl');
    const updateInterval = document.getElementById('updateInterval');
    const customInterval = document.getElementById('customInterval');
    const customIntervalValue = document.getElementById('customIntervalValue');
    const customIntervalUnit = document.getElementById('customIntervalUnit');
    const updateFileName = document.getElementById('updateFileName');
    const updateType = document.getElementById('updateType');
    const startAutoUpdate = document.getElementById('startAutoUpdate');
    const stopAutoUpdate = document.getElementById('stopAutoUpdate');
    const checkNow = document.getElementById('checkNow');
    const autoUpdateStatus = document.getElementById('autoUpdateStatus');
    const statusInfo = document.getElementById('statusInfo');
    const historyList = document.getElementById('historyList');
    
    // 邮件通知相关元素
    const emailNotification = document.getElementById('emailNotification');
    const emailSettings = document.getElementById('emailSettings');
    const emailAddress = document.getElementById('emailAddress');
    const emailCondition = document.getElementById('emailCondition');
    
    // SMTP配置相关元素
    const testEmailBtn = document.getElementById('testEmailBtn');
    const saveSmtpBtn = document.getElementById('saveSmtpBtn');
    const smtpHost = document.getElementById('smtpHost');
    const smtpPort = document.getElementById('smtpPort');
    const smtpUsername = document.getElementById('smtpUsername');
    const smtpPassword = document.getElementById('smtpPassword');
    const smtpEncryption = document.getElementById('smtpEncryption');
    const fromName = document.getElementById('fromName');

    let currentDownloadUrl = '';
    let parseAttempts = 0;
    let currentPreviewFile = '';
    let autoUpdateInterval = null;
    let isAutoUpdating = false;
    let updateCount = 0;

    // 显示错误消息
    function showError(message) {
        parseAttempts++;
        errorAlert.textContent = message;
        errorAlert.style.display = 'block';
        successAlert.style.display = 'none';
        
        if (parseAttempts >= 3) {
            setTimeout(() => {
                if (!errorAlert.textContent.includes('更换链接')) {
                    errorAlert.textContent += ' 如果多次失败，请尝试更换抖音主页链接。';
                }
            }, 1000);
        }
        
        setTimeout(() => {
            errorAlert.style.display = 'none';
        }, 5000);
    }
    
    // 显示成功消息
    function showSuccess(message) {
        parseAttempts = 0;
        successAlert.textContent = message;
        successAlert.style.display = 'block';
        errorAlert.style.display = 'none';
        setTimeout(() => {
            successAlert.style.display = 'none';
        }, 5000);
    }
    
    // 弹窗控制函数
    function openModal() {
        helpModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function closeModalFunc() {
        helpModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    function openPreviewModal() {
        previewModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
    
    function closePreviewModalFunc() {
        previewModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // 弹窗事件监听
    helpTrigger.addEventListener('click', openModal);
    showHelp.addEventListener('click', openModal);
    closeModal.addEventListener('click', closeModalFunc);
    understandBtn.addEventListener('click', closeModalFunc);
    closePreviewModal.addEventListener('click', closePreviewModalFunc);
    closePreviewBtn.addEventListener('click', closePreviewModalFunc);
    
    // 点击模态框外部关闭
    helpModal.addEventListener('click', function(event) {
        if (event.target === helpModal) {
            closeModalFunc();
        }
    });
    
    previewModal.addEventListener('click', function(event) {
        if (event.target === previewModal) {
            closePreviewModalFunc();
        }
    });
    
    // 按ESC键关闭模态框
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (helpModal.style.display === 'block') closeModalFunc();
            if (previewModal.style.display === 'block') closePreviewModalFunc();
        }
    });

    // 加载文件列表
    function loadFileList() {
        fetch('file_manager.php?action=list&t=' + Date.now())
            .then(response => {
                if (!response.ok) {
                    throw new Error('网络响应不正常');
                }
                return response.json();
            })
            .then(data => {
                if (data.code === 1) {
                    if (data.data && data.data.length > 0) {
                        renderFileList(data.data);
                        fileCount.textContent = data.data.length;
                    } else {
                        fileList.innerHTML = `
                            <div class="empty-state">
                                <i class="fas fa-folder-open"></i>
                                <p>暂无文件，解析链接后文件将显示在这里</p>
                            </div>
                        `;
                        fileCount.textContent = '0';
                    }
                } else {
                    showError('加载文件列表失败: ' + data.msg);
                }
            })
            .catch(error => {
                console.error('加载文件列表失败:', error);
                showError('加载文件列表失败: ' + error.message);
            });
    }
    
    // 渲染文件列表
    function renderFileList(files) {
        if (!files || files.length === 0) {
            fileList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>暂无文件，解析链接后文件将显示在这里</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        files.forEach(file => {
            html += `
                <div class="file-item" data-file="${file.name}">
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-meta">${file.size} | ${file.time}</div>
                    </div>
                    <div class="file-actions">
                        <button class="btn btn-preview btn-small" onclick="previewFile('${file.name}')">
                            <i class="fas fa-eye"></i> 预览
                        </button>
                        <button class="btn btn-download btn-small" onclick="downloadFile('${file.name}')">
                            <i class="fas fa-download"></i> 下载
                        </button>
                        <button class="btn btn-copy btn-small" onclick="copyFileUrl('${file.download_url}')">
                            <i class="fas fa-copy"></i> 复制
                        </button>
                        <button class="btn btn-danger btn-small" onclick="deleteFile('${file.name}')">
                            <i class="fas fa-trash"></i> 删除
                        </button>
                    </div>
                </div>
            `;
        });
        fileList.innerHTML = html;
    }
    
    // 预览文件
    window.previewFile = function(fileName) {
        currentPreviewFile = fileName;
        previewFileName.textContent = `文件: ${fileName}`;
        filePreviewContent.textContent = '正在加载文件内容...';
        
        openPreviewModal();
        
        // 获取文件内容
        fetch(`file_preview.php?file=${encodeURIComponent(fileName)}&t=${Date.now()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('文件加载失败');
                }
                return response.text();
            })
            .then(content => {
                filePreviewContent.textContent = content;
            })
            .catch(error => {
                filePreviewContent.textContent = '文件加载失败: ' + error.message;
            });
    };
    
    // 下载预览中的文件
    window.downloadPreviewFile = function() {
        if (currentPreviewFile) {
            downloadFile(currentPreviewFile);
        }
    };
    
    // 下载文件
    window.downloadFile = function(fileName) {
        window.location.href = `file_manager.php?action=download&file=${encodeURIComponent(fileName)}`;
    };
    
    // 复制文件链接
    window.copyFileUrl = function(url) {
        navigator.clipboard.writeText(url)
            .then(() => {
                showSuccess('文件链接已复制到剪贴板');
            })
            .catch(err => {
                showError('复制失败: ' + err);
            });
    };
    
    // 删除文件
    window.deleteFile = function(fileName) {
        if (confirm(`确定要删除文件 "${fileName}" 吗？此操作不可恢复！`)) {
            fetch(`file_manager.php?action=delete&file=${encodeURIComponent(fileName)}&t=${Date.now()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.code === 1) {
                        showSuccess(data.msg);
                        const fileItem = document.querySelector(`[data-file="${fileName}"]`);
                        if (fileItem) {
                            fileItem.remove();
                        }
                        setTimeout(loadFileList, 500);
                    } else {
                        showError('删除失败: ' + data.msg);
                    }
                })
                .catch(error => {
                    showError('删除文件失败: ' + error.message);
                });
        }
    };
    
    // 自动清理文件
    function cleanupFiles() {
        const hours = cleanupHours.value;
        if (confirm(`确定要清理 ${hours} 小时前创建的所有文件吗？此操作不可恢复！`)) {
            fetch(`file_manager.php?action=cleanup&hours=${hours}&t=${Date.now()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.code === 1) {
                        showSuccess(`自动清理完成，删除了 ${data.data.deleted_count} 个文件`);
                        loadFileList();
                    } else {
                        showError('自动清理失败: ' + data.msg);
                    }
                })
                .catch(error => {
                    showError('自动清理失败: ' + error.message);
                });
        }
    }
    
    // 解析抖音链接
    parseBtn.addEventListener('click', function() {
        const douyinUrl = douyinUrlInput.value.trim();
        const fileName = fileNameInput.value.trim() || 'video_links';
        const parseType = parseTypeSelect.value;
        
        if (!douyinUrl) {
            showError('请输入抖音主页链接');
            return;
        }
        
        if (!douyinUrl.includes('douyin.com')) {
            showError('请输入有效的抖音链接');
            return;
        }
        
        loading.style.display = 'block';
        resultContainer.style.display = 'none';
        
        const requestUrl = `Douyin.php?url=${encodeURIComponent(douyinUrl)}&filename=${encodeURIComponent(fileName)}&type=${parseType}&t=${Date.now()}`;
        
        fetch(requestUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('网络请求失败: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                loading.style.display = 'none';
                
                if (data.code === 1) {
                    videoCount.textContent = data.data.video_count;
                    linkCount.textContent = data.data.video_count;
                    fileNameDisplay.textContent = data.data.file_name;
                    currentDownloadUrl = data.data.download_url;
                    
                    resultContainer.style.display = 'block';
                    showSuccess(data.msg);
                    
                    setTimeout(loadFileList, 1000);
                } else {
                    showError(data.msg);
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                showError('解析失败: ' + error.message);
            });
    });
    
    // 下载当前解析的文件
    downloadBtn.addEventListener('click', function() {
        if (currentDownloadUrl) {
            window.location.href = currentDownloadUrl;
        } else {
            showError('没有可下载的文件');
        }
    });
    
    // 复制当前解析的链接
    copyBtn.addEventListener('click', function() {
        if (currentDownloadUrl) {
            navigator.clipboard.writeText(currentDownloadUrl)
                .then(() => {
                    showSuccess('下载链接已复制到剪贴板');
                })
                .catch(err => {
                    showError('复制失败: ' + err);
                });
        } else {
            showError('没有可复制的链接');
        }
    });
    
    // 刷新文件列表
    refreshBtn.addEventListener('click', function() {
        loadFileList();
        showSuccess('文件列表已刷新');
    });
    
    // 自动清理文件
    cleanupBtn.addEventListener('click', cleanupFiles);
    
    // 粘贴功能
    douyinUrlInput.addEventListener('click', function() {
        navigator.clipboard.readText()
            .then(text => {
                if (text.includes('douyin.com')) {
                    douyinUrlInput.value = text;
                    showSuccess('已自动粘贴剪贴板中的抖音链接');
                }
            })
            .catch(err => {
                // 忽略错误
            });
    });

    // 输入框回车键支持
    douyinUrlInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            parseBtn.click();
        }
    });

    fileNameInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            parseBtn.click();
        }
    });

    // ==================== 自动更新功能 ====================
    
    // 折叠/展开自动更新设置
    autoUpdateHeader.addEventListener('click', function() {
        autoUpdateControls.classList.toggle('show');
        autoUpdateToggle.classList.toggle('rotated');
    });
    
    // 自定义时间间隔显示/隐藏
    updateInterval.addEventListener('change', function() {
        if (this.value === 'custom') {
            customInterval.style.display = 'flex';
        } else {
            customInterval.style.display = 'none';
        }
    });
    
    // 邮件通知开关
    emailNotification.addEventListener('change', function() {
        if (this.checked) {
            emailSettings.style.display = 'grid';
        } else {
            emailSettings.style.display = 'none';
        }
    });
    
    // 获取实际更新间隔（分钟）
    function getActualInterval() {
        if (updateInterval.value === 'custom') {
            const value = parseInt(customIntervalValue.value) || 60;
            const unit = customIntervalUnit.value;
            
            switch (unit) {
                case 'minutes': return value;
                case 'hours': return value * 60;
                case 'days': return value * 24 * 60;
                default: return 60;
            }
        } else {
            return parseInt(updateInterval.value) || 60;
        }
    }
    
    // 测试邮件发送
    testEmailBtn.addEventListener('click', function() {
        const emailAddr = emailAddress.value.trim();
        const smtpHostVal = smtpHost.value.trim();
        const smtpPortVal = smtpPort.value.trim();
        const smtpUsernameVal = smtpUsername.value.trim();
        const smtpPasswordVal = smtpPassword.value.trim();
        
        if (!emailAddr) {
            showError('请输入接收邮箱地址');
            return;
        }
        
        if (!smtpHostVal || !smtpPortVal || !smtpUsernameVal || !smtpPasswordVal) {
            showError('请填写完整的SMTP配置信息');
            return;
        }
        
        const params = new URLSearchParams({
            action: 'test_email',
            email_address: emailAddr,
            smtp_host: smtpHostVal,
            smtp_port: smtpPortVal,
            smtp_username: smtpUsernameVal,
            smtp_password: smtpPasswordVal,
            smtp_encryption: smtpEncryption.value,
            from_name: fromName.value || '抖音监控系统'
        });
        
        testEmailBtn.disabled = true;
        testEmailBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 发送中...';
        
        fetch(`auto_update.php?${params.toString()}&t=${Date.now()}`)
            .then(response => response.json())
            .then(data => {
                testEmailBtn.disabled = false;
                testEmailBtn.innerHTML = '<i class="fas fa-paper-plane"></i> 测试邮件发送';
                
                if (data.code === 1) {
                    showSuccess('测试邮件发送成功！请检查您的邮箱。');
                } else {
                    showError('测试邮件发送失败: ' + data.msg);
                }
            })
            .catch(error => {
                testEmailBtn.disabled = false;
                testEmailBtn.innerHTML = '<i class="fas fa-paper-plane"></i> 测试邮件发送';
                showError('测试邮件发送失败: ' + error.message);
            });
    });
    
    // 保存SMTP配置
    saveSmtpBtn.addEventListener('click', function() {
        const smtpHostVal = smtpHost.value.trim();
        const smtpPortVal = smtpPort.value.trim();
        const smtpUsernameVal = smtpUsername.value.trim();
        const smtpPasswordVal = smtpPassword.value.trim();
        
        if (!smtpHostVal || !smtpPortVal || !smtpUsernameVal || !smtpPasswordVal) {
            showError('请填写完整的SMTP配置信息');
            return;
        }
        
        const params = new URLSearchParams({
            action: 'save_smtp',
            smtp_host: smtpHostVal,
            smtp_port: smtpPortVal,
            smtp_username: smtpUsernameVal,
            smtp_password: smtpPasswordVal,
            smtp_encryption: smtpEncryption.value,
            from_name: fromName.value || '抖音监控系统'
        });
        
        saveSmtpBtn.disabled = true;
        saveSmtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 保存中...';
        
        fetch(`auto_update.php?${params.toString()}&t=${Date.now()}`)
            .then(response => response.json())
            .then(data => {
                saveSmtpBtn.disabled = false;
                saveSmtpBtn.innerHTML = '<i class="fas fa-save"></i> 保存SMTP配置';
                
                if (data.code === 1) {
                    showSuccess('SMTP配置保存成功！');
                } else {
                    showError('SMTP配置保存失败: ' + data.msg);
                }
            })
            .catch(error => {
                saveSmtpBtn.disabled = false;
                saveSmtpBtn.innerHTML = '<i class="fas fa-save"></i> 保存SMTP配置';
                showError('SMTP配置保存失败: ' + error.message);
            });
    });
    
    // 加载自动更新状态
    function loadAutoUpdateStatus() {
        fetch('auto_update.php?action=status&t=' + Date.now())
            .then(response => response.json())
            .then(data => {
                if (data.code === 1 && data.data) {
                    const status = data.data;
                    if (status.is_running) {
                        startAutoUpdate.disabled = true;
                        stopAutoUpdate.disabled = false;
                        isAutoUpdating = true;
                        
                        // 更新状态显示
                        autoUpdateStatus.className = 'auto-update-status running';
                        autoUpdateStatus.querySelector('.status-indicator').className = 'status-indicator running';
                        autoUpdateStatus.querySelector('.status-indicator span').textContent = '自动更新运行中';
                        
                        statusInfo.textContent = `上次检查: ${status.last_check || '从未'} | 下次检查: ${status.next_check || '-'} | 更新次数: ${status.update_count || 0}`;
                        
                        // 设置定时器
                        if (autoUpdateInterval) {
                            clearInterval(autoUpdateInterval);
                        }
                        const intervalMinutes = getActualInterval();
                        autoUpdateInterval = setInterval(checkForUpdates, intervalMinutes * 60 * 1000);
                    }
                    
                    // 加载邮件设置
                    if (status.email_notification) {
                        emailNotification.checked = true;
                        emailSettings.style.display = 'grid';
                        emailAddress.value = status.email_address || '';
                        emailCondition.value = status.email_condition || 'new_content';
                    }
                    
                    // 加载SMTP配置
                    if (status.smtp_config) {
                        smtpHost.value = status.smtp_config.host || '';
                        smtpPort.value = status.smtp_config.port || '465';
                        smtpUsername.value = status.smtp_config.username || '';
                        smtpPassword.value = status.smtp_config.password || '';
                        smtpEncryption.value = status.smtp_config.encryption || 'ssl';
                        fromName.value = status.smtp_config.from_name || '抖音监控系统';
                    }
                    
                    // 加载历史记录
                    loadUpdateHistory();
                }
            })
            .catch(error => {
                console.error('加载自动更新状态失败:', error);
            });
    }
    
    // 加载更新历史
    function loadUpdateHistory() {
        fetch('auto_update.php?action=history&t=' + Date.now())
            .then(response => response.json())
            .then(data => {
                if (data.code === 1 && data.data) {
                    renderUpdateHistory(data.data);
                }
            })
            .catch(error => {
                console.error('加载更新历史失败:', error);
            });
    }
    
    // 渲染更新历史
    function renderUpdateHistory(history) {
        if (!history || history.length === 0) {
            historyList.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-clock"></i>
                    <p>暂无更新记录</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        history.forEach(record => {
            const statusClass = record.status === 'success' ? 'success' : 'error';
            const statusIcon = record.status === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            html += `
                <div class="history-item ${statusClass}">
                    <div class="history-time">
                        <i class="fas fa-clock"></i> ${record.time}
                    </div>
                    <div class="history-details">
                        <i class="fas ${statusIcon}"></i>
                        ${record.message}
                    </div>
                    <div class="history-count">
                        ${record.new_count ? `新增: ${record.new_count}` : ''}
                    </div>
                </div>
            `;
        });
        historyList.innerHTML = html;
    }
    
    // 检查更新
    function checkForUpdates() {
        const url = autoUpdateUrl.value.trim();
        const fileName = updateFileName.value.trim() || 'auto_update';
        const type = updateType.value;
        const emailEnabled = emailNotification.checked;
        const emailAddr = emailAddress.value.trim();
        const emailCond = emailCondition.value;
        
        if (!url) {
            showError('请先设置要监控的抖音主页链接');
            return;
        }
        
        const params = new URLSearchParams({
            action: 'check',
            url: url,
            filename: fileName,
            type: type,
            email_enabled: emailEnabled ? '1' : '0',
            email_address: emailAddr,
            email_condition: emailCond,
            smtp_host: smtpHost.value.trim(),
            smtp_port: smtpPort.value.trim(),
            smtp_username: smtpUsername.value.trim(),
            smtp_password: smtpPassword.value.trim(),
            smtp_encryption: smtpEncryption.value,
            from_name: fromName.value || '抖音监控系统'
        });
        
        fetch(`auto_update.php?${params.toString()}&t=${Date.now()}`)
            .then(response => response.json())
            .then(data => {
                if (data.code === 1) {
                    updateCount++;
                    const message = data.data.new_count > 0 ? 
                        `发现 ${data.data.new_count} 个新内容，文件已更新` : 
                        '内容无变化';
                    
                    // 添加历史记录
                    addHistoryRecord({
                        time: new Date().toLocaleString(),
                        status: 'success',
                        message: message,
                        new_count: data.data.new_count
                    });
                    
                    showSuccess(`自动更新检查完成: ${message}`);
                    
                    // 刷新文件列表
                    loadFileList();
                } else {
                    addHistoryRecord({
                        time: new Date().toLocaleString(),
                        status: 'error',
                        message: data.msg
                    });
                    
                    showError(`自动更新检查失败: ${data.msg}`);
                }
                
                // 更新状态信息
                updateStatusInfo();
            })
            .catch(error => {
                addHistoryRecord({
                    time: new Date().toLocaleString(),
                    status: 'error',
                    message: '网络错误: ' + error.message
                });
                
                showError('自动更新检查失败: ' + error.message);
            });
    }
    
    // 添加历史记录
    function addHistoryRecord(record) {
        const historyItem = document.createElement('div');
        historyItem.className = `history-item ${record.status}`;
        
        const statusIcon = record.status === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        historyItem.innerHTML = `
            <div class="history-time">
                <i class="fas fa-clock"></i> ${record.time}
            </div>
            <div class="history-details">
                <i class="fas ${statusIcon}"></i>
                ${record.message}
            </div>
            <div class="history-count">
                ${record.new_count ? `新增: ${record.new_count}` : ''}
            </div>
        `;
        
        historyList.insertBefore(historyItem, historyList.firstChild);
        
        // 限制历史记录数量
        const items = historyList.querySelectorAll('.history-item');
        if (items.length > 20) {
            historyList.removeChild(items[items.length - 1]);
        }
    }
    
    // 更新状态信息
    function updateStatusInfo() {
        const now = new Date();
        const intervalMinutes = getActualInterval();
        const nextCheck = new Date(now.getTime() + intervalMinutes * 60 * 1000);
        
        statusInfo.textContent = `上次检查: ${now.toLocaleString()} | 下次检查: ${nextCheck.toLocaleString()} | 更新次数: ${updateCount}`;
    }
    
    // 开始自动更新
    startAutoUpdate.addEventListener('click', function() {
        const url = autoUpdateUrl.value.trim();
        
        if (!url) {
            showError('请先设置要监控的抖音主页链接');
            return;
        }
        
        if (!url.includes('douyin.com')) {
            showError('请输入有效的抖音链接');
            return;
        }
        
        const interval = getActualInterval();
        const fileName = updateFileName.value.trim() || 'auto_update';
        const type = updateType.value;
        const emailEnabled = emailNotification.checked;
        const emailAddr = emailAddress.value.trim();
        const emailCond = emailCondition.value;
        
        const params = new URLSearchParams({
            action: 'start',
            url: url,
            interval: interval,
            filename: fileName,
            type: type,
            email_enabled: emailEnabled ? '1' : '0',
            email_address: emailAddr,
            email_condition: emailCond,
            smtp_host: smtpHost.value.trim(),
            smtp_port: smtpPort.value.trim(),
            smtp_username: smtpUsername.value.trim(),
            smtp_password: smtpPassword.value.trim(),
            smtp_encryption: smtpEncryption.value,
            from_name: fromName.value || '抖音监控系统'
        });
        
        fetch(`auto_update.php?${params.toString()}&t=${Date.now()}`)
            .then(response => response.json())
            .then(data => {
                if (data.code === 1) {
                    startAutoUpdate.disabled = true;
                    stopAutoUpdate.disabled = false;
                    isAutoUpdating = true;
                    
                    // 更新状态显示
                    autoUpdateStatus.className = 'auto-update-status running';
                    autoUpdateStatus.querySelector('.status-indicator').className = 'status-indicator running';
                    autoUpdateStatus.querySelector('.status-indicator span').textContent = '自动更新运行中';
                    
                    // 设置定时器
                    if (autoUpdateInterval) {
                        clearInterval(autoUpdateInterval);
                    }
                    autoUpdateInterval = setInterval(checkForUpdates, interval * 60 * 1000);
                    
                    // 立即执行一次检查
                    checkForUpdates();
                    
                    showSuccess('自动更新已启动');
                } else {
                    showError('启动自动更新失败: ' + data.msg);
                }
            })
            .catch(error => {
                showError('启动自动更新失败: ' + error.message);
            });
    });
    
    // 停止自动更新
    stopAutoUpdate.addEventListener('click', function() {
        fetch('auto_update.php?action=stop&t=' + Date.now())
            .then(response => response.json())
            .then(data => {
                if (data.code === 1) {
                    startAutoUpdate.disabled = false;
                    stopAutoUpdate.disabled = true;
                    isAutoUpdating = false;
                    
                    // 更新状态显示
                    autoUpdateStatus.className = 'auto-update-status stopped';
                    autoUpdateStatus.querySelector('.status-indicator').className = 'status-indicator stopped';
                    autoUpdateStatus.querySelector('.status-indicator span').textContent = '自动更新已停止';
                    
                    // 清除定时器
                    if (autoUpdateInterval) {
                        clearInterval(autoUpdateInterval);
                        autoUpdateInterval = null;
                    }
                    
                    showSuccess('自动更新已停止');
                } else {
                    showError('停止自动更新失败: ' + data.msg);
                }
            })
            .catch(error => {
                showError('停止自动更新失败: ' + error.message);
            });
    });
    
    // 立即检查更新
    checkNow.addEventListener('click', function() {
        if (isAutoUpdating) {
            checkForUpdates();
        } else {
            const url = autoUpdateUrl.value.trim();
            if (!url) {
                showError('请先设置要监控的抖音主页链接');
                return;
            }
            checkForUpdates();
        }
    });
    
    // 初始化时加载文件列表和自动更新状态
    loadFileList();
    loadAutoUpdateStatus();
});