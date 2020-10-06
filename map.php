<!-- 地圖顯示頁面 -->
<?php
session_start();
include "./data_base/data.php";
include "./data_base/get_address.php";
// 取得單一地址
$get_address = new GetAddress();
$get_address->address = $_GET["address"]; // 讀取欄位地址
$get_address->get_address();
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
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8kBxTI4-IP2ikLYNdKcLBTc-xOeDxeHE&callback=initMap"></script>
    <script type="text/javascript">
        function initMap() {
            // 起點位置
            var origin = {
                lat: 25.0372057,
                lng: 121.5452242
            };

            // 各家訪地址位置
            var locations = [
                ['<?= $get_address->address ?>', <?= $get_address->LAT ?>, <?= $get_address->LON ?>, '<?= $get_address->patient_name ?>', '<?= $get_address->address_chinese ?>'],
            ];

            // 初始化地圖
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: origin
            });

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
                $value = "<b>" + locations[count][3] + "<b>" + "<br>" + locations[count][4];
                infowindow.setContent($value);
                infowindow.open(map, marker);
            }

            // 點擊顯示標記訊息
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            // 載入路線服務與路線顯示圖層
            var directionsService = new google.maps.DirectionsService();
            var directionsDisplay = new google.maps.DirectionsRenderer({
                suppressMarkers: true
            });

            // 放置路線圖層
            directionsDisplay.setMap(map);

            // 路線相關設定
            var request = {
                origin: {
                    lat: 25.0372057,
                    lng: 121.5452242,
                },
                destination: {
                    lat: <?= $get_address->LAT ?>,
                    lng: <?= $get_address->LON ?>
                },
                travelMode: 'DRIVING'
            };

            // 繪製路線
            directionsService.route(request, function(result) {
                directionsDisplay.setDirections(result);
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
        #map {
            height: 500px;
            width: 100%;
        }

        body {
            font-family: "Microsoft JhengHei";
        }

        @media only screen and (min-width: 768px) and (max-width: 1920px) {
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

    <main style="padding-left:5%; padding-right: 5%; padding-top: 1%; padding-bottom:2%;">
        <!-- 返回查詢畫面連結 -->
        <div style="text-align:left; padding:1%;">
            <a href="javascript:history.back()">返回上一頁</a>
        </div>
        <div id="map" style="border-style: solid; border-color:lightgray;"></div>
    </main>
</body>

</html>