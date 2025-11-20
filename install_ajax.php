<?php
// install_ajax.php - 安装处理脚本
header('Content-Type: application/json; charset=utf-8');

// 允许跨域
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// 获取POST数据
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

function jsonResponse($success, $message = '', $data = []) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    switch ($action) {
        case 'test_connection':
            testDatabaseConnection($input);
            break;
            
        case 'install':
            installDatabase($input);
            break;
            
        default:
            jsonResponse(false, '未知操作');
    }
} catch (Exception $e) {
    jsonResponse(false, '安装错误: ' . $e->getMessage());
}

function testDatabaseConnection($config) {
    $host = $config['db_host'] ?? 'localhost';
    $name = $config['db_name'] ?? 'douyin_monitor';
    $user = $config['db_user'] ?? 'root';
    $pass = $config['db_pass'] ?? '';
    $charset = $config['db_charset'] ?? 'utf8mb4';
    
    try {
        // 测试连接
        $dsn = "mysql:host=$host;charset=$charset";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        
        // 测试创建数据库（如果不存在）
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET $charset COLLATE {$charset}_unicode_ci");
        
        jsonResponse(true, '数据库连接测试成功');
        
    } catch (PDOException $e) {
        jsonResponse(false, '数据库连接失败: ' . $e->getMessage());
    }
}

function installDatabase($config) {
    $host = $config['db_host'] ?? 'localhost';
    $name = $config['db_name'] ?? 'douyin_monitor';
    $user = $config['db_user'] ?? 'root';
    $pass = $config['db_pass'] ?? '';
    $charset = $config['db_charset'] ?? 'utf8mb4';
    
    try {
        $dsn = "mysql:host=$host;dbname=$name;charset=$charset";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        // 读取并执行本地的 database.sql 文件
        $sqlFile = __DIR__ . '/database.sql';
        if (!file_exists($sqlFile)) {
            throw new Exception('找不到 database.sql 文件，请确保该文件存在于安装目录中');
        }
        
        $sql = file_get_contents($sqlFile);
        if (!$sql) {
            throw new Exception('无法读取 database.sql 文件');
        }
        
        // 执行SQL语句
        $pdo->exec($sql);
        
        // 创建配置文件到根目录
        $configContent = createConfigFile($config);
        $rootConfigPath = dirname(__DIR__) . '/config.php';
        if (file_put_contents($rootConfigPath, $configContent) === false) {
            throw new Exception('无法创建配置文件到根目录');
        }
        
        // 创建安装锁文件到安装目录
        $lockContent = '<?php
/**
 * 安装锁文件
 * 创建时间: ' . date('Y-m-d H:i:s') . '
 * 数据库: ' . $name . '
 * 配置文件: ' . dirname(__DIR__) . '/config.php
 * 如需重新安装，请删除此文件和根目录的config.php文件
 */
?>
';
        $lockPath = __DIR__ . '/install.lock';
        if (file_put_contents($lockPath, $lockContent) === false) {
            throw new Exception('无法创建安装锁文件');
        }
        
        // 检查创建的表
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $tablesCreated = count($tables);
        
        jsonResponse(true, '安装完成', [
            'tables_created' => $tablesCreated,
            'tables' => $tables,
            'config_path' => $rootConfigPath,
            'lock_path' => $lockPath
        ]);
        
    } catch (PDOException $e) {
        jsonResponse(false, '数据库安装失败: ' . $e->getMessage());
    }
}

function createConfigFile($config) {
    return '<?php
/**
 * 抖音监控系统 - 数据库配置文件
 * 自动生成时间: ' . date('Y-m-d H:i:s') . '
 * 请勿手动修改此文件，如需修改请通过安装程序重新配置
 */

class Database {
    private $host = \'' . ($config['db_host'] ?? 'localhost') . '\';
    private $db_name = \'' . ($config['db_name'] ?? 'douyin_monitor') . '\';
    private $username = \'' . ($config['db_user'] ?? 'root') . '\';
    private $password = \'' . addslashes($config['db_pass'] ?? '') . '\';
    public $conn;
    
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", 
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            error_log("数据库连接错误: " . $exception->getMessage());
            throw new Exception("数据库连接失败，请检查配置");
        }
        return $this->conn;
    }
}

/**
 * 获取数据库连接
 * @return PDO
 */
function getDB() {
    $database = new Database();
    return $database->getConnection();
}

/**
 * 检查系统是否已安装
 * @return bool
 */
function isInstalled() {
    return file_exists(__DIR__ . \'/config.php\') && file_exists(__DIR__ . \'/install/install.lock\');
}

/**
 * 安全检测 - 防止直接访问安装页面
 */
if (php_sapi_name() !== \'cli\') {
    $current_script = basename($_SERVER[\'SCRIPT_NAME\']);
    if ($current_script === \'install.php\' || $current_script === \'install_ajax.php\') {
        if (isInstalled()) {
            http_response_code(403);
            die(\'
            <!DOCTYPE html>
            <html lang="zh-CN">
            <head>
                <meta charset="UTF-8">
                <title>系统已安装</title>
                <style>
                    body { font-family: Arial, sans-serif; background: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
                    .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); text-align: center; max-width: 500px; }
                    .warning { font-size: 48px; margin-bottom: 20px; }
                    h1 { color: #dc3545; margin-bottom: 20px; }
                    p { margin-bottom: 20px; color: #666; }
                    a { color: #007bff; text-decoration: none; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="warning">⚠️</div>
                    <h1>系统已安装</h1>
                    <p>抖音监控系统已经安装完成，如需重新安装请先删除以下文件：</p>
                    <ul style="text-align: left; margin-bottom: 20px;">
                        <li>根目录的 config.php 文件</li>
                        <li>install/install.lock 文件</li>
                    </ul>
                    <p><a href="../index.php">返回首页</a></p>
                </div>
            </body>
            </html>
            \');
        }
    }
}

// 配置文件结束
?>';
}
?>