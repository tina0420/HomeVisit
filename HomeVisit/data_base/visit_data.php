<?php
    // 取得家訪資料(查詢頁面-下拉選單)
    class Visit{
        private $db_connect;
        public $doctor;

        // 連線資料庫
        public function __construct(){
            $db = new DataBase();
            $this->db_connect = $db->getConnection();
        }

        // 醫生取得姓名function
        public function get_patient_name(){
            $sql = "SELECT DISTINCT patient_name FROM visit_data WHERE doctor1 = :doctor";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->bindParam(":doctor",$this->doctor);
            $stmt->execute();
            return $stmt;
        }

        // 醫生取得身分證字號function
        public function get_id(){
            $sql = "SELECT DISTINCT id FROM visit_data WHERE doctor1 = :doctor";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->bindParam(":doctor",$this->doctor);
            $stmt->execute();
            return $stmt;
        }

        // 醫生取得病歷號碼function
        public function get_chart_no(){
            $sql = "SELECT DISTINCT chart_no FROM visit_data WHERE doctor1 = :doctor";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->bindParam(":doctor",$this->doctor);
            $stmt->execute();
            return $stmt;
        }

        // 醫生取得家訪日期function
        public function get_date_for_visit(){
            $sql = "SELECT DISTINCT date_for_visit FROM visit_data WHERE doctor1 = :doctor";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->bindParam(":doctor",$this->doctor);
            $stmt->execute();
            return $stmt;
        }

        // 管理員取得姓名function
        public function ad_get_patient_name(){
            $sql = "SELECT DISTINCT patient_name FROM visit_data";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // 管理員取得身分證字號function
        public function ad_get_id(){
            $sql = "SELECT DISTINCT id FROM visit_data";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // 管理員取得病歷號碼function
        public function ad_get_chart_no(){
            $sql = "SELECT DISTINCT chart_no FROM visit_data";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // 管理員取得家訪日期function
        public function ad_get_date_for_visit(){
            $sql = "SELECT DISTINCT date_for_visit FROM visit_data";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }
    }
?>