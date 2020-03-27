<!-- 所有家訪資料頁面 -->
<?php
    session_start();
    include "./data_base/data.php";
    include "./data_base/get_data.php";

    // 取 所有家訪資料
    $get_all_data = new GetData();
    $stmt = $get_all_data->get_all_data();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Data</title>
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
        <div style="width:90%">
            <h2>所有家訪資料</h2>
        </div>
        <div style="width: 30%; float: right;">
            <input type="button" onclick=confirmChoice() value="登出"></input>
        </div>
        <!-- <div style="width:10%" class="collapse navbar-collapse">
            <a class="nav-link" href="./index.php?logout=true" id="logout">登出</a>
        </div> -->
    </nav>

    <!-- 所有家訪資料表格 -->
    <main style="padding-left:5%; padding-right: 5%; padding-top: 1%;">
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
                        <!-- foreach 取 所有家訪資料row -->
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?= $row["date_for_visit"] ?></td>
                                <td><?= $row["patient_name"] ?></td>
                                <td><?= $row["time_for_visit"] ?></td>
                                <td><?= $row["visit_type"] ?></td>
                                <td><a href="./map.php?address=<?= $row['address']?>" target="_parent"><img src="./img/icon-map.png" width="70px"></a><?= $row["address"] ?></td>
                            </tr>
                        <?php endforeach; ?>
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