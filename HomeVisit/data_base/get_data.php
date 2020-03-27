<?php
    // 取得查詢結果
    class GetData{
        private $db_connect;
        public $doctor1;

        // 連線資料庫
        public function __construct(){
            $db = new DataBase();
            $this->db_connect = $db->getConnection();
        }

        // 查詢function
        public function get_data(){
            $patient_name = $_GET["patient_name"];
            $id = $_GET["id"];
            $chart_no = $_GET["chart_no"];
            $date_for_visit = $_GET["date_for_visit"];
            $sql = "SELECT vd.date_for_visit, vd.patient_name, vd.time_for_visit, vd.visit_type, ga.address
                    FROM visit_data vd JOIN personal_data pd ON(vd.patient_name = pd.patient_name)
                                        JOIN google_address ga ON(pd.address = ga.address)
                    WHERE (vd.patient_name='$patient_name' OR '$patient_name'='') AND
                    	  (vd.id='$id' OR '$id'='') AND
                    	  (vd.chart_no='$chart_no' OR '$chart_no'='') AND
                          (vd.date_for_visit='$date_for_visit' OR '$date_for_visit'='')";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }

        // 查詢欄位為空(user)
        public function get_null_data(){
            $sql = "SELECT vd.date_for_visit, vd.patient_name, vd.time_for_visit, vd.visit_type, ga.address
                    FROM visit_data vd JOIN personal_data pd ON(vd.patient_name = pd.patient_name)
                                        JOIN google_address ga ON(pd.address = ga.address)
                                        JOIN employees em ON(vd.doctor1 = em.employee_id)
                    WHERE vd.doctor1=:doctor1";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->bindParam(":doctor1",$this->doctor1);
            $stmt->execute();
            return $stmt;
        }
        // 查詢欄位為空(administrator)
        public function get_all_data(){
            $sql = "SELECT vd.date_for_visit, vd.patient_name, vd.time_for_visit, vd.visit_type, ga.address
                    FROM visit_data vd JOIN personal_data pd ON(vd.patient_name = pd.patient_name)
                                        JOIN google_address ga ON(pd.address = ga.address)";
            $stmt = $this->db_connect->prepare($sql);
            $stmt->execute();
            return $stmt;
        }
    }
?>