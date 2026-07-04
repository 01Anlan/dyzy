const $ = id => document.getElementById(id);

function showSuccess(msg) {
    $('successMsg').textContent = msg;
    $('successAlert').classList.add('show');
    $('errorAlert').classList.remove('show');
}

function showError(msg) {
    $('errorMsg').textContent = msg;
    $('errorAlert').classList.add('show');
    $('successAlert').classList.remove('show');
}

function safeRedirect(url) {
    if (!url || url.startsWith('http://') || url.startsWith('https://') || url.startsWith('//')) {
        return './';
    }
    return url;
}

$('authBtn').addEventListener('click', function () {
    const mode = $('authMode').value;
    const username = $('username').value.trim();
    const password = $('password').value;
    const confirmPassword = $('confirmPassword') ? $('confirmPassword').value : password;

    if (!username || !password) return showError('请输入账号和密码');
    if (mode === 'init' && password !== confirmPassword) return showError('两次输入的密码不一致');

    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 处理中...';

    fetch('api/auth_api.php?action=' + mode, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
    })
    .then(r => r.json())
    .then(data => {
        if (data.code === 1) {
            showSuccess(data.msg || '操作成功');
            setTimeout(() => {
                window.location.href = safeRedirect($('redirectUrl').value);
            }, 500);
            return;
        }
        this.disabled = false;
        this.innerHTML = mode === 'init'
            ? '<i class="fas fa-arrow-right-to-bracket"></i> 创建并登录'
            : '<i class="fas fa-arrow-right-to-bracket"></i> 登录后台';
        showError(data.msg || '操作失败');
    })
    .catch(err => {
        this.disabled = false;
        this.innerHTML = mode === 'init'
            ? '<i class="fas fa-arrow-right-to-bracket"></i> 创建并登录'
            : '<i class="fas fa-arrow-right-to-bracket"></i> 登录后台';
        showError('请求失败: ' + err.message);
    });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Enter') $('authBtn').click();
});
