<?php
include "./data_base/data.php";
include "./data_base/satisfaction_data.php";
$satisfaction = new Satisfaction();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>satisfaction</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/litera/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        @media only screen and (min-width: 1100px) and (max-width: 1920px) {

            #excellent,
            #great,
            #soso,
            #bad,
            #worst {
                float: left;
            }

            main {
                margin: auto;
                width: 50%;
                margin-top: 2%;
            }
        }

        /* / iphone 678 /  */
        @media only screen and (max-width: 1100px) {

            label {
                width: 100%;
            }

            main {
                margin: auto;
                width: 80%;
                margin-top: 2%;
            }
        }

        body {
            background: #e6e6e6;
            font-family: "Microsoft JhengHei";
        }

        img {
            width: 60px;
            height: 60px;
            padding: 10px;
        }

        input[type=radio] {
            display: none;
        }

        /* / IMAGE STYLES /  */
        input[type=radio]+img {
            cursor: pointer;

        }

        /* / CHECKED STYLES /  */
        input[type=radio]:checked+img {
            padding: 10px;
            background: #e6e6e6;
            border: none;
            border-radius: 5px;
        }

        .form-control {
            border: 3px;
            border-style: solid;
            border-color: #ececec;
            border-radius: 4px;
        }

        textarea:focus {
            border: 5px;
            border-style: solid;
            border-color: #ececec;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <!-- 滿意度表單 -->
    <main>
        <?php
        if (isset($_POST['submit'])) {
            $satisfaction->UI = $_POST['UI'];
            $satisfaction->usability = $_POST['usability'];
            $satisfaction->integrity = $_POST['integrity'];
            $satisfaction->feedback = $_POST['feedback'];
            if ($satisfaction->create()) {
                echo "<div class='alert alert-dismissible alert-success'>新增成功~</div>";
                echo "<meta http-equiv=REFRESH CONTENT=0;url=index.php>";
            } else {
                // 否則 顯示"失敗訊息"
                echo "<div class='alert alert-dismissible alert-warning'>新增失敗!</div>";
            }
        }
        ?>

        <div class="card" style="border:none; border-radius: 10px ;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
            <form action="satisfaction.php" method="POST">
                <div class="title" style="text-align:center; padding:2%; width:100%;font-size:45px;">
                    滿意度調查
                </div>
                <div class="card-body">
                    <h5 class="card-title">介面</h5>
                    <label>
                        <input type="radio" name="UI" value="excellent" id="excellent" checked="checked">
                        <img src="./img/excellent.png">
                        非常滿意
                    </label>
                    <label>
                        <input type="radio" name="UI" value="great" id="great">
                        <img src="./img/great.png">
                        滿意
                    </label>
                    <label for="soso">
                        <input type="radio" name="UI" value="soso" id="soso">
                        <img src="./img/soso.png">
                        普通
                    </label>
                    <label>
                        <input type="radio" name="UI" value="bad" id="bad">
                        <img src="./img/bad.png">
                        不滿意
                    </label>
                    <label>
                        <input type="radio" name="UI" value="worst" id="worst">
                        <img src="./img/worst.png">
                        非常不滿意
                    </label>

                    <h5 class="card-title">使用性</h5>
                    <label>
                        <input type="radio" name="usability" value="excellent" id="excellent" checked="checked">
                        <img src="./img/excellent.png">
                        非常滿意
                    </label>
                    <label>
                        <input type="radio" name="usability" value="great" id="great">
                        <img src="./img/great.png">
                        滿意
                    </label>
                    <label>
                        <input type="radio" name="usability" value="soso" id="soso">
                        <img src="./img/soso.png">
                        普通
                    </label>
                    <label>
                        <input type="radio" name="usability" value="bad" id="bad">
                        <img src="./img/bad.png">
                        不滿意
                    </label>
                    <label>
                        <input type="radio" name="usability" value="worst" id="worst">
                        <img src="./img/worst.png">
                        非常不滿意
                    </label>
                    <h5 class="card-title">整體滿意度</h5>
                    <label>
                        <input type="radio" name="integrity" value="excellent" id="excellent" checked="checked">
                        <img src="./img/excellent.png">
                        非常滿意
                    </label>
                    <label>
                        <input type="radio" name="integrity" value="great" id="great">
                        <img src="./img/great.png">
                        滿意
                    </label>
                    <label>
                        <input type="radio" name="integrity" value="soso" id="soso">
                        <img src="./img/soso.png">
                        普通
                    </label>
                    <label>
                        <input type="radio" name="integrity" value="bad" id="bad">
                        <img src="./img/bad.png">
                        不滿意
                    </label>
                    <label>
                        <input type="radio" name="integrity" value="worst" id="worst">
                        <img src="./img/worst.png">
                        非常不滿意
                    </label>
                    <h5 class="card-title">回饋</h5>
                    <textarea name="feedback" class="form-control" id="feedback" rows="3"></textarea>
                    <br>
                    <button type="submit" name="submit" class="btn btn-dark" style="width:100%">確認</button>
                </div>
            </form>
        </div>
    </main>

    <!-- 返回查詢畫面連結 -->
    <footer style="text-align:center; padding:2%;">
        <a href="./index.php">返回登入頁</a>
    </footer>
</body>

</html>