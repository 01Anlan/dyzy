const page = window.__DOWNLOADS_PAGE__ || {};
const allLinks = Array.isArray(page.allLinks) ? page.allLinks : [];
const linkType = page.linkType || '';
const fileFolder = page.fileFolder || '';
const csrfToken = page.csrfToken || '';

function csrfHeaders(extra = {}) {
    return csrfToken ? { ...extra, 'X-CSRF-Token': csrfToken } : extra;
}

// ── 下载模式 ──
        let downloadMode = 'local'; // local | server

        function setDownloadMode(mode) {
            downloadMode = mode;
            document.querySelectorAll('.mode-option').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.mode === mode);
            });
        }

        function showToast(msg) {
            const toast = document.getElementById('toast');
            toast.textContent = msg;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2500);
        }

        function copySingle(btn, text) {
            navigator.clipboard.writeText(text).then(() => {
                btn.classList.add('copied');
                btn.innerHTML = '<i class="fas fa-check"></i>';
                showToast('已复制链接');
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerHTML = '<i class="fas fa-copy"></i>';
                }, 1500);
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast('已复制链接');
            });
        }

        function copyAllLinks() {
            const text = allLinks.join('\n');
            navigator.clipboard.writeText(text).then(() => {
                showToast('已复制全部 ' + allLinks.length + ' 条链接');
            }).catch(() => {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                showToast('已复制全部 ' + allLinks.length + ' 条链接');
            });
        }

        function getMediaFilename(url, index) {
            const ext = linkType === 'image' ? '.webp' : '.mp4';
            try {
                const params = new URL(url).searchParams;
                const videoId = params.get('video_id');
                if (videoId) return videoId + ext;
            } catch(e) {}
            return 'media_' + (index + 1) + ext;
        }

        // ── 单条下载（支持本地/服务器模式） ──
        function downloadSingle(btn) {
            const li = btn.closest('.link-item');
            const url = (li.dataset.url || '').trim();
            const filename = li.dataset.filename;

            if (!url) {
                showToast('保存失败: 缺少 url 参数');
                return;
            }

            btn.classList.add('downloading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            if (downloadMode === 'server') {
                // 服务器模式：调用 api/server_save.php
                fetch('api/server_save.php', {
                    method: 'POST',
                    headers: csrfHeaders({ 'Content-Type': 'application/json' }),
                    body: JSON.stringify({ url, filename, folder: fileFolder })
                })
                .then(r => r.json())
                .then(data => {
                    btn.classList.remove('downloading');
                    if (data.success) {
                        btn.innerHTML = '<i class="fas fa-check"></i>';
                        btn.style.color = '#16a34a';
                        if (data.cloud_url) {
                            showToast('已保存并上传对象存储');
                        } else if (data.storage && data.storage.success === false) {
                            showToast('已保存到服务器，但云上传失败');
                        } else {
                            showToast(data.skipped ? '文件已存在，跳过' : '已保存到服务器 ' + data.path);
                        }
                    } else {
                        btn.innerHTML = '<i class="fas fa-times"></i>';
                        btn.style.color = '#dc2626';
                        showToast('保存失败: ' + data.message);
                    }
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-download"></i>';
                        btn.style.color = '';
                    }, 2000);
                })
                .catch(err => {
                    btn.classList.remove('downloading');
                    btn.innerHTML = '<i class="fas fa-times"></i>';
                    btn.style.color = '#dc2626';
                    showToast('请求失败: ' + err.message);
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-download"></i>';
                        btn.style.color = '';
                    }, 2000);
                });
            } else {
                // 本地模式：通过代理触发浏览器下载
                const proxyUrl = 'api/download_proxy.php?url=' + encodeURIComponent(url) + '&filename=' + encodeURIComponent(filename);
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = proxyUrl;
                document.body.appendChild(iframe);

                setTimeout(() => {
                    try { document.body.removeChild(iframe); } catch(e) {}
                    btn.classList.remove('downloading');
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.style.color = '#16a34a';
                    showToast('已开始下载 ' + filename);
                    setTimeout(() => {
                        btn.innerHTML = '<i class="fas fa-download"></i>';
                        btn.style.color = '';
                    }, 2000);
                }, 1000);
            }
        }

        // ── 批量下载状态管理 ──
        let batchState = 'idle'; // idle | running | paused | stopped
        let batchCurrentIndex = 0;
        let batchTotal = 0;
        let batchResolve = null;
        let batchSuccessCount = 0;
        let batchFailCount = 0;

        const batchStatus     = document.getElementById('batchStatus');
        const batchStatusText = document.getElementById('batchStatusText');
        const progressBar     = document.getElementById('downloadProgressBar');
        const btnBatchStart   = document.getElementById('btnBatchStart');
        const btnBatchPause   = document.getElementById('btnBatchPause');
        const btnBatchResume  = document.getElementById('btnBatchResume');
        const btnBatchStop    = document.getElementById('btnBatchStop');

        function updateBatchUI() {
            const modeLabel = downloadMode === 'server' ? '服务器' : '本地';
            switch (batchState) {
                case 'idle':
                    btnBatchStart.style.display = '';
                    btnBatchPause.style.display = 'none';
                    btnBatchResume.style.display = 'none';
                    btnBatchStop.style.display = 'none';
                    batchStatus.classList.remove('show');
                    progressBar.style.width = '0%';
                    break;
                case 'running':
                    btnBatchStart.style.display = 'none';
                    btnBatchPause.style.display = '';
                    btnBatchResume.style.display = 'none';
                    btnBatchStop.style.display = '';
                    batchStatus.classList.add('show');
                    batchStatus.querySelector('i').className = 'fas fa-spinner fa-spin';
                    break;
                case 'paused':
                    btnBatchStart.style.display = 'none';
                    btnBatchPause.style.display = 'none';
                    btnBatchResume.style.display = '';
                    btnBatchStop.style.display = '';
                    batchStatus.querySelector('i').className = 'fas fa-pause-circle';
                    batchStatusText.textContent = `已暂停 ${batchCurrentIndex} / ${batchTotal}（${modeLabel}）`;
                    break;
                case 'stopped':
                    btnBatchStart.style.display = '';
                    btnBatchPause.style.display = 'none';
                    btnBatchResume.style.display = 'none';
                    btnBatchStop.style.display = 'none';
                    batchStatus.querySelector('i').className = 'fas fa-stop-circle';
                    batchStatusText.textContent = `已停止 ${batchCurrentIndex}/${batchTotal}，成功${batchSuccessCount} 失败${batchFailCount}`;
                    setTimeout(() => {
                        batchState = 'idle';
                        updateBatchUI();
                    }, 3000);
                    break;
                case 'done':
                    btnBatchStart.style.display = '';
                    btnBatchPause.style.display = 'none';
                    btnBatchResume.style.display = 'none';
                    btnBatchStop.style.display = 'none';
                    batchStatus.querySelector('i').className = 'fas fa-check-circle';
                    batchStatusText.textContent = `完成！共 ${batchTotal} 个，成功${batchSuccessCount} 失败${batchFailCount}`;
                    progressBar.style.width = '100%';
                    showToast(`批量下载完成，成功${batchSuccessCount}个，失败${batchFailCount}个`);
                    setTimeout(() => {
                        batchState = 'idle';
                        updateBatchUI();
                    }, 3000);
                    break;
            }
        }

        function waitIfPaused() {
            if (batchState === 'paused') {
                return new Promise(resolve => { batchResolve = resolve; });
            }
            return Promise.resolve();
        }

        // 单条下载任务（供批量调用）
        function downloadOneToServer(url, filename) {
            return fetch('api/server_save.php', {
                method: 'POST',
                headers: csrfHeaders({ 'Content-Type': 'application/json' }),
                body: JSON.stringify({ url, filename, folder: fileFolder })
            })
            .then(r => r.json())
            .then(data => data.success);
        }

        function downloadOneToLocal(url, filename) {
            return new Promise(resolve => {
                const proxyUrl = 'api/download_proxy.php?url=' + encodeURIComponent(url) + '&filename=' + encodeURIComponent(filename);
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = proxyUrl;
                document.body.appendChild(iframe);
                setTimeout(() => {
                    try { document.body.removeChild(iframe); } catch(e) {}
                    resolve(true);
                }, 800);
            });
        }

        async function batchDownload() {
            if (allLinks.length === 0) return;
            if (batchState === 'running') return;

            batchTotal = allLinks.length;
            batchCurrentIndex = 0;
            batchSuccessCount = 0;
            batchFailCount = 0;
            batchState = 'running';
            progressBar.style.width = '0%';
            updateBatchUI();

            const modeLabel = downloadMode === 'server' ? '服务器' : '本地';

            for (let i = 0; i < batchTotal; i++) {
                if (batchState === 'stopped') break;
                await waitIfPaused();
                if (batchState === 'stopped') break;

                batchCurrentIndex = i + 1;
                const url = (allLinks[i] || '').trim();
                const filename = getMediaFilename(url, i);

                if (!url) {
                    batchFailCount++;
                    progressBar.style.width = (batchCurrentIndex / batchTotal * 100) + '%';
                    continue;
                }

                batchStatusText.textContent = `正在下载 ${batchCurrentIndex} / ${batchTotal} 到${modeLabel}...`;

                try {
                    let ok;
                    if (downloadMode === 'server') {
                        ok = await downloadOneToServer(url, filename);
                    } else {
                        ok = await downloadOneToLocal(url, filename);
                    }
                    if (ok) batchSuccessCount++;
                    else batchFailCount++;
                } catch(e) {
                    batchFailCount++;
                }

                progressBar.style.width = (batchCurrentIndex / batchTotal * 100) + '%';

                // 间隔控制
                if (downloadMode === 'server') {
                    await new Promise(r => setTimeout(r, 300));
                } else {
                    await new Promise(r => setTimeout(r, 500));
                }
            }

            if (batchState === 'stopped') {
                updateBatchUI();
            } else {
                batchState = 'done';
                updateBatchUI();
            }
        }

        function batchPause() {
            if (batchState !== 'running') return;
            batchState = 'paused';
            updateBatchUI();
        }

        function batchResume() {
            if (batchState !== 'paused') return;
            batchState = 'running';
            updateBatchUI();
            if (batchResolve) {
                batchResolve();
                batchResolve = null;
            }
        }

        function batchStop() {
            batchState = 'stopped';
            if (batchResolve) {
                batchResolve();
                batchResolve = null;
            }
            updateBatchUI();
        }
