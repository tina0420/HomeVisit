<?php
    // 取得地址資料(地圖座標用)
    class GetAddress{
        private $db_connect;
        public $address;
        public $LAT;
        public $LON;
        public $patient_name;
        public $get_address = array();
        public $data;
        public $n;

        // 連線資料庫
        public function __construct(){
            $db = new DataBase();
            $this->db_connect = $db->getConnection();
        }

        // 取得地址資料(經緯度)function
        public function get_address(){
            $sql = "SELECT ga.LAT, ga.LON, pd.patient_name 
                    FROM google_address ga JOIN personal_data pd USING(address)
                    WHERE address = :address";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->bindParam(":address",$this->address);
            $stmt->execute();

            // 利用欄位地址查經緯度、病人姓名
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->LAT = $row['LAT'];
            $this->LON = $row['LON'];
            $this->patient_name = $row['patient_name'];
        }

        // 取得ALL地址資料function
        public function get_all_address(){
            $this->n = implode("','", $this->data);
            $sql = "SELECT ga.LAT, ga.LON, pd.patient_name 
                    FROM google_address ga JOIN personal_data pd USING(address)
                    WHERE ga.address IN('$this->n')";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }
    }
?>