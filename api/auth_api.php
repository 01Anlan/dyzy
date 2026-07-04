<?php

require_once __DIR__ . '/../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    $input = [];
}

switch ($action) {
    case 'status':
        echo json_encode(['code' => 1, 'data' => dyzy_auth_status()], JSON_UNESCAPED_UNICODE);
        break;

    case 'init':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['code' => 0, 'msg' => '仅支持 POST 请求'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $result = dyzy_initialize_admin($input['username'] ?? '', $input['password'] ?? '');
        if ($result['success']) {
            dyzy_login($input['username'] ?? '', $input['password'] ?? '');
            echo json_encode(['code' => 1, 'msg' => $result['message']], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['code' => 0, 'msg' => $result['message']], JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['code' => 0, 'msg' => '仅支持 POST 请求'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $result = dyzy_login($input['username'] ?? '', $input['password'] ?? '');
        echo json_encode([
            'code' => $result['success'] ? 1 : 0,
            'msg' => $result['message'],
        ], JSON_UNESCAPED_UNICODE);
        break;

    case 'logout':
        dyzy_require_csrf(true);
        dyzy_logout();
        echo json_encode(['code' => 1, 'msg' => '已退出登录'], JSON_UNESCAPED_UNICODE);
        break;

    default:
        echo json_encode(['code' => 0, 'msg' => '未知操作'], JSON_UNESCAPED_UNICODE);
        break;
}
