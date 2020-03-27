<?php
    // 資料庫連線
    class DataBase{
        public $connect;

        // 資料庫連線function
        public function getConnection(){
            $this->connect = new PDO("mysql:host=localhost;port=3306;dbname=home_visit", "root", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
            return $this->connect;
        }
    }
?>