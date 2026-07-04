const $ = id => document.getElementById(id);
let currentDownloadUrl = '';
let currentWatchFile = '';
let csrfToken = '';

function csrfHeaders(extra = {}) {
    return csrfToken ? { ...extra, 'X-CSRF-Token': csrfToken } : extra;
}

function showError(msg) {
    $('errorMsg').textContent = msg;
    $('errorAlert').classList.add('show');
    $('successAlert').classList.remove('show');
}

function showSuccess(msg) {
    $('successMsg').textContent = msg;
    $('successAlert').classList.add('show');
    $('errorAlert').classList.remove('show');
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

function emptyState(icon, text) {
    return `
        <div class="empty-state">
            <i class="fas fa-${icon}"></i>
            <p>${escapeHtml(text)}</p>
        </div>`;
}

function renderSingleWorkInfo(result) {
    const container = $('singleWorkInfo');
    if (!container) return;

    const title = String(result.work_title || '').trim();
    const author = String(result.work_author || result.nickname || '').trim();
    const musicTitle = String(result.music_title || '').trim();
    const musicAuthor = String(result.music_author || '').trim();
    const musicUrl = String(result.music_url || '').trim();
    const hasMusic = musicTitle || musicAuthor || musicUrl;
    if (!title && !author && !hasMusic) {
        container.style.display = 'none';
        container.innerHTML = '';
        return;
    }

    let musicHtml = '';
    if (hasMusic) {
        let musicText = '';
        if (musicTitle && musicAuthor) {
            musicText = `${musicTitle} - ${musicAuthor}`;
        } else if (musicTitle) {
            musicText = musicTitle;
        } else if (musicAuthor) {
            musicText = musicAuthor;
        }
        if (musicText) {
            musicHtml = `<div class="result-work-meta"><i class="fas fa-music"></i> ${escapeHtml(musicText)}</div>`;
            if (musicUrl) {
                musicHtml += `<div class="result-work-meta"><a href="${escapeHtml(musicUrl)}" target="_blank" class="music-link"><i class="fas fa-external-link-alt"></i> 音乐地址</a></div>`;
            }
        }
    }

    container.style.display = 'flex';
    container.innerHTML = `
        <div class="result-thumb is-fallback">
            <i class="fas fa-image"></i>
        </div>
        <div class="result-work-text">
            <div class="result-work-title">${escapeHtml(title || '单作品解析')}</div>
            ${author ? `<div class="result-work-meta">作者：${escapeHtml(author)}</div>` : ''}
            ${musicHtml}
        </div>`;
}

function handleAuthResponse(response) {
    if (response.status === 401) {
        window.location.href = 'user.php?redirect=single.php';
        throw new Error('请先登录用户账号');
    }
    return response;
}

function checkUserStatus() {
    fetch(`api/user_api.php?action=status&t=${Date.now()}`)
        .then(r => r.json())
        .then(data => {
            csrfToken = data.data?.csrf_token || csrfToken;
            if (data.code !== 1 || !data.data?.user) {
                window.location.href = 'user.php?redirect=single.php';
                return;
            }
            $('userStatus').innerHTML = `<i class="fas fa-user"></i> ${escapeHtml(data.data.user.username)}`;
            refreshSingleLists();
        })
        .catch(() => {
            window.location.href = 'user.php?redirect=single.php';
        });
}

function defaultFileName() {
    const now = new Date();
    const pad = value => String(value).padStart(2, '0');
    return `single_${now.getFullYear()}${pad(now.getMonth() + 1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}`;
}

function parseSingleWork() {
    const url = $('singleUrl').value.trim();
    const filename = $('singleFileName').value.trim() || defaultFileName();
    const type = $('singleParseType').value;

    if (!url) {
        showError('请输入抖音作品链接');
        return;
    }

    $('singleParseBtn').disabled = true;
    $('singleLoading').classList.add('show');
    $('singleResultContainer').classList.remove('show');
    currentDownloadUrl = '';
    currentWatchFile = '';
    renderSingleWorkInfo({});

    const params = new URLSearchParams({
        url,
        filename,
        type,
        mode: 'single',
        auto_update: '0',
        t: Date.now().toString()
    });

    fetch(`api/Douyin.php?${params}`, {
        headers: csrfHeaders({ 'X-Requested-With': 'XMLHttpRequest' })
    })
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '解析失败');

            const result = data.data || {};
            currentDownloadUrl = result.download_url || '';
            currentWatchFile = result.file_name || '';

            $('singleCount').textContent = `${result.video_count || 0} 个链接`;
            $('singleFileNameDisplay').textContent = result.file_name || `${filename}.txt`;
            $('singleMeta').textContent = `类型：${result.parse_type === '2' ? '图文' : '视频'} · 保存时间：${result.file_time || '-'}`;
            renderSingleWorkInfo(result);
            $('singleResultContainer').classList.add('show');
            refreshSingleLists();
            showSuccess(data.msg || '作品解析成功');
        })
        .catch(err => showError('作品解析失败: ' + err.message))
        .finally(() => {
            $('singleParseBtn').disabled = false;
            $('singleLoading').classList.remove('show');
        });
}

function loadSingleRecords() {
    const list = $('singleRecordsList');
    const count = $('singleRecordsCount');
    if (!list || !count) return;

    fetch(`api/manage_records.php?action=list&mode=single&t=${Date.now()}`)
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            const records = Array.isArray(data.data) ? data.data : [];
            count.textContent = records.length;
            list.innerHTML = records.length ? renderSingleRecordsList(records) : emptyState('database', '暂无解析记录');
        })
        .catch(err => {
            count.textContent = '0';
            list.innerHTML = emptyState('database', `加载解析记录失败：${err.message}`);
        });
}

function renderSingleRecordsList(records) {
    return records.map(record => {
        const typeName = record.parse_type === '2' ? '图文' : '视频';
        const title = String(record.work_title || record.douyin_url || '').trim();
        const author = String(record.work_author || '').trim();
        const desc = author ? `作者：${author}` : (record.douyin_url || '');
        return `
        <div class="list-item list-item-media" data-id="${escapeHtml(record.id)}">
            ${renderMediaThumb('fa-link')}
            <div class="list-info">
                <div class="list-name">${escapeHtml(title)}</div>
                <div class="list-desc">${escapeHtml(desc)}</div>
                <div class="list-meta">${escapeHtml(record.custom_filename || '')} · ${typeName} · ${escapeHtml(record.video_count || 0)} 个 · ${escapeHtml(record.last_parse_time || '')}</div>
            </div>
            <div class="list-actions">
                <button class="btn btn-danger" onclick="deleteSingleRecord(${Number(record.id) || 0})"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
}

function loadSingleFiles() {
    const list = $('singleFileList');
    const count = $('singleFileCount');
    if (!list || !count) return;

    fetch(`api/file_manager.php?action=list&mode=single&t=${Date.now()}`)
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            const files = Array.isArray(data.data) ? data.data : [];
            count.textContent = files.length;
            list.innerHTML = files.length ? renderSingleFileList(files) : emptyState('folder-open', '暂无文件，解析链接后将显示在这里');
        })
        .catch(err => {
            count.textContent = '0';
            list.innerHTML = emptyState('folder-open', `加载文件列表失败：${err.message}`);
        });
}

function renderSingleFileList(files) {
    return files.map(file => {
        const title = String(file.work_title || file.custom_filename || file.name || '').trim();
        const author = String(file.work_author || '').trim();
        const typeName = file.parse_type === '2' ? '图文' : (file.parse_type === '1' ? '视频' : '文件');
        const countText = Number(file.video_count || 0) > 0 ? `${file.video_count} 个链接` : '';
        const meta = [typeName, countText, file.size, file.last_parse_time || file.time].filter(Boolean).join(' · ');
        const desc = author ? `作者：${author}` : (file.douyin_url || file.name || '');
        return `
        <div class="list-item list-item-media" data-file="${escapeHtml(file.name)}">
            ${renderMediaThumb('fa-file-lines')}
            <div class="list-info">
                <div class="list-name">${escapeHtml(title)}</div>
                <div class="list-desc">${escapeHtml(desc)}</div>
                <div class="list-meta">${escapeHtml(meta)}</div>
            </div>
            <div class="list-actions">
                <button class="btn" onclick="watchFile('${escapeJsString(file.name)}')"><i class="fas fa-play"></i></button>
                <button class="btn" onclick="downloadSingleFile('${escapeJsString(file.download_url)}')"><i class="fas fa-download"></i></button>
                <button class="btn" onclick="copySingleFileUrl('${escapeJsString(file.download_url)}')"><i class="fas fa-copy"></i></button>
                <button class="btn btn-danger" onclick="deleteSingleFile('${escapeJsString(file.name)}')"><i class="fas fa-trash"></i></button>
            </div>
        </div>`;
    }).join('');
}

function refreshSingleLists() {
    loadSingleRecords();
    loadSingleFiles();
}

function watchFile(fileName) {
    if (!fileName) {
        showError('暂无可观看文件');
        return;
    }
    window.open(`watch.php?file=${encodeURIComponent(fileName)}`, '_blank');
}

window.deleteSingleRecord = function (recordId) {
    if (!recordId || !confirm('确定要删除此解析记录吗？相关文件也将被删除！')) return;
    fetch(`api/manage_records.php?action=delete_record&id=${recordId}&t=${Date.now()}`, {
        headers: csrfHeaders()
    })
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '删除失败');
            showSuccess('记录删除成功');
            refreshSingleLists();
        })
        .catch(err => showError('删除失败: ' + err.message));
};

window.deleteSingleFile = function (fileName) {
    if (!fileName || !confirm('确定要删除此文件吗？')) return;
    fetch(`api/file_manager.php?action=delete&file=${encodeURIComponent(fileName)}&t=${Date.now()}`, {
        headers: csrfHeaders()
    })
        .then(handleAuthResponse)
        .then(r => r.json())
        .then(data => {
            if (data.code !== 1) throw new Error(data.msg || '删除失败');
            showSuccess('文件删除成功');
            refreshSingleLists();
        })
        .catch(err => showError('删除失败: ' + err.message));
};

window.downloadSingleFile = function (url) {
    url ? window.location.href = url : showError('暂无可下载文件');
};

window.copySingleFileUrl = function (url) {
    if (!url) {
        showError('暂无可复制链接');
        return;
    }
    navigator.clipboard.writeText(url)
        .then(() => showSuccess('下载链接已复制到剪贴板'))
        .catch(err => showError('复制失败: ' + err.message));
};

$('singleParseBtn').addEventListener('click', parseSingleWork);
$('singleDownloadBtn').addEventListener('click', () => {
    currentDownloadUrl ? window.location.href = currentDownloadUrl : showError('暂无可下载文件');
});
$('singleCopyBtn').addEventListener('click', () => {
    if (!currentDownloadUrl) {
        showError('暂无可复制链接');
        return;
    }
    navigator.clipboard.writeText(currentDownloadUrl)
        .then(() => showSuccess('下载链接已复制到剪贴板'))
        .catch(err => showError('复制失败: ' + err.message));
});
$('singleWatchBtn').addEventListener('click', () => watchFile(currentWatchFile));
$('singleRefreshRecordsBtn').addEventListener('click', loadSingleRecords);
$('singleRefreshFilesBtn').addEventListener('click', loadSingleFiles);
$('logoutUserBtn').addEventListener('click', () => {
    fetch(`api/user_api.php?action=logout&t=${Date.now()}`, {
        headers: csrfHeaders()
    })
        .then(r => r.json())
        .finally(() => window.location.href = 'user.php?redirect=single.php');
});

[$('singleUrl'), $('singleFileName')].forEach(input => {
    input.addEventListener('keypress', event => {
        if (event.key === 'Enter') parseSingleWork();
    });
});

checkUserStatus();
