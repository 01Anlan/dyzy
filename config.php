<?php
// config.php - 数据库配置
class Database {
    private $host = 'localhost';
    private $db_name = '数据库名';
    private $username = '用户名';
    private $password = '数据库密码';
    public $conn;
    
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "数据库连接错误: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// 创建数据库连接
function getDB() {
    $database = new Database();
    return $database->getConnection();
}
?>