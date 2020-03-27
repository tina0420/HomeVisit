<!-- 查詢頁面 -->
<?php
    session_start();
    include "./data_base/data.php";
    include "./data_base/employees.php";
    include "./data_base/visit_data.php";

    // 取得醫師姓名
    $employees = new Employees();
    $stmt_dc_name = $employees->get_employee_name();
    $data_dc_name = $stmt_dc_name->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['employee_name'] = $data_dc_name[0]['employee_name'];

    // 取下拉選單的值
    $visit_data = new Visit();
    $visit_data->doctor = $_SESSION['username'];
    $level = $_SESSION['level'];
    if ($level == "user") {
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
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
        setTimeout("preventBack()", 0);
        window.onunload = function() {
            null
        };
        //登出視窗
        function confirmChoice(){
            if(confirm("確定登出")){
                location.href='index.php?logout=true';
            }
        }
    </script>
</head>

<body>
    <div data-role="page" data-title="search" id="search">
        <!-- 頁面標題 -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div style="width:100%">
                <div>
                    <div style="float: left;">
                        <h2>查詢</h2>
                        <h4 style="color:firebrick">歡迎 <?= $_SESSION['employee_name']?> 醫師使用</h4>
                    </div>
                    <div style="width: 30%; float: right;">
                        <input type="button" onclick=confirmChoice() value="登出"></input>
                    </div>
                </div>
            </div>
        </nav>

        <!-- 查詢表單 -->
        <main style="margin-left:30%; margin-right:30%; margin-top:2%">
            <!-- 表單action位址 => response.php -->
            <form action="response.php" method="GET">
                <ul data-role="listview">
                    <li>
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
                    </li>
                    <li>
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
                    </li>
                    <li>
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
                    </li>
                    <li>
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
                    </li>
                </ul>

                <div style="margin-left:30%; margin-right:30%; margin-top:2%">
                    <!-- 查詢按鈕 -->
                    <div style="float: left;">
                        <input type="submit" value="查詢">
                    </div>
                    <!-- 清除按鈕 -->
                    <div style="float: right;">
                        <input type="reset" value="清除">
                    </div>
                </div>
            </form>

        </main>
        <footer>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>