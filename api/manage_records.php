<?php
// manage_records.php - 管理解析记录
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config.php';
dyzy_require_user(true);

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        listRecords();
        break;
    case 'toggle_auto_update':
        dyzy_require_csrf(true);
        toggleAutoUpdate();
        break;
    case 'delete_record':
        dyzy_require_csrf(true);
        deleteRecord();
        break;
    default:
        echo json_encode(['code' => 0, 'msg' => '未知操作']);
}

function listRecords() {
    $db = getDB();
    dyzy_ensure_user_schema($db);
    dyzy_ensure_parse_records_session_column($db);
    $user = dyzy_current_user();
    $userId = (int)$user['id'];
    $mode = trim((string)($_GET['mode'] ?? ''));
    $where = 'WHERE r.user_id = ?';
    $params = [$userId];

    if (in_array($mode, ['post', 'favorite', 'collection', 'single'], true)) {
        if ($mode === 'single') {
            $where .= " AND (r.source_mode = ? OR (r.source_mode IS NULL AND r.custom_filename LIKE 'single\\_%'))";
            $params[] = $mode;
        } else {
            $where .= ' AND r.source_mode = ?';
            $params[] = $mode;
        }
    } else {
        // 默认过滤：排除 single 来源的记录（兼容旧数据）
        $where .= " AND (r.source_mode IS NULL OR r.source_mode != 'single')";
    }

    $stmt = $db->prepare("
        SELECT r.*,
               (SELECT COUNT(*) FROM auto_update_logs l WHERE l.record_id = r.id) as update_count,
               (SELECT created_at FROM auto_update_logs l WHERE l.record_id = r.id ORDER BY id DESC LIMIT 1) as last_update
        FROM parse_records r
        {$where}
        ORDER BY r.created_at DESC
    ");
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'code' => 1,
        'data' => $records
    ], JSON_UNESCAPED_UNICODE);
}

function toggleAutoUpdate() {
    $recordId = $_GET['id'] ?? 0;
    $autoUpdate = $_GET['auto_update'] ?? 0;
    
    if (!$recordId) {
        echo json_encode(['code' => 0, 'msg' => '记录ID不能为空']);
        return;
    }
    
    $db = getDB();
    dyzy_ensure_parse_records_session_column($db);
    $user = dyzy_current_user();
    $userId = (int)$user['id'];
    $stmt = $db->prepare("UPDATE parse_records SET auto_update = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$autoUpdate ? 1 : 0, $recordId, $userId]);
    
    echo json_encode([
        'code' => 1,
        'msg' => '自动更新设置已更新'
    ], JSON_UNESCAPED_UNICODE);
}

function deleteRecord() {
    $recordId = $_GET['id'] ?? 0;
    
    if (!$recordId) {
        echo json_encode(['code' => 0, 'msg' => '记录ID不能为空']);
        return;
    }
    
    $db = getDB();
    dyzy_ensure_parse_records_session_column($db);
    $user = dyzy_current_user();
    $userId = (int)$user['id'];
    
    // 先获取文件路径，删除物理文件
    $stmt = $db->prepare("SELECT file_path FROM parse_records WHERE id = ? AND user_id = ?");
    $stmt->execute([$recordId, $userId]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record && file_exists($record['file_path'])) {
        unlink($record['file_path']);
    }
    
    // 删除数据库记录（外键会自动删除关联的日志）
    $stmt = $db->prepare("DELETE FROM parse_records WHERE id = ? AND user_id = ?");
    $stmt->execute([$recordId, $userId]);
    
    echo json_encode([
        'code' => 1,
        'msg' => '记录删除成功'
    ], JSON_UNESCAPED_UNICODE);
}
?>