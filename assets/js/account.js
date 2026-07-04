const $ = id => document.getElementById(id);
let currentUser = null;
let csrfToken = '';

function csrfHeaders(extra = {}) {
    return csrfToken ? { ...extra, 'X-CSRF-Token': csrfToken } : extra;
}

function showMessage(type, msg) {
    const success = $('successAlert');
    const error = $('errorAlert');
    success.classList.remove('show');
    error.classList.remove('show');
    if (type === 'success') {
        $('successMsg').textContent = msg;
        success.classList.add('show');
    } else {
        $('errorMsg').textContent = msg;
        error.classList.add('show');
    }
}

function handleAuthResponse(response) {
    if (response.status === 401) {
        window.location.href = 'user.php?redirect=account.php';
        throw new Error('请先登录用户账号');
    }
    return response;
}

function updateEmailStatus(email) {
    if (email) {
        $('boundEmailStatus').textContent = `已绑定邮箱：${email}；忘记密码时可通过该邮箱找回。`;
        $('bindEmail').value = email;
    } else {
        $('boundEmailStatus').textContent = '当前账号尚未绑定邮箱，绑定后可用于忘记密码找回。';
        $('bindEmail').value = '';
    }
}

function loadUserStatus() {
    fetch(`api/user_api.php?action=status&t=${Date.now()}`)
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            currentUser = data.data?.user || null;
            csrfToken = data.data?.csrf_token || csrfToken;
            if (!currentUser) {
                window.location.href = 'user.php?redirect=account.php';
                return;
            }
            updateEmailStatus(currentUser.email || '');
        })
        .catch(err => showMessage('error', err.message));
}

function getEmailCodeScene() {
    const inputEmail = $('bindEmail').value.trim();
    const boundEmail = currentUser?.email || '';
    return boundEmail && inputEmail === boundEmail ? 'unbind' : 'bind';
}

function sendEmailCode() {
    const scene = getEmailCodeScene();
    const email = scene === 'unbind' ? (currentUser?.email || '') : $('bindEmail').value.trim();
    if (!email) {
        showMessage('error', scene === 'unbind' ? '当前账号未绑定邮箱' : '请输入邮箱地址');
        return;
    }
    const btn = $('sendEmailCodeBtn');
    btn.disabled = true;
    fetch('api/user_api.php?action=send_email_code', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ email, scene })
    })
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '发送失败');
            showMessage('success', data.msg || '验证码已发送，请查收邮箱');
        })
        .catch(err => showMessage('error', err.message))
        .finally(() => { btn.disabled = false; });
}

function submitEmailBinding(action) {
    const isBind = action === 'email_bind';
    const email = $('bindEmail').value.trim();
    const captcha = $('bindEmailCode').value.trim();
    if (!captcha || (isBind && !email)) {
        showMessage('error', isBind ? '请输入邮箱和验证码' : '请输入解绑验证码');
        return;
    }
    fetch('api/user_api.php?action=' + action, {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ email, captcha })
    })
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '操作失败');
            showMessage('success', data.msg || '操作成功');
            $('bindEmailCode').value = '';
            loadUserStatus();
        })
        .catch(err => showMessage('error', err.message));
}

const sendEmailCodeBtn = $('sendEmailCodeBtn');
const bindEmailBtn = $('bindEmailBtn');
const unbindEmailBtn = $('unbindEmailBtn');

if (sendEmailCodeBtn) sendEmailCodeBtn.addEventListener('click', sendEmailCode);
if (bindEmailBtn) bindEmailBtn.addEventListener('click', () => submitEmailBinding('email_bind'));
if (unbindEmailBtn) unbindEmailBtn.addEventListener('click', () => submitEmailBinding('email_unbind'));

if (sendEmailCodeBtn && bindEmailBtn && unbindEmailBtn) loadUserStatus();
