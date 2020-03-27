<!-- 地圖顯示頁面 -->
<?php
session_start();
include "./data_base/data.php";
include "./data_base/employees.php";
include "./data_base/get_address.php";
// 取得單一地址
$get_address = new GetAddress();
$get_address->address = $_GET["address"]; // 讀取欄位地址
$get_address->get_address();

// 取得醫師姓名
$employees = new Employees();
$stmt_dc_name = $employees->get_employee_name();
$data_dc_name = $stmt_dc_name->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['employee_name'] = $data_dc_name[0]['employee_name'];
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
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=[myKey]&callback=initMap"></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
    <script type="text/javascript">
        function initMap() {
            
            //當下定位
            // navigator.geolocation.watchPosition(Position) => {
            //     console.log(position.coords);
            //     //把當下的經緯度存在coords裡面
            //     var lat=postion.coords.latitude;
            //     var lng=position.coords.logitude;
            //     var map=new google.maps.Map(document.getElementById('map')),{
            //         zoom: 13;
            //         center: {lat: lat;lng:lng}
            //     }
            
            //載入路線服務與路線顯示圖層
            var directionService = new google.maps.DirectionService();
            var directionDisplay = new google.maps.DirectionRender();

            // 起點位置
            var origin = {
                lat: 25.0372057,
                lng: 121.5452242
            };

            // 各家訪地址位置
            var locations = [
                ['<?= $get_address->address ?>', <?= $get_address->LAT ?>, <?= $get_address->LON ?>, '<?= $get_address->patient_name ?>'],
            ];

            // 初始化地圖
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 13,
                center: origin
            });

            //放置路線圖層
            directionDisplay.setMap(map);

            // 標記標示(起點)
            var marker_origin = new google.maps.Marker({
                position: origin,
                map: map,
                icon: "./img/origin-icon.png",
                title: "仁愛醫院"
            });

            // 標記標示(各家訪地址)
            var infowindow;
            var marker, count;
            for (count = 0; count < locations.length; count++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[count][1], locations[count][2]),
                    map: map,
                    title: locations[count][0]
                });
                infowindow = new google.maps.InfoWindow({});
                $value = "<b>" + locations[count][3] + "<b>" + "<br>" + locations[count][0];
                infowindow.setContent($value);
                infowindow.open(map, marker);
            }

            // 點擊顯示標記訊息
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            // 路線相關設定
            var request = {
                origin: {
                    lat: 25.0372057,
                    lng: 121.5452242
                }
                destination: {
                    lat: <?= $get_address->LAT ?>,
                    lng: <?= $get_address->LON ?>
                },
                travelMode: 'DRIVING'
            };

            // 繪製路線
            directionsService.route(request, function(result, status) {
                if (status == 'OK') {
                    // 回傳路線上每個步驟的細節
                    console.log(result.routes[0].legs[0].steps);
                    directionsDisplay.setDirections(result);
                } else {
                    console.log(status);
                }
            });
        }
    </script>
    <script>
        //登出視窗
        function confirmChoice(){
            if(confirm("確定登出")){
                location.href='index.php?logout=true';
            }
        }
    </script>
</head>

<body>
    <!-- 頁面標題 -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div style="width:100%">
            <div>
                <div style="float: left;">
                    <h2>Google Maps</h2>
                    <h4 style="color:firebrick">歡迎 <?= $_SESSION['employee_name'] ?> 醫師使用</h4>
                </div>
                <div style="width: 30%; float: right;">
                        <input type="button" onclick=confirmChoice() value="登出"></input>
                </div>
            </div>
        </div>
    </nav>

    <main style="padding-left:5%; padding-right: 5%; padding-top: 1%;">
        <div id="map"></div>
    </main>

    <!-- 返回查詢畫面連結 -->
    <footer style="text-align:center; padding:2%;">
        <a href="javascript:history.back()">返回上一頁</a>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>