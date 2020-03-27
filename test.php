<?php
// include "./data_base/get_address.php";

// // $get_address = new GetAddress();
// // $get_address->address = $_GET["address"]; // 讀取欄位地址
// // $get_address->get_address();

// $api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=[location]&destinations=[destinations]&key=[myKey]");
// $data = json_decode($api);

// echo ((int)$data->rows[0]->elements[0]->distance->value / 1000).' Km';


    include "./data_base/data.php";
    include "./data_base/get_address.php";
    $getAddress = new GetAddress();
    $stmt = $getAddress->get_address_name();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($data);
    
    for($i=0; $i<count($data);$i++){
        $driving_url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=106台北市大安區仁愛路四段10號&destinations=".$data[$i]['address']."&key=AIzaSyB8kBxTI4-IP2ikLYNdKcLBTc-xOeDxeHE";
        // $get_url = file_get_contents($driving_url);
        $result = json_decode($driving_url);
        $distance[$i] = $result->rows[0]->elements[0]->distance->text;
        $time[$i] = $result->rows[0]->elements[0]->duration->text; 
    }
    echo min($distance);
    echo min($time);
?>