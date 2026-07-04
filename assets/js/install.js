const form = document.getElementById('installForm');
const messageBox = document.getElementById('messageBox');
const testBtn = document.getElementById('testBtn');
const installBtn = document.getElementById('installBtn');

function getPayload(action) {
    const data = Object.fromEntries(new FormData(form).entries());
    data.action = action;
    if (data.db_port && !String(data.db_host).includes(':')) {
        data.db_host = `${data.db_host}:${data.db_port}`;
    }
    delete data.db_port;
    return data;
}

function setMessage(type, text) {
    messageBox.className = `notice ${type || ''}`.trim();
    messageBox.textContent = text;
}

function setLoading(loading, text) {
    testBtn.disabled = loading;
    installBtn.disabled = loading;
    if (text) setMessage('', text);
}

async function requestInstall(action) {
    const response = await fetch('install_ajax.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(getPayload(action))
    });
    if (!response.ok) throw new Error(`请求失败：${response.status}`);
    return response.json();
}

testBtn.addEventListener('click', async () => {
    try {
        setLoading(true, '正在测试数据库连接...');
        const result = await requestInstall('test_connection');
        setMessage(result.success ? 'success' : 'error', result.message || (result.success ? '连接成功' : '连接失败'));
    } catch (error) {
        setMessage('error', error.message);
    } finally {
        setLoading(false);
    }
});

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    if (!confirm('确定开始安装吗？安装会写入数据库并生成配置文件。')) return;
    try {
        setLoading(true, '正在创建数据库、导入表结构并写入配置...');
        const result = await requestInstall('install');
        if (!result.success) {
            setMessage('error', result.message || '安装失败');
            setLoading(false);
            return;
        }
        setMessage('success', `安装完成，共检测到 ${result.data.tables_created || 0} 张数据表，3 秒后返回首页。`);
        setTimeout(() => { window.location.href = '../index.php'; }, 3000);
    } catch (error) {
        setMessage('error', error.message);
        setLoading(false);
    }
});
