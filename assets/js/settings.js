const $ = id => document.getElementById(id);
let csrfToken = '';

function csrfHeaders(extra = {}) {
    return csrfToken ? { ...extra, 'X-CSRF-Token': csrfToken } : extra;
}

function showSuccess(msg) {
    $('successMsg').textContent = msg;
    $('successAlert').classList.add('show');
    $('errorAlert').classList.remove('show');
    setTimeout(() => $('successAlert').classList.remove('show'), 3000);
}

function showError(msg) {
    $('errorMsg').textContent = msg;
    $('errorAlert').classList.add('show');
    $('successAlert').classList.remove('show');
    setTimeout(() => $('errorAlert').classList.remove('show'), 4000);
}

function formatDuration(seconds) {
    if (seconds === null || seconds === undefined) return '';
    const days = Math.floor(seconds / 86400);
    const hours = Math.floor((seconds % 86400) / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    if (days > 0) return `${days} 天 ${hours} 小时`;
    if (hours > 0) return `${hours} 小时 ${minutes} 分钟`;
    return `${minutes} 分钟`;
}

function renderCookieExpiry(info) {
    if (!info) return;
    let text = info.message || '无法检测 Cookie 过期时间';
    if (info.source) text += `，来源：${info.source}`;
    if (info.login_at) text += `，登录时间：${info.login_at}`;
    if (info.expires_at) text += `，过期时间：${info.expires_at}`;
    if (info.seconds_left !== null && info.seconds_left !== undefined) text += `，剩余：${formatDuration(info.seconds_left)}`;
    $('cookieExpiryText').textContent = text;
}

function checkCookieExpiry() {
    const cookie = $('globalCookie').value.trim();
    $('checkCookieBtn').disabled = true;
    fetch('api/settings_api.php?action=check_cookie', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ cookie })
    })
        .then(r => r.json())
        .then(data => {
            $('checkCookieBtn').disabled = false;
            if (data.code === 1) renderCookieExpiry(data.data);
            else showError(data.msg || '检测失败');
        })
        .catch(err => { $('checkCookieBtn').disabled = false; showError('检测失败: ' + err.message); });
}

function loadSettings() {
    fetch('api/settings_api.php?action=load&t=' + Date.now())
        .then(r => r.json())
        .then(data => {
            if (data.code === 1) {
                csrfToken = data.data?.csrf_token || csrfToken;
                $('globalCookie').value = data.data.cookie || '';
                if ($('registerCaptchaMode')) $('registerCaptchaMode').value = data.data.register_captcha_mode || 'math';
                $('statusText').textContent = data.data.updated_at
                    ? '上次保存：' + data.data.updated_at
                    : '尚未保存过';
                checkCookieExpiry();
            }
        })
        .catch(() => {});
}

function loadStorageSettings() {
    fetch('api/settings_api.php?action=storage_load&t=' + Date.now())
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1 || !data.data) return;
            csrfToken = data.data.csrf_token || csrfToken;
            const c = data.data;
            $('storageEnabled').value = c.enabled ? '1' : '0';
            $('storageProvider').value = c.provider || 's3';
            $('storageEndpoint').value = c.endpoint || '';
            $('storageRegion').value = c.region || 'auto';
            $('storageBucket').value = c.bucket || '';
            $('storagePathPrefix').value = c.path_prefix || 'dyzy/{date}/';
            $('storageAccessKey').value = c.access_key_masked || '';
            $('storageAccessKey').dataset.masked = c.access_key_masked || '';
            $('storageSecretKey').value = c.secret_key_masked || '';
            $('storageSecretKey').dataset.masked = c.secret_key_masked || '';
            $('storagePublicBaseUrl').value = c.public_base_url || '';
            $('storageDeleteLocal').value = c.delete_local_after_upload ? '1' : '0';
        })
        .catch(() => {});
}

function getStoragePayload() {
    const accessValue = $('storageAccessKey').value.trim();
    const secretValue = $('storageSecretKey').value.trim();
    return {
        enabled: $('storageEnabled').value === '1',
        provider: $('storageProvider').value,
        endpoint: $('storageEndpoint').value.trim(),
        region: $('storageRegion').value.trim() || 'auto',
        bucket: $('storageBucket').value.trim(),
        access_key: accessValue && accessValue === $('storageAccessKey').dataset.masked ? '__KEEP__' : accessValue,
        secret_key: secretValue && secretValue === $('storageSecretKey').dataset.masked ? '__KEEP__' : secretValue,
        path_prefix: $('storagePathPrefix').value.trim(),
        public_base_url: $('storagePublicBaseUrl').value.trim(),
        delete_local_after_upload: $('storageDeleteLocal').value === '1'
    };
}

function loadEmailSettings() {
    fetch('api/settings_api.php?action=email_load&t=' + Date.now())
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1 || !data.data) return;
            csrfToken = data.data.csrf_token || csrfToken;
            const c = data.data;
            $('smtpHost').value = c.smtp_host || '';
            $('smtpPort').value = c.smtp_port || 465;
            $('smtpUsername').value = c.smtp_username || '';
            $('smtpPassword').value = c.smtp_password_masked || '';
            $('smtpPassword').dataset.masked = c.smtp_password_masked || '';
            $('smtpEncryption').value = c.smtp_encryption || 'ssl';
            $('fromName').value = c.from_name || '抖音监控系统';
            $('testEmailAddress').value = c.email_address || '';
        })
        .catch(() => {});
}

function getEmailPayload() {
    const passwordValue = $('smtpPassword').value.trim();
    return {
        email_address: $('testEmailAddress').value.trim(),
        smtp_host: $('smtpHost').value.trim(),
        smtp_port: $('smtpPort').value.trim() || '465',
        smtp_username: $('smtpUsername').value.trim(),
        smtp_password: passwordValue && passwordValue === $('smtpPassword').dataset.masked ? '' : passwordValue,
        smtp_encryption: $('smtpEncryption').value,
        from_name: $('fromName').value.trim() || '抖音监控系统'
    };
}

function loadUsers() {
    fetch('api/settings_api.php?action=users_list&t=' + Date.now())
        .then(r => r.json())
        .then(data => {
            const tbody = $('usersTableBody');
            if (data.code !== 1) {
                tbody.innerHTML = `<tr><td colspan="6">${data.msg || '加载失败'}</td></tr>`;
                return;
            }
            const users = data.data || [];
            if (!users.length) {
                tbody.innerHTML = '<tr><td colspan="6">暂无用户</td></tr>';
                return;
            }
            tbody.innerHTML = users.map(user => {
                const enabled = Number(user.status) === 1;
                return `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td><span class="badge-status ${enabled ? 'enabled' : 'disabled'}">${enabled ? '启用' : '禁用'}</span></td>
                        <td>${user.created_at || '-'}</td>
                        <td>${user.last_login_at || '-'}</td>
                        <td>
                            <button class="btn btn-outline table-action" data-user-id="${user.id}" data-next-status="${enabled ? 0 : 1}">
                                ${enabled ? '禁用' : '启用'}
                            </button>
                        </td>
                    </tr>`;
            }).join('');
        })
        .catch(err => { $('usersTableBody').innerHTML = `<tr><td colspan="6">加载失败：${err.message}</td></tr>`; });
}

$('saveBtn').addEventListener('click', function () {
    this.disabled = true;
    fetch('api/settings_api.php?action=save', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({
            cookie: $('globalCookie').value.trim(),
            register_captcha_mode: $('registerCaptchaMode') ? $('registerCaptchaMode').value : 'math'
        })
    })
        .then(r => r.json())
        .then(data => {
            this.disabled = false;
            if (data.code === 1) {
                showSuccess('后台备用 Cookie 已保存。');
                if (data.data.updated_at) $('statusText').textContent = '上次保存：' + data.data.updated_at;
                checkCookieExpiry();
            } else {
                showError(data.msg || '保存失败');
            }
        })
        .catch(err => { this.disabled = false; showError('保存失败: ' + err.message); });
});

$('checkCookieBtn').addEventListener('click', checkCookieExpiry);

$('clearBtn').addEventListener('click', function () {
    if (!confirm('确定清空 Cookie 配置？')) return;
    $('globalCookie').value = '';
    $('saveBtn').click();
});

$('saveStorageBtn').addEventListener('click', function () {
    this.disabled = true;
    fetch('api/settings_api.php?action=storage_save', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify(getStoragePayload())
    })
        .then(r => r.json())
        .then(data => {
            this.disabled = false;
            if (data.code === 1) {
                showSuccess('对象存储配置已保存');
                loadStorageSettings();
            } else {
                showError(data.msg || '保存对象存储配置失败');
            }
        })
        .catch(err => { this.disabled = false; showError('保存失败: ' + err.message); });
});

$('testStorageBtn').addEventListener('click', function () {
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 测试中...';
    fetch('api/settings_api.php?action=storage_test&t=' + Date.now(), {
        headers: csrfHeaders()
    })
        .then(r => r.json())
        .then(data => {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-plug-circle-check"></i> 测试上传';
            if (data.code === 1) showSuccess('测试上传成功：' + (data.data.url || data.data.object_key || ''));
            else showError(data.msg || '测试上传失败');
        })
        .catch(err => {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-plug-circle-check"></i> 测试上传';
            showError('测试失败: ' + err.message);
        });
});

$('saveEmailBtn').addEventListener('click', function () {
    this.disabled = true;
    fetch('api/settings_api.php?action=email_save', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify(getEmailPayload())
    })
        .then(r => r.json())
        .then(data => {
            this.disabled = false;
            if (data.code === 1) {
                showSuccess(data.msg || '邮件配置已保存');
                loadEmailSettings();
            } else {
                showError(data.msg || '保存邮件配置失败');
            }
        })
        .catch(err => { this.disabled = false; showError('保存失败: ' + err.message); });
});

$('testEmailBtn').addEventListener('click', function () {
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 发送中...';
    fetch('api/settings_api.php?action=email_test', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ email_address: $('testEmailAddress').value.trim() })
    })
        .then(r => r.json())
        .then(data => {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-paper-plane"></i> 测试发送';
            if (data.code === 1) showSuccess(data.msg || '测试邮件发送成功');
            else showError(data.msg || '测试邮件发送失败');
        })
        .catch(err => {
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-paper-plane"></i> 测试发送';
            showError('测试失败: ' + err.message);
        });
});

$('usersTableBody').addEventListener('click', function (event) {
    const btn = event.target.closest('[data-user-id]');
    if (!btn) return;
    const userId = Number(btn.dataset.userId);
    const nextStatus = Number(btn.dataset.nextStatus);
    if (!confirm(nextStatus === 1 ? '确定启用该用户？' : '确定禁用该用户？')) return;
    btn.disabled = true;
    fetch('api/settings_api.php?action=user_toggle_status', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ id: userId, status: nextStatus })
    })
        .then(r => r.json())
        .then(data => {
            if (data.code === 1) {
                showSuccess(data.msg || '用户状态已更新');
                loadUsers();
            } else {
                btn.disabled = false;
                showError(data.msg || '更新失败');
            }
        })
        .catch(err => { btn.disabled = false; showError('更新失败: ' + err.message); });
});

$('logoutBtn').addEventListener('click', function () {
    fetch('api/auth_api.php?action=logout&t=' + Date.now(), {
        headers: csrfHeaders()
    })
        .then(r => r.json())
        .then(() => { window.location.href = 'login.php'; })
        .catch(() => { window.location.href = 'login.php'; });
});

loadSettings();
loadStorageSettings();
loadEmailSettings();
loadUsers();
