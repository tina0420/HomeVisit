<!-- 查詢結果頁面 -->
<?php
session_start();
include "./data_base/data.php";
include "./data_base/employees.php";
include "./data_base/get_data.php";
include "./data_base/get_address.php";

// 取得醫師姓名
$employees = new Employees();
$stmt_dc_name = $employees->get_employee_name();
$data_dc_name = $stmt_dc_name->fetchAll(PDO::FETCH_ASSOC);
$_SESSION['employee_name'] = $data_dc_name[0]['employee_name'];

// 取得查詢結果
$get_data = new GetData();

$patient_name = $_GET["patient_name"];
$id = $_GET["id"];
$chart_no = $_GET["chart_no"];
$date_for_visit = $_GET["date_for_visit"];
$get_data->doctor1 = $_SESSION['username'];

if ($_SESSION['level'] == "administrator") {
    if ($patient_name == "" && $id == "" && $chart_no == "" && $date_for_visit == "") {
        $stmt = $get_data->get_all_data();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $get_data->get_data();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查詢結果</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
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
                    <h2>查詢結果</h2>
                    <h4 style="color:firebrick">歡迎 <?= $_SESSION['employee_name'] ?> 醫師使用</h4>
                </div>
                <div style="width: 30%; float: right;">
                        <input type="button" onclick=confirmChoice() value="登出"></input>
                </div>
            </div>
        </div>
    </nav>

    <!-- 查詢結果表格 -->
    <main style="padding-left:5%; padding-right: 5%; padding-top: 1%;">
        <div id="bt_map">
            <button onclick="javascript:location.href='./map_all.php'" style="background-color: lightblue; color: black; width: 100px; height: 75px;">地圖</button>
        </div>
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <table class="table table-bordered table-hover">
                        <!-- 表頭 -->
                        <thead>
                            <th scope="col" width="15%">Date</th>
                            <th scope="col" width="15%">Name</th>
                            <th scope="col" width="15%">Time</th>
                            <th scope="col" width="15%">type</th>
                            <th scope="col" width="40%">address</th>
                        </thead>
                        <!-- foreach 取 查詢結果row -->
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?= $row["date_for_visit"] ?></td>
                                <td><?= $row["patient_name"] ?></td>
                                <!-- <?php ob_start() ?> -->
                                <td><?= $row["time_for_visit"] ?></td>
                                <td><?= $row["visit_type"] ?></td>
                                <td><a href="./map.php?address=<?= $row['address'] ?>" target="_parent"><img src="./img/icon-map.png" width="70px"></a><?= $row["address"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php
                        for ($i = 0; $i < count($data); $i++) {
                            array_push($getAddress->get_address, $data[$i]['address']);
                            setcookie('addr', serialize($getAddress->get_address));
                        }
                        // ob_end_flush();
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- 返回查詢畫面連結 -->
    <footer style="text-align:center; padding:2%;">
        <a href="search.php">返回查詢畫面</a>
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>