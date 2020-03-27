<?php
    // 取得員工資料(登入認證)
    class Employees{
        private $db_connect;
        public $employee_id;
        public $level;

        // 連線資料庫
        public function __construct(){
            $db = new DataBase();
            $this->db_connect = $db->getConnection();
        }

        // 取得員工資料function
        public function get_employee(){
            $sql = "SELECT employee_id, phone, level
                    FROM employees";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // 取得醫師姓名
        public function get_employee_name(){
            $employee_id = $_SESSION['username'];
            $sql = "SELECT employee_name
            FROM employees
            WHERE employee_id = '$employee_id'";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }
    }
?>