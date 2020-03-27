<?php
include "./data_base/get_address.php";

// $get_address = new GetAddress();
// $get_address->address = $_GET["address"]; // 讀取欄位地址
// $get_address->get_address();

$api = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=[location]&destinations=[destinations]&key=[myKey]");
$data = json_decode($api);

echo ((int)$data->rows[0]->elements[0]->distance->value / 1000).' Km';
?>