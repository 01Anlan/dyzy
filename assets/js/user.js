const $ = id => document.getElementById(id);
let registerCaptchaMode = 'math';
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

function loadCaptcha() {
    const captchaQuestion = $('captchaQuestion');
    const captcha = $('captcha');
    if (!captchaQuestion || !captcha) return;

    fetch('api/user_api.php?action=captcha&t=' + Date.now())
        .then(r => r.json())
        .then(data => {
            registerCaptchaMode = data.data?.mode || 'math';
            csrfToken = data.data?.csrf_token || csrfToken;
            const emailField = $('emailField');
            const sendEmailCodeBtn = $('sendEmailCodeBtn');
            if (registerCaptchaMode === 'email') {
                if (emailField) emailField.style.display = '';
                if (sendEmailCodeBtn) sendEmailCodeBtn.style.display = '';
                if ($('captchaLabel')) $('captchaLabel').textContent = '邮箱验证码';
                if ($('captchaHint')) $('captchaHint').textContent = '请先填写邮箱并发送验证码，验证码 10 分钟内有效。';
                captchaQuestion.textContent = '邮箱验证码';
                captcha.placeholder = '请输入 6 位邮箱验证码';
            } else {
                if (emailField) emailField.style.display = 'none';
                if (sendEmailCodeBtn) sendEmailCodeBtn.style.display = 'none';
                if ($('captchaLabel')) $('captchaLabel').textContent = '计算验证码';
                if ($('captchaHint')) $('captchaHint').textContent = '验证码用于防止批量注册，请填写上方算式结果。';
                captchaQuestion.textContent = data.code === 1 ? data.data.question : '刷新验证码';
                captcha.placeholder = '输入计算结果';
            }
            captcha.value = '';
        })
        .catch(() => {
            captchaQuestion.textContent = '刷新验证码';
        });
}

function sendEmailCode(scene) {
    const email = $('email') ? $('email').value.trim() : '';
    if (!email) {
        showMessage('error', '请输入邮箱地址');
        return;
    }
    const btn = $('sendEmailCodeBtn');
    if (btn) btn.disabled = true;
    fetch('api/user_api.php?action=send_email_code', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ email, scene })
    })
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '发送失败');
            showMessage('success', data.msg || '验证码已发送，请查收邮箱');
        })
        .catch(err => showMessage('error', err.message))
        .finally(() => { if (btn) setTimeout(() => { btn.disabled = false; }, 1000); });
}

function submitUser(action) {
    const username = $('username') ? $('username').value.trim() : '';
    const password = $('password').value;
    const captcha = $('captcha') ? $('captcha').value.trim() : '';
    const email = $('email') ? $('email').value.trim() : '';
    const redirect = $('redirectUrl').value || 'parser.html';

    if (!username || !password) {
        showMessage('error', '请输入用户名和密码');
        return;
    }
    if (action === 'register' && !captcha) {
        showMessage('error', '注册请输入验证码');
        return;
    }
    if (action === 'register' && registerCaptchaMode === 'email' && !email) {
        showMessage('error', '邮箱验证码注册需要填写邮箱');
        return;
    }

    fetch('api/user_api.php?action=' + action, {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ username, password, captcha, email })
    })
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '操作失败');
            showMessage('success', data.msg || '操作成功');
            setTimeout(() => { window.location.href = redirect; }, 500);
        })
        .catch(err => {
            showMessage('error', err.message);
            if (action === 'register' && registerCaptchaMode === 'math') loadCaptcha();
        });
}

function resetForgotPassword() {
    const email = $('email').value.trim();
    const captcha = $('captcha').value.trim();
    const password = $('password').value;
    if (!email || !captcha || !password) {
        showMessage('error', '请填写邮箱、验证码和新密码');
        return;
    }
    fetch('api/user_api.php?action=forgot_reset', {
        method: 'POST',
        headers: csrfHeaders({ 'Content-Type': 'application/json' }),
        body: JSON.stringify({ email, captcha, password })
    })
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '重置失败');
            showMessage('success', data.msg || '密码已重置');
            setTimeout(() => { window.location.href = 'user.php'; }, 800);
        })
        .catch(err => showMessage('error', err.message));
}

const loginBtn = $('loginBtn');
const registerBtn = $('registerBtn');
const refreshCaptchaBtn = $('refreshCaptchaBtn');
const captchaInput = $('captcha');
const sendEmailCodeBtn = $('sendEmailCodeBtn');
const forgotResetBtn = $('forgotResetBtn');

if (loginBtn) loginBtn.addEventListener('click', () => submitUser('login'));
if (registerBtn) registerBtn.addEventListener('click', () => submitUser('register'));
if (refreshCaptchaBtn) refreshCaptchaBtn.addEventListener('click', loadCaptcha);
if (sendEmailCodeBtn) sendEmailCodeBtn.addEventListener('click', () => sendEmailCode(sendEmailCodeBtn.dataset.scene || 'register'));
if (forgotResetBtn) forgotResetBtn.addEventListener('click', resetForgotPassword);
$('password').addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        if (forgotResetBtn) resetForgotPassword();
        else submitUser(registerBtn && !loginBtn ? 'register' : 'login');
    }
});
if (captchaInput) {
    captchaInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            if (forgotResetBtn) resetForgotPassword();
            else submitUser('register');
        }
    });
}

loadCaptcha();
