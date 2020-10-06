 <!-- ALL地圖顯示頁面 -->
 <?php
    session_start();
    include "./data_base/data.php";
    include "./data_base/get_address.php";

    // 取得該頁面所有地址
    $getAddress = new GetAddress();
    $getAddress->data = unserialize($_COOKIE['addr']);
    $stmt = $getAddress->get_all_address();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 取得起點位址
    $stmt2 = $getAddress->get_origin();
    $data_origin = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $origin = $data_origin[0]['address'];

    // 設 array() data_addr 存取包含起點的所有地址
    $data_addr = array();
    array_push($data_addr, $origin);
    for ($i = 0; $i < count($data); $i++) {
        array_push($data_addr, $data[$i]['address']);
    }

    $getAddress->addr = $data_addr;
    $stmt3 = $getAddress->get_address_info();
    $info = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    // 算出所有點之間距離 並存入二維陣列 $graph 中
    for ($j = 0; $j < count($data_addr); $j++) {
        $origin = $data_addr[$j];
        for ($i = 0; $i < count($data_addr); $i++) {
            $destin = $data_addr[$i];
            $driving_url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=$origin&destinations=$destin&key=AIzaSyB8kBxTI4-IP2ikLYNdKcLBTc-xOeDxeHE";
            $get_url = file_get_contents($driving_url);
            $result = json_decode($get_url);

            $graph_distance[$j][$i] = $result->rows[0]->elements[0]->distance->value;
            // $graph_distance[$j][$i] = $distanceVal;
            $graph_time[$j][$i] = $result->rows[0]->elements[0]->duration->value;
            // $graph_time[$j][$i] = $timeVal;
        }
    }

    // 計算出最短路徑時間
    $getadd = new SaleMan($graph_time, 0);
    $result = array();
    $route = array();
    $value = $getadd->saleman();

    // print_r($route); // 最短路徑排列組合
    $dist = $route;
    array_unshift($dist, 0);
    array_push($dist, 0);

    $money = array();
    $taxi_dist = array();
    $walk_text = array();
    for ($i = 0; $i < count($dist) - 1; $i++) {
        $x = $dist[$i];
        $y = $dist[$i + 1];
        $taxi_dist[$i + 1] = $graph_distance[$x][$y];
        if ($graph_distance[$x][$y] < 1250) {
            array_push($money, 70);
        } else {
            $money_once = 70 + intval(($graph_distance[$x][$y] - 1250) / 200) * 5;
            array_push($money, $money_once);
        }
        if ($graph_distance[$x][$y] < 1000) {
            $origin = $data_addr[$x];
            $destin = $data_addr[$y];
            $walking_url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&mode=walking&origins=$origin&destinations=$destin&key=AIzaSyB8kBxTI4-IP2ikLYNdKcLBTc-xOeDxeHE";
            $get_url = file_get_contents($walking_url);
            $result = json_decode($get_url);

            // $timeVal_walking = $result->rows[0]->elements[0]->duration->value;
            $walk_text[$i + 1] = intval($result->rows[0]->elements[0]->duration->value / 60);
        }
    }

    // 排序後的地址資訊(除了起點)
    $latlon = array();
    for ($i = 0; $i < count($route); $i++) {
        array_push($latlon, $info[$route[$i]]);
    }

    $all_address = array();
    $dist2 = $route;
    array_unshift($dist2, 0);
    for ($i = 0; $i < count($dist2); $i++) {
        array_push($all_address, $info[$dist2[$i]]);
    }

    class SaleMan
    {
        private $_graph;
        private $s;

        // 自訂建構子
        public function __construct($graph_input, $s_input)
        {
            $this->_graph = $graph_input;
            $this->s = $s_input;
        }

        // 找出所有路線中最短的function
        public function saleman()
        {
            global $result; // 所有排列結果
            global $route;

            // 二維陣列 $graph 的一維數量
            $V = count($this->_graph);

            // 將起點之外的號碼存進陣列 $vector
            $vector = array();
            for ($i = 0; $i < $V; $i++) {
                if ($i != $this->s) {
                    array_push($vector, $i);
                }
            }

            // 預設 $min_path 為預設最大值
            $min_path = PHP_INT_MAX;

            // 將 $vector 做排列組合 (permute function)
            permute($vector, 0, count($vector) - 1);

            // $result 為排列組合後所有結果(陣列)
            for ($i = 0; $i < count($result); $i++) {
                $current_path = 0; // 路程

                // $vector 為單一次的排列組合
                $vector = array_replace($vector, $result[$i]);

                // 此排列組合的路徑加總
                $tmp = $this->s;
                for ($j = 0; $j < count($vector); $j++) {
                    $current_path += $this->_graph[$tmp][$vector[$j]];
                    $tmp = $vector[$j];
                }
                $current_path += $this->_graph[$tmp][$this->s];

                // 每一次的路徑總長和最小路徑比較 取最小
                $min_path = min($min_path, $current_path);

                // 存最短路徑之排列組合
                if ($current_path == $min_path) {
                    $route = array_replace($route, $vector);
                }
            }
            // 回傳最短路經之距離
            return $min_path;
        }
    }

    // 所有排列組合function*2
    function permute($str, $l, $r)
    {
        global $result;
        if ($l == $r) {
            array_push($result, $str);
        } else {
            for ($i = $l; $i <= $r; $i++) {
                $str = swap($str, $l, $i);
                permute($str, $l + 1, $r);
                $str = swap($str, $l, $i);
            }
        }
    }

    function swap($a, $i, $j)
    {
        $temp = $a[$i];
        $a[$i] = $a[$j];
        $a[$j] = $temp;
        return $a;
    }

    ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>地圖</title>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css">
     <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
     <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8kBxTI4-IP2ikLYNdKcLBTc-xOeDxeHE&language=zh-tw&callback=initMap"></script>
     <script type="text/javascript">
         function initMap() {
             // 起點位置
             var origin = {
                 lat: <?= $data_origin[0]['LAT'] ?>,
                 lng: <?= $data_origin[0]['LON'] ?>
             };

             // 各家訪地址位置
             var locations = [
                 <?php for ($i = 0; $i < count($latlon); $i++) : ?>[<?= $latlon[$i]['LAT'] ?>, <?= $latlon[$i]['LON'] ?>, '<?= $latlon[$i]['address'] ?>', '<?= $latlon[$i]['patient_name'] ?>'],
                 <?php endfor; ?>
             ];

             // 初始化地圖
             var map = new google.maps.Map(document.getElementById('map'), {
                 zoom: 15,
                 center: {
                     lat: <?= $data_origin[0]['LAT'] ?>,
                     lng: <?= $data_origin[0]['LON'] ?>
                 }
             });

             // 標記標示(起點)
             var marker_origin = new google.maps.Marker({
                 position: origin,
                 map: map,
                 icon: "./img/origin-icon.png",
                 title: "仁愛醫院"
             });



             // 載入路線服務與路線顯示圖層
             var directionsService = new google.maps.DirectionsService();
             var directionsDisplay = new google.maps.DirectionsRenderer({
                 suppressMarkers: true
             });

             // 放置路線圖層
             directionsDisplay.setMap(map);

             // 標記標示(各家訪地址)
             var infowindow;
             var marker;
             for (var i = 0; i < locations.length; i++) {
                 // for (count = 0; count < latlng.length; count++) {
                 marker = new google.maps.Marker({
                     position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                     map: map,
                     title: locations[i][2],
                     label: "" + (i + 1)
                 });
                 attachSecretMessage(marker, locations[i][3] + "<br>" + locations[i][2]);
             }

             function attachSecretMessage(marker, places) {
                 var infowindow = new google.maps.InfoWindow({
                     content: places
                 });

                 marker.addListener('click', function() {
                     if (infowindow.anchor) {
                         infowindow.close();
                     } else {
                         infowindow.open(map, marker);
                     }
                 });
             }

             var waypts = [];
             for (var i = 0; i < locations.length; i++) {
                 waypts.push({
                     location: new google.maps.LatLng(locations[i][0], locations[i][1]),
                     stopover: false
                 });
             };

             // 路線相關設定
             var request = {
                 origin: origin,
                 destination: origin,
                 travelMode: 'DRIVING',
                 waypoints: waypts,
                 optimizeWaypoints: true
             };

             // 繪製路線
             directionsService.route(request, function(result, status) {
                 if (status == 'OK') {
                     directionsDisplay.setDirections(result);
                 } else {
                     console.log(status);
                 }
             });
         }

         //登出視窗
         function confirmChoice() {
             if (confirm("確定登出")) {
                 location.href = 'index.php?logout=true';
             }
         }
     </script>
     <style type="text/css">
         body {
             font-family: "Microsoft JhengHei";
             overflow-x: hidden;
         }

         @media only screen and (min-width: 768px) and (max-width: 1920px) {
             #map {
                 height: 500px;
                 float: left;
                 width: 70%;
             }

             .info {
                 padding-left: 2%;
                 float: left;
                 width: 28%;
             }

             #logo {
                 width: 30%;
                 float: left;
             }

             #title {
                 width: 40%;
                 float: left;
                 text-align: center;
             }

             #log {
                 width: 30%;
                 float: right;
                 margin-top: 1%;
             }

             #logout {
                 border: 1px solid #dbdbdb;
                 padding: 10px 20px;
                 border-radius: 10px;
                 color: #000;
                 line-height: 50px;
                 float: right;
             }

             #logout:hover {
                 border: 1px solid #dbdbdb;
                 padding: 10px 20px;
                 border-radius: 10px;
                 color: #fff;
                 background-color: #dbdbdb;
                 line-height: 50px
             }

             h1 {
                 text-align: left;
                 color: darkorange;
                 font-size: xx-large;
                 text-shadow: 2px 2px 2px black;
             }
         }

         @media only screen and (max-width: 767px) {
             #map {
                 height: 500px;
                 padding-left: 10%;
                 padding-right: 10%;
                 width: 100%;
             }

             .info {
                 padding-top: 5%;
                 padding-left: 5%;
                 padding-right: 5%;
                 width: 100%;
             }

             #logo {
                 width: 100%;
                 padding-bottom: 5%;
             }

             #title {
                 width: 70%;
                 float: left;
             }

             #log {
                 width: 30%;
                 float: right;
                 margin-top: 10%;
             }

             #logout {
                 border: 1px solid #dbdbdb;
                 padding: 10px 20px;
                 border-radius: 10px;
                 color: #000;
                 line-height: 25px;
                 float: right;
             }

             #logout:hover {
                 border: 1px solid #dbdbdb;
                 padding: 10px 20px;
                 border-radius: 10px;
                 color: #fff;
                 background-color: #dbdbdb;
                 line-height: 25px
             }

             h1 {
                 text-align: center;
                 color: darkorange;
                 font-size: xx-large;
                 text-shadow: 2px 2px 2px black;
             }
         }
     </style>
 </head>

 <body>
     <!-- 頁面標題 -->
     <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <div style="width:100%">
             <div id="logo">
                 <h1>Home Visit GO!</h1>
             </div>
             <div id="title">
                 <h3>Google Maps</h3>
                 <h4 style="color:firebrick">歡迎 <?= $_SESSION['employee_name'] ?> 醫師使用</h4>
             </div>
             <div id="log">
                 <input id="logout" type="button" onclick=confirmChoice() value="登出"></input>
             </div>
         </div>
     </nav>

     <main style="padding-left:5%; padding-right: 5%; padding-bottom: 2%;" class="row row-cols-3">
         <div style="width:100%;">
             <!-- 返回查詢畫面連結 -->
             <div style="text-align:left; padding:1%;">
                 <a href="javascript:history.back()" id="back">返回上一頁</a>
             </div>
             <div id="map" style="border-style: solid; border-color:lightgray;"></div>
             <div class="info">
                 <table class="table table-bordered table-hover" style="font-size: 12pt; font-family:Microsoft JhengHei;">
                     <tbody>
                         <?php for ($i = 1; $i < count($all_address); $i++) : ?>
                             <tr>
                                 <td><?php
                                        if (!empty($walk_text[$i])) {
                                            echo "<b>" . $i . "<br>" . $all_address[$i]['patient_name'] . "<br>" . $all_address[$i]['address'] . "<br>計程車費用 : " . $money[$i - 1] . "元<br>距離為 : " . $taxi_dist[$i] . " 公尺<br>建議步行，時間為 : " . $walk_text[$i] . " 分鐘";
                                        } else {
                                            echo "<b>" . $i . "<br>" . $all_address[$i]['patient_name'] . "<br>" . $all_address[$i]['address'] . "<br>計程車費用 : " . $money[$i - 1] . "元<br>距離為 : " . $taxi_dist[$i] . " 公尺";
                                        }
                                        ?>
                                 </td>
                             </tr>
                         <?php endfor; ?>
                         <tr>
                             <td>
                                 <?php
                                    if (!empty(end($walk_text))) {
                                        echo "<b>仁愛醫院<br>" . $all_address[0]['address'] . "<br>計程車費用 : " . end($money) . "元<br>距離為 : " . end($taxi_dist) . " 公尺<br>建議步行，時間為 : " . end($walk_text) . " 分鐘";
                                    } else {
                                        echo "<b>仁愛醫院<br>" . $all_address[0]['address'] . "<br>計程車費用 : " . end($money) . "元<br>距離為 : " . end($taxi_dist) . " 公尺";
                                    }
                                    ?>
                             </td>
                         </tr>
                     </tbody>
                 </table>
             </div>
         </div>
     </main>
 </body>

 </html>