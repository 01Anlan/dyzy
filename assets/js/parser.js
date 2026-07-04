document.addEventListener('DOMContentLoaded', function () {

    /* ── refs ── */
    const $ = id => document.getElementById(id);

    const parseBtn          = $('parseBtn');
    const douyinUrlInput    = $('douyinUrl');
    const fileNameInput     = $('fileName');
    const parseTypeSelect   = $('parseType');
    const autoUpdateOption  = $('autoUpdateOption');
    const loading           = $('loading');
    const resultContainer   = $('resultContainer');
    const videoCount        = $('videoCount');
    const fileNameDisplay   = $('fileNameDisplay');
    const linkCount         = $('linkCount');
    const downloadBtn       = $('downloadBtn');
    const copyBtn           = $('copyBtn');
    const watchBtn          = $('watchBtn');
    const errorAlert        = $('errorAlert');
    const successAlert      = $('successAlert');
    const errorMsg          = $('errorMsg');
    const successMsg        = $('successMsg');
    const refreshBtn        = $('refreshBtn');
    const refreshRecordsBtn = $('refreshRecordsBtn');
    const fileList          = $('fileList');
    const recordsList       = $('recordsList');
    const fileCountEl       = $('fileCount');
    const recordsCount      = $('recordsCount');
    const cleanupBtn        = $('cleanupBtn');
    const cleanupHours      = $('cleanupHours');
    const statusInfo        = $('statusInfo');
    const historyList       = $('historyList');
    const emailNotification = $('emailNotification');
    const emailSettings     = $('emailSettings');
    const emailAddress      = $('emailAddress');
    const emailCondition    = $('emailCondition');
    const browserTabs       = document.querySelectorAll('.browser-tab');
    const tabPanels         = document.querySelectorAll('.tab-panel');
    const accountParseBtn   = $('accountParseBtn');
    const accountCookie     = $('accountCookie');
    const accountMode       = $('accountMode');
    const accountType       = $('accountType');
    const accountFilename   = $('accountFilename');
    const accountEmail      = $('accountEmail');
    const accountInlineStatus = $('accountInlineStatus');
    const accountLoading    = $('accountLoading');
    const accountLoadingText= $('accountLoadingText');
    const accountResultContainer = $('accountResultContainer');
    const accountResultTitle= $('accountResultTitle');
    const accountStatusBadge= $('accountStatusBadge');
    const accountFileNameDisplay = $('accountFileNameDisplay');
    const accountResultMeta = $('accountResultMeta');
    const accountDownloadBtn= $('accountDownloadBtn');
    const accountWatchBtn   = $('accountWatchBtn');
    const accountCopyBtn    = $('accountCopyBtn');
    const userStatus        = $('userStatus');
    const logoutUserBtn     = $('logoutUserBtn');
    const personalCookieStatus = $('personalCookieStatus');
    const savePersonalCookieBtn = $('savePersonalCookieBtn');
    const clearCookieInputBtn = $('clearCookieInputBtn');

    let currentDownloadUrl  = '';
    let currentWatchFile    = '';
    let parseAttempts       = 0;
    let currentPreviewFile  = '';
    let currentAccountDownloadUrl = '';
    let currentAccountWatchFile = '';
    let accountPollTimer = null;
    let currentUser = null;
    let hasSavedPersonalCookie = false;
    let csrfToken = '';

    function csrfHeaders(extra = {}) {
        return csrfToken ? { ...extra, 'X-CSRF-Token': csrfToken } : extra;
    }

    /* ── helpers ── */
    function showError(msg) {
        parseAttempts++;
        errorMsg.textContent = parseAttempts >= 3 && !msg.includes('更换链接')
            ? msg + ' 如果多次失败，请尝试更换抖音主页链接。'
            : msg;
        errorAlert.classList.add('show');
        successAlert.classList.remove('show');
        setTimeout(() => errorAlert.classList.remove('show'), 6000);
    }

    function showSuccess(msg) {
        parseAttempts = 0;
        successMsg.textContent = msg;
        successAlert.classList.add('show');
        errorAlert.classList.remove('show');
        setTimeout(() => successAlert.classList.remove('show'), 5000);
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '\u0026amp;',
            '<': '\u0026lt;',
            '>': '\u0026gt;',
            '"': '\u0026quot;',
            "'": '\u0026#39;'
        }[char]));
    }

    function escapeJsString(value) {
        return String(value ?? '')
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/\r/g, '\\r')
            .replace(/\n/g, '\\n');
    }

    function renderMediaThumb(iconClass) {
        return `
            <div class="list-thumb is-fallback">
                <i class="fas ${iconClass}"></i>
            </div>`;
    }

    browserTabs.forEach(tab => {
        tab.addEventListener('click', function () {
            const target = this.dataset.tab;
            if (!target) return; // 跳过无 data-tab 的标签（如外部链接）
            browserTabs.forEach(item => item.classList.toggle('active', item === this));
            tabPanels.forEach(panel => panel.classList.toggle('active', panel.id === target));
        });
    });

    /* ── 用户状态与个人 Cookie ── */
    function redirectToUserLogin() {
        window.location.href = `user.php?redirect=${encodeURIComponent(window.location.pathname.split('/').pop() || 'parser.html')}`;
    }

    function handleAuthResponse(response) {
        if (response.status === 401) {
            redirectToUserLogin();
            throw new Error('请先登录用户账号');
        }
        return response;
    }

    function checkUserStatus() {
        return fetch(`api/user_api.php?action=status&t=${Date.now()}`)
            .then(r => r.json())
            .then(data => {
                currentUser = data.data?.user || null;
                csrfToken = data.data?.csrf_token || csrfToken;
                if (!currentUser) {
                    redirectToUserLogin();
                    return null;
                }
                userStatus.innerHTML = `<i class="fas fa-user"></i> ${currentUser.username}`;
                loadPersonalCookie();
                return currentUser;
            })
            .catch(() => redirectToUserLogin());
    }

    function loadPersonalCookie() {
        fetch(`api/user_api.php?action=cookie_load&t=${Date.now()}`)
            .then(handleAuthResponse)
            .then(r => r.json())
            .then(data => {
                if (data.code !== 1) return;
                hasSavedPersonalCookie = !!data.data?.has_cookie;
                personalCookieStatus.textContent = hasSavedPersonalCookie
                    ? '已保存个人 Cookie；留空输入框时会自动使用已保存 Cookie。'
                    : '个人 Cookie 未保存；保存后当前用户的主页解析、点赞和收藏解析都会优先使用它。';
            })
            .catch(() => { /* 登录跳转或静默失败 */ });
    }

    let latestCronCommand = '/usr/bin/php tasks/cron_auto_update.php --token=加载中';

    /* ── plan task command ── */
    function updatePlanTaskCommand(command) {
        const domain = window.location.hostname;
        latestCronCommand = command || latestCronCommand;
        $('auto-domain-path').textContent =
            `cd /www/wwwroot/${domain}/\n${latestCronCommand}`;
    }

    window.copyCommand = function () {
        navigator.clipboard.writeText($('auto-domain-path').textContent)
            .then(() => showSuccess('命令已复制到剪贴板！'))
            .catch(() => showError('复制失败，请手动复制。'));
    };

    /* ── modals ── */
    function openModal(id) {
        $(id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        $(id).classList.remove('show');
        document.body.style.overflow = '';
    }

    $('helpTrigger').addEventListener('click', () => openModal('helpModalBg'));
    $('showHelp').addEventListener('click', () => openModal('helpModalBg'));
    $('showCookieHelp').addEventListener('click', () => openModal('cookieHelpModalBg'));
    $('closeHelpModal').addEventListener('click', () => closeModal('helpModalBg'));
    $('understandBtn').addEventListener('click', () => closeModal('helpModalBg'));
    $('closePreviewModal').addEventListener('click', () => closeModal('previewModalBg'));
    $('closePreviewBtn').addEventListener('click', () => closeModal('previewModalBg'));
    $('closeCookieHelpModal').addEventListener('click', () => closeModal('cookieHelpModalBg'));
    $('closeCookieHelpBtn').addEventListener('click', () => closeModal('cookieHelpModalBg'));

    ['helpModalBg', 'previewModalBg', 'cookieHelpModalBg'].forEach(id => {
        $(id).addEventListener('click', e => {
            if (e.target === $(id)) closeModal(id);
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal('helpModalBg');
            closeModal('previewModalBg');
            closeModal('cookieHelpModalBg');
        }
    });

    /* ── email toggle ── */
    emailNotification.addEventListener('change', function () {
        emailSettings.style.display = this.checked ? 'block' : 'none';
    });

    /* ── file list ── */
    function loadFileList() {
        fetch('api/file_manager.php?action=list&t=' + Date.now())
            .then(r => { if (!r.ok) throw new Error('网络响应不正常'); return r.json(); })
            .then(data => {
                if (data.code === 1 && data.data && data.data.length > 0) {
                    renderFileList(data.data);
                    fileCountEl.textContent = data.data.length;
                } else {
                    fileList.innerHTML = emptyState('folder-open', '暂无文件，解析链接后将显示在这里');
                    fileCountEl.textContent = '0';
                }
            })
            .catch(err => showError('加载文件列表失败: ' + err.message));
    }

    function renderFileList(files) {
        fileList.innerHTML = files.map(f => {
            const title = String(f.work_title || f.custom_filename || f.name || '').trim();
            const author = String(f.work_author || '').trim();
            const typeName = f.parse_type === '2' ? '图文' : (f.parse_type === '1' ? '视频' : '文件');
            const countText = Number(f.video_count || 0) > 0 ? `${f.video_count} 个链接` : '';
            const meta = [typeName, countText, f.size, f.last_parse_time || f.time].filter(Boolean).join(' · ');
            const desc = author ? `作者：${author}` : (f.douyin_url || f.name || '');
            return `
            <div class="list-item list-item-media" data-file="${escapeHtml(f.name)}">
                ${renderMediaThumb('fa-file-lines')}
                <div class="list-info">
                    <div class="list-name">${escapeHtml(title)}</div>
                    <div class="list-desc">${escapeHtml(desc)}</div>
                    <div class="list-meta">${escapeHtml(meta)}</div>
                </div>
                <div class="list-actions">
                    <button class="btn" onclick="previewFile('${escapeJsString(f.name)}')"><i class="fas fa-eye"></i></button>
                    <button class="btn" onclick="watchFile('${escapeJsString(f.name)}')"><i class="fas fa-play"></i></button>
                    <button class="btn" onclick="downloadFile('${escapeJsString(f.download_url)}')"><i class="fas fa-download"></i></button>
                    <button class="btn" onclick="copyFileUrl('${escapeJsString(f.download_url)}')"><i class="fas fa-copy"></i></button>
                    <button class="btn btn-danger" onclick="deleteFile('${escapeJsString(f.name)}')"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('');
    }

    /* ── records ── */
    function loadRecords() {
        fetch('api/manage_records.php?action=list&t=' + Date.now())
            .then(r => { if (!r.ok) throw new Error('网络响应不正常'); return r.json(); })
            .then(data => {
                if (data.code === 1 && data.data && data.data.length > 0) {
                    renderRecordsList(data.data);
                    recordsCount.textContent = data.data.length;
                } else {
                    recordsList.innerHTML = emptyState('database', '暂无解析记录');
                    recordsCount.textContent = '0';
                }
            })
            .catch(err => showError('加载解析记录失败: ' + err.message));
    }

    function renderRecordsList(records) {
        recordsList.innerHTML = records.map(r => {
            const typeName = r.parse_type === '1' ? '视频' : '图片';
            const checked  = r.auto_update ? 'checked' : '';
            const title = String(r.work_title || r.douyin_url || '').trim();
            const author = String(r.work_author || '').trim();
            const desc = author ? `作者：${author}` : (r.douyin_url || '');
            return `
            <div class="list-item list-item-media" data-id="${escapeHtml(r.id)}">
                ${renderMediaThumb('fa-link')}
                <div class="list-info">
                    <div class="list-name">${escapeHtml(title)}</div>
                    <div class="list-desc">${escapeHtml(desc)}</div>
                    <div class="list-meta">
                        ${escapeHtml(r.custom_filename)} &nbsp;·&nbsp; ${typeName} &nbsp;·&nbsp;
                        ${escapeHtml(r.video_count)} 个 &nbsp;·&nbsp; ${escapeHtml(r.last_parse_time || '')}
                    </div>
                </div>
                <div class="list-actions">
                    <div class="record-toggle">
                        <label class="toggle-switch" title="自动更新">
                            <input type="checkbox" ${checked} onchange="toggleAutoUpdate(${r.id}, this.checked)">
                            <div class="toggle-track"></div>
                            <div class="toggle-thumb"></div>
                        </label>
                    </div>
                    <button class="btn btn-danger" onclick="deleteRecord(${r.id})"><i class="fas fa-trash"></i></button>
                </div>
            </div>`;
        }).join('');
    }

    window.toggleAutoUpdate = function (recordId, enabled) {
        fetch(`api/manage_records.php?action=toggle_auto_update&id=${recordId}&auto_update=${enabled ? 1 : 0}&t=${Date.now()}`, {
            headers: csrfHeaders()
        })
            .then(r => r.json())
            .then(data => {
                if (data.code === 1) { showSuccess('自动更新设置已更新'); loadAutoUpdateStatus(); }
                else { showError('更新失败: ' + data.msg); loadRecords(); }
            })
            .catch(err => { showError('更新失败: ' + err.message); loadRecords(); });
    };

    window.deleteRecord = function (recordId) {
        if (!confirm('确定要删除此解析记录吗？相关文件也将被删除！')) return;
        fetch(`api/manage_records.php?action=delete_record&id=${recordId}&t=${Date.now()}`, {
            headers: csrfHeaders()
        })
            .then(r => r.json())
            .then(data => {
                if (data.code === 1) {
                    showSuccess('记录删除成功');
                    setTimeout(() => { loadFileList(); loadRecords(); loadAutoUpdateStatus(); }, 500);
                } else { showError('删除失败: ' + data.msg); }
            })
            .catch(err => showError('删除失败: ' + err.message));
    };

    /* ── auto update status ── */
    function loadAutoUpdateStatus() {
        fetch('api/auto_update.php?action=status&t=' + Date.now())
            .then(r => r.json())
            .then(data => {
                if (data.code === 1 && data.data) {
                    const s = data.data;
                    if (s.cron_command) updatePlanTaskCommand(s.cron_command);
                    statusInfo.textContent = s.total_records > 0
                        ? `正在监控 ${s.total_records} 个记录 · 上次检查: ${s.last_check || '从未'}`
                        : '暂无开启自动更新的记录';
                    loadUpdateHistory();
                }
            }).catch(console.error);
    }

    function loadUpdateHistory() {
        fetch('api/auto_update.php?action=history&t=' + Date.now())
            .then(r => r.json())
            .then(data => { if (data.code === 1 && data.data) renderUpdateHistory(data.data); })
            .catch(console.error);
    }

    function renderUpdateHistory(history) {
        if (!history || !history.length) {
            historyList.innerHTML = emptyState('clock', '暂无更新记录');
            return;
        }
        historyList.innerHTML = history.map(h => {
            const icon = h.status === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation';
            return `
            <div class="history-item ${h.status}">
                <span class="history-time"><i class="fas fa-clock"></i> ${h.time}</span>
                <span class="history-msg"><i class="fas ${icon}"></i> ${h.message}</span>
                ${h.new_count ? `<span class="history-count">+${h.new_count}</span>` : ''}
            </div>`;
        }).join('');
    }

    /* ── file actions ── */
    window.previewFile = function (fileName) {
        currentPreviewFile = fileName;
        $('previewTitle').textContent = fileName;
        $('filePreviewContent').textContent = '正在加载...';
        openModal('previewModalBg');
        fetch(`api/file_preview.php?file=${encodeURIComponent(fileName)}&t=${Date.now()}`)
            .then(r => { if (!r.ok) throw new Error('文件加载失败'); return r.text(); })
            .then(text => $('filePreviewContent').textContent = text)
            .catch(err => $('filePreviewContent').textContent = '文件加载失败: ' + err.message);
    };

    window.downloadPreviewFile = function () {
        if (currentPreviewFile)
            downloadFile(`api/file_manager.php?action=download&file=${encodeURIComponent(currentPreviewFile)}`);
    };

    window.watchFile = function (fileName) {
        if (!fileName) return showError('观看文件无效');
        window.location.href = `watch.php?file=${encodeURIComponent(fileName)}`;
    };

    window.downloadFile = function (url) {
        if (!url) return showError('下载链接无效');
        window.location.href = url;
    };

    window.copyFileUrl = function (url) {
        navigator.clipboard.writeText(url)
            .then(() => showSuccess('链接已复制到剪贴板'))
            .catch(err => showError('复制失败: ' + err));
    };

    window.deleteFile = function (fileName) {
        if (!confirm(`确定要删除文件 "${fileName}" 吗？此操作不可恢复！`)) return;
        fetch(`api/file_manager.php?action=delete&file=${encodeURIComponent(fileName)}&t=${Date.now()}`, {
            headers: csrfHeaders()
        })
            .then(r => r.json())
            .then(data => {
                if (data.code === 1) { showSuccess(data.msg); setTimeout(loadFileList, 500); }
                else showError('删除失败: ' + data.msg);
            })
            .catch(err => showError('删除文件失败: ' + err.message));
    };

    /* ── cleanup ── */
    cleanupBtn.addEventListener('click', function () {
        const hours = cleanupHours.value;
        if (!confirm(`确定要清理 ${hours} 小时前创建的所有文件吗？此操作不可恢复！`)) return;
        fetch(`api/file_manager.php?action=cleanup&hours=${hours}&t=${Date.now()}`, {
            headers: csrfHeaders()
        })
            .then(r => r.json())
            .then(data => {
                if (data.code === 1) { showSuccess(`清理完成，删除了 ${data.data.deleted_count} 个文件`); loadFileList(); }
                else showError('清理失败: ' + data.msg);
            })
            .catch(err => showError('清理失败: ' + err.message));
    });

    /* ── parse ── */
    parseBtn.addEventListener('click', function () {
        const douyinUrl = douyinUrlInput.value.trim();
        const fileName  = fileNameInput.value.trim() || 'video_links';
        const parseType = parseTypeSelect.value;
        const autoUpdate= autoUpdateOption.value === '1';

        if (!douyinUrl) return showError('请输入抖音主页链接');
        if (!douyinUrl.includes('douyin.com')) return showError('请输入有效的抖音链接');

        loading.classList.add('show');
        resultContainer.classList.remove('show');
        parseBtn.disabled = true;

        const manualCookie = accountCookie.value.trim();
        let parseUrl = `api/Douyin.php?url=${encodeURIComponent(douyinUrl)}&filename=${encodeURIComponent(fileName)}&type=${parseType}&auto_update=${autoUpdate ? 1 : 0}`;
        if (manualCookie) {
            parseUrl += `&cookie=${encodeURIComponent(manualCookie)}`;
        }
        parseUrl += `&t=${Date.now()}`;

        fetch(parseUrl, {
            headers: csrfHeaders({ 'X-Requested-With': 'XMLHttpRequest' })
        })
            .then(handleAuthResponse)
            .then(r => { if (!r.ok) throw new Error('网络请求失败: ' + r.status); return r.json(); })
            .then(data => {
                loading.classList.remove('show');
                parseBtn.disabled = false;
                if (data.code === 1) {
                    videoCount.textContent  = data.data.video_count + ' 个链接';
                    linkCount.textContent   = data.data.video_count;
                    fileNameDisplay.textContent = data.data.file_name;
                    currentDownloadUrl      = data.data.download_url;
                    currentWatchFile        = data.data.file_name;
                    resultContainer.classList.add('show');
                    showSuccess(data.msg);
                    setTimeout(() => { loadFileList(); loadRecords(); loadAutoUpdateStatus(); }, 1000);
                } else {
                    showError(data.msg);
                }
            })
            .catch(err => {
                loading.classList.remove('show');
                parseBtn.disabled = false;
                showError('解析失败: ' + err.message);
            });
    });

    function stopAccountPolling() {
        if (accountPollTimer) {
            clearTimeout(accountPollTimer);
            accountPollTimer = null;
        }
    }

    function pollAccountJob(jobId, recordId, filename, type) {
        fetch(`api/AccountCookie.php?action=poll&job_id=${encodeURIComponent(jobId)}&record_id=${recordId}&filename=${encodeURIComponent(filename)}&type=${type}&t=${Date.now()}`, {
            headers: csrfHeaders()
        })
            .then(r => { if (!r.ok) throw new Error('轮询任务失败'); return r.json(); })
            .then(data => {
                if (data.code !== 1) throw new Error(data.msg || '轮询失败');

                const status = data.data?.status || 'queued';
                accountStatusBadge.textContent = status;

                if (status !== 'done') {
                    accountLoading.classList.add('show');
                    accountLoadingText.textContent = `任务状态：${status}，正在轮询...`;
                    accountResultTitle.textContent = '任务处理中';
                    accountResultMeta.textContent = data.data?.message || '解析完成后将自动同步到文件管理';
                    accountPollTimer = setTimeout(() => pollAccountJob(jobId, recordId, filename, type), 5000);
                    return;
                }

                stopAccountPolling();
                accountLoading.classList.remove('show');
                accountResultContainer.classList.add('show');
                accountResultTitle.textContent = '解析完成';
                accountStatusBadge.textContent = 'done';
                accountFileNameDisplay.textContent = data.data.file_name || '已生成文件';
                accountResultMeta.textContent = `共 ${data.data.video_count || 0} 个链接，已同步到文件管理`;
                currentAccountDownloadUrl = data.data.download_url || '';
                currentAccountWatchFile = data.data.file_name || '';
                showSuccess('任务已完成，文件已同步到本地');
                setTimeout(() => { loadFileList(); loadRecords(); loadAutoUpdateStatus(); }, 500);
            })
            .catch(err => {
                stopAccountPolling();
                accountLoading.classList.remove('show');
                showError('Cookie 解析失败: ' + err.message);
            });
    }

    accountParseBtn.addEventListener('click', function () {
        const cookie = accountCookie.value.trim();
        const mode = accountMode.value;
        const type = accountType.value;
        const filename = accountFilename.value.trim() || (mode === 'collection' ? '我的收藏' : '我的喜欢');
        const emailAddr = accountEmail.value.trim() || emailAddress.value.trim();

        accountInlineStatus.textContent = '已点击，正在校验参数...';
        if (!cookie && !hasSavedPersonalCookie) {
            accountInlineStatus.textContent = '缺少 Cookie，请填写 Cookie 或先保存个人 Cookie';
            return showError('请输入抖音登录 Cookie，或先保存个人 Cookie');
        }

        stopAccountPolling();
        accountParseBtn.disabled = true;
        accountLoading.classList.add('show');
        accountLoadingText.textContent = '任务提交中...';
        accountInlineStatus.textContent = '任务提交中，请稍等...';
        accountResultContainer.classList.remove('show');
        currentAccountDownloadUrl = '';
        currentAccountWatchFile = '';

        const params = new URLSearchParams({
            action: 'submit',
            mode,
            filename,
            type,
            email_address: emailAddr
        });
        if (cookie) params.set('cookie', cookie);

        fetch(`api/AccountCookie.php?${params}&t=${Date.now()}`, {
            headers: csrfHeaders()
        })
            .then(handleAuthResponse)
            .then(r => { if (!r.ok) throw new Error('任务提交失败'); return r.json(); })
            .then(data => {
                accountParseBtn.disabled = false;
                if (data.code !== 1) throw new Error(data.msg || '任务提交失败');

                accountResultContainer.classList.add('show');
                accountResultTitle.textContent = '任务已提交';
                accountStatusBadge.textContent = data.data.status || 'queued';
                accountFileNameDisplay.textContent = `${data.data.filename}.txt`;
                accountResultMeta.textContent = data.msg || '解析完成后将邮件通知';
                accountInlineStatus.textContent = data.msg || '任务已提交，正在轮询解析状态...';
                currentAccountWatchFile = `${data.data.filename}.txt`;
                showSuccess(data.msg || '任务已提交，解析完成后将邮件通知');
                pollAccountJob(data.data.job_id, data.data.record_id, data.data.filename, data.data.type);
            })
            .catch(err => {
                accountParseBtn.disabled = false;
                accountLoading.classList.remove('show');
                accountInlineStatus.textContent = '提交失败：' + err.message;
                showError('Cookie 解析提交失败: ' + err.message);
            });
    });

    accountDownloadBtn.addEventListener('click', () => {
        currentAccountDownloadUrl ? window.location.href = currentAccountDownloadUrl : showError('暂无可下载文件');
    });

    accountCopyBtn.addEventListener('click', () => {
        if (!currentAccountDownloadUrl) return showError('暂无可复制链接');
        navigator.clipboard.writeText(currentAccountDownloadUrl)
            .then(() => showSuccess('下载链接已复制到剪贴板'))
            .catch(err => showError('复制失败: ' + err));
    });

    accountWatchBtn.addEventListener('click', () => {
        currentAccountWatchFile ? watchFile(currentAccountWatchFile) : showError('暂无可观看文件');
    });

    downloadBtn.addEventListener('click', () => {
        currentDownloadUrl ? (window.location.href = currentDownloadUrl) : showError('没有可下载的文件');
    });

    watchBtn.addEventListener('click', () => {
        currentWatchFile ? watchFile(currentWatchFile) : showError('没有可观看的文件');
    });

    copyBtn.addEventListener('click', () => {
        if (!currentDownloadUrl) return showError('没有可复制的链接');
        navigator.clipboard.writeText(currentDownloadUrl)
            .then(() => showSuccess('下载链接已复制到剪贴板'))
            .catch(err => showError('复制失败: ' + err));
    });

    savePersonalCookieBtn.addEventListener('click', function () {
        const cookie = accountCookie.value.trim();
        if (!cookie) return showError('请输入要保存的 Cookie');

        savePersonalCookieBtn.disabled = true;
        savePersonalCookieBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 保存中...';
        fetch('api/user_api.php?action=cookie_save', {
            method: 'POST',
            headers: csrfHeaders({ 'Content-Type': 'application/json' }),
            body: JSON.stringify({ cookie })
        })
            .then(handleAuthResponse)
            .then(r => r.json())
            .then(data => {
                if (data.code !== 1) throw new Error(data.msg || '保存失败');
                hasSavedPersonalCookie = true;
                personalCookieStatus.textContent = '已保存个人 Cookie；留空输入框时会自动使用已保存 Cookie。';
                showSuccess(data.msg || '个人 Cookie 已保存');
            })
            .catch(err => showError('保存 Cookie 失败: ' + err.message))
            .finally(() => {
                savePersonalCookieBtn.disabled = false;
                savePersonalCookieBtn.innerHTML = '<i class="fas fa-floppy-disk"></i> 保存个人 Cookie';
            });
    });

    clearCookieInputBtn.addEventListener('click', function () {
        accountCookie.value = '';
        showSuccess('Cookie 输入框已清空，已保存的个人 Cookie 不受影响');
    });

    logoutUserBtn.addEventListener('click', function () {
        fetch(`api/user_api.php?action=logout&t=${Date.now()}`, {
            headers: csrfHeaders()
        })
            .then(r => r.json())
            .finally(() => { window.location.href = 'user.php'; });
    });

    /* ── refresh ── */
    refreshBtn.addEventListener('click', () => { loadFileList(); showSuccess('文件列表已刷新'); });
    refreshRecordsBtn.addEventListener('click', () => { loadRecords(); showSuccess('解析记录已刷新'); });

    /* ── clipboard paste ── */
    douyinUrlInput.addEventListener('click', function () {
        navigator.clipboard.readText().then(text => {
            if (text.includes('douyin.com')) {
                douyinUrlInput.value = text;
                showSuccess('已自动粘贴剪贴板中的抖音链接');
            }
        }).catch(() => {});
    });

    [douyinUrlInput, fileNameInput].forEach(el => {
        el.addEventListener('keypress', e => { if (e.key === 'Enter') parseBtn.click(); });
    });

    /* ── utilities ── */
    function emptyState(icon, text) {
        return `<div class="empty-state"><i class="fas fa-${icon}"></i><p>${text}</p></div>`;
    }

    /* ── init ── */
    checkUserStatus().then(user => {
        if (!user) return;
        loadFileList();
        loadRecords();
        loadAutoUpdateStatus();
        updatePlanTaskCommand();
    });

});
