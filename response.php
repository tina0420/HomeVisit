<!-- 查詢結果頁面 -->
<?php
session_start();
include "./data_base/data.php";
include "./data_base/get_data.php";
include "./data_base/get_address.php";

$get_data = new GetData();
// 取得表單查詢條件內容
$patient_name = $_GET["patient_name"];
$id = $_GET["id"];
$chart_no = $_GET["chart_no"];
$date_for_visit = $_GET["date_for_visit"];

// 取得查詢結果
if ($_SESSION['level'] == "administrator") {
    if ($patient_name == "" && $id == "" && $chart_no == "" && $date_for_visit == "") {
        $stmt = $get_data->get_all_data();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $get_data->get_data();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $get_data->doctor1 = $_SESSION['username'];
    if ($patient_name == "" && $id == "" && $chart_no == "" && $date_for_visit == "") {
        $stmt = $get_data->get_null_data();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $get_data->get_data();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$getAddress = new GetAddress();
?>
<?php ob_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查詢結果</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <script>
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

        * {
            list-style: none;
            box-sizing: border-box;
        }

        @media only screen and (min-width: 768px) and (max-width: 1920px) {
            #logo {
                width: 30%;
                float: left;
                padding-left: 1%;
            }

            #title {
                width: 40%;
                float: left;
                text-align: center;
            }

            #log {
                width: 30%;
                float: right;
                padding-right: 1%;
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

            .main-container {
                float: left;
                position: relative;
                left: 50%;
            }

            .fixer-container {
                float: left;
                position: relative;
                left: -50%;
            }

            ul {
                width: 100%;
                display: block;
                margin-left: auto;
                margin-right: auto;
                border-collapse: collapse;
            }

            .thead {
                display: table-header-group;
                margin: 0 auto;
            }

            .tr {
                display: table-row;
            }

            .tbody {
                display: table-row-group;
                margin: 0 auto;
            }

            .thead li,
            .tr li {
                display: table-cell;
                padding: 10px;
                border: 1px solid #aaa;
            }

            .thead li {
                text-align: center;
                font-weight: bold;
            }

            #bt_map {
                float: right;
                background-color: lightblue;
                color: black;
                width: 120px;
                height: 100px;
                text-align: center;
            }

            #bt_map_img {
                width: 100px;
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

            .thead {
                display: none;
            }

            .tr {
                display: block;
                border: #ddd 1px solid;
                margin-bottom: 5px;
            }

            .tr li {
                display: inline-block;
                width: 100%;
                border: none;
            }

            .tr li:before {
                content: attr(data-title);
                display: inline-block;
                width: auto;
                min-width: 20%;
                font-weight: 900;
                padding-right: 1rem;
            }

            #bt_map {
                float: right;
                background-color: lightblue;
                color: black;
                width: 80px;
                height: 75px;
                text-align: center;
            }

            #bt_map_img {
                width: 70px;
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
                <h3>結果</h3>
                <h4 style="color:firebrick">歡迎 <?= $_SESSION['employee_name'] ?> 醫師使用</h4>
            </div>
            <div id="log">
                <input id="logout" type="button" onclick=confirmChoice() value="登出"></input>
            </div>
        </div>
    </nav>

    <!-- 查詢結果表格 -->
    <main style="height: 100%; width: 100%; padding-left: 5%; padding-right: 5%; padding-top: 1%;">
        <div style="height: 80px; width:100%;">
            <!-- 返回查詢畫面連結 -->
            <div style="float:left;">
                <a href="search.php">返回查詢畫面</a>
            </div>
            <div>
                <button id="bt_map" onclick="javascript:location.href='./map_all.php'"><img id="bt_map_img" src="./img/icon-map.png">地圖</button>
            </div>
        </div>
        <div class="main-container">
            <div class="fixer-container">
                <ul>
                    <li class="thead">
                        <ol class="tr">
                            <li>Date</li>
                            <li>Name</li>
                            <li>Time</li>
                            <li>type</li>
                            <li>address</li>
                        </ol>
                    </li>
                    <li class="tbody">
                        <?php foreach ($data as $row) : ?>
                            <ol class="tr">
                                <li data-title="Date"><?= $row["date_for_visit"] ?></li>
                                <li data-title="Name"><?= $row["patient_name"] ?></li>
                                <li data-title="Time"><?= $row["time_for_visit"] ?></li>
                                <li data-title="type"><?= $row["visit_type"] ?></li>
                                <li data-title="address"><a href="./map.php?address=<?= $row['address'] ?>" target="_parent"><img src="./img/Maps_icon.png" style="padding-right:5px;width:20px;"></a><?= $row["address"] ?></li>
                            </ol>
                        <?php endforeach; ?>
                    </li>
                </ul>
                <?php
                for ($i = 0; $i < count($data); $i++) {
                    array_push($getAddress->get_address, $data[$i]['address']);
                    setcookie('addr', serialize($getAddress->get_address));
                }
                ob_end_flush();
                ?>
            </div>
        </div>
    </main>
</body>

</html>