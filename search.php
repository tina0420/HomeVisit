<!-- 查詢頁面 -->
<?php
session_start();
include "./data_base/data.php";
include "./data_base/visit_data.php";

// 取下拉選單的值
$visit_data = new Visit();
$visit_data->doctor = $_SESSION['username'];
if ($_SESSION['level'] == "user") {
    // 取 姓名
    $stmt1 = $visit_data->get_patient_name();
    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    // 取 身分證字號
    $stmt2 = $visit_data->get_id();
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    // 取 病歷號碼
    $stmt3 = $visit_data->get_chart_no();
    $data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    // 取 家訪日期
    $stmt4 = $visit_data->get_date_for_visit();
    $data4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
} else {
    // 取 姓名
    $stmt1 = $visit_data->ad_get_patient_name();
    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    // 取 身分證字號
    $stmt2 = $visit_data->ad_get_id();
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    // 取 病歷號碼
    $stmt3 = $visit_data->ad_get_chart_no();
    $data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    // 取 家訪日期
    $stmt4 = $visit_data->ad_get_date_for_visit();
    $data4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查詢</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
        setTimeout("preventBack()", 0);
        window.onunload = function() {
            null
        };

        //登出視窗
        function confirmChoice() {
            if (confirm("確定登出")) {
                location.href = 'index.php?logout=true';
            }
        }

        function resetSelect() {
            var s1 = document.getElementById("patient_name");
            s1.selectedIndex = "";
            var s2 = document.getElementById("id");
            s2.selectedIndex = "";
            var s3 = document.getElementById("chart_no");
            s3.selectedIndex = "";
            var s4 = document.getElementById("date_for_visit");
            s4.selectedIndex = "";
        }
    </script>
    <style type="text/css">
        body {
            font-family: "Microsoft JhengHei";
            font-size: 20px;
        }

        @media only screen and (min-width: 768px) and (max-width: 1920px) {
            main {
                background-color: #FFFFFF;
                margin-left: 30%;
                margin-right: 30%;
            }

            form {
                padding: 5%;
            }

            select {
                background-color: #FFFFFF;
                border-radius: 5px;
                padding: 1%;
                margin-bottom: 5%;
                height: 50px;
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
            main {
                background-color: #FFFFFF;
                margin-top: 3%;
                margin-left: 5%;
                margin-right: 5%;
                margin-bottom: 3%;
            }

            form {
                padding: 5%;
            }

            select {
                background-color: #FFFFFF;
                border-radius: 5px;
                padding: 1%;
                margin-bottom: 5%;
                height: 50px;
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
                <h3>查詢</h3>
                <h4 style="color:firebrick">歡迎 <?= $_SESSION['employee_name'] ?> 醫師使用</h4>
            </div>
            <div id="log">
                <input id="logout" type="button" onclick=confirmChoice() value="登出"></input>
            </div>
        </div>
    </nav>

    <!-- 查詢表單 -->
    <main>
        <!-- 表單action位址 => response.php -->
        <form action="response.php" method="GET">
            <label>姓名</label>
            <select name="patient_name" id="patient_name" style="width: 100%;">
                <!-- selected => 選單預設空值 -->
                <option value="" selected>請選擇</option>
                <!-- for 取 姓名column -->
                <?php for ($i = 0; $i < count($data1); $i++) : ?>
                    <option value="<?= $data1[$i]["patient_name"] ?>">
                        <?= $data1[$i]["patient_name"] ?>
                    </option>
                <?php endfor; ?>
            </select>
            <label>身分證字號</label>
            <select name="id" id="id" style="width: 100%;">
                <option value="" selected>請選擇</option>
                <!-- for 取 身分證字號column -->
                <?php for ($i = 0; $i < count($data2); $i++) : ?>
                    <option value="<?= $data2[$i]["id"] ?>">
                        <?= $data2[$i]["id"] ?>
                    </option>
                <?php endfor; ?>
            </select>
            <label>病歷號碼</label>
            <select name="chart_no" id="chart_no" style="width: 100%;">
                <option value="" selected>請選擇</option>
                <!-- for 取 病歷號碼column -->
                <?php for ($i = 0; $i < count($data3); $i++) : ?>
                    <option value="<?= $data3[$i]["chart_no"] ?>">
                        <?= $data3[$i]["chart_no"] ?>
                    </option>
                <?php endfor; ?>
            </select>
            <label>家訪日期</label>
            <select name="date_for_visit" id="date_for_visit" style="width: 100%;">
                <option value="" selected>請選擇</option>
                <!-- for 取 家訪日期column -->
                <?php for ($i = 0; $i < count($data4); $i++) : ?>
                    <option value="<?= $data4[$i]["date_for_visit"] ?>">
                        <?= $data4[$i]["date_for_visit"] ?>
                    </option>
                <?php endfor; ?>
            </select>
            <div style="padding-bottom: 5%;padding-top: 5%; width:100%;" class="button">
                <!-- 查詢按鈕 -->
                <div style="float:left; padding-left:10%; width:50%;">
                    <input style="float:left;font-size:20px; width:80%; font-family: 'Microsoft JhengHei';" class="btn btn-primary" type="submit" value="查詢">
                </div>
                <!-- 清除按鈕 -->
                <div style="float:right; padding-right:10%; width:50%;">
                    <input style="float:right;font-size:20px; width:80%; font-family: 'Microsoft JhengHei';" class="btn btn-primary" type="reset" value="清除" onClick="resetSelect()">
                </div>
            </div>
        </form>

    </main>
</body>

</html>