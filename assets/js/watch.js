const page = window.__WATCH_PAGE__ || {};
const links = Array.isArray(page.links) ? page.links : [];
const viewFile = page.viewFile || '';
const storageKey = `watchState:${viewFile || 'default'}`;
let currentIndex = 0;
let autoNextEnabled = false;
let restoreTime = 0;
let saveTimer = null;

function isImage(url) {
    return /\.(webp|jpg|jpeg|png|gif)(\?|$)/i.test(url) || url.includes('douyinpic.com');
}

function proxyMediaUrl(url) {
    return `api/download_proxy.php?inline=1&url=${encodeURIComponent(url)}`;
}

function escapeAttr(value) {
    return value.replace(/&/g, '&').replace(/"/g, '"').replace(/</g, '<').replace(/>/g, '>');
}

function loadState() {
    try {
        const state = JSON.parse(localStorage.getItem(storageKey) || '{}');
        autoNextEnabled = state.autoNext === true;
        currentIndex = Number.isInteger(state.index) ? Math.max(0, Math.min(state.index, links.length - 1)) : 0;
        restoreTime = Number(state.time) || 0;
    } catch (error) {
        currentIndex = 0;
        restoreTime = 0;
    }
}

function saveState(time) {
    localStorage.setItem(storageKey, JSON.stringify({
        autoNext: autoNextEnabled,
        index: currentIndex,
        time: Math.max(0, Number(time) || 0),
        updatedAt: Date.now()
    }));
}

function bindVideoState(video) {
    video.addEventListener('loadedmetadata', () => {
        if (restoreTime > 0 && Number.isFinite(video.duration) && restoreTime < video.duration - 2) {
            video.currentTime = restoreTime;
        }
        restoreTime = 0;
    }, { once: true });

    video.addEventListener('timeupdate', () => {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(() => saveState(video.currentTime), 300);
    });

    video.addEventListener('ended', () => {
        saveState(0);
        if (autoNextEnabled && currentIndex < links.length - 1) {
            renderPlayer(currentIndex + 1, 0);
        }
    });
}

function renderPlayer(index, startTime = null) {
    if (!links.length) return;
    currentIndex = Math.max(0, Math.min(index, links.length - 1));
    if (startTime !== null) restoreTime = startTime;
    const url = links[currentIndex];
    const mediaUrl = proxyMediaUrl(url);
    const wrap = document.getElementById('playerWrap');
    const safeMediaUrl = escapeAttr(mediaUrl);
    wrap.innerHTML = isImage(url)
        ? `<img src="${safeMediaUrl}" alt="media-${currentIndex + 1}">`
        : `<video id="mediaPlayer" src="${safeMediaUrl}" controls autoplay playsinline preload="metadata"></video>`;
    document.getElementById('counter').textContent = `${currentIndex + 1} / ${links.length}`;
    document.getElementById('currentUrl').textContent = url;
    document.getElementById('openBtn').href = mediaUrl;
    document.querySelectorAll('.media-item').forEach(item => {
        item.classList.toggle('active', Number(item.dataset.index) === currentIndex);
    });
    const video = document.getElementById('mediaPlayer');
    if (video) bindVideoState(video);
    saveState(isImage(url) ? 0 : restoreTime);
}

if (links.length) {
    loadState();
    const autoNextToggle = document.getElementById('autoNextToggle');
    autoNextToggle.checked = autoNextEnabled;
    autoNextToggle.addEventListener('change', () => {
        autoNextEnabled = autoNextToggle.checked;
        const video = document.getElementById('mediaPlayer');
        saveState(video ? video.currentTime : 0);
    });
    document.getElementById('prevBtn').addEventListener('click', () => renderPlayer(currentIndex - 1, 0));
    document.getElementById('nextBtn').addEventListener('click', () => renderPlayer(currentIndex + 1, 0));
    document.getElementById('copyBtn').addEventListener('click', () => navigator.clipboard.writeText(links[currentIndex]));
    document.querySelectorAll('.media-item').forEach(item => {
        item.addEventListener('click', () => renderPlayer(Number(item.dataset.index), 0));
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'ArrowLeft') renderPlayer(currentIndex - 1, 0);
        if (event.key === 'ArrowRight') renderPlayer(currentIndex + 1, 0);
    });
    renderPlayer(currentIndex, restoreTime);
}
