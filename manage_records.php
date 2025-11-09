<?php
// manage_records.php - 管理解析记录
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'list':
        listRecords();
        break;
    case 'toggle_auto_update':
        toggleAutoUpdate();
        break;
    case 'delete_record':
        deleteRecord();
        break;
    default:
        echo json_encode(['code' => 0, 'msg' => '未知操作']);
}

function listRecords() {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT r.*, 
               (SELECT COUNT(*) FROM auto_update_logs l WHERE l.record_id = r.id) as update_count,
               (SELECT created_at FROM auto_update_logs l WHERE l.record_id = r.id ORDER BY id DESC LIMIT 1) as last_update
        FROM parse_records r 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute();
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
    $stmt = $db->prepare("UPDATE parse_records SET auto_update = ? WHERE id = ?");
    $stmt->execute([$autoUpdate ? 1 : 0, $recordId]);
    
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
    
    // 先获取文件路径，删除物理文件
    $stmt = $db->prepare("SELECT file_path FROM parse_records WHERE id = ?");
    $stmt->execute([$recordId]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record && file_exists($record['file_path'])) {
        unlink($record['file_path']);
    }
    
    // 删除数据库记录（外键会自动删除关联的日志）
    $stmt = $db->prepare("DELETE FROM parse_records WHERE id = ?");
    $stmt->execute([$recordId]);
    
    echo json_encode([
        'code' => 1,
        'msg' => '记录删除成功'
    ], JSON_UNESCAPED_UNICODE);
}
?>