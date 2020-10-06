<!-- 登入頁面 -->
<!-- 頁首 -->
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>login</title>
    <style type="text/css">
        body {
            font-family: "Microsoft JhengHei";
            background-color: #FAFAFA;
        }

        .picture {
            width: 120px;
            height: 120px;
            margin: auto;
        }

        h2 {
            text-align: center;
        }

        .container {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .button {
            text-align: center;
        }

        input[type="submit"]:hover {
            border: 2px #279141 solid;
        }

        @media only screen and (min-width: 768px) and (max-width: 1920px) {
            .login {
                border: 1px #D0D0D0 solid;
                border-radius: 5px;
                margin: 20px 0 0 0;
                padding: 20px 20px;
                line-height: 60px;
                background-color: #FFF;
            }

            .logincontent {
                width: 100%;
                font-size: 18px;
            }

            input {
                height: 30px;
                border: 1px #D0D0D0 solid;
                border-radius: 2px;
            }

            input[type="submit"] {
                width: 100%;
                height: 35px;
                background-color: #32C855;
                background-image: linear-gradient(#32C855, #29A847);
                color: #FFF;
                border: 2px #2EAD4E solid;
                border-radius: 4px;
                cursor: pointer;
                font-family: "Microsoft JhengHei";
            }

        }

        /* iphone 678 */
        @media only screen and (max-width: 767px) {
            .login {
                border: 1px #D0D0D0 solid;
                border-radius: 5px;
                margin: 20px 0 0 0;
                padding: 20px 20px;
                line-height: 50px;
                background-color: #FFF;
            }

            .logincontent {
                width: 100%;
                font-size: 14px;
            }

            input {
                height: 20px;
                border: 1px #D0D0D0 solid;
                border-radius: 2px;
            }

            input[type="submit"] {
                width: 100%;
                height: 25px;
                background-color: #32C855;
                background-image: linear-gradient(#32C855, #29A847);
                color: #FFF;
                border: 2px #2EAD4E solid;
                border-radius: 4px;
                cursor: pointer;
                font-family: "Microsoft JhengHei";
            }

        }
    </style>
    <script type="text/javascript">
        function preventBack() {
            window.history.forward();
        }
        setTimeout("preventBack()", 0);
        window.onunload = function() {
            null
        };
    </script>

</head>

<body>

    <?php
    include "./data_base/data.php";
    include "./data_base/employees.php";
    $employees = new Employees();

    // 設布林值初始值為false
    $boolean = false;

    if (!isset($_SESSION['employee_name']) || $_SESSION['employee_name'] == "") {
        // 判斷 按下登入按鈕
        if (isset($_POST['submit'])) {
            // 則 取帳號密碼的值(去空白值)
            $employees->employee_id = trim($_POST['employee_id']);
            $employees->phone = trim($_POST['password']);

            $stmt = $employees->get_employee();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count_data = count($data);

            // 判斷 帳密是否有在資料庫中
            if ($count_data > 0) {
                // 若有 則布林值為true
                $boolean = true;
            }

            if ($boolean == true) {
                // 登入成功 跳搜尋頁面
                $_SESSION['username'] = $employees->employee_id;
                $_SESSION['employee_name'] = $data[0]['employee_name'];
                $_SESSION['level'] = $data[0]['level'];
                echo "<meta http-equiv=REFRESH CONTENT=0;url=search.php>";
            } else {
                // 登入失敗 跳回登入頁面
                echo "<script>alert('登入失敗!')</script>";
                echo "<meta http-equiv=REFRESH CONTENT=0;url=index.php>";
            }
        }
    }

    if (isset($_GET['logout']) && $_GET['logout'] == "true") {
        unset($_SESSION['username']);
        unset($_SESSION['employee_name']);
        unset($_SESSION['level']);
        echo "<meta http-equiv=REFRESH CONTENT=0;url=satisfaction.php>";
    }

    ?>
    <!-- 頁面標題 -->
    <nav class="headcontainer">
        <div>
            <h1 style="text-align:center; color:darkorange; font-size:xx-large; text-shadow:2px 2px 2px black;">Home Visit GO!</h1>
        </div>
    </nav>

    <!-- 登入表單 -->
    <div class="container ">
        <div class="login">
            <div>
                <h2>登入</h2>
            </div>
            <div class="picture">
                <img src="./img/bg.png" style="width: 120px; height: 120px;">
            </div>
            <form action="" method="POST" class="form-horizontal">
                <div class="logincontent">
                    <div>
                        <label for="employee_id">帳號：</label>
                        <input type="text" name="employee_id" id="employee_id" required>
                    </div>
                    <div>
                        <label for="password">密碼：</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                </div>

                <div class="button">
                    <input type="submit" name="submit" value="登入">
                </div>
            </form>
        </div>
    </div>
</body>

</html>