<?php
// config.php - 自动生成的数据库配置
class Database {
    private $host = '127.0.0.1';
    private $db_name = 'dyzy';
    private $username = 'dyzy';
    private $password = 'XdhhRx4Lesm4MzP7';
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